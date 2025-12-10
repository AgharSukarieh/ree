# 📋 بنية الفورم الكاملة - Complete Form Structure

## 📌 معلومات عامة
- **Form ID**: `cvForm`
- **Form Action**: `register.store`
- **Form Method**: `POST`
- **Enctype**: `multipart/form-data`

---

## 📑 الأقسام المشتركة (Common Sections)

### 1️⃣ المعلومات الشخصية الأساسية (Personal Information)
**Section ID**: `personalInformation`  
**Required**: ✅ Yes

| Field Name | Type | Label (AR/EN) | Required | Notes |
|------------|------|--------------|----------|-------|
| `profile_image` | file | الصورة الشخصية / Profile Picture | ❌ | accept="image/*" |
| `name` | text | الاسم الكامل / Full Name | ✅ | - |
| `jop_title` | text | المسمى الوظيفي / Job Title | ✅ | - |
| `phone` | tel | رقم الهاتف / Phone Number | ❌ | - |
| `email` | email | البريد الإلكتروني / Email Address | ❌ | - |
| `city` | text | المدينة / City | ❌ | - |
| `major` | select | التخصص الرئيسي / Major Field | ✅ | Options: IT, Medicine, Business, Engineering |
| `linkedin_profile` | url | ملف LinkedIn / LinkedIn Profile | ❌ | - |
| `github_profile` | url | ملف GitHub / GitHub Profile | ❌ | - |
| `profile_summary` | textarea | الملخص المهني / Professional Summary | ❌ | - |

---

### 2️⃣ اللغات (Languages)
**Section ID**: `languages`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Options |
|------------|------|--------------|---------|
| `language_name[]` | text | اسم اللغة / Language Name | - |
| `proficiency_level[]` | select | مستوى الإتقان / Proficiency Level | Beginner, Intermediate, Advanced, Native |

---

### 3️⃣ المهارات الشخصية (Soft Skills)
**Section ID**: `softSkills`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `soft_name[]` | text | اسم المهارة / Skill Name |

---

### 4️⃣ الخبرات العملية (Experiences)
**Section ID**: `experiences`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `title[]` | text | المسمى الوظيفي / Job Title |
| `company[]` | text | اسم الشركة / Company Name |
| `location[]` | text | الموقع / Location |
| `start_date[]` | date | تاريخ البداية / Start Date |
| `end_date[]` | date | تاريخ النهاية / End Date |
| `description[]` | textarea | وصف العمل / Job Description |
| `is_internship[]` | checkbox | تدريب تعاوني / Internship |

---

### 5️⃣ المؤهلات الأكاديمية (Education)
**Section ID**: `education`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Constraints |
|------------|------|--------------|-------------|
| `degree_name[]` | text | اسم الدرجة / Degree Name | - |
| `field_of_study[]` | text | مجال الدراسة / Field of Study | - |
| `university_name[]` | text | اسم الجامعة / University Name | - |
| `start_year[]` | date | سنة البداية / Start Year | min: 1950, max: 2030 |
| `end_year[]` | date | سنة التخرج / End Year | min: 1950, max: 2030 |

---

### 6️⃣ الشهادات (Certifications)
**Section ID**: `certifications`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `certifications_name[]` | text | اسم الشهادة / Certification Name |
| `issuing_org[]` | text | الجهة المانحة / Issuing Organization |
| `issue_date[]` | date | تاريخ الإصدار / Issue Date |
| `expiration_date-disable[]` | date | تاريخ الانتهاء / Expiration Date |
| `link_driver[]` | url | رابط الشهادة / Certificate Link |

---

### 7️⃣ العضويات المهنية (Memberships)
**Section ID**: `memberships`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Options |
|------------|------|--------------|---------|
| `organization_name[]` | text | اسم المنظمة / Organization Name | - |
| `membership_type[]` | text | نوع العضوية / Membership Type | - |
| `start_date_membership[]` | date | تاريخ البداية / Start Date | - |
| `end_date_membership[]` | date | تاريخ النهاية / End Date | - |
| `membership_status[]` | select | حالة العضوية / Membership Status | Active, Inactive, Expired |

---

### 8️⃣ الأنشطة (Activities)
**Section ID**: `activities`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `activity_title[]` | text | عنوان النشاط / Activity Title |
| `organization[]` | text | اسم المنظمة / Organization |
| `activity_date[]` | date | تاريخ النشاط / Activity Date |
| `description_activity[]` | textarea | وصف النشاط / Activity Description |
| `activity_link[]` | url | رابط النشاط / Activity Link |

---

## 🎓 الأقسام حسب التخصص (Major-Specific Sections)

### 💻 تخصص IT (Information Technology)

#### 9️⃣ المهارات التقنية (IT Skills)
**Section ID**: `itSkills`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Category Options |
|------------|------|--------------|------------------|
| `skill_name[]` | text | اسم المهارة / Skill Name | - |
| `category_id[]` | select | فئة المهارة / Skill Category | 1-24 (Programming, Web Dev, Mobile, Database, DevOps, Data Science, ML/AI, Cybersecurity, UI/UX, PM, QA, System Admin, Network Admin, Game Dev, Blockchain, IoT, AR/VR, Microservices, API, Version Control, Testing, Performance, Code Review, Documentation) |

#### 🔟 المشاريع (IT Projects)
**Section ID**: `itProjects`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `project_title[]` | text | عنوان المشروع / Project Title |
| `technologies_used[]` | text | التقنيات المستخدمة / Technologies Used |
| `description_project[]` | textarea | وصف المشروع / Project Description |
| `link[]` | url | رابط المشروع / Project Link |

#### 1️⃣1️⃣ المهارات التحليلية (IT Analytical Skills)
**Section ID**: `itAnalytical`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `analytical_skill_name[]` | text | اسم المهارة التحليلية / Analytical Skill Name |

---

### 🏥 تخصص Medicine (الطب)

#### 1️⃣2️⃣ المهارات الطبية (Medical Skills)
**Section ID**: `medicalSkills`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Category Options |
|------------|------|--------------|------------------|
| `medical_skill_name[]` | text | اسم المهارة الطبية / Medical Skill Name | - |
| `medical_category_id[]` | select | فئة المهارة الطبية / Medical Skill Category | 1-16 (Clinical, Diagnostic, Surgical, Emergency, Pediatric, Geriatric, Mental Health, Radiology, Pathology, Pharmacology, Cardiology, Neurology, Oncology, Dermatology, Orthopedics, Ophthalmology) |

#### 1️⃣3️⃣ الأبحاث الطبية (Medical Research)
**Section ID**: `medicalResearch`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Constraints |
|------------|------|--------------|-------------|
| `research_title[]` | text | عنوان البحث / Research Title | - |
| `publication_year[]` | number | سنة النشر / Publication Year | min: 1950, max: 2030 |
| `research_description[]` | textarea | وصف البحث / Research Description | - |
| `research_link[]` | url | رابط البحث / Research Link | - |

---

### 💼 تخصص Business (إدارة الأعمال)

#### 1️⃣4️⃣ مهارات الأعمال (Business Skills)
**Section ID**: `businessSkills`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Category Options |
|------------|------|--------------|------------------|
| `business_skill_name[]` | text | اسم المهارة / Skill Name | - |
| `business_category_id[]` | select | فئة المهارة / Skill Category | 24-39 (Legal Research, Case Analysis, Accounting Software, Financial Reporting, Business Strategy, Market Analysis, HR Management, Teaching Skills, Educational Planning, Negotiation, Leadership, Project Coordination, Public Speaking, Time Management, Critical Thinking, Other) |

#### 1️⃣5️⃣ الكفاءات الأساسية (Business Competencies)
**Section ID**: `businessCompetencies`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `competency_name[]` | text | اسم الكفاءة / Competency Name |
| `competency_description[]` | textarea | وصف الكفاءة / Competency Description |

#### 1️⃣6️⃣ الاهتمامات التجارية (Business Interests)
**Section ID**: `businessInterests`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) |
|------------|------|--------------|
| `interest_name[]` | text | اسم الاهتمام / Interest Name |

---

### 🔧 تخصص Engineering (الهندسة)

#### 1️⃣7️⃣ المهارات الهندسية (Engineering Skills)
**Section ID**: `engineeringSkills`  
**Dynamic**: ✅ Yes (Array)

| Field Name | Type | Label (AR/EN) | Category Options |
|------------|------|--------------|------------------|
| `engineering_skill_name[]` | text | اسم المهارة / Skill Name | - |
| `engineering_category_id[]` | select | فئة المهارة / Skill Category | 8-16, 24 (CAD Software, 3D Modeling, Simulation & Analysis, Technical Drawing, Manufacturing Tools, Control Systems, BIM, Robotics & Automation, Electrical Design Tools, Other) |

---

## 📊 ملخص الأقسام حسب التخصص

| التخصص | الأقسام المطلوبة |
|--------|------------------|
| **IT** | المهارات التقنية + المشاريع + المهارات التحليلية |
| **Medicine** | المهارات الطبية + الأبحاث الطبية |
| **Business** | مهارات الأعمال + الكفاءات الأساسية + الاهتمامات التجارية |
| **Engineering** | المهارات الهندسية |

---

## 📝 ملاحظات مهمة

1. **الحقول الديناميكية**: جميع الحقول التي تنتهي بـ `[]` هي arrays ويمكن إضافة عدة قيم لها
2. **الأقسام الديناميكية**: الأقسام التي لها `dynamic: true` يمكن إضافة/حذف عناصر منها
3. **الأقسام حسب التخصص**: تظهر فقط عند اختيار التخصص المناسب
4. **الحقول المطلوبة**: فقط `name`, `jop_title`, و `major` مطلوبة في القسم الأول

---

## 🔗 الملفات المرجعية

- **JSON Structure**: `FORM_STRUCTURE.json`
- **Blade Files**: `resources/views/components/register/sections/`
- **CSS**: `public/css/register.css`
- **JavaScript**: `public/js/register.js`

