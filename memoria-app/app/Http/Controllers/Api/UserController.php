<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with([
            'activities',
            'analyticalSkills',
            'certifications',
            'coreCompetencies',
            'experiences',
            'interests',
            'languages',
            'medicalSkills.category',
            'memberships',
            'projects',
            'research',
            'skills.category',
            'softSkills',
            'wishes'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qr_id' => 'required|string|unique:users,qr_id|max:255',
            'name' => 'required|string|max:60',
            'phone' => 'required|string|max:60',
            'city' => 'required|string|max:60',
            'job_title' => 'required|string|max:60',
            'profile_summary' => 'required|string',
            'email' => 'required|email|unique:users,email|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'github_profile' => 'nullable|url|max:255',
            'profile_website' => 'nullable|url|max:255',
            'profile_image' => 'nullable|string|max:255',
            'major' => 'required|in:IT,Medicine,Engineering,Business',
            'status' => 'nullable|integer|min:0|max:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $qr_id): JsonResponse
    {
        $user = User::with([
            'activities',
            'analyticalSkills',
            'certifications',
            'coreCompetencies',
            'experiences',
            'interests',
            'languages',
            'medicalSkills.category',
            'memberships',
            'projects',
            'research',
            'skills.category',
            'softSkills',
            'wishes'
        ])->find($qr_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $qr_id): JsonResponse
    {
        $user = User::find($qr_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:60',
            'phone' => 'sometimes|required|string|max:60',
            'city' => 'sometimes|required|string|max:60',
            'job_title' => 'sometimes|required|string|max:60',
            'profile_summary' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $qr_id . ',qr_id|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'github_profile' => 'nullable|url|max:255',
            'profile_website' => 'nullable|url|max:255',
            'profile_image' => 'nullable|string|max:255',
            'major' => 'sometimes|required|in:IT,Medicine,Engineering,Business',
            'status' => 'nullable|integer|min:0|max:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $qr_id): JsonResponse
    {
        $user = User::find($qr_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get user profile by QR ID
     */
    public function profile(string $qr_id): JsonResponse
    {
        $user = User::with([
            'activities',
            'analyticalSkills',
            'certifications',
            'coreCompetencies',
            'experiences',
            'interests',
            'languages',
            'medicalSkills.category',
            'memberships',
            'projects',
            'research',
            'skills.category',
            'softSkills',
            'wishes'
        ])->where('status', 1)->find($qr_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found or inactive'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}