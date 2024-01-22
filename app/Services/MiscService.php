<?php

namespace App\Services;

use App\Models\Modules;
use App\Models\Permissions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MiscService
{
    /**
     * Generate an auto-generated code for a module.
     * @param int $count The count for generating the module ID.
     * @param string $prefix The prefix for the module ID.
     * @return string The auto-generated module ID.
     */
    public function autoGenCode($count, $prefix): string
    {
        $prefix = "MOD"; // prefix for the module ID
        $loopCount = str_pad($count, 3, "0", STR_PAD_LEFT); // pad loop counter with leading zeroes
        $currentDate = date("ymd"); // get current year, month, and day in ymd format
        $modID = $prefix . $currentDate . $loopCount . rand(10000, 99999); // generate unique module ID

        return $modID;
    }

    /**
     * Generate a unique user ID based on the given name.
     * @param string $name The name used to generate the user ID.
     * @return string The generated user ID.
     */
    public function generateUserid($name): string
    {
        $count = User::count() + 1;
        return strtoupper(substr(preg_replace('/[^a-zA-Z_]/', "", $name), 0, 3) . Carbon::now()->format('y') . 'U' . bin2hex(random_bytes(4)) . $count);
    }

    /**
     * Returns the permissions for the current route's associated module for the authenticated user's role.
     * @param string $roleId
     * @return object Returns an object containing the permissions for the current route's associated module for the authenticated user's role.
     * If the current route doesn't have an associated module, returns an object with all permissions set to 0.
     */
    public function permissions($roleId): object
    {
        // Check if the current route name is in the format "name.view"
        $currentRouteName = Route::currentRouteName();
        $moduleName = $currentRouteName;
        if (strpos($currentRouteName, '.view') !== false) {
            $moduleName = substr($currentRouteName, 0, strpos($currentRouteName, '.view'));
        }

        // Retrieve the current module associated with the current route
        $currentModule = Modules::where('modName', $moduleName)
            ->where('modStatus', 1)
            ->first();

        // If there is no current module associated with the current route, return an object with all permissions set to 1.
        if (!$currentModule) {
            $defaultPermissions = ['modID' => 1, 'modRead' => 1, 'modCreate' => 1, 'modUpdate' => 1, 'modDelete' => 1, 'modReport' => 1];
            return json_decode(json_encode($defaultPermissions), false);
        }

        // Retrieve the module privileges for the authenticated user's role for the current module
        $permissions = Permissions::where('modID', $currentModule->modID)
            ->where('role', $roleId)
            ->first();

        return $permissions;
    }

    /**
     * Check if the authenticated user has a specific permission for a module.
     * @param Request $request The HTTP request containing the user ID, module ID, and module action.
     * @return bool Returns true if the user has the required permission, otherwise false.
     */
    public function checkPermission(Request $request)
    {
        try {
            // Retrieve the current module associated with the current modID
            $currentModule = Modules::where('modID', $request->modID)
                ->where('modStatus', 1)
                ->first();

            // If there is no current module associated with the current modID, return true.
            if (!$currentModule) {
                return true;
            }

            // Find the permission entry based on the provided module ID, user role, and module action
            $permission = Permissions::where('modID', $request->modID)
                ->where('role', $request->user()->role_id)
                ->where("$request->modAction", 1)
                ->first();

            // Check if the permission exists
            if (!$permission) {
                // Return false indicating that the user does not have the required permission
                return false;
            }

            // Return true indicating that the user has the required permission
            return true;
        } catch (\Throwable $e) {
            // If an error occurs during the permission check, return false as a fallback
            return false;
        }
    }

    /**
     * Uploads an image from the request's avatar parameter to storage and returns the new file path.
     *
     * @param Request $request The HTTP request object containing the 'avatar' parameter.
     * @param string $newFileName The new file name to be assigned to the uploaded image.
     * @param string $previousFilePath The path of the previous image file, if it exists.
     * @return string The new file path of the uploaded image.
     */
    public function uploadImage($requestFile, $newFileName, $previousFilePath)
    {
        // Create the new image file name and path
        $imageName = $newFileName . '.jpg';
        $imagePath = 'public/config/uploads/' . $imageName;

        // Store the new image in the storage
        $requestFile->storeAs('public/config/uploads', $imageName);

        // Get the new file path of the uploaded image
        $newFilePath = Storage::url($imagePath);

        // If there is a previous image file, delete it from storage
        if ($previousFilePath && $newFilePath) {
            // Extract filename from profile image path and delete file if it exists in public storage folder
            $path = explode('/storage/', $previousFilePath);
            $file = end($path);
            if (File::exists(public_path("storage/$file"))) {
                File::delete(public_path("storage/$file"));
            }
        }

        // Return the new file path of the uploaded image
        return config('app.url') . $newFilePath;
    }

    /**
     * Uploads an external image from the parameter to storage and returns the new file path.
     *
     * @param string $imageURL The path of the image file.
     * @return string The new file path of the uploaded image.
     */
    public function uploadExternalImage($imageURL)
    {
        // Get the image data from the external URL
        $imageData = @file_get_contents($imageURL);

        if ($imageData === false) {
            // Return the original image URL if the image could not be fetched
            return $imageURL;
        }

        // Extract the old image name from the URL
        $oldImageName = basename($imageURL);

        // Create the new image file name and path
        $newImagePath = 'public/config/uploads/' . $oldImageName;

        // Check if the same image name already exists
        if (Storage::exists($newImagePath)) {
            // Return the existing file path of the image
            return config('app.url') . Storage::url($newImagePath);
        }

        // Store the new image in the storage
        Storage::put($newImagePath, $imageData);

        // Get the new file path of the uploaded image
        $newFilePath = Storage::url($newImagePath);

        // Return the new file path of the uploaded image
        return config('app.url') . $newFilePath;
    }

    public function generateRefCode($prefix, $name): string
    {
        $count = User::count() + 1;
        return strtoupper(substr(preg_replace('/[^a-zA-Z_]/', "", $name), 0, 3) . Carbon::now()->format('y') . 'U' . bin2hex(random_bytes(4)) . $count);
    }

    /**
     * The function generates a unique transaction ID by concatenating a randomly generated string of numbers and uppercase letters with the current date in the format STX<randomString><ddmmyy>. 
     * The length of the random string can be specified as an optional parameter, which defaults to 8 characters if not provided.
     * The function first defines a string of characters that can be used to generate the random string. It then uses a loop to select a random character from this string and append it to the randomString variable for the specified length. 
     * Finally, it returns the concatenated string with the 'STX' prefix and the current date in the format dmy.
     */
    public function getTransactionUID($length = 8)
    {
        // Generate a random string of numbers and uppercase letters with a specified length and append the current date
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'TX' . $randomString . date('dmy');
    }

    /**
     * The function generates a random transaction ID with a prefix "TP" followed by a random string of uppercase letters and numbers of a specified length and the current date in the format 'dmy'.
     * The function first declares the available characters to use in the generated string and its length. It then initializes an empty string variable $randomString to hold the randomly generated characters.
     * It then uses a for loop to generate a random string of characters with a length specified by the input parameter $length. The loop generates a random index of the available characters using the rand() function and appends the character to the $randomString variable.
     * Finally, the function returns the generated transaction ID string with the prefix "TP", the random string of characters, and the current date in the format 'dmy'.
     */
    public function genTransactionID($length = 8)
    {
        // Generate a random string of numbers and uppercase letters with a specified length and append the current date with a different prefix
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'IN' . $randomString . date('dmy');
    }

    /**
     * Generates a random ID with a specified prefix and length.
     * @param string $prefix The prefix to be added to the random ID.
     * @param int $length The length of the random ID (excluding the prefix and date).
     * @return string The generated random ID.
     */
    public function generateRandomID($prefix = '', $length = 8)
    {
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $prefix . $randomString . date('dmy');
    }

    /**
     * Resize an uploaded image, save it as a JPEG, and return its URL.
     *
     * @param \Illuminate\Http\UploadedFile $file The uploaded image file.
     *
     * @return string The URL of the resized and converted image.
     */
    public function resizeImage($file)
    {
        // Get the uploaded file
        $uploadedFile = $file;

        // Create a temporary file with a unique name and the '.jpg' extension
        $tempPath = tempnam(sys_get_temp_dir(), 'upload') . '.jpg';

        // Create an image instance from the uploaded file, resize it to 300x200 pixels, and encode it as a JPEG with 80% quality
        $img = Image::make($uploadedFile)->resize(300, 200)->encode('jpg', 80);

        // Save the resized image to the temporary path
        $img->save($tempPath);

        // Generate the converted file URL by replacing 'public' with 'storage' in the path and prepending it with the application's URL
        $convertedFile = env("APP_URL") . "/" . str_replace("public", "storage", (new UploadedFile($tempPath, $uploadedFile->getClientOriginalName()))->store("public/thumbnails"));

        // Return the URL of the converted image
        return $convertedFile;
    }
}
