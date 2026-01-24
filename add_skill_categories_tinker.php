<?php
// أمر Tinker لإضافة فئات المهارات إلى السيرفر
// استخدم هذا الأمر في Tinker على السيرفر:
// php artisan tinker
// ثم انسخ والصق المحتوى أدناه

$categories = [
    ["id" => 1, "category_name" => "Programming Languages"],
    ["id" => 2, "category_name" => "Web Development"],
    ["id" => 3, "category_name" => "Mobile Development"],
    ["id" => 4, "category_name" => "Database Management"],
    ["id" => 5, "category_name" => "DevOps & Cloud"],
    ["id" => 6, "category_name" => "Data Science & Analytics"],
    ["id" => 7, "category_name" => "Machine Learning & AI"],
    ["id" => 8, "category_name" => "Cybersecurity"],
    ["id" => 9, "category_name" => "UI/UX Design"],
    ["id" => 10, "category_name" => "Project Management"],
    ["id" => 11, "category_name" => "Quality Assurance"],
    ["id" => 12, "category_name" => "System Administration"],
    ["id" => 13, "category_name" => "Network Administration"],
    ["id" => 14, "category_name" => "Game Development"],
    ["id" => 15, "category_name" => "Blockchain & Cryptocurrency"],
    ["id" => 16, "category_name" => "IoT Development"],
    ["id" => 17, "category_name" => "AR/VR Development"],
    ["id" => 18, "category_name" => "Microservices Architecture"],
    ["id" => 19, "category_name" => "API Development"],
    ["id" => 20, "category_name" => "Version Control"],
    ["id" => 21, "category_name" => "Testing Frameworks"],
    ["id" => 22, "category_name" => "Performance Optimization"],
    ["id" => 23, "category_name" => "Code Review"],
    ["id" => 24, "category_name" => "Documentation"]
];

foreach($categories as $cat) {
    DB::table('skill_categories')->updateOrInsert(
        ["id" => $cat["id"]],
        ["category_name" => $cat["category_name"], "created_at" => now(), "updated_at" => now()]
    );
}

echo "✅ تم إضافة/تحديث " . count($categories) . " فئة بنجاح!\n";

