#!/bin/bash
# أمر شامل لإضافة جميع الفئات لجميع التخصصات إلى السيرفر

echo "=== إضافة جميع الفئات إلى السيرفر ==="
echo ""

php artisan tinker --execute="
// 1. IT Skill Categories (skill_categories)
echo '📝 إضافة فئات IT Skills...' . PHP_EOL;
\$itCategories = [
    ['id' => 1, 'category_name' => 'Programming Languages'],
    ['id' => 2, 'category_name' => 'Web Development'],
    ['id' => 3, 'category_name' => 'Mobile Development'],
    ['id' => 4, 'category_name' => 'Database Management'],
    ['id' => 5, 'category_name' => 'DevOps & Cloud'],
    ['id' => 6, 'category_name' => 'Data Science & Analytics'],
    ['id' => 7, 'category_name' => 'Machine Learning & AI'],
    ['id' => 8, 'category_name' => 'Cybersecurity'],
    ['id' => 9, 'category_name' => 'UI/UX Design'],
    ['id' => 10, 'category_name' => 'Project Management'],
    ['id' => 11, 'category_name' => 'Quality Assurance'],
    ['id' => 12, 'category_name' => 'System Administration'],
    ['id' => 13, 'category_name' => 'Network Administration'],
    ['id' => 14, 'category_name' => 'Game Development'],
    ['id' => 15, 'category_name' => 'Blockchain & Cryptocurrency'],
    ['id' => 16, 'category_name' => 'IoT Development'],
    ['id' => 17, 'category_name' => 'AR/VR Development'],
    ['id' => 18, 'category_name' => 'Microservices Architecture'],
    ['id' => 19, 'category_name' => 'API Development'],
    ['id' => 20, 'category_name' => 'Version Control'],
    ['id' => 21, 'category_name' => 'Testing Frameworks'],
    ['id' => 22, 'category_name' => 'Performance Optimization'],
    ['id' => 23, 'category_name' => 'Code Review'],
    ['id' => 24, 'category_name' => 'Documentation']
];

foreach(\$itCategories as \$cat) {
    DB::table('skill_categories')->updateOrInsert(
        ['id' => \$cat['id']],
        ['category_name' => \$cat['category_name'], 'created_at' => now(), 'updated_at' => now()]
    );
}
echo '✅ تم إضافة/تحديث ' . count(\$itCategories) . ' فئة IT' . PHP_EOL . PHP_EOL;

// 2. Business Skill Categories (تستخدم نفس جدول skill_categories ب IDs 25-39)
echo '📝 إضافة فئات Business Skills...' . PHP_EOL;
\$businessCategories = [
    ['id' => 25, 'category_name' => 'Legal Research'],
    ['id' => 26, 'category_name' => 'Case Analysis'],
    ['id' => 27, 'category_name' => 'Accounting Software'],
    ['id' => 28, 'category_name' => 'Financial Reporting'],
    ['id' => 29, 'category_name' => 'Business Strategy'],
    ['id' => 30, 'category_name' => 'Market Analysis'],
    ['id' => 31, 'category_name' => 'Human Resource Management'],
    ['id' => 32, 'category_name' => 'Teaching Skills'],
    ['id' => 33, 'category_name' => 'Educational Planning'],
    ['id' => 34, 'category_name' => 'Negotiation & Conflict Resolution'],
    ['id' => 35, 'category_name' => 'Leadership & Management'],
    ['id' => 36, 'category_name' => 'Project Coordination'],
    ['id' => 37, 'category_name' => 'Public Speaking'],
    ['id' => 38, 'category_name' => 'Time Management'],
    ['id' => 39, 'category_name' => 'Critical Thinking']
];

foreach(\$businessCategories as \$cat) {
    DB::table('skill_categories')->updateOrInsert(
        ['id' => \$cat['id']],
        ['category_name' => \$cat['category_name'], 'created_at' => now(), 'updated_at' => now()]
    );
}
echo '✅ تم إضافة/تحديث ' . count(\$businessCategories) . ' فئة Business' . PHP_EOL . PHP_EOL;

// 3. Medical Skill Categories (medical_skill_categories)
echo '📝 إضافة فئات Medical Skills...' . PHP_EOL;
\$medicalCategories = [
    ['id' => 1, 'category_name' => 'Clinical Skills'],
    ['id' => 2, 'category_name' => 'Diagnostic Skills'],
    ['id' => 3, 'category_name' => 'Surgical Skills'],
    ['id' => 4, 'category_name' => 'Emergency Medicine'],
    ['id' => 5, 'category_name' => 'Pediatric Care'],
    ['id' => 6, 'category_name' => 'Geriatric Care'],
    ['id' => 7, 'category_name' => 'Mental Health'],
    ['id' => 8, 'category_name' => 'Radiology'],
    ['id' => 9, 'category_name' => 'Pathology'],
    ['id' => 10, 'category_name' => 'Pharmacology'],
    ['id' => 11, 'category_name' => 'Cardiology'],
    ['id' => 12, 'category_name' => 'Neurology'],
    ['id' => 13, 'category_name' => 'Oncology'],
    ['id' => 14, 'category_name' => 'Dermatology'],
    ['id' => 15, 'category_name' => 'Orthopedics'],
    ['id' => 16, 'category_name' => 'Ophthalmology']
];

foreach(\$medicalCategories as \$cat) {
    DB::table('medical_skill_categories')->updateOrInsert(
        ['id' => \$cat['id']],
        ['category_name' => \$cat['category_name'], 'created_at' => now(), 'updated_at' => now()]
    );
}
echo '✅ تم إضافة/تحديث ' . count(\$medicalCategories) . ' فئة Medical' . PHP_EOL . PHP_EOL;

echo '🎉 تم إضافة جميع الفئات بنجاح!' . PHP_EOL;
"

