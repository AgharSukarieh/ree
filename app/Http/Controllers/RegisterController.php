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
use App\Models\Research;
use App\Models\Certification;
use App\Models\Membership;
use App\Models\AnalyticalSkill;
use App\Models\CoreCompetency;
use App\Models\Interest;
use App\Services\CloudinaryService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Route;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function success(Request $request)
    {
        // Get data from session (passed from store method)
        $qr_id = session('qr_id');
        $qr_code_url = session('qr_code_url');
        $profile_url = session('profile_url');

        // If no session data, redirect to register
        if (!$qr_id || !$profile_url) {
            return redirect()->route('register')->with('error', 'لم يتم العثور على بيانات السيرة الذاتية');
        }

        return view('success', [
            'qr_id' => $qr_id,
            'qr_code_url' => $qr_code_url,
            'profile_url' => $profile_url
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:60',
            'jop_title' => 'required|string|max:60',
            'phone' => 'nullable|string|max:60',
            'email' => ['nullable', 'email', 'max:255', function ($attribute, $value, $fail) {
                if (!empty($value) && User::where('email', $value)->exists()) {
                    $fail('البريد الإلكتروني مستخدم بالفعل.');
                }
            }],
            'city' => 'nullable|string|max:60',
            'major' => 'required|in:IT,Medicine,Business,Engineering',
            'linkedin_profile' => 'nullable|url|max:255',
            'github_profile' => 'nullable|url|max:255',
            'profile_summary' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'project_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per project image
        ]);

        try {
            // Generate unique QR ID
            $qr_id = 'USER' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            
            // Ensure QR ID is unique
            while (User::where('qr_id', $qr_id)->exists()) {
                $qr_id = 'USER' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }

            // Handle profile image upload to Cloudinary
            $profile_image = '';
            if ($request->hasFile('profile_image')) {
                try {
                    $cloudinaryService = new CloudinaryService();
                    // استخدام QR ID في public_id للصورة
                    $uploadResult = $cloudinaryService->uploadImage(
                        $request->file('profile_image'),
                        'profiles',
                        [
                            'public_id' => "profiles/user_{$qr_id}",
                            'overwrite' => true,
                            'transformation' => [
                                ['width' => 400, 'height' => 400, 'crop' => 'fill'],
                                ['quality' => 'auto'],
                                ['fetch_format' => 'auto']
                            ]
                        ]
                    );
                    $profile_image = $uploadResult['url']; // حفظ رابط Cloudinary في قاعدة البيانات
                } catch (\Exception $e) {
                    \Log::error('Cloudinary upload failed during registration', [
                        'error' => $e->getMessage(),
                        'qr_id' => $qr_id
                    ]);
                    // في حالة فشل الرفع، يمكنك إما رمي استثناء أو المتابعة بدون صورة
                    // سنتابع بدون صورة في هذه الحالة
                    $profile_image = '';
                }
            }

            // Create user
            // Convert empty strings to null for nullable fields to avoid UNIQUE constraint issues
            $user = User::create([
                'qr_id' => $qr_id,
                'name' => $request->name,
                'phone' => !empty($request->phone) ? $request->phone : null,
                'city' => !empty($request->city) ? $request->city : null,
                'job_title' => $request->jop_title,
                'profile_summary' => !empty($request->profile_summary) ? $request->profile_summary : null,
                'email' => !empty($request->email) ? $request->email : null, // Use null instead of empty string
                'linkedin_profile' => !empty($request->linkedin_profile) ? $request->linkedin_profile : null,
                'github_profile' => !empty($request->github_profile) ? $request->github_profile : null,
                'profile_website' => !empty($request->profile_website) ? $request->profile_website : null,
                'profile_image' => !empty($profile_image) ? $profile_image : null,
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
                            'description' => !empty($request->description[$index]) ? $request->description[$index] : '',
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
                        // Handle project image upload to Cloudinary
                        $project_image = null;
                        // Check if project_image array exists and has file at this index
                        if ($request->hasFile('project_image') && isset($request->file('project_image')[$index])) {
                            try {
                                $cloudinaryService = new CloudinaryService();
                                $uploadResult = $cloudinaryService->uploadImage(
                                    $request->file('project_image')[$index],
                                    'projects',
                                    [
                                        'public_id' => "projects/user_{$qr_id}_project_{$index}",
                                        'overwrite' => true,
                                        'transformation' => [
                                            ['width' => 800, 'height' => 600, 'crop' => 'fill'],
                                            ['quality' => 'auto'],
                                            ['fetch_format' => 'auto']
                                        ]
                                    ]
                                );
                                $project_image = $uploadResult['url'];
                            } catch (\Exception $e) {
                                \Log::error('Cloudinary project image upload failed', [
                                    'error' => $e->getMessage(),
                                    'qr_id' => $qr_id,
                                    'project_index' => $index
                                ]);
                                $project_image = null;
                            }
                        }

                        Project::create([
                            'qr_id' => $qr_id,
                            'project_title' => $project_title,
                            'description' => !empty($request->description_project[$index]) ? $request->description_project[$index] : '',
                            'technologies_used' => !empty($request->technologies_used[$index]) ? $request->technologies_used[$index] : null,
                            'link' => !empty($request->link[$index]) ? $request->link[$index] : null,
                            'project_image' => $project_image
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

            // Handle Medical Research
            if ($request->has('research_title') && $request->major === 'Medicine') {
                foreach ($request->research_title as $index => $research_title) {
                    if (!empty($research_title)) {
                        Research::create([
                            'qr_id' => $qr_id,
                            'title' => $research_title,
                            'publication_year' => $request->publication_year[$index] ?? date('Y'),
                            'description' => !empty($request->research_description[$index]) ? $request->research_description[$index] : '',
                            'link' => !empty($request->research_link[$index]) ? $request->research_link[$index] : null
                        ]);
                    }
                }
            }

            // Handle Certifications
            if ($request->has('certifications_name')) {
                foreach ($request->certifications_name as $index => $cert_name) {
                    if (!empty($cert_name)) {
                        Certification::create([
                            'qr_id' => $qr_id,
                            'certifications_name' => $cert_name,
                            'issuing_org' => $request->issuing_org[$index] ?? '',
                            'issue_date' => $request->issue_date[$index] ?? null,
                            'expiration_date' => $request->expiration_date[$index] ?? null,
                            'link' => $request->link_driver[$index] ?? null
                        ]);
                    }
                }
            }

            // Handle Memberships
            if ($request->has('organization_name')) {
                foreach ($request->organization_name as $index => $org_name) {
                    if (!empty($org_name)) {
                        Membership::create([
                            'qr_id' => $qr_id,
                            'organization_name' => $org_name,
                            'membership_type' => $request->membership_type[$index] ?? '',
                            'start_date' => $request->start_date_membership[$index] ?? null,
                            'end_date' => $request->end_date_membership[$index] ?? null,
                            'membership_status' => $request->membership_status[$index] ?? 'Active'
                        ]);
                    }
                }
            }

            // Handle Activities
            if ($request->has('activity_title')) {
                foreach ($request->activity_title as $index => $activity_title) {
                    if (!empty($activity_title)) {
                        Activity::create([
                            'qr_id' => $qr_id,
                            'activity_title' => $activity_title,
                            'organization' => $request->organization[$index] ?? '',
                            'activity_date' => $request->activity_date[$index] ?? date('Y-m-d'),
                            'description' => $request->description_activity[$index] ?? '',
                            'activity_link' => $request->activity_link[$index] ?? null
                        ]);
                    }
                }
            }

            // Handle Analytical Skills
            if ($request->has('analytical_skill_name') && $request->major === 'IT') {
                foreach ($request->analytical_skill_name as $analytical_skill_name) {
                    if (!empty($analytical_skill_name)) {
                        AnalyticalSkill::create([
                            'qr_id' => $qr_id,
                            'skill_name' => $analytical_skill_name
                        ]);
                    }
                }
            }

            // Handle Core Competencies
            if ($request->has('competency_name')) {
                foreach ($request->competency_name as $index => $competency_name) {
                    if (!empty($competency_name)) {
                        CoreCompetency::create([
                            'qr_id' => $qr_id,
                            'competency_name' => $competency_name,
                            'description' => $request->competency_description[$index] ?? ''
                        ]);
                    }
                }
            }

            // Handle Interests
            if ($request->has('interest_name')) {
                foreach ($request->interest_name as $interest_name) {
                    if (!empty($interest_name)) {
                        Interest::create([
                            'qr_id' => $qr_id,
                            'interest_name' => $interest_name
                        ]);
                    }
                }
            }

            // Generate QR Code
            $profile_url = route('profile', $qr_id);
            $qr_code_url = '';
            
            try {
                // Generate QR Code as SVG (doesn't require ImageMagick)
                // Then convert to PNG using base64 data URI for Cloudinary
                $qr_code_svg = QrCode::format('svg')
                    ->size(400)
                    ->margin(2)
                    ->generate($profile_url);
                
                // Save QR Code SVG temporarily
                $temp_dir = storage_path('app/temp');
                if (!file_exists($temp_dir)) {
                    mkdir($temp_dir, 0755, true);
                }
                $temp_svg_path = $temp_dir . '/qr_' . $qr_id . '.svg';
                file_put_contents($temp_svg_path, $qr_code_svg);
                
                // Upload SVG to Cloudinary (Cloudinary can handle SVG and convert to PNG)
                $cloudinaryService = new CloudinaryService();
                $uploadResult = $cloudinaryService->uploadImage(
                    $temp_svg_path,
                    'qr_codes',
                    [
                        'public_id' => "user_{$qr_id}", // بدون المجلد لأن folder محدد
                        'overwrite' => true,
                        'transformation' => [
                            ['width' => 400, 'height' => 400, 'crop' => 'fill'],
                            ['format' => 'png'], // Cloudinary will convert SVG to PNG
                            ['quality' => 'auto']
                        ]
                    ]
                );
                
                $qr_code_url = $uploadResult['url'];
                
                // Delete temporary file
                if (file_exists($temp_svg_path)) {
                    unlink($temp_svg_path);
                }
                
                \Log::info('QR Code generated and uploaded successfully', [
                    'qr_id' => $qr_id,
                    'qr_code_url' => $qr_code_url
                ]);
            } catch (\Exception $e) {
                \Log::error('QR Code generation/upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'qr_id' => $qr_id
                ]);
                // Continue without QR code if generation fails
            }

            // Redirect to success page with data
            // Check if request is AJAX (from fetch API)
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                // Store data in session first
                session([
                    'qr_id' => $qr_id,
                    'qr_code_url' => $qr_code_url,
                    'profile_url' => $profile_url
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الملف الشخصي بنجاح!',
                    'redirect_url' => route('register.success')
                ]);
            }
            
            return redirect()->route('register.success')->with([
                'qr_id' => $qr_id,
                'qr_code_url' => $qr_code_url,
                'profile_url' => $profile_url
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
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['profile_image', 'project_image'])
            ]);
            
            // Return detailed error in development, generic in production
            $errorMessage = config('app.debug') 
                ? 'حدث خطأ: ' . $e->getMessage() . ' في الملف: ' . basename($e->getFile()) . ' السطر: ' . $e->getLine()
                : 'حدث خطأ أثناء إنشاء الملف الشخصي. يرجى المحاولة مرة أخرى.';
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }
}