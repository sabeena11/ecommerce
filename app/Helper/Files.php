<?php

// namespace App\Helper;

// use Illuminate\Support\Facades\Storage;

// class Files
// {
//     /**
//      * Update the user's image.
//      *
//      * @param \Illuminate\Http\Request $request
//      * @param \App\Models\User $user
//      * @param string $directory
//      * @param string $imageField
//      * @param string $defaultImage
//      * @return \App\Models\User
//      */
//     public static function updateUserImage($request, $user, $directory, $imageField = 'image', $defaultImage = 'default.png')
//     {
//         // Check if the request has a file for the given field
//         if ($request->hasFile($imageField)) {
//             // Delete old image if it exists and is not the default image
//             self::deleteOldImage($user->$imageField, $directory, $defaultImage);

//             // Store the new image and update the user model
//             $user->$imageField = self::storeNewImage($request, $directory, $imageField);
//         } elseif (!$user->$imageField) {
//             // Set default image if none exists
//             $user->$imageField = $defaultImage;
//         }

//         return $user;
//     }

//     /**
//      * Delete the old image if it exists and is not the default image.
//      *
//      * @param string|null $image
//      * @param string $directory
//      * @param string $defaultImage
//      * @return void
//      */
//     private static function deleteOldImage($image, $directory, $defaultImage)
//     {
//         if ($image && $image !== $defaultImage && Storage::disk('public')->exists($directory . '/' . $image)) {
//             Storage::disk('public')->delete($directory . '/' . $image);
//         }
//     }

//     /**
//      * Store the new image and return its basename.
//      *
//      * @param \Illuminate\Http\Request $request
//      * @param string $directory
//      * @param string $imageField
//      * @return string
//      */
//     private static function storeNewImage($request, $directory, $imageField)
//     {
//         $path = $request->file($imageField)->store($directory, 'public');
//         return basename($path);
//     }
// }


namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class Files
{
    /**
     * Upload a new image, delete the old one if it exists, and return the new image's basename.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $directory
     * @param string|null $oldImage
     * @return string|null
     */
    public static function updateUserImage($image, $directory, $oldImage = null)
    {
        if ($image) {
            // Delete the old image if it exists
            self::deleteOldImage($oldImage, $directory);

            // Store the new image and return its basename
            return self::storeNewImage($image, $directory);
        }

        return null;
    }

    /**
     * Delete the old image if it exists.
     *
     * @param string|null $oldImage
     * @param string $directory
     * @return void
     */
    private static function deleteOldImage($oldImage, $directory)
    {
        if ($oldImage && Storage::disk('public')->exists($directory . '/' . $oldImage)) {
            Storage::disk('public')->delete($directory . '/' . $oldImage);
        }
    }

    /**
     * Store the new image and return its basename.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $directory
     * @return string
     */
    private static function storeNewImage($image, $directory)
    {
        $path = $image->store($directory, 'public');
        return basename($path);
    }
}




