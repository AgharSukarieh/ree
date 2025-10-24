<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use App\Models\Experience;
use App\Models\Language;
use App\Models\SoftSkill;
use App\Models\Skill;
use App\Models\Project;
use App\Models\MedicalSkill;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:60',
            'jop_title' => 'required|string|max:60',
            'phone' => 'nullable|string|max:60',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:60',
            'major' => 'required|in:IT,Medicine,Business,Engineering',
            'linkedin_profile' => 'nullable|url|max:255',
            'github_profile' => 'nullable|url|max:255',
            'profile_summary' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
            // Generate unique QR ID
            $qr_id = 'USER' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            
            // Ensure QR ID is unique
            while (User::where('qr_id', $qr_id)->exists()) {
                $qr_id = 'USER' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }

            // Handle profile image upload
            $profile_image = '';
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . $qr_id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/profiles', $filename);
                $profile_image = 'profiles/' . $filename;
            }

            // Create user
            $user = User::create([
                'qr_id' => $qr_id,
                'name' => $request->name,
                'phone' => $request->phone ?? '',
                'city' => $request->city ?? '',
                'job_title' => $request->jop_title,
                'profile_summary' => $request->profile_summary ?? '',
                'email' => $request->email ?? '',
                'linkedin_profile' => $request->linkedin_profile ?? '',
                'github_profile' => $request->github_profile ?? '',
                'profile_website' => $request->profile_website ?? '',
                'profile_image' => $profile_image,
                'major' => $request->major,
                'status' => 1
            ]);

            // Handle languages
            if ($request->has('language_name')) {
                foreach ($request->language_name as $index => $language_name) {
                    if (!empty($language_name)) {
                        Language::create([
                            'qr_id' => $qr_id,
                            'language_name' => $language_name,
                            'proficiency_level' => $request->proficiency_level[$index] ?? 'Beginner'
                        ]);
                    }
                }
            }

            // Handle soft skills
            if ($request->has('soft_name')) {
                foreach ($request->soft_name as $soft_name) {
                    if (!empty($soft_name)) {
                        SoftSkill::create([
                            'qr_id' => $qr_id,
                            'soft_name' => $soft_name
                        ]);
                    }
                }
            }

            // Handle experiences
            if ($request->has('title')) {
                foreach ($request->title as $index => $title) {
                    if (!empty($title)) {
                        Experience::create([
                            'qr_id' => $qr_id,
                            'title' => $title,
                            'company' => $request->company[$index] ?? '',
                            'location' => $request->location[$index] ?? '',
                            'start_date' => $request->start_date[$index] ?? '',
                            'end_date' => $request->end_date[$index] ?? null,
                            'description' => $request->description[$index] ?? '',
                            'is_internship' => isset($request->is_internship[$index]) ? 1 : 0
                        ]);
                    }
                }
            }

            // Handle IT Skills
            if ($request->has('skill_name') && $request->major === 'IT') {
                foreach ($request->skill_name as $index => $skill_name) {
                    if (!empty($skill_name)) {
                        Skill::create([
                            'qr_id' => $qr_id,
                            'skill_name' => $skill_name,
                            'category_id' => $request->category_id[$index] ?? 1
                        ]);
                    }
                }
            }

            // Handle IT Projects
            if ($request->has('project_title') && $request->major === 'IT') {
                foreach ($request->project_title as $index => $project_title) {
                    if (!empty($project_title)) {
                        Project::create([
                            'qr_id' => $qr_id,
                            'project_title' => $project_title,
                            'description' => $request->description_project[$index] ?? '',
                            'technologies_used' => $request->technologies_used[$index] ?? '',
                            'link' => $request->link[$index] ?? ''
                        ]);
                    }
                }
            }

            // Handle Medical Skills
            if ($request->has('medical_skill_name') && $request->major === 'Medicine') {
                foreach ($request->medical_skill_name as $index => $medical_skill_name) {
                    if (!empty($medical_skill_name)) {
                        MedicalSkill::create([
                            'qr_id' => $qr_id,
                            'skill_name' => $medical_skill_name,
                            'category_id' => $request->medical_category_id[$index] ?? 1
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء ملفك الشخصي بنجاح!',
                'qr_id' => $qr_id,
                'redirect_url' => route('profile', $qr_id)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من صحة البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('CV Registration Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['profile_image'])
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الملف الشخصي. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }
}