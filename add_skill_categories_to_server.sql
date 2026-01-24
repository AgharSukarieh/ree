-- أمر SQL لإضافة فئات المهارات إلى السيرفر
-- استخدم هذا الأمر في MySQL على السيرفر

-- حذف الفئات الموجودة أولاً (اختياري - احذف هذا السطر إذا كنت تريد الاحتفاظ بالفئات الموجودة)
-- DELETE FROM skill_categories;

-- إضافة الفئات
INSERT INTO skill_categories (id, category_name, created_at, updated_at) VALUES
(1, 'Programming Languages', NOW(), NOW()),
(2, 'Web Development', NOW(), NOW()),
(3, 'Mobile Development', NOW(), NOW()),
(4, 'Database Management', NOW(), NOW()),
(5, 'DevOps & Cloud', NOW(), NOW()),
(6, 'Data Science & Analytics', NOW(), NOW()),
(7, 'Machine Learning & AI', NOW(), NOW()),
(8, 'Cybersecurity', NOW(), NOW()),
(9, 'UI/UX Design', NOW(), NOW()),
(10, 'Project Management', NOW(), NOW()),
(11, 'Quality Assurance', NOW(), NOW()),
(12, 'System Administration', NOW(), NOW()),
(13, 'Network Administration', NOW(), NOW()),
(14, 'Game Development', NOW(), NOW()),
(15, 'Blockchain & Cryptocurrency', NOW(), NOW()),
(16, 'IoT Development', NOW(), NOW()),
(17, 'AR/VR Development', NOW(), NOW()),
(18, 'Microservices Architecture', NOW(), NOW()),
(19, 'API Development', NOW(), NOW()),
(20, 'Version Control', NOW(), NOW()),
(21, 'Testing Frameworks', NOW(), NOW()),
(22, 'Performance Optimization', NOW(), NOW()),
(23, 'Code Review', NOW(), NOW()),
(24, 'Documentation', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    category_name = VALUES(category_name),
    updated_at = NOW();

