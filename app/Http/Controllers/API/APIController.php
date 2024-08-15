<?php

namespace App\Http\Controllers\API;

use App\Helper\HttpClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificaitonMail;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helper\Files;

class APIController extends Controller
{
    // For Registration
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',  
        ]);

        if ($validator->fails()) {
			return response()->json([
				'message' => $validator->errors(),
			], 400);
		}


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Mail::to("saugatkumal452@gmail.com")->send(new VerificaitonMail($data));
        

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);

    }

    //For Login
    // public function login(Request $request)
    // {

    //     $credentials = $request->only('email', 'password');

	// 	$user = User::where('email', $credentials['email'])->first();

    //     try {
            
    //         if (! $token = JWTAuth::attempt($credentials)) {
    //             return response()->json(['message' => 'invalid_credentials'], 400);
    //         }
    //     } catch (JWTException $e) {
            
    //         return response()->json(['message' => 'could_not_create_token'], 500);
    //     }


	// 	$token = JWTAuth::fromUser($user);

	// 	$refreshToken = $token; 

	// 	return response()->json([
	// 		'success' => true,
	// 		'message' => 'User logged in successfully',
	// 		'token' => $token,
	// 		'refresh' => $refreshToken,
    //         'expires_in' => auth('api')->factory()->getTTL() * 60,
	// 		'user' => [
	// 			'id' => $user->id,
	// 			'email' => $user->email,
	// 			'name' => $user->name,
	// 		],
	// 	]);

        

    // }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        $key = 'login_attempts_' . $request->ip();

        if (Cache::has($key) && Cache::get($key) >= 3) {
            $timeLeft = now()->diffInSeconds(Cache::get($key . '_time')) ?: 60; 
            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . gmdate('i:s', $timeLeft) . ' minutes.',
                'attempts' => Cache::get($key),
                'time_left' => gmdate('i:s', $timeLeft)
            ], 429);
        }

        $user = User::where('email', $credentials['email'])->first();

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $attempts = Cache::increment($key);
                if ($attempts >= 3) {
                    Cache::put($key . '_time', now()->addMinutes(), 60); 
                }
                Cache::put($key, $attempts, 60); 

                return response()->json([
                    'message' => 'Invalid credentials',
                    'attempts' => $attempts,
                    'time_left' => $attempts >= 3 ? '01:00' : null 
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token'], 500);
        }

        Cache::forget($key);
        Cache::forget($key . '_time');

        $token = JWTAuth::fromUser($user);
        $refreshToken = $token;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
            'refresh' => $refreshToken,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ],
        ]);
    }



    public function profile()
    {
        try {
			$user = JWTAuth::parseToken()->authenticate();
		} catch (JWTException $e) {
			return response()->json([
				'message' => 'Given token not valid for any token type', 
				'code' => 'token_not_valid',
			], 401);
		}

        return response()->json([
            'user_data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'profile_image' => asset($user->profile_image === 'default.jpg' ? 'user-uploads/default.jpg' : 'storage/user-uploads/profile/' . $user->profile_image),
                'cover_image' => asset($user->cover_image === 'cover-default.jpg' ? 'user-uploads/cover-default.jpg' : 'storage/user-uploads/cover/' . $user->cover_image),
                ]
        ]);
    }

    public function uploadImageProfile(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Given token not valid for any token type', 
                'code' => 'token_not_valid',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            $user = User::find($user->id);

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            // Update profile image
            if ($request->hasFile('profile_image')) {
                $user->profile_image = Files::updateUserImage($request->file('profile_image'),'user-uploads/profile', $user->profile_image);
            }

            // Update cover image
            if ($request->hasFile('cover_image')) {
                $user->cover_image = Files::updateUserImage($request->file('cover_image'),'user-uploads/cover',$user->cover_image);
            }

            $user->save();

            return response()->json([
                'message' => 'Profile Updated Successfully',
                'user_data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'profile_image' => asset($user->profile_image === 'default.jpg' ? 'user-uploads/default.jpg' : 'storage/user-uploads/profile/' . $user->profile_image),
                    'cover_image' => asset($user->cover_image === 'cover-default.jpg' ? 'user-uploads/cover-default.jpg' : 'storage/user-uploads/cover/' . $user->cover_image),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'detail' => 'Parse Error',
                'code' => 'parse_Error',
            ], 500);
        }
    }

    public function checkToken(Request $request)
    {
        try {
            // Get the token from the request (usually from the Authorization header)
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json(['message' => 'Token not provided'], 400);
            }

            // Parse the token and authenticate the user
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Token is valid',
                
            ]);

        } catch (TokenExpiredException $e) {
            // Token has expired
            return response()->json(['message' => 'Token has expired. Please log in again'], 401);
        } catch (TokenInvalidException $e) {
            // Token is invalid
            return response()->json(['message' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            // General JWT exception
            return response()->json(['message' => 'Token is absent or not valid'], 401);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 401);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

}
