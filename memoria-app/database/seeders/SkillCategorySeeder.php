<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkillCategory;

class SkillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Programming Languages'],
            ['category_name' => 'Web Development'],
            ['category_name' => 'Mobile Development'],
            ['category_name' => 'Database Management'],
            ['category_name' => 'DevOps & Cloud'],
            ['category_name' => 'Data Science & Analytics'],
            ['category_name' => 'Machine Learning & AI'],
            ['category_name' => 'Cybersecurity'],
            ['category_name' => 'UI/UX Design'],
            ['category_name' => 'Project Management'],
            ['category_name' => 'Quality Assurance'],
            ['category_name' => 'System Administration'],
            ['category_name' => 'Network Administration'],
            ['category_name' => 'Game Development'],
            ['category_name' => 'Blockchain & Cryptocurrency'],
            ['category_name' => 'IoT Development'],
            ['category_name' => 'AR/VR Development'],
            ['category_name' => 'Microservices Architecture'],
            ['category_name' => 'API Development'],
            ['category_name' => 'Version Control'],
            ['category_name' => 'Testing Frameworks'],
            ['category_name' => 'Performance Optimization'],
            ['category_name' => 'Code Review'],
            ['category_name' => 'Documentation']
        ];

        foreach ($categories as $category) {
            SkillCategory::create($category);
        }
    }
}