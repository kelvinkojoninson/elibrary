<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogs;
use App\Http\Resources\UserResource;
use App\Models\Roles;
use App\Models\User;
use App\Services\MiscService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private $miscService;  // Private property to store the MiscService instance

    public function __construct(MiscService $miscService)
    {
        $this->miscService = $miscService;  // Inject the MiscService instance into the controller
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
                "data" => [],
                "draw" => (int) $request->dt_draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
            ]);
        }

        // This code will not be executed if the permission check failed

        $query = User::query();

        // Apply filters
        $query->when($request->dt_search, function ($q) use ($request) {
            return $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->dt_search . '%')
                    ->orWhere('userid', 'like', '%' . $request->dt_search . '%')
                    ->orWhere('email', 'like', '%' . $request->dt_search . '%')
                    ->orWhere('phone', 'like', '%' . $request->dt_search . '%')
                    ->orWhere('status', 'like', '%' . $request->dt_search . '%')
                    ->orWhere('dob', 'like', '%' . $request->dt_search . '%');
            });
        })->when($request->status && count($request->status) > 0, function ($q)  use ($request) {
            return $q->whereIn('status', $request->status);
        })->when($request->has('isDate') && $request->dateCreated, function ($q)  use ($request) {
            return $q->whereBetween('created_at', [Carbon::createFromFormat('m/d/Y', trim(explode('-', $request->dateCreated)[0]))->format('Y-m-d'), Carbon::createFromFormat('m/d/Y', trim(explode('-', $request->dateCreated)[1]))->format('Y-m-d')]);
        })->when($request->roleId && count($request->roleId) > 0, function ($q)  use ($request) {
            return $q->whereIn('role_id', $request->roleId);
        });

        // Get the total count of records
        $total = $query->count();

        // Apply pagination and ordering
        $data = $query->offset($request->dt_start)
            ->limit($request->dt_length)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => UserResource::collection($data),
            "draw" => (int)$request->dt_draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total, // Use total count for filtered records count
            "request" => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
            ]);
        }

        // Create a validator instance and define validation rules
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'email' => ['required', 'email', 'unique:' . User::class . ',email'],
            'password' => ['required'],
            'roleId' => ['required', 'exists:' . Roles::class . ',id'],
        ], [
            // Define custom error messages for validation rules
            "name.required" => "Name is required!",
            "name.string" => "Name must be a string!",
            "name.max" => "Name max length is 255 characters!",
            "email.required" => "No email supplied",
            "email.email" => "Please provide a valid email",
            "email.unique" => "Email already taken",
            "avatar.image" => "Uploaded file must be an image",
            "avatar.mimes" => "Uploaded file must have a jpeg, png, jpg, gif, or svg extension",
            "avatar.max" => "Uploaded file size should not exceed 2MB",
            "password.required" => "Password is required!",
            "roleId.required" => "Role is required!",
            "roleId.exists" => "Role not found!",
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        // If permission and validation are successful, continue processing the request
        try {
            $transResult = DB::transaction(function () use ($request) {
                // Initialize the file path variable
                $filePath = null;

                // Check if the 'avatar' file exists in the request
                if ($request->hasFile('avatar')) {
                    // Generate the file path for storing the avatar
                    $filePath = env("APP_URL") . "/" . str_replace("public", "storage", $request->file("avatar")->store("public/users"));
                }

                // Create a new user record in the database
                $user = User::create([
                    'userid' => $this->miscService->generateUserid($request->name),
                    'name' => $request->name,
                    'email' =>  $request->email,
                    'phone' =>  $request->phone,
                    'password' => bcrypt($request->password),
                    'picture' => $filePath,
                    'role_id' => $request->roleId,
                    'status' => $request->status,
                    'createuser' => $request->user()->userid,
                    'email_verified_at' => $request->has('sendVerification') ? null : Carbon::now()->getTimestamp(),
                ]);

                // Check if 'sendVerification' flag is set
                if ($request->has('sendVerification')) {
                    // Trigger the 'Registered' event for email verification
                    event(new Registered($user));
                }

                // Log the user creation event
                event(new UserLogs($request->user()->userid, 'Users', "User created with ID $user->userid", 'POST /api/users', '201'));
            });

            // Check if any exception occurred during the transaction
            if (!empty($transResult)) {
                // Throw an exception with the transaction result
                throw new Exception($transResult);
            }

            // Return a JSON response indicating successful record addition
            return response()->json([
                "ok" => true,
                "msg" => "Record added successfully",
            ]);
        } catch (\Exception $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An error occured while adding record, please contact admin",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    public function setFCMToken(Request $request)
    {
        // Create a validator instance and define validation rules
        $validator = Validator::make($request->all(), [
            'token' => ['required']
        ], [
            // Define custom error messages for validation rules
            "token.required" => "FCM token is required!",
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        // If permission and validation are successful, continue processing the request
        try {
            $user = User::where('userid', $request->user()->userid)->first();
            $user->update(['firebaseKey' => $request->token]);

            // Return a JSON response indicating successful record addition
            return response()->json([
                "ok" => true,
                "msg" => "In App notification enabled",
            ]);
        } catch (\Exception $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An error occured while adding record, please contact admin",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
            ]);
        }

        // Create a validator instance and define validation rules
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:' . User::class . ',id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique(User::class, 'email')->ignore($request->id, 'id')],
            'avatar' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'roleId' => ['required', 'exists:' . Roles::class . ',id'],
        ], [
            // Define custom error messages for validation rules
            "name.required" => "Name is required!",
            "name.string" => "Name must be a string!",
            "name.max" => "Name max length is 255 characters!",
            "email.required" => "No email supplied",
            "email.email" => "Please provide a valid email",
            "email.unique" => "Email already taken",
            "avatar.image" => "Uploaded file must be an image",
            "avatar.mimes" => "Uploaded file must have a jpeg, png, jpg, gif or svg extension",
            "avatar.max" => "Uploaded file must size should not be more than 2MB",
            "id.required" => "User ID is required!",
            "id.exists" => "User not found!",
            "roleId.required" => "Role is required!",
            "roleId.exists" => "Role not found!",
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        // If permission and validation are successful, continue processing the request
        try {
            $transResult = DB::transaction(function () use ($request) {
                // Find the user record by ID
                $user = User::find($request->id);

                // Get the existing picture file path
                $filePath = $user->picture;

                // Check if the 'avatar' file exists in the request
                if ($request->hasFile('avatar')) {
                    // Delete the existing picture file if it exists
                    if ($user->picture) {
                        $path = explode('/storage/', $user->picture);
                        $file = end($path);
                        if (File::exists(public_path("storage/$file"))) {
                            File::delete(public_path("storage/$file"));
                        }
                    }
                    // Generate the new file path for storing the updated avatar
                    $filePath = env("APP_URL") . "/" . str_replace("public", "storage", $request->file("avatar")->store("public/users"));
                }

                // Update the user record with the provided data
                $user->update([
                    'name' => $request->name,
                    'email' =>  $request->email,
                    'phone' =>  $request->phone,
                    'picture' => $filePath,
                    'country' =>  $request->country,
                    'gender' =>  $request->gender ? strtoupper($request->gender) : $user->gender,
                    'dob' =>  $request->dob,
                    'role_id' => $request->roleId,
                    'password' => $request->password ? bcrypt($request->password) : $user->password,
                    'status' => $request->status,
                    'modifyuser' => $request->user()->userid,
                    'email_verified_at' => $request->has('sendVerification') ? null : Carbon::now()->getTimestamp(),
                ]);

                // Check if 'sendVerification' flag is set
                if ($request->has('sendVerification')) {
                    // Trigger the 'Registered' event for email verification
                    event(new Registered($user));
                }

                // Log the user update event
                event(new UserLogs($request->user()->userid, 'Users', "User updated with ID $user->userid", 'POST /api/users/update', '201'));
            });

            // Check if any exception occurred during the transaction
            if (!empty($transResult)) {
                // Throw an exception with the transaction result
                throw new Exception($transResult);
            }

            // Return a JSON response indicating successful update
            return response()->json([
                "ok" => true,
                "msg" => "Account updated successfully.",
            ], 201);
        } catch (\Throwable $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An error occurred while updating the record, please contact the admin",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(Request $request)
    {
        // Create a validator instance and define validation rules
        $validator = Validator::make(
            $request->all(),
            [
                "password" => "required",
            ],
            [
                // Define custom error messages for validation rulesF
                "password.required" => "Password is required",
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        try {
            // Check if the provided password matches the user's hashed password
            if (!Hash::check($request->password, $request->user()->password)) {
                // Return a JSON response indicating incorrect password
                return response()->json([
                    "ok" => false,
                    "msg" => "Incorrect password",
                ]);
            }

            // Update the user record with the deactivation reason and modified email
            // Email is concatenated with _date('ymdhis') becuase it is a unique column in the database and since the record is not deleted permanently from database then there is the need to add the date to distinguish it
            $request->user()->update(
                [
                    'deactivation_reason' => implode(',', $request->deleteReason),
                    'email' => $request->user()->email . '_' . date('ymdhis')
                ]
            );

            // Logout the currently authenticated user
            Auth::logout();
            event(new UserLogs($request->userid, 'Profile', "User Account with id " . $request->user()->userid . " deleted", 'POST /api/users/delete', '201'));

            // Return a JSON response indicating successful deletion of the user
            return response()->json([
                "ok" => true,
                "msg" => "Account deleted.",
            ]);
        } catch (\Throwable $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "Request failed. An internal error occured",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Remove the specified user records from storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
            ]);
        }

        try {
            // Iterate over the selected user IDs from the JSON input
            foreach (json_decode($request->selected) as $key => $value) {
                // Find the user record by ID
                $user = User::find($value);

                // Check if the user record exists
                if ($user) {
                    // Update the user status to 'PENDING'
                    $user->update([
                        'status' => 'INACTIVE',
                        'email' => $user->email . '_' . date('ymdhis')
                    ]);

                    // Delete the user record
                    $user->delete();
                    event(new UserLogs($request->user()->userid, 'Users', "User account with id $value deleted", 'POST /api/users/delete', '201'));
                }
            }

            // Return a JSON response indicating successful deactivation
            return response()->json([
                "ok" => true,
                "msg" => "Users deactivated successfully",
            ]);
        } catch (\Throwable $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "Request failed. An internal error occurred",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Reset the password for the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        // Create a validator instance and define validation rules
        $validator = Validator::make(
            $request->all(),
            [
                "password" => "required|min:8",
                "current_password" => "required",
            ],
            [
                // Define custom error messages for validation rules
                "password.required" => "You have to supply your new password",
                "password.min" => "Your new password must be at least 8 characters long",
                "current_password.required" => "You have to supply your current password",
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        //update new password with the authenticated user
        try {
            // Return if current password is invalid
            if (!Hash::check($request->current_password, $request->user()->password)) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Incorrect password",
                ]);
            }

            // Update password
            $request->user()->update([
                'password' => bcrypt($request->password),
            ]);

            event(new UserLogs($request->user()->userid, 'Profile', "User " . $request->user()->userid . " password updated with", 'POST /api/users/reset-password', '201'));

            // Return a JSON response indicating successful update
            return response()->json([
                "ok" => true,
                "msg" => "Password changed!",
            ]);
        } catch (\Exception $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An internal error occured. Reset failed",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Add two-factor authentication for the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addTwoFactorAuthentication(Request $request)
    {
        // Create a validator instance and define validation rules
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|email"
            ],
            [
                // Define custom error messages for validation rulesF
                "email.required" => "Email is required",
                "email.email" => "A valid email is required",
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        try {
            // Find the user record by ID
            $user = $request->user();

            // Check if the user's provided email is the same as their current email
            if ($user->email == $request->email) {
                // Return a JSON response indicating that the provided email cannot be used for two-factor authentication
                return response()->json([
                    "ok" => false,
                    "msg" => "You cannot use your sign-in email for two-factor authentication. Please use another valid email.",
                ]);
            }

            // Update the user's two-step authentication setting with the provided email
            $user->update([
                'two_step' => $request->email,
            ]);

            // Store the OTP, timestamp, and verification status in the session
            $request->session()->put(
                "user_2fa",
                [
                    "otp" => Hash::make(time()), // Hash the OTP for security
                    "timestamp" => time(), // Current timestamp
                    "verified" => true // Initial verification status is set to false
                ]
            );

            // Log the user profile event for enabling two-step authentication
            event(new UserLogs($user->userid, 'Profile', "Enabled two-step authentication", 'POST /api/users/add-2fa', '201'));

            // Return a JSON response indicating successful two-factor authentication setup
            return response()->json([
                "ok" => true,
                "msg" => "Two-factor authentication set!",
            ]);
        } catch (\Exception $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An internal error occured. Reset failed",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    /**
     * Remove the specified user's two-factor authentication from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeTwoFactorAuthentication(Request $request)
    {
        try {
            // Update user two factor authentication to null
            $request->user()->update(['two_step' => NULL]);

            // Log the user profile event for disabling two-step authentication
            event(new UserLogs($request->user()->userid, 'Profile', "Disabled two step authentication", 'POST /api/users/remove-2fa', '201'));

            // Return a JSON response indicating successful two-factor authentication removal
            return response()->json([
                "ok" => true,
                "msg" => "Two factor authentication disabled!",
            ]);
        } catch (\Exception $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "An internal error occured. Reset failed",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }
}
