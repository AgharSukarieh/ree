-- أمر SQL لإضافة فئات Business Skills إلى السيرفر
-- استخدم هذا الأمر في MySQL على السيرفر

INSERT INTO business_skill_categories (id, category_name, created_at, updated_at) VALUES
(1, 'Legal Research', NOW(), NOW()),
(2, 'Case Analysis', NOW(), NOW()),
(3, 'Accounting Software', NOW(), NOW()),
(4, 'Financial Reporting', NOW(), NOW()),
(5, 'Business Strategy', NOW(), NOW()),
(6, 'Market Analysis', NOW(), NOW()),
(7, 'Human Resource Management', NOW(), NOW()),
(8, 'Teaching Skills', NOW(), NOW()),
(9, 'Educational Planning', NOW(), NOW()),
(10, 'Negotiation & Conflict Resolution', NOW(), NOW()),
(11, 'Leadership & Management', NOW(), NOW()),
(12, 'Project Coordination', NOW(), NOW()),
(13, 'Public Speaking', NOW(), NOW()),
(14, 'Time Management', NOW(), NOW()),
(15, 'Critical Thinking', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    category_name = VALUES(category_name),
    updated_at = NOW();

