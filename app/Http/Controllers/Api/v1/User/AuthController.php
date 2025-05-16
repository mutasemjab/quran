<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\ClassTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\FCMController; // <-- Import the FCMController here
use App\Models\ParentStudent;
use Auth;

class AuthController extends Controller
{

    public function active()
    {
        $user = auth()->user();
        if ($user->activate == 2) {
            return response(['errors' => ['Your account has been InActive']], 403);
        }

        return response()->json(['user' => $user]);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        try {
            // Update the `activate` column to 2
            $user->update(['activate' => 2]);

            return response()->json(['message' => 'Account Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to deactivate account', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'required', // Can be either email or username
            'user_type' => 'required', // 1: User, 2: Teacher, 3: Parent
            'password' => 'required'
        ]);

        // Find user by email or username
        $user = User::where('email', $data['login'])
                    ->orWhere('username', $data['login'])
                    ->where('user_type', $data['user_type'])
                    ->first();

        if (!$user) {
            return response(['errors' => ['User not found or user type mismatch']], 402);
        }

        // Check if user is not active
        if ($user->activate == 2) {
            return response(['errors' => ['User account is not active']], 403);
        }

        // Attempt authentication
        if (!Auth::guard('user')->attempt([
            'email' => $user->email, // Authenticate using email
            'password' => $data['password']
        ], $request->remember)) {
            return response(['errors' => ['Password is not correct']], 402);
        }

        // Generate access token
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        // Save FCM token if provided
        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

            // Retrieve all brothers (users with the same family_id, excluding the logged-in user)
            $brothers = User::with('clas')
            ->where('user_type', 1)
            ->whereNotNull('family_id') // Ensure family_id is not null
            ->where('family_id', $user->family_id) // Match the logged-in user's family_id
            ->where('id', '!=', $user->id) // Exclude the logged-in user
            ->get();

            // Generate access tokens for each brother and include their full data
            $brothersWithTokens = $brothers->map(function ($brother) {
            $brotherToken = $brother->createToken('authToken')->accessToken;
            $brotherData = $brother->toArray();  // Convert brother data to array for easy merging
            $brotherData['token'] = $brotherToken; // Add token to brother data
            return $brotherData;
            });

            // Include the logged-in user's full data with the token
            $userData = $user->toArray();
            $userData['token'] = $accessToken;

          // Return user data with token and brothers' data with their tokens
            return response([
                'user' => $userData,
                'brothers' => $brothersWithTokens
            ], 200);
    }



    public function register(Request $request)
    {
        // Validate input data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:4',
            'user_type' => 'required|in:1,2,3', // 1: User, 2: Teacher, 3: Parent
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Optional photo upload
        ]);

        // Handle file upload for photo (if provided)
        $photoPath = null;
        if ($request->has('photo')) {
            $photoPath = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Hash the password
        $hashedPassword = Hash::make($data['password']);

        // Create the user in the `users` table
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $hashedPassword,
            'user_type' => $data['user_type'],
            'phone' => $data['phone'] ?? null,
            'photo' => $photoPath,
            'activate' => 1,
        ]);

        // Add additional data for teachers or parents
        if ($data['user_type'] == 2) { // Teacher
            Teacher::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $hashedPassword,
                'phone' => $data['phone'] ?? null,
                'photo' => $photoPath,
                'user_id' => $user->id,
            ]);
        } elseif ($data['user_type'] == 3) { // Parent
            ParentStudent::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $hashedPassword,
                'phone' => $data['phone'] ?? null,
                'photo' => $photoPath,
                'user_id' => $user->id,
            ]);
        }

        // Create access token
        $accessToken = $user->createToken('authToken')->accessToken;

        // Return the registered user data with the token
        return response([
            'user' => $user,
            'token' => $accessToken,
        ], 200);
    }

    public function userProfile()
    {
        // Authenticate the user
        $user = auth()->user();

        // Return the user's profile
        return response([
            'user' => $user,
        ], 200);
    }




    public function updateProfile(Request $request)
    {
        // Authenticate the user
        $user = auth()->user();

        // Validate input data
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id, // Ensure email is unique
            'username' => 'nullable|string|unique:users,username,' . $user->id, // Ensure username is unique
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Optional photo upload
        ]);

        // Handle file upload for photo (if provided)

        if ($request->has('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Update user data
        $user->update($data);

        // Return updated user data
        return response([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }



     public function notifications()
    {
        $user = auth()->user();

        // Define user_type-based notification types
        $userTypeMapping = [
            1 => 1, // Regular Users
            2 => 3, // Teachers
            3 => 2, // Parents
        ];

        // Fetch notifications
        $notifications = Notification::query()
            ->where(function ($query) use ($user, $userTypeMapping) {
                $query->where('type', 0) // Global notifications (for all users)
                      ->orWhere(function ($q) use ($user) {
                          // Notifications specifically for this user
                          $q->where('type', 4)->where('user_id', $user->id);
                      });

                // Include user_type-specific notifications if applicable
                if (isset($userTypeMapping[$user->user_type])) {
                    $query->orWhere('type', $userTypeMapping[$user->user_type]);
                }
            })
            ->orderBy('id', 'DESC')
            ->get();

        return response(['data' => $notifications], 200);
    }




    public function sendToUser(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        try {
            // Call the sendMessageToUser method in the FCMController
            $response = FCMController::sendMessageToUser(
                $request->title,
                $request->body,
                $request->user_id,
            );

            if ($response) {

                return redirect()->back()->with('message', 'Notification sent successfully to the user');
            } else {
                return redirect()->back()->with('error', 'Notification was not sent to the user');
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
