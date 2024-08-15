<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    protected $redirectTo = '/admin/dashboard';


    public function showLoginForm()
    {
       
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }



        // // Attempt to log the user in
        // $credentials = $request->only('email', 'password');

        // if (Auth::attempt($credentials)) {
            
        //     return redirect()->route('admin.dashboard');
        // }

        // // If authentication fails
        // return redirect()->back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->withInput();



        if ($validator->passes()) {
            if (Auth::guard('admin')
            ->attempt(['email' => $request->email,'password' => $request->password,], $request->get('remember'))){

                $admin = Auth::guard('admin')->user();

                if ($admin->role == 1){
                    return redirect()->route('admin.dashboard');
                }
                else{
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')
                    ->with('error','You are not authorized to access Admin Panel');
                }

            }
            else{
                return redirect()->route('admin.login')
                ->with('error','Either Email/Password is Incorrect');
            }

        }
        else{
            return redirect()->route('admin.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    

    
}
