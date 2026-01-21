<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalSkillCategory;

class MedicalSkillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Clinical Skills'],
            ['category_name' => 'Diagnostic Skills'],
            ['category_name' => 'Surgical Skills'],
            ['category_name' => 'Emergency Medicine'],
            ['category_name' => 'Pediatric Care'],
            ['category_name' => 'Geriatric Care'],
            ['category_name' => 'Mental Health'],
            ['category_name' => 'Radiology'],
            ['category_name' => 'Pathology'],
            ['category_name' => 'Pharmacology'],
            ['category_name' => 'Cardiology'],
            ['category_name' => 'Neurology'],
            ['category_name' => 'Oncology'],
            ['category_name' => 'Dermatology'],
            ['category_name' => 'Orthopedics'],
            ['category_name' => 'Ophthalmology']
        ];

        foreach ($categories as $category) {
            MedicalSkillCategory::create($category);
        }
    }
}