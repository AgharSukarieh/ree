<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Activity::with('user');

        if ($request->has('qr_id')) {
            $query->where('qr_id', $request->qr_id);
        }

        $activities = $query->get();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qr_id' => 'required|string|exists:users,qr_id',
            'activity_title' => 'required|string|max:100',
            'organization' => 'required|string|max:100',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'activity_link' => 'nullable|url|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $activity = Activity::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'data' => $activity->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $activity = Activity::with('user')->find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'qr_id' => 'sometimes|required|string|exists:users,qr_id',
            'activity_title' => 'sometimes|required|string|max:100',
            'organization' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string',
            'activity_date' => 'sometimes|required|date',
            'activity_link' => 'nullable|url|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $activity->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'data' => $activity->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully'
        ]);
    }

    /**
     * Get activities by user QR ID
     */
    public function byUser(string $qr_id): JsonResponse
    {
        $user = User::find($qr_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $activities = $user->activities;

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
}