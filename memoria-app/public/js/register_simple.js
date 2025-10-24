// Simple JavaScript for Register Page

// Global Variables
let currentTheme = 'light';
let currentLang = 'ar';

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
});

function initializePage() {
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    // Language toggle functionality
    const langToggle = document.getElementById('langToggle');
    if (langToggle) {
        langToggle.addEventListener('click', toggleLanguage);
    }
    
    // Form submission
    const form = document.getElementById('cvForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
    
    // Major selection change
    const majorSelect = document.getElementById('major');
    if (majorSelect) {
        majorSelect.addEventListener('change', toggleDynamicSections);
    }
    
    // Initialize dynamic sections
    toggleDynamicSections();
    
    // Initialize image upload
    initializeImageUpload();
}

function toggleTheme() {
    currentTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    // Apply theme to body
    if (currentTheme === 'dark') {
        document.body.style.backgroundColor = '#1a1a1a';
        document.body.style.color = '#ffffff';
        document.body.classList.add('dark-theme');
    } else {
        document.body.style.backgroundColor = '#f5f5f5';
        document.body.style.color = '#333333';
        document.body.classList.remove('dark-theme');
    }
    
    // Update toggle button
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        const text = themeToggle.querySelector('span');
        
        if (currentTheme === 'dark') {
            icon.className = 'fas fa-sun';
            text.textContent = 'الوضع الفاتح';
        } else {
            icon.className = 'fas fa-moon';
            text.textContent = 'الوضع الداكن';
        }
    }
    
    // Update form container
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        if (currentTheme === 'dark') {
            formContainer.style.backgroundColor = '#2d2d2d';
            formContainer.style.color = '#ffffff';
        } else {
            formContainer.style.backgroundColor = '#ffffff';
            formContainer.style.color = '#333333';
        }
    }
    
    // Update form sections
    const formSections = document.querySelectorAll('.form-section');
    formSections.forEach(section => {
        if (currentTheme === 'dark') {
            section.style.backgroundColor = '#3d3d3d';
            section.style.borderColor = '#555555';
        } else {
            section.style.backgroundColor = '#ffffff';
            section.style.borderColor = '#dddddd';
        }
    });
}

function initializeImageUpload() {
    const profileImageUpload = document.getElementById('profileImageUploadArea');
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profileImagePreview');
    
    if (profileImageUpload && profileImageInput && profileImagePreview) {
        // Click to upload
        profileImageUpload.addEventListener('click', function() {
            profileImageInput.click();
        });
        
        // Drag and drop
        profileImageUpload.addEventListener('dragover', function(e) {
            e.preventDefault();
            profileImageUpload.style.borderColor = '#ff5722';
            profileImageUpload.style.backgroundColor = '#fff3e0';
        });
        
        profileImageUpload.addEventListener('dragleave', function(e) {
            e.preventDefault();
            profileImageUpload.style.borderColor = '#ddd';
            profileImageUpload.style.backgroundColor = '';
        });
        
        profileImageUpload.addEventListener('drop', function(e) {
            e.preventDefault();
            profileImageUpload.style.borderColor = '#ddd';
            profileImageUpload.style.backgroundColor = '';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleImageUpload(files[0]);
            }
        });
        
        // File input change
        profileImageInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleImageUpload(e.target.files[0]);
            }
        });
    }
}

function handleImageUpload(file) {
    // Check file type
    if (!file.type.startsWith('image/')) {
        alert('يرجى اختيار ملف صورة فقط');
        return;
    }
    
    // Check file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت');
        return;
    }
    
    // Create file reader
    const reader = new FileReader();
    reader.onload = function(e) {
        const profileImagePreview = document.getElementById('profileImagePreview');
        if (profileImagePreview) {
            profileImagePreview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
        }
    };
    reader.readAsDataURL(file);
}

function toggleLanguage() {
    currentLang = currentLang === 'ar' ? 'en' : 'ar';
    document.documentElement.lang = currentLang;
    document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
    
    // Update language toggle button
    const langToggle = document.getElementById('langToggle');
    if (langToggle) {
        const text = langToggle.querySelector('span');
        text.textContent = currentLang === 'ar' ? 'English' : 'العربية';
    }
    
    // Translate all elements with data-ar and data-en attributes
    const translatableElements = document.querySelectorAll('[data-ar][data-en]');
    translatableElements.forEach(element => {
        const arabicText = element.getAttribute('data-ar');
        const englishText = element.getAttribute('data-en');
        
        if (currentLang === 'ar') {
            element.textContent = arabicText;
        } else {
            element.textContent = englishText;
        }
    });
    
    // Update form labels and placeholders
    updateFormTexts();
    
    console.log('Language changed to:', currentLang);
}

function updateFormTexts() {
    // Update form labels
    const labels = document.querySelectorAll('label');
    labels.forEach(label => {
        const span = label.querySelector('span[data-ar][data-en]');
        if (span) {
            const arabicText = span.getAttribute('data-ar');
            const englishText = span.getAttribute('data-en');
            
            if (currentLang === 'ar') {
                span.textContent = arabicText;
            } else {
                span.textContent = englishText;
            }
        }
    });
    
    // Update placeholders
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        const arabicPlaceholder = input.getAttribute('data-ar-placeholder');
        const englishPlaceholder = input.getAttribute('data-en-placeholder');
        
        if (arabicPlaceholder && englishPlaceholder) {
            if (currentLang === 'ar') {
                input.placeholder = arabicPlaceholder;
            } else {
                input.placeholder = englishPlaceholder;
            }
        }
    });
    
    // Update section titles and descriptions
    const sectionTitles = document.querySelectorAll('.section-title[data-ar][data-en]');
    sectionTitles.forEach(title => {
        const arabicText = title.getAttribute('data-ar');
        const englishText = title.getAttribute('data-en');
        
        if (currentLang === 'ar') {
            title.textContent = arabicText;
        } else {
            title.textContent = englishText;
        }
    });
    
    const sectionDescriptions = document.querySelectorAll('.section-description[data-ar][data-en]');
    sectionDescriptions.forEach(desc => {
        const arabicText = desc.getAttribute('data-ar');
        const englishText = desc.getAttribute('data-en');
        
        if (currentLang === 'ar') {
            desc.textContent = arabicText;
        } else {
            desc.textContent = englishText;
        }
    });
}

function toggleDynamicSections() {
    console.log('=== toggleDynamicSections called ===');
    
    const majorSelect = document.getElementById('major');
    if (!majorSelect) {
        console.log('ERROR: majorSelect not found');
        return;
    }
    
    const selectedMajor = majorSelect.value;
    const dynamicSections = document.querySelectorAll('.dynamic-section');
    
    console.log('Selected major:', selectedMajor);
    console.log('Found dynamic sections:', dynamicSections.length);
    
    // Hide all dynamic sections first
    dynamicSections.forEach(section => {
        section.classList.remove('show');
        section.style.display = 'none';
        console.log('Hiding section:', section.id, 'majors:', section.dataset.majors);
    });
    
    // Show dynamic sections based on selected major
    if (selectedMajor) {
        dynamicSections.forEach(section => {
            const majors = section.dataset.majors;
            console.log('Section majors:', majors, 'Selected major:', selectedMajor);
            
            if (majors && majors.includes(selectedMajor)) {
                section.classList.add('show');
                section.style.display = 'block';
                console.log('Showing section:', section.id);
            }
        });
    } else {
        console.log('No major selected, hiding all dynamic sections');
    }
    
    console.log('=== toggleDynamicSections completed ===');
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = document.getElementById('submitBtn');
    
    // Show loading state
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
    }
    
    // Create FormData
    const formData = new FormData(form);
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال السيرة الذاتية بنجاح!');
            form.reset();
        } else {
            alert('حدث خطأ: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال بالخادم');
    })
    .finally(() => {
        // Reset button state
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال السيرة الذاتية';
        }
    });
}

// Dynamic form functions
function addEducation() {
    addDynamicItem('educationContainer', createEducationItem);
}

function removeEducation(button) {
    removeDynamicItem(button);
}

function addLanguage() {
    addDynamicItem('languagesContainer', createLanguageItem);
}

function removeLanguage(button) {
    removeDynamicItem(button);
}

function addSoftSkill() {
    addDynamicItem('softSkillsContainer', createSoftSkillItem);
}

function removeSoftSkill(button) {
    removeDynamicItem(button);
}

function addExperience() {
    addDynamicItem('experienceContainer', createExperienceItem);
}

function removeExperience(button) {
    removeDynamicItem(button);
}

function addCertification() {
    addDynamicItem('certificationsContainer', createCertificationItem);
}

function removeCertification(button) {
    removeDynamicItem(button);
}

function addMembership() {
    addDynamicItem('membershipsContainer', createMembershipItem);
}

function removeMembership(button) {
    removeDynamicItem(button);
}

function addActivity() {
    addDynamicItem('activitiesContainer', createActivityItem);
}

function removeActivity(button) {
    removeDynamicItem(button);
}

function addSkill() {
    addDynamicItem('skillsContainer', createSkillItem);
}

function removeSkill(button) {
    removeDynamicItem(button);
}

function addProject() {
    addDynamicItem('projectsContainer', createProjectItem);
}

function removeProject(button) {
    removeDynamicItem(button);
}

function addAnalyticalSkill() {
    addDynamicItem('analyticalSkillsContainer', createAnalyticalSkillItem);
}

function removeAnalyticalSkill(button) {
    removeDynamicItem(button);
}

function addMedicalSkill() {
    addDynamicItem('medicalSkillsContainer', createMedicalSkillItem);
}

function removeMedicalSkill(button) {
    removeDynamicItem(button);
}

function addResearch() {
    addDynamicItem('medicalResearchContainer', createResearchItem);
}

function removeResearch(button) {
    removeDynamicItem(button);
}

function addBusinessSkill() {
    addDynamicItem('businessSkillsContainer', createBusinessSkillItem);
}

function removeBusinessSkill(button) {
    removeDynamicItem(button);
}

function addCoreCompetency() {
    addDynamicItem('coreCompetenciesContainer', createCoreCompetencyItem);
}

function removeCoreCompetency(button) {
    removeDynamicItem(button);
}

function addInterest() {
    addDynamicItem('interestsContainer', createInterestItem);
}

function removeInterest(button) {
    removeDynamicItem(button);
}

function addEngineeringSkill() {
    addDynamicItem('engineeringSkillsContainer', createEngineeringSkillItem);
}

function removeEngineeringSkill(button) {
    removeDynamicItem(button);
}

// Helper functions
function addDynamicItem(containerId, createItemFunction) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const newItem = createItemFunction();
    container.appendChild(newItem);
    
    // Show remove buttons if there are multiple items
    updateRemoveButtons(container);
}

function removeDynamicItem(button) {
    const item = button.closest('.dynamic-item');
    if (item) {
        item.remove();
        
        // Update remove buttons visibility
        const container = item.parentElement;
        updateRemoveButtons(container);
    }
}

function updateRemoveButtons(container) {
    const items = container.querySelectorAll('.dynamic-item');
    const removeButtons = container.querySelectorAll('.remove-btn');
    
    removeButtons.forEach((button, index) => {
        button.style.display = items.length > 1 ? 'flex' : 'none';
    });
}

// Create item functions (simplified versions)
function createEducationItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم الدرجة</label>
                <input type="text" name="degree_name[]" placeholder="بكالوريوس، ماجستير، دكتوراه">
            </div>
            <div class="form-group">
                <label>مجال الدراسة</label>
                <input type="text" name="field_of_study[]" placeholder="علوم الحاسوب، الطب، الهندسة">
            </div>
            <div class="form-group">
                <label>اسم الجامعة</label>
                <input type="text" name="university_name[]" placeholder="جامعة الملك سعود">
            </div>
            <div class="form-group">
                <label>سنة البداية</label>
                <input type="date" name="start_year[]">
            </div>
            <div class="form-group">
                <label>سنة التخرج</label>
                <input type="date" name="end_year[]">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeEducation(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createLanguageItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم اللغة</label>
                <input type="text" name="language_name[]" placeholder="العربية، الإنجليزية، الفرنسية">
            </div>
            <div class="form-group">
                <label>مستوى الإتقان</label>
                <select name="proficiency_level[]">
                    <option value="Beginner">مبتدئ</option>
                    <option value="Intermediate">متوسط</option>
                    <option value="Advanced">متقدم</option>
                    <option value="Native">لغة أم</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeLanguage(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createSoftSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>اسم المهارة</label>
                <input type="text" name="soft_name[]" placeholder="التواصل، القيادة، العمل الجماعي">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeSoftSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createExperienceItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>المسمى الوظيفي</label>
                <input type="text" name="title[]" placeholder="مطور برمجيات، مهندس، طبيب">
            </div>
            <div class="form-group">
                <label>اسم الشركة</label>
                <input type="text" name="company[]" placeholder="شركة التقنية المتقدمة">
            </div>
            <div class="form-group">
                <label>الموقع</label>
                <input type="text" name="location[]" placeholder="الرياض، السعودية">
            </div>
            <div class="form-group">
                <label>تاريخ البداية</label>
                <input type="date" name="start_date[]">
            </div>
            <div class="form-group">
                <label>تاريخ النهاية</label>
                <input type="date" name="end_date[]">
            </div>
            <div class="form-group full-width">
                <label>وصف العمل</label>
                <textarea name="description[]" placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك"></textarea>
            </div>
            <div class="form-group full-width">
                <label>
                    <input type="checkbox" name="is_internship[]" value="1">
                    تدريب تعاوني
                </label>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeExperience(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

// Add other create functions as needed...
function createCertificationItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم الشهادة</label>
                <input type="text" name="certifications_name[]" placeholder="AWS Certified, PMP, CISSP">
            </div>
            <div class="form-group">
                <label>الجهة المانحة</label>
                <input type="text" name="issuing_org[]" placeholder="Amazon, PMI, ISC2">
            </div>
            <div class="form-group">
                <label>تاريخ الإصدار</label>
                <input type="date" name="issue_date[]">
            </div>
            <div class="form-group">
                <label>تاريخ الانتهاء</label>
                <input type="date" name="expiration_date[]">
            </div>
            <div class="form-group full-width">
                <label>رابط الشهادة</label>
                <input type="url" name="link_driver[]" placeholder="https://certificate-link.com">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeCertification(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

// Add remaining create functions...
function createMembershipItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المنظمة</label>
                <input type="text" name="organization_name[]" placeholder="الجمعية السعودية للحاسب الآلي">
            </div>
            <div class="form-group">
                <label>نوع العضوية</label>
                <input type="text" name="membership_type[]" placeholder="عضو عامل، عضو مؤسس">
            </div>
            <div class="form-group">
                <label>تاريخ البداية</label>
                <input type="date" name="start_date_membership[]">
            </div>
            <div class="form-group">
                <label>تاريخ النهاية</label>
                <input type="date" name="end_date_membership[]">
            </div>
            <div class="form-group">
                <label>حالة العضوية</label>
                <select name="membership_status[]">
                    <option value="Active">نشطة</option>
                    <option value="Inactive">غير نشطة</option>
                    <option value="Expired">منتهية</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeMembership(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createActivityItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>عنوان النشاط</label>
                <input type="text" name="activity_title[]" placeholder="تطوع في جمعية خيرية">
            </div>
            <div class="form-group">
                <label>اسم المنظمة</label>
                <input type="text" name="organization[]" placeholder="جمعية البر الخيرية">
            </div>
            <div class="form-group">
                <label>تاريخ النشاط</label>
                <input type="date" name="activity_date[]">
            </div>
            <div class="form-group full-width">
                <label>وصف النشاط</label>
                <textarea name="description_activity[]" placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه"></textarea>
            </div>
            <div class="form-group full-width">
                <label>رابط النشاط</label>
                <input type="url" name="activity_link[]" placeholder="https://activity-link.com">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeActivity(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المهارة</label>
                <input type="text" name="skill_name[]" placeholder="JavaScript, Python, React">
            </div>
            <div class="form-group">
                <label>فئة المهارة</label>
                <select name="category_id[]">
                    <option value="1">Front-End Development</option>
                    <option value="2">Back-End Development</option>
                    <option value="3">API Integration</option>
                    <option value="4">DevOps Tools</option>
                    <option value="5">Cybersecurity</option>
                    <option value="6">Cloud Platforms</option>
                    <option value="7">Testing & Debugging</option>
                    <option value="22">Development Methodologies</option>
                    <option value="23">State Management</option>
                    <option value="24">Other</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createProjectItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>عنوان المشروع</label>
                <input type="text" name="project_title[]" placeholder="نظام إدارة المحتوى">
            </div>
            <div class="form-group">
                <label>التقنيات المستخدمة</label>
                <input type="text" name="technologis_used[]" placeholder="React, Node.js, MongoDB">
            </div>
            <div class="form-group full-width">
                <label>وصف المشروع</label>
                <textarea name="description_project[]" placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه"></textarea>
            </div>
            <div class="form-group full-width">
                <label>رابط المشروع</label>
                <input type="url" name="link[]" placeholder="https://github.com/username/project">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeProject(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createAnalyticalSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>اسم المهارة التحليلية</label>
                <input type="text" name="analytical_skill_name[]" placeholder="تحليل البيانات، حل المشكلات، التفكير النقديố">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeAnalyticalSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createMedicalSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المهارة الطبية</label>
                <input type="text" name="medical_skill_name[]" placeholder="الجراحة، التشخيص، العلاج">
            </div>
            <div class="form-group">
                <label>فئة المهارة الطبية</label>
                <select name="medical_category_id[]">
                    <option value="17">Lab Equipment Handling</option>
                    <option value="18">Medical Software</option>
                    <option value="19">Diagnostic Techniques</option>
                    <option value="20">Clinical Procedures</option>
                    <option value="21">Biomedical Instruments</option>
                    <option value="24">Other</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeMedicalSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createResearchItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>عنوان البحث</label>
                <input type="text" name="research_title[]" placeholder="دراسة حول علاج السكري">
            </div>
            <div class="form-group">
                <label>سنة النشر</label>
                <input type="number" name="publication_year[]" min="1950" max="2030" placeholder="2024">
            </div>
            <div class="form-group full-width">
                <label>وصف البحث</label>
                <textarea name="research_description[]" placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه"></textarea>
            </div>
            <div class="form-group full-width">
                <label>رابط البحث</label>
                <input type="url" name="research_link[]" placeholder="https://research-link.com">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeResearch(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createBusinessSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المهارة</label>
                <input type="text" name="business_skill_name[]" placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي">
            </div>
            <div class="form-group">
                <label>فئة المهارة</label>
                <select name="business_category_id[]">
                    <option value="25">Legal Research</option>
                    <option value="26">Case Analysis</option>
                    <option value="27">Accounting Software</option>
                    <option value="28">Financial Reporting</option>
                    <option value="29">Business Strategy</option>
                    <option value="30">Market Analysis</option>
                    <option value="31">Human Resource Management</option>
                    <option value="32">Teaching Skills</option>
                    <option value="33">Educational Planning</option>
                    <option value="34">Negotiation & Conflict Resolution</option>
                    <option value="35">Leadership & Management</option>
                    <option value="36">Project Coordination</option>
                    <option value="37">Public Speaking</option>
                    <option value="38">Time Management</option>
                    <option value="39">Critical Thinking</option>
                    <option value="24">Other</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeBusinessSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createCoreCompetencyItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم الكفاءة</label>
                <input type="text" name="competency_name[]" placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار">
            </div>
            <div class="form-group full-width">
                <label>وصف الكفاءة</label>
                <textarea name="competency_description[]" placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها"></textarea>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeCoreCompetency(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createInterestItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>اسم الاهتمام</label>
                <input type="text" name="interest_name[]" placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeInterest(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createEngineeringSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المهارة</label>
                <input type="text" name="engineering_skill_name[]" placeholder="AutoCAD، SolidWorks، تحليل الهياكل">
            </div>
            <div class="form-group">
                <label>فئة المهارة</label>
                <select name="engineering_category_id[]">
                    <option value="8">CAD Software</option>
                    <option value="9">3D Modeling</option>
                    <option value="10">Simulation & Analysis</option>
                    <option value="11">Technical Drawing</option>
                    <option value="12">Manufacturing Tools</option>
                    <option value="13">Control Systems</option>
                    <option value="14">Building Information Modeling (BIM)</option>
                    <option value="15">Robotics & Automation</option>
                    <option value="16">Electrical Design Tools</option>
                    <option value="24">Other</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeEngineeringSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function createBasicItem(type) {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>اسم ${type}</label>
                <input type="text" name="${type}_name[]" placeholder="أدخل ${type}">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="remove${type.charAt(0).toUpperCase() + type.slice(1)}(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}
