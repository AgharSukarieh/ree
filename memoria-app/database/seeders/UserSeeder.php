<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use App\Models\AnalyticalSkill;
use App\Models\Certification;
use App\Models\CoreCompetency;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Language;
use App\Models\MedicalSkill;
use App\Models\Membership;
use App\Models\Project;
use App\Models\Research;
use App\Models\Skill;
use App\Models\SoftSkill;
use App\Models\Wish;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'qr_id' => 'USER001',
                'name' => 'Ahmed Hassan',
                'phone' => '+1234567890',
                'city' => 'Cairo',
                'job_title' => 'Software Developer',
                'profile_summary' => 'Experienced software developer with expertise in web development and mobile applications.',
                'email' => 'ahmed.hassan@example.com',
                'linkedin_profile' => 'https://linkedin.com/in/ahmedhassan',
                'github_profile' => 'https://github.com/ahmedhassan',
                'profile_website' => 'https://ahmedhassan.dev',
                'profile_image' => 'profile1.jpg',
                'major' => 'IT',
                'status' => 1
            ],
            [
                'qr_id' => 'USER002',
                'name' => 'Dr. Sara Mohamed',
                'phone' => '+1234567891',
                'city' => 'Alexandria',
                'job_title' => 'Medical Doctor',
                'profile_summary' => 'Dedicated medical professional with specialization in internal medicine.',
                'email' => 'sara.mohamed@example.com',
                'linkedin_profile' => 'https://linkedin.com/in/saramohamed',
                'github_profile' => '',
                'profile_website' => '',
                'profile_image' => 'profile2.jpg',
                'major' => 'Medicine',
                'status' => 1
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Create sample activities for the user
            if ($user->qr_id === 'USER001') {
                Activity::create([
                    'qr_id' => $user->qr_id,
                    'activity_title' => 'Tech Conference 2024',
                    'organization' => 'Tech Community Egypt',
                    'description' => 'Participated in the annual tech conference as a speaker.',
                    'activity_date' => '2024-03-15',
                    'activity_link' => 'https://techconf-egypt.com'
                ]);

                // Create sample skills
                Skill::create([
                    'qr_id' => $user->qr_id,
                    'skill_name' => 'PHP',
                    'category_id' => 1 // Programming Languages
                ]);

                Skill::create([
                    'qr_id' => $user->qr_id,
                    'skill_name' => 'Laravel',
                    'category_id' => 2 // Web Development
                ]);

                // Create sample experience
                Experience::create([
                    'qr_id' => $user->qr_id,
                    'title' => 'Senior Developer',
                    'company' => 'Tech Solutions Inc.',
                    'location' => 'Cairo',
                    'start_date' => '2022-01-15',
                    'end_date' => null,
                    'description' => 'Lead development team for web applications.',
                    'is_internship' => 0
                ]);
            }

            if ($user->qr_id === 'USER002') {
                // Create sample medical skills
                MedicalSkill::create([
                    'qr_id' => $user->qr_id,
                    'skill_name' => 'Patient Diagnosis',
                    'category_id' => 2 // Diagnostic Skills
                ]);

                MedicalSkill::create([
                    'qr_id' => $user->qr_id,
                    'skill_name' => 'Emergency Response',
                    'category_id' => 4 // Emergency Medicine
                ]);

                // Create sample experience
                Experience::create([
                    'qr_id' => $user->qr_id,
                    'title' => 'Resident Doctor',
                    'company' => 'Alexandria General Hospital',
                    'location' => 'Alexandria',
                    'start_date' => '2023-07-01',
                    'end_date' => null,
                    'description' => 'Internal medicine residency program.',
                    'is_internship' => 0
                ]);
            }

            // Create common data for both users
            Language::create([
                'qr_id' => $user->qr_id,
                'language_name' => 'Arabic',
                'proficiency_level' => 'Native'
            ]);

            Language::create([
                'qr_id' => $user->qr_id,
                'language_name' => 'English',
                'proficiency_level' => 'Fluent'
            ]);

            Interest::create([
                'qr_id' => $user->qr_id,
                'interest_name' => 'Technology'
            ]);

            SoftSkill::create([
                'qr_id' => $user->qr_id,
                'soft_name' => 'Leadership'
            ]);

            SoftSkill::create([
                'qr_id' => $user->qr_id,
                'soft_name' => 'Communication'
            ]);
        }
    }
}