// Global variables
let currentTheme = 'light';
let currentLang = 'ar';
let selectedMajor = '';

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load skill categories from database first
    Promise.all([
        loadSkillCategories(),
        loadMedicalSkillCategories()
    ]).then(() => {
        console.log('✅ All skill categories loaded, initializing form...');
    });
    
    initializeThemeToggle();
    initializeLanguageToggle();
    initializeImageUpload();
    initializeProjectImageUpload();
    initializeMajorSelection();
    initializeDynamicSections();
    initializeMobileOptimizations();
    initializeFormSubmission();
});

// Initialize form submission handler
function initializeFormSubmission() {
    const form = document.getElementById('cvForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitForm(); // Use AJAX submission instead
        });
    }
}

// Mobile optimizations
function initializeMobileOptimizations() {
    // Prevent zoom on input focus for mobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                if (window.innerWidth < 768) {
                    this.style.fontSize = '16px';
                }
            });
        });
    }

    // Improve checkbox functionality
    initializeCheckboxes();

    // Initialize header and back to top functionality
    initializeHeaderAndBackToTop();

    // Improve touch scrolling
    document.body.style.webkitOverflowScrolling = 'touch';
    
    // Handle orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 100);
    });

    // Add mobile-specific classes
    if (window.innerWidth < 768) {
        document.body.classList.add('mobile-device');
    }

    // Handle resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            document.body.classList.add('mobile-device');
        } else {
            document.body.classList.remove('mobile-device');
        }
    });
}

// Initialize header and back to top functionality
function initializeHeaderAndBackToTop() {
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    const backToTop = document.getElementById('backToTop');
    const scrollThreshold = 100; // Show back to top after scrolling 100px

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Hide/show header based on scroll direction
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down - hide header
            header.classList.add('hidden');
        } else {
            // Scrolling up - show header
            header.classList.remove('hidden');
        }
        
        lastScrollTop = scrollTop;

        // Show/hide back to top button
        if (scrollTop > scrollThreshold) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
}

// Scroll to top function
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Initialize checkboxes
function initializeCheckboxes() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        // Add click event to label for better mobile experience
        const label = checkbox.closest('label');
        if (label) {
            label.addEventListener('click', function(e) {
                if (e.target !== checkbox) {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        }
        
        // Improve visual feedback
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.style.backgroundColor = 'var(--primary-color)';
            } else {
                this.style.backgroundColor = '';
            }
        });
    });
}

// Theme Toggle Function
function initializeThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
}

function toggleTheme() {
    currentTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    // Update theme toggle button
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        const text = themeToggle.querySelector('span');
        
        if (currentTheme === 'dark') {
            icon.className = 'fas fa-sun';
            text.textContent = currentLang === 'ar' ? 'الوضع الفاتح' : 'Light Mode';
        } else {
            icon.className = 'fas fa-moon';
            text.textContent = currentLang === 'ar' ? 'الوضع الداكن' : 'Dark Mode';
        }
    }
    
    console.log('Theme changed to:', currentTheme);
}

// Language Toggle Function
function initializeLanguageToggle() {
    const langToggle = document.getElementById('langToggle');
    if (langToggle) {
        langToggle.addEventListener('click', toggleLanguage);
    }
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
        const textToUse = currentLang === 'ar' ? arabicText : englishText;
        
        // Special handling for buttons with icons (send button in robot)
        if (element.querySelector('svg')) {
            // Find the span with text or create text node
            const textSpan = element.querySelector('span');
            if (textSpan) {
                textSpan.textContent = textToUse;
            } else {
                // Update only text nodes, preserve SVG
                const textNodes = Array.from(element.childNodes).filter(node => node.nodeType === Node.TEXT_NODE);
                if (textNodes.length > 0) {
                    textNodes[0].textContent = textToUse;
                }
            }
        } else if (element.children.length > 0 && !element.classList.contains('text-line')) {
            // Has children but not robot text-line, find first text node
            const textNode = Array.from(element.childNodes).find(node => node.nodeType === Node.TEXT_NODE);
            if (textNode) {
                textNode.textContent = textToUse;
            }
        } else {
            // Safe to use innerHTML for robot text-lines and simple elements
            element.innerHTML = textToUse;
        }
    });
    
    // Update form labels and placeholders
    updateFormTexts();
    
    // Dispatch custom event for robot component
    document.dispatchEvent(new CustomEvent('languageChanged', { 
        detail: { lang: currentLang } 
    }));
    
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

// Image Upload Function
function initializeImageUpload() {
    const profileImageUploadArea = document.getElementById('profileImageUploadArea');
    const profileImageInput = document.getElementById('profile_image');
    
    if (profileImageUploadArea && profileImageInput) {
        profileImageUploadArea.addEventListener('click', () => {
            profileImageInput.click();
        });
        
        profileImageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            profileImageUploadArea.style.borderColor = '#3498db';
        });
        
        profileImageUploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            profileImageUploadArea.style.borderColor = '';
        });
        
        profileImageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            profileImageUploadArea.style.borderColor = '';
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleImageUpload(files[0]);
            }
        });
        
        profileImageInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleImageUpload(e.target.files[0]);
            }
        });
    }
}

function handleImageUpload(file) {
    if (file && file.type.startsWith('image/')) {
        if (file.size > 5 * 1024 * 1024) {
            alert(currentLang === 'ar' ? 'حجم الصورة كبير جداً. الحد الأقصى 5 ميجابايت' : 'Image size is too large. Maximum 5MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profileImagePreview');
            if (preview) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
        };
        reader.readAsDataURL(file);
    } else {
        alert(currentLang === 'ar' ? 'يرجى اختيار ملف صورة صالح' : 'Please select a valid image file');
    }
}

function handleProjectImageUpload(file, previewElement) {
    if (file && file.type.startsWith('image/')) {
        if (file.size > 5 * 1024 * 1024) {
            alert(currentLang === 'ar' ? 'حجم الصورة كبير جداً. الحد الأقصى 5 ميجابايت' : 'Image size is too large. Maximum 5MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewElement) {
                previewElement.innerHTML = `<img src="${e.target.result}" alt="Project Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
        };
        reader.readAsDataURL(file);
    } else {
        alert(currentLang === 'ar' ? 'يرجى اختيار ملف صورة صالح' : 'Please select a valid image file');
    }
}

// Initialize project image upload for existing items
function initializeProjectImageUpload() {
    const projectImageUploads = document.querySelectorAll('.project-image-upload');
    projectImageUploads.forEach(uploadArea => {
        const projectImageInput = uploadArea.querySelector('.project-image-input');
        const projectImagePreview = uploadArea.querySelector('.project-image-preview');
        
        if (projectImageInput && projectImagePreview) {
            uploadArea.addEventListener('click', () => {
                projectImageInput.click();
            });
            
            projectImageInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleProjectImageUpload(e.target.files[0], projectImagePreview);
                }
            });
        }
    });
}

// Major Selection Function
function initializeMajorSelection() {
    const majorSelect = document.getElementById('major');
    if (majorSelect) {
        majorSelect.addEventListener('change', function() {
            selectedMajor = this.value;
            toggleDynamicSections();
        });
    }
}

function toggleDynamicSections() {
    console.log('toggleDynamicSections called with selectedMajor:', selectedMajor);
    
    // Hide all dynamic sections first
    const allDynamicSections = document.querySelectorAll('.dynamic-section');
    allDynamicSections.forEach(section => {
        section.style.display = 'none';
        section.classList.remove('visible');
        console.log('Hiding section:', section.id);
    });
    
    // Show sections that match the selected major
    if (selectedMajor) {
        const matchingSections = document.querySelectorAll(`[data-majors*="${selectedMajor}"]`);
        matchingSections.forEach(section => {
            section.style.display = 'block';
            section.classList.add('visible');
            console.log('Showing section:', section.id, 'for major:', selectedMajor);
        });
    }
    
    console.log('Dynamic sections updated for major:', selectedMajor);
}

// Dynamic Sections Management
function initializeDynamicSections() {
    // Initialize with no major selected
    toggleDynamicSections();
}

// Dynamic Item Functions
function addLanguage() {
    const container = document.getElementById('languagesContainer');
    const newItem = createLanguageItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeLanguage(button) {
    const container = document.getElementById('languagesContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createLanguageItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم اللغة" data-en="Language Name">اسم اللغة</span>
                </label>
                <input type="text" name="language_name[]" placeholder="العربية، الإنجليزية، الفرنسية" data-ar-placeholder="العربية، الإنجليزية، الفرنسية" data-en-placeholder="Arabic, English, French">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="مستوى الإتقان" data-en="Proficiency Level">مستوى الإتقان</span>
                </label>
                <select name="proficiency_level[]">
                    <option value="Beginner" data-ar="مبتدئ" data-en="Beginner">مبتدئ</option>
                    <option value="Intermediate" data-ar="متوسط" data-en="Intermediate">متوسط</option>
                    <option value="Advanced" data-ar="متقدم" data-en="Advanced">متقدم</option>
                    <option value="Native" data-ar="لغة أم" data-en="Native">لغة أم</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeLanguage(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addSoftSkill() {
    const container = document.getElementById('softSkillsContainer');
    const newItem = createSoftSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeSoftSkill(button) {
    const container = document.getElementById('softSkillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createSoftSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="اسم المهارة الشخصية" data-en="Soft Skill Name">اسم المهارة الشخصية</span>
                </label>
                <input type="text" name="soft_name[]" placeholder="التواصل، القيادة، العمل الجماعي" data-ar-placeholder="التواصل، القيادة، العمل الجماعي" data-en-placeholder="Communication, Leadership, Teamwork">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeSoftSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addExperience() {
    const container = document.getElementById('experienceContainer');
    if (container) {
        const newItem = createExperienceItem();
        container.appendChild(newItem);
        updateRemoveButtons();
    } else {
        console.error('experienceContainer not found');
    }
}

function removeExperience(button) {
    const container = document.getElementById('experienceContainer');
    if (container && container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createExperienceItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي</span>
                </label>
                <input type="text" name="title[]" placeholder="مطور برمجيات، مهندس، طبيب" data-ar-placeholder="مطور برمجيات، مهندس، طبيب" data-en-placeholder="Software Developer, Engineer, Doctor">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="اسم الشركة" data-en="Company Name">اسم الشركة</span>
                </label>
                <input type="text" name="company[]" placeholder="شركة التقنية المتقدمة" data-ar-placeholder="شركة التقنية المتقدمة" data-en-placeholder="Advanced Technology Company">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="الموقع" data-en="Location">الموقع</span>
                </label>
                <input type="text" name="location[]" placeholder="الرياض، السعودية" data-ar-placeholder="الرياض، السعودية" data-en-placeholder="Riyadh, Saudi Arabia">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                </label>
                <input type="date" name="start_date[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                </label>
                <input type="date" name="end_date[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="وصف العمل" data-en="Job Description">وصف العمل</span>
                </label>
                <textarea name="description[]" placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-ar-placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-en-placeholder="Write a detailed description of your tasks and responsibilities"></textarea>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <input type="checkbox" name="is_internship[]" value="1">
                    <span data-ar="تدريب تعاوني" data-en="Internship">تدريب تعاوني</span>
                </label>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeExperience(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addSkill() {
    const container = document.getElementById('skillsContainer');
    const newItem = createSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeSkill(button) {
    const container = document.getElementById('skillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

// Global variables to store skill categories
let skillCategories = [];
let medicalSkillCategories = [];

// Load IT skill categories from API
async function loadSkillCategories() {
    try {
        const response = await fetch('/api/skill-categories');
        if (response.ok) {
            skillCategories = await response.json();
            console.log('✅ IT Skill categories loaded:', skillCategories.length);
            // Update existing skill dropdowns on the page
            updateExistingSkillDropdowns();
        } else {
            console.error('❌ Failed to load skill categories');
            // Fallback to default categories
            skillCategories = [
                {id: 1, category_name: 'Programming Languages'},
                {id: 2, category_name: 'Web Development'},
                {id: 3, category_name: 'Mobile Development'},
                {id: 4, category_name: 'Database Management'},
                {id: 5, category_name: 'DevOps & Cloud'},
                {id: 6, category_name: 'Data Science & Analytics'},
                {id: 7, category_name: 'Machine Learning & AI'},
                {id: 8, category_name: 'Cybersecurity'},
                {id: 9, category_name: 'UI/UX Design'},
                {id: 10, category_name: 'Project Management'},
                {id: 11, category_name: 'Quality Assurance'},
                {id: 12, category_name: 'System Administration'},
                {id: 13, category_name: 'Network Administration'},
                {id: 14, category_name: 'Game Development'},
                {id: 15, category_name: 'Blockchain & Cryptocurrency'},
                {id: 16, category_name: 'IoT Development'},
                {id: 17, category_name: 'AR/VR Development'},
                {id: 18, category_name: 'Microservices Architecture'},
                {id: 19, category_name: 'API Development'},
                {id: 20, category_name: 'Version Control'},
                {id: 21, category_name: 'Testing Frameworks'},
                {id: 22, category_name: 'Performance Optimization'},
                {id: 23, category_name: 'Code Review'},
                {id: 24, category_name: 'Documentation'}
            ];
        }
    } catch (error) {
        console.error('❌ Error loading skill categories:', error);
        // Fallback to default categories
        skillCategories = [
            {id: 1, category_name: 'Programming Languages'},
            {id: 2, category_name: 'Web Development'},
            {id: 3, category_name: 'Mobile Development'},
            {id: 4, category_name: 'Database Management'},
            {id: 5, category_name: 'DevOps & Cloud'},
            {id: 6, category_name: 'Data Science & Analytics'},
            {id: 7, category_name: 'Machine Learning & AI'},
            {id: 8, category_name: 'Cybersecurity'},
            {id: 9, category_name: 'UI/UX Design'},
            {id: 10, category_name: 'Project Management'},
            {id: 11, category_name: 'Quality Assurance'},
            {id: 12, category_name: 'System Administration'},
            {id: 13, category_name: 'Network Administration'},
            {id: 14, category_name: 'Game Development'},
            {id: 15, category_name: 'Blockchain & Cryptocurrency'},
            {id: 16, category_name: 'IoT Development'},
            {id: 17, category_name: 'AR/VR Development'},
            {id: 18, category_name: 'Microservices Architecture'},
            {id: 19, category_name: 'API Development'},
            {id: 20, category_name: 'Version Control'},
            {id: 21, category_name: 'Testing Frameworks'},
            {id: 22, category_name: 'Performance Optimization'},
            {id: 23, category_name: 'Code Review'},
            {id: 24, category_name: 'Documentation'}
        ];
    }
}

// Load Medical skill categories from API
async function loadMedicalSkillCategories() {
    try {
        const response = await fetch('/api/medical-skill-categories');
        if (response.ok) {
            medicalSkillCategories = await response.json();
            console.log('✅ Medical Skill categories loaded:', medicalSkillCategories.length);
            // Update existing medical skill dropdowns on the page
            updateExistingMedicalSkillDropdowns();
        } else {
            console.error('❌ Failed to load medical skill categories');
            // Fallback to default categories
            medicalSkillCategories = [
                {id: 1, category_name: 'Clinical Skills'},
                {id: 2, category_name: 'Diagnostic Skills'},
                {id: 3, category_name: 'Surgical Skills'},
                {id: 4, category_name: 'Emergency Medicine'},
                {id: 5, category_name: 'Pediatric Care'},
                {id: 6, category_name: 'Geriatric Care'},
                {id: 7, category_name: 'Mental Health'},
                {id: 8, category_name: 'Radiology'},
                {id: 9, category_name: 'Pathology'},
                {id: 10, category_name: 'Pharmacology'},
                {id: 11, category_name: 'Cardiology'},
                {id: 12, category_name: 'Neurology'},
                {id: 13, category_name: 'Oncology'},
                {id: 14, category_name: 'Dermatology'},
                {id: 15, category_name: 'Orthopedics'},
                {id: 16, category_name: 'Ophthalmology'}
            ];
        }
    } catch (error) {
        console.error('❌ Error loading medical skill categories:', error);
        // Fallback to default categories
        medicalSkillCategories = [
            {id: 1, category_name: 'Clinical Skills'},
            {id: 2, category_name: 'Diagnostic Skills'},
            {id: 3, category_name: 'Surgical Skills'},
            {id: 4, category_name: 'Emergency Medicine'},
            {id: 5, category_name: 'Pediatric Care'},
            {id: 6, category_name: 'Geriatric Care'},
            {id: 7, category_name: 'Mental Health'},
            {id: 8, category_name: 'Radiology'},
            {id: 9, category_name: 'Pathology'},
            {id: 10, category_name: 'Pharmacology'},
            {id: 11, category_name: 'Cardiology'},
            {id: 12, category_name: 'Neurology'},
            {id: 13, category_name: 'Oncology'},
            {id: 14, category_name: 'Dermatology'},
            {id: 15, category_name: 'Orthopedics'},
            {id: 16, category_name: 'Ophthalmology'}
        ];
    }
}

// Update existing IT skill dropdowns on the page
function updateExistingSkillDropdowns() {
    const existingDropdowns = document.querySelectorAll('select[name="category_id[]"]');
    existingDropdowns.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '';
        skillCategories.forEach((category, index) => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.category_name;
            if (category.id == currentValue || (index === 0 && !currentValue)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    });
}

// Update existing Medical skill dropdowns on the page
function updateExistingMedicalSkillDropdowns() {
    const existingDropdowns = document.querySelectorAll('select[name="medical_category_id[]"]');
    existingDropdowns.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '';
        medicalSkillCategories.forEach((category, index) => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.category_name;
            if (category.id == currentValue || (index === 0 && !currentValue)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    });
}

function createSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    
    // Build options from loaded categories
    let optionsHtml = '';
    if (skillCategories.length > 0) {
        skillCategories.forEach((category, index) => {
            const selected = index === 0 ? 'selected' : '';
            optionsHtml += `<option value="${category.id}" ${selected}>${category.category_name}</option>`;
        });
    } else {
        // Fallback if categories not loaded yet
        optionsHtml = `
            <option value="1" selected>Programming Languages</option>
            <option value="2">Web Development</option>
            <option value="3">Mobile Development</option>
            <option value="4">Database Management</option>
            <option value="5">DevOps & Cloud</option>
            <option value="6">Data Science & Analytics</option>
            <option value="7">Machine Learning & AI</option>
            <option value="8">Cybersecurity</option>
            <option value="9">UI/UX Design</option>
            <option value="10">Project Management</option>
            <option value="11">Quality Assurance</option>
            <option value="12">Other</option>
        `;
    }
    
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                </label>
                <input type="text" name="skill_name[]" placeholder="JavaScript, Python, React" data-ar-placeholder="JavaScript, Python, React" data-en-placeholder="JavaScript, Python, React">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                </label>
                <select name="category_id[]">
                    ${optionsHtml}
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addProject() {
    const container = document.getElementById('projectsContainer');
    const newItem = createProjectItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeProject(button) {
    const container = document.getElementById('projectsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createProjectItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="عنوان المشروع" data-en="Project Title">عنوان المشروع</span>
                </label>
                <input type="text" name="project_title[]" placeholder="نظام إدارة المحتوى" data-ar-placeholder="نظام إدارة المحتوى" data-en-placeholder="Content Management System">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="التقنيات المستخدمة" data-en="Technologies Used">التقنيات المستخدمة</span>
                </label>
                <input type="text" name="technologies_used[]" placeholder="React, Node.js, MongoDB" data-ar-placeholder="React, Node.js, MongoDB" data-en-placeholder="React, Node.js, MongoDB">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="رابط المشروع" data-en="Project Link">رابط المشروع</span>
                </label>
                <input type="url" name="link[]" placeholder="https://github.com/username/project" data-ar-placeholder="https://github.com/username/project" data-en-placeholder="https://github.com/username/project">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="وصف المشروع" data-en="Project Description">وصف المشروع</span>
                </label>
                <textarea name="description_project[]" placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-ar-placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-en-placeholder="Write a detailed description of the project and its objectives"></textarea>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="صورة المشروع" data-en="Project Image">صورة المشروع</span>
                </label>
                <div class="project-image-upload" style="display: flex; flex-direction: column; align-items: center; gap: 1rem; padding: 1.5rem; border: 2px dashed var(--border-color); border-radius: var(--border-radius); background-color: var(--surface-color); cursor: pointer; transition: var(--transition);">
                    <div class="project-image-preview" style="width: 120px; height: 120px; border-radius: 8px; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid var(--primary-color);">
                        <i class="fas fa-image" style="font-size: 2rem; color: var(--text-muted);"></i>
                    </div>
                    <p class="project-image-text" style="color: var(--text-color); font-weight: 600; text-align: center; margin: 0;" data-ar="انقر لرفع صورة المشروع" data-en="Click to upload project image">انقر لرفع صورة المشروع</p>
                    <input type="file" name="project_image[]" accept="image/*" class="project-image-input" style="display: none;">
                </div>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeProject(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Initialize project image upload for the new item
    const projectImageUpload = div.querySelector('.project-image-upload');
    const projectImageInput = div.querySelector('.project-image-input');
    const projectImagePreview = div.querySelector('.project-image-preview');
    
    if (projectImageUpload && projectImageInput && projectImagePreview) {
        projectImageUpload.addEventListener('click', () => {
            projectImageInput.click();
        });
        
        projectImageInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleProjectImageUpload(e.target.files[0], projectImagePreview);
            }
        });
    }
    
    return div;
}

function addAnalyticalSkill() {
    const container = document.getElementById('analyticalSkillsContainer');
    const newItem = createAnalyticalSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeAnalyticalSkill(button) {
    const container = document.getElementById('analyticalSkillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createAnalyticalSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="اسم المهارة التحليلية" data-en="Analytical Skill Name">اسم المهارة التحليلية</span>
                </label>
                <input type="text" name="analytical_skill_name[]" placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-ar-placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-en-placeholder="Data Analysis, Problem Solving, Critical Thinking">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeAnalyticalSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addMedicalSkill() {
    const container = document.getElementById('medicalSkillsContainer');
    const newItem = createMedicalSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeMedicalSkill(button) {
    const container = document.getElementById('medicalSkillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createMedicalSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    
    // Build options from loaded medical categories
    let optionsHtml = '';
    if (medicalSkillCategories.length > 0) {
        medicalSkillCategories.forEach((category, index) => {
            const selected = index === 0 ? 'selected' : '';
            optionsHtml += `<option value="${category.id}" ${selected}>${category.category_name}</option>`;
        });
    } else {
        // Fallback if categories not loaded yet
        optionsHtml = `
            <option value="1" selected>Clinical Skills</option>
            <option value="2">Diagnostic Skills</option>
            <option value="3">Surgical Skills</option>
            <option value="4">Emergency Medicine</option>
            <option value="5">Pediatric Care</option>
            <option value="6">Geriatric Care</option>
            <option value="7">Mental Health</option>
            <option value="8">Radiology</option>
            <option value="9">Pathology</option>
            <option value="10">Pharmacology</option>
            <option value="11">Cardiology</option>
            <option value="12">Neurology</option>
            <option value="13">Oncology</option>
            <option value="14">Dermatology</option>
            <option value="15">Orthopedics</option>
            <option value="16">Ophthalmology</option>
        `;
    }
    
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم المهارة الطبية" data-en="Medical Skill Name">اسم المهارة الطبية</span>
                </label>
                <input type="text" name="medical_skill_name[]" placeholder="الجراحة، التشخيص، العلاج" data-ar-placeholder="الجراحة، التشخيص، العلاج" data-en-placeholder="Surgery, Diagnosis, Treatment">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="فئة المهارة الطبية" data-en="Medical Skill Category">فئة المهارة الطبية</span>
                </label>
                <select name="medical_category_id[]">
                    ${optionsHtml}
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeMedicalSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addResearch() {
    const container = document.getElementById('researchContainer');
    const newItem = createResearchItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeResearch(button) {
    const container = document.getElementById('researchContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createResearchItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="عنوان البحث" data-en="Research Title">عنوان البحث</span>
                </label>
                <input type="text" name="research_title[]" placeholder="دراسة حول علاج السكري" data-ar-placeholder="دراسة حول علاج السكري" data-en-placeholder="Study on Diabetes Treatment">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="سنة النشر" data-en="Publication Year">سنة النشر</span>
                </label>
                <input type="number" name="publication_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="رابط البحث" data-en="Research Link">رابط البحث</span>
                </label>
                <input type="url" name="research_link[]" placeholder="https://research-link.com" data-ar-placeholder="https://research-link.com" data-en-placeholder="https://research-link.com">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="وصف البحث" data-en="Research Description">وصف البحث</span>
                </label>
                <textarea name="research_description[]" placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-ar-placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-en-placeholder="Write a detailed description of the research and its results"></textarea>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeResearch(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addBusinessSkill() {
    const container = document.getElementById('businessSkillsContainer');
    const newItem = createBusinessSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeBusinessSkill(button) {
    const container = document.getElementById('businessSkillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createBusinessSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                </label>
                <input type="text" name="business_skill_name[]" placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-ar-placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-en-placeholder="Project Management, Digital Marketing, Financial Analysis">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                </label>
                <select name="business_category_id[]">
                    <option value="25" data-ar="بحث قانوني" data-en="Legal Research">Legal Research</option>
                    <option value="26" data-ar="تحليل حالة" data-en="Case Analysis">Case Analysis</option>
                    <option value="27" data-ar="برامج محاسبة" data-en="Accounting Software">Accounting Software</option>
                    <option value="28" data-ar="تقارير مالية" data-en="Financial Reporting">Financial Reporting</option>
                    <option value="29" data-ar="استراتيجية عمل" data-en="Business Strategy">Business Strategy</option>
                    <option value="30" data-ar="تحليل سوق" data-en="Market Analysis">Market Analysis</option>
                    <option value="31" data-ar="إدارة موارد بشرية" data-en="Human Resource Management">Human Resource Management</option>
                    <option value="32" data-ar="مهارات تدريس" data-en="Teaching Skills">Teaching Skills</option>
                    <option value="33" data-ar="تخطيط تعليمي" data-en="Educational Planning">Educational Planning</option>
                    <option value="34" data-ar="تفاوض وحل نزاعات" data-en="Negotiation & Conflict Resolution">Negotiation & Conflict Resolution</option>
                    <option value="35" data-ar="قيادة وإدارة" data-en="Leadership & Management">Leadership & Management</option>
                    <option value="36" data-ar="تنسيق مشاريع" data-en="Project Coordination">Project Coordination</option>
                    <option value="37" data-ar="تحدث عام" data-en="Public Speaking">Public Speaking</option>
                    <option value="38" data-ar="إدارة وقت" data-en="Time Management">Time Management</option>
                    <option value="39" data-ar="تفكير نقدي" data-en="Critical Thinking">Critical Thinking</option>
                    <option value="24" data-ar="أخرى" data-en="Other">Other</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeBusinessSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addCoreCompetency() {
    const container = document.getElementById('coreCompetenciesContainer');
    const newItem = createCoreCompetencyItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeCoreCompetency(button) {
    const container = document.getElementById('coreCompetenciesContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createCoreCompetencyItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم الكفاءة" data-en="Competency Name">اسم الكفاءة</span>
                </label>
                <input type="text" name="competency_name[]" placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-ar-placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-en-placeholder="Leadership, Strategic Planning موفقDecision Making">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="وصف الكفاءة" data-en="Competency Description">وصف الكفاءة</span>
                </label>
                <textarea name="competency_description[]" placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-ar-placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-en-placeholder="Write a detailed description of this competency and how you applied it"></textarea>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeCoreCompetency(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addInterest() {
    const container = document.getElementById('interestsContainer');
    const newItem = createInterestItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeInterest(button) {
    const container = document.getElementById('interestsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createInterestItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="اسم الاهتمام" data-en="Interest Name">اسم الاهتمام</span>
                </label>
                <input type="text" name="interest_name[]" placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-ar-placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-en-placeholder="Entrepreneurship, Investment, E-commerce">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeInterest(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addEngineeringSkill() {
    const container = document.getElementById('engineeringSkillsContainer');
    const newItem = createEngineeringSkillItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeEngineeringSkill(button) {
    const container = document.getElementById('engineeringSkillsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createEngineeringSkillItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="اسم المهارة الهندسية" data-en="Engineering Skill Name">اسم المهارة الهندسية</span>
                </label>
                <input type="text" name="engineering_skill_name[]" placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-ar-placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-en-placeholder="AutoCAD, SolidWorks, Structural Analysis">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeEngineeringSkill(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addEducation() {
    const container = document.getElementById('educationContainer');
    const newItem = createEducationItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeEducation(button) {
    const container = document.getElementById('educationContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createEducationItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="الدرجة العلمية" data-en="Degree">الدرجة العلمية</span>
                </label>
                <input type="text" name="degree_name[]" placeholder="بكالوريوس، ماجستير، دكتوراه" data-ar-placeholder="بكالوريوس، ماجستير، دكتوراه" data-en-placeholder="Bachelor's, Master's, PhD">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="التخصص" data-en="Field of Study">التخصص</span>
                </label>
                <input type="text" name="field_of_study[]" placeholder="علوم الحاسوب، الطب، الهندسة" data-ar-placeholder="علوم الحاسوب، الطب، الهندسة" data-en-placeholder="Computer Science, Medicine, Engineering">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="اسم الجامعة" data-en="University Name">اسم الجامعة</span>
                </label>
                <input type="text" name="university_name[]" placeholder="جامعة الملك سعود" data-ar-placeholder="جامعة الملك سعود" data-en-placeholder="King Saud University">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                </label>
                <input type="date" name="start_year[]" min="1950" max="2030" placeholder="2020" data-ar-placeholder="2020" data-en-placeholder="2020">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ التخرج" data-en="End Date">تاريخ التخرج</span>
                </label>
                <input type="date" name="end_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeEducation(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addCertification() {
    const container = document.getElementById('certificationsContainer');
    const newItem = createCertificationItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeCertification(button) {
    const container = document.getElementById('certificationsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createCertificationItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم الشهادة" data-en="Certification Name">اسم الشهادة</span>
                </label>
                <input type="text" name="certifications_name[]" placeholder="AWS Certified, PMP, CISSP" data-ar-placeholder="AWS Certified, PMP, CISSP" data-en-placeholder="AWS Certified, PMP, CISSP">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="الجهة المصدرة" data-en="Issuing Organization">الجهة المصدرة</span>
                </label>
                <input type="text" name="issuing_org[]" placeholder="Amazon, PMI, ISC2" data-ar-placeholder="Amazon, PMI, ISC2" data-en-placeholder="Amazon, PMI, ISC2">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ الإصدار" data-en="Issue Date">تاريخ الإصدار</span>
                </label>
                <input type="date" name="issue_date[]">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ الانتهاء" data-en="Expiration Date">تاريخ الانتهاء</span>
                </label>
                <input type="date" name="expiration_date[]">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="رابط الشهادة" data-en="Certificate Link">رابط الشهادة</span>
                </label>
                <input type="url" name="link_driver[]" placeholder="https://certificate-link.com" data-ar-placeholder="https://certificate-link.com" data-en-placeholder="https://certificate-link.com">
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeCertification(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addMembership() {
    const container = document.getElementById('membershipsContainer');
    const newItem = createMembershipItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeMembership(button) {
    const container = document.getElementById('membershipsContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createMembershipItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="اسم المنظمة" data-en="Organization Name">اسم المنظمة</span>
                </label>
                <input type="text" name="organization_name[]" placeholder="الجمعية السعودية للحاسب الآلي" data-ar-placeholder="الجمعية السعودية للحاسب الآلي" data-en-placeholder="Saudi Computer Society">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="نوع العضوية" data-en="Membership Type">نوع العضوية</span>
                </label>
                <input type="text" name="membership_type[]" placeholder="عضو عامل، عضو مؤسس" data-ar-placeholder="عضو عامل، عضو مؤسس" data-en-placeholder="Active Member, Founding Member">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                </label>
                <input type="date" name="start_date[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                </label>
                <input type="date" name="end_date[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="حالة العضوية" data-en="Membership Status">حالة العضوية</span>
                </label>
                <select name="membership_status[]">
                    <option value="Active" data-ar="نشط" data-en="Active">نشط</option>
                    <option value="Inactive" data-ar="غير نشط" data-en="Inactive">غير نشط</option>
                    <option value="Expired" data-ar="منتهي الصلاحية" data-en="Expired">منتهي الصلاحية</option>
                </select>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeMembership(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

function addActivity() {
    const container = document.getElementById('activitiesContainer');
    const newItem = createActivityItem();
    container.appendChild(newItem);
    updateRemoveButtons();
}

function removeActivity(button) {
    const container = document.getElementById('activitiesContainer');
    if (container.children.length > 1) {
        button.closest('.dynamic-item').remove();
        updateRemoveButtons();
    }
}

function createActivityItem() {
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label>
                    <span data-ar="عنوان النشاط" data-en="Activity Title">عنوان النشاط</span>
                </label>
                <input type="text" name="activity_title[]" placeholder="تطوع في جمعية خيرية" data-ar-placeholder="تطوع في جمعية خيرية" data-en-placeholder="Volunteer at Charity Organization">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="اسم المنظمة" data-en="Organization">اسم المنظمة</span>
                </label>
                <input type="text" name="organization[]" placeholder="جمعية البر الخيرية" data-ar-placeholder="جمعية البر الخيرية" data-en-placeholder="Al-Birr Charity Organization">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="تاريخ النشاط" data-en="Activity Date">تاريخ النشاط</span>
                </label>
                <input type="date" name="activity_date[]">
            </div>
            <div class="form-group">
                <label>
                    <span data-ar="رابط النشاط" data-en="Activity Link">رابط النشاط</span>
                </label>
                <input type="url" name="activity_link[]" placeholder="https://activity-link.com" data-ar-placeholder="https://activity-link.com" data-en-placeholder="https://activity-link.com">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>
                    <span data-ar="وصف النشاط" data-en="Activity Description">وصف النشاط</span>
                </label>
                <textarea name="description_activity[]" placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-ar-placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-en-placeholder="Write a detailed description of the activity and your role in it"></textarea>
            </div>
        </div>
        <button type="button" class="remove-btn" onclick="removeActivity(this)" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    `;
    return div;
}

// Update remove buttons visibility
function updateRemoveButtons() {
    const containers = document.querySelectorAll('.dynamic-container');
    containers.forEach(container => {
        const items = container.querySelectorAll('.dynamic-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-btn');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'flex' : 'none';
            }
        });
    });
}

// Form submission
function submitForm() {
    console.log('🚀 submitForm called');
    const form = document.getElementById('cvForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    if (!form) {
        console.error('❌ Form not found!');
        return;
    }
    
    if (!submitBtn) {
        console.error('❌ Submit button not found!');
        return;
    }
    
    console.log('✅ Form and submit button found, starting submission...');
    
    // Disable submit button and show loading state
    submitBtn.disabled = true;
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span data-ar="جاري الإرسال..." data-en="Sending...">جاري الإرسال...</span>';
    
    if (loadingSpinner) {
        loadingSpinner.style.display = 'inline-block';
    }
    
    // Clean up empty fields before creating FormData
    // Remove empty/null values from array inputs to prevent issues
    const arrayInputs = form.querySelectorAll('input[type="text"], input[type="url"], input[type="date"], textarea, select');
    arrayInputs.forEach(input => {
        if (input.name && input.name.includes('[]')) {
            // For array fields, clear empty values
            if (input.value === null || input.value === 'null' || (typeof input.value === 'string' && input.value.trim() === '')) {
                input.value = '';
            }
        }
    });
    
    const formData = new FormData(form);
    
    // Debug: Log skill_name and category_id arrays
    console.log('📤 FormData entries for skills:');
    const skillNames = formData.getAll('skill_name[]');
    const categoryIds = formData.getAll('category_id[]');
    console.log('  skill_name[]:', skillNames);
    console.log('  category_id[]:', categoryIds);
    console.log('  skill_name count:', skillNames.length);
    console.log('  category_id count:', categoryIds.length);
    
    // Verify that each skill has a corresponding category_id
    if (skillNames.length > 0) {
        skillNames.forEach((skillName, index) => {
            if (skillName && skillName.trim() !== '') {
                const categoryId = categoryIds[index] || 'MISSING';
                console.log(`  Skill ${index + 1}: "${skillName}" -> category_id: ${categoryId}`);
            }
        });
    }
    
    console.log('📤 Sending form data to:', form.action);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        redirect: 'manual' // Handle redirect manually
    })
    .then(async response => {
        console.log('📥 Response received:', response.status, response.statusText);
        
        // Check if response is a redirect (status 302, 301, etc.)
        if (response.status >= 300 && response.status < 400) {
            // Get redirect URL from Location header or response
            const redirectUrl = response.headers.get('Location') || response.url;
            console.log('🔄 Redirect detected:', redirectUrl);
            if (redirectUrl) {
                // Follow the redirect to success page
                window.location.href = redirectUrl;
                return;
            }
        }
        
        // Check content type to determine if it's JSON or HTML
        const contentType = response.headers.get('content-type');
        console.log('📄 Content-Type:', contentType);
        
        if (contentType && contentType.includes('application/json')) {
            // Parse JSON response
            const data = await response.json();
            console.log('📋 JSON data:', data);
            
            if (data.success) {
                // If success but no redirect, redirect manually
                if (data.redirect_url) {
                    console.log('✅ Success! Redirecting to:', data.redirect_url);
                    window.location.href = data.redirect_url;
                } else {
                    showNotification('success', data.message || (currentLang === 'ar' ? 'تم إنشاء الملف الشخصي بنجاح!' : 'Profile created successfully!'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'none';
                    }
                }
            } else {
                console.error('❌ Error in response:', data.message);
                console.error('❌ Error details:', data.error);
                
                // Show detailed error message
                let errorMessage = data.message || (currentLang === 'ar' ? 'حدث خطأ أثناء إنشاء الملف الشخصي' : 'An error occurred while creating the profile');
                
                // Add error details if available
                if (data.error) {
                    console.error('Error file:', data.error.file);
                    console.error('Error line:', data.error.line);
                    if (data.error.trace) {
                        console.error('Error trace:', data.error.trace);
                    }
                }
                
                showNotification('error', errorMessage);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'none';
                }
            }
        } else {
            // If HTML response (redirect), try to get redirect URL from response
            const text = await response.text();
            console.log('📄 HTML response received');
            
            // Check if response contains redirect URL
            const match = text.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
            if (match && match[1]) {
                console.log('🔄 Found redirect in HTML:', match[1]);
                window.location.href = match[1];
            } else if (response.status === 200) {
                // If status is 200 but we got HTML, it might be a redirect page
                // Try to redirect to success page
                console.log('⚠️ Got HTML response with status 200, redirecting to success page');
                window.location.href = '/register/success';
            } else {
                console.error('❌ Unexpected response:', response.status);
                showNotification('error', currentLang === 'ar' ? 'حدث خطأ أثناء إنشاء الملف الشخصي' : 'An error occurred while creating the profile');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'none';
                }
            }
        }
    })
    .catch(error => {
        console.error('❌ Fetch error:', error);
        showNotification('error', currentLang === 'ar' ? 'حدث خطأ في الاتصال' : 'Connection error occurred');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }
    });
}

// Fill form from OpenAI data
function fillFormFromOpenAI(openAIData) {
    console.log('🔄 Starting to fill form from OpenAI data...');
    
    try {
        // Helper function to set value and trigger events
        const setValueAndTrigger = (element, value) => {
            if (element && value !== undefined && value !== null) {
                element.value = value;
                // Trigger input and change events for validation
                element.dispatchEvent(new Event('input', { bubbles: true }));
                element.dispatchEvent(new Event('change', { bubbles: true }));
            }
        };
        
        // 1. Personal Information
        if (openAIData.name) {
            const nameInput = document.querySelector('input[name="name"]');
            setValueAndTrigger(nameInput, openAIData.name);
        }
        
        if (openAIData.jop_title) {
            const jobTitleInput = document.querySelector('input[name="jop_title"]');
            setValueAndTrigger(jobTitleInput, openAIData.jop_title);
        }
        
        if (openAIData.phone) {
            const phoneInput = document.querySelector('input[name="phone"]');
            setValueAndTrigger(phoneInput, openAIData.phone);
        }
        
        if (openAIData.email) {
            const emailInput = document.querySelector('input[name="email"]');
            setValueAndTrigger(emailInput, openAIData.email);
        }
        
        if (openAIData.city) {
            const cityInput = document.querySelector('input[name="city"]');
            setValueAndTrigger(cityInput, openAIData.city);
        }
        
        if (openAIData.major) {
            const majorSelect = document.getElementById('major');
            if (majorSelect) {
                majorSelect.value = openAIData.major;
                // Trigger change event to show/hide sections
                majorSelect.dispatchEvent(new Event('change'));
                selectedMajor = openAIData.major;
            }
        }
        
        if (openAIData.linkedin_profile) {
            const linkedinInput = document.querySelector('input[name="linkedin_profile"]');
            setValueAndTrigger(linkedinInput, openAIData.linkedin_profile);
        }
        
        if (openAIData.github_profile) {
            const githubInput = document.querySelector('input[name="github_profile"]');
            setValueAndTrigger(githubInput, openAIData.github_profile);
        }
        
        if (openAIData.profile_summary) {
            const summaryTextarea = document.querySelector('textarea[name="profile_summary"]');
            setValueAndTrigger(summaryTextarea, openAIData.profile_summary);
        }
        
        // 2. Languages
        if (openAIData.languages && Array.isArray(openAIData.languages)) {
            const languagesContainer = document.getElementById('languagesContainer');
            if (languagesContainer) {
                // Clear existing items except first
                while (languagesContainer.children.length > 1) {
                    languagesContainer.removeChild(languagesContainer.lastChild);
                }
                
                // Fill first item
                const firstItem = languagesContainer.querySelector('.dynamic-item');
                if (firstItem && openAIData.languages[0]) {
                    const langNameInput = firstItem.querySelector('input[name="language_name[]"]');
                    const proficiencySelect = firstItem.querySelector('select[name="proficiency_level[]"]');
                    setValueAndTrigger(langNameInput, openAIData.languages[0].language_name || '');
                    if (proficiencySelect) {
                        proficiencySelect.value = openAIData.languages[0].proficiency_level || 'Beginner';
                        proficiencySelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
                
                // Add remaining languages
                for (let i = 1; i < openAIData.languages.length; i++) {
                    addLanguage();
                    const newItem = languagesContainer.children[languagesContainer.children.length - 1];
                    const langNameInput = newItem.querySelector('input[name="language_name[]"]');
                    const proficiencySelect = newItem.querySelector('select[name="proficiency_level[]"]');
                    setValueAndTrigger(langNameInput, openAIData.languages[i].language_name || '');
                    if (proficiencySelect) {
                        proficiencySelect.value = openAIData.languages[i].proficiency_level || 'Beginner';
                        proficiencySelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            }
        }
        
        // 3. Soft Skills
        if (openAIData.softSkills && Array.isArray(openAIData.softSkills)) {
            const softSkillsContainer = document.getElementById('softSkillsContainer');
            if (softSkillsContainer) {
                // Clear existing items except first
                while (softSkillsContainer.children.length > 1) {
                    softSkillsContainer.removeChild(softSkillsContainer.lastChild);
                }
                
                // Fill first item
                const firstItem = softSkillsContainer.querySelector('.dynamic-item');
                if (firstItem && openAIData.softSkills[0]) {
                    const skillInput = firstItem.querySelector('input[name="soft_name[]"]');
                    setValueAndTrigger(skillInput, openAIData.softSkills[0].soft_name || '');
                }
                
                // Add remaining skills
                for (let i = 1; i < openAIData.softSkills.length; i++) {
                    addSoftSkill();
                    const newItem = softSkillsContainer.children[softSkillsContainer.children.length - 1];
                    const skillInput = newItem.querySelector('input[name="soft_name[]"]');
                    setValueAndTrigger(skillInput, openAIData.softSkills[i].soft_name || '');
                }
            }
        }
        
        // 4. Experiences
        if (openAIData.experiences && Array.isArray(openAIData.experiences)) {
            const experiencesContainer = document.getElementById('experiencesContainer') || document.getElementById('experienceContainer');
            if (experiencesContainer) {
                // Clear existing items except first
                while (experiencesContainer.children.length > 1) {
                    experiencesContainer.removeChild(experiencesContainer.lastChild);
                }
                
                // Fill experiences
                openAIData.experiences.forEach((exp, index) => {
                    if (index === 0) {
                        const firstItem = experiencesContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillExperienceItem(firstItem, exp);
                        }
                    } else {
                        addExperience();
                        const newItem = experiencesContainer.children[experiencesContainer.children.length - 1];
                        fillExperienceItem(newItem, exp);
                    }
                });
            }
        }
        
        // 5. Education
        if (openAIData.education && Array.isArray(openAIData.education)) {
            const educationContainer = document.getElementById('educationContainer');
            if (educationContainer) {
                // Clear existing items except first
                while (educationContainer.children.length > 1) {
                    educationContainer.removeChild(educationContainer.lastChild);
                }
                
                // Fill education
                openAIData.education.forEach((edu, index) => {
                    if (index === 0) {
                        const firstItem = educationContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillEducationItem(firstItem, edu);
                        }
                    } else {
                        addEducation();
                        const newItem = educationContainer.children[educationContainer.children.length - 1];
                        fillEducationItem(newItem, edu);
                    }
                });
            }
        }
        
        // 6. Certifications
        if (openAIData.certifications && Array.isArray(openAIData.certifications)) {
            const certificationsContainer = document.getElementById('certificationsContainer');
            if (certificationsContainer) {
                // Clear existing items except first
                while (certificationsContainer.children.length > 1) {
                    certificationsContainer.removeChild(certificationsContainer.lastChild);
                }
                
                // Fill certifications
                openAIData.certifications.forEach((cert, index) => {
                    if (index === 0) {
                        const firstItem = certificationsContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillCertificationItem(firstItem, cert);
                        }
                    } else {
                        addCertification();
                        const newItem = certificationsContainer.children[certificationsContainer.children.length - 1];
                        fillCertificationItem(newItem, cert);
                    }
                });
            }
        }
        
        // 7. Activities
        if (openAIData.activities && Array.isArray(openAIData.activities)) {
            const activitiesContainer = document.getElementById('activitiesContainer');
            if (activitiesContainer) {
                // Clear existing items except first
                while (activitiesContainer.children.length > 1) {
                    activitiesContainer.removeChild(activitiesContainer.lastChild);
                }
                
                // Fill activities
                openAIData.activities.forEach((activity, index) => {
                    if (index === 0) {
                        const firstItem = activitiesContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillActivityItem(firstItem, activity);
                        }
                    } else {
                        addActivity();
                        const newItem = activitiesContainer.children[activitiesContainer.children.length - 1];
                        fillActivityItem(newItem, activity);
                    }
                });
            }
        }
        
        // 8. IT Skills (if major is IT)
        if (openAIData.major === 'IT' && openAIData.itSkills && Array.isArray(openAIData.itSkills)) {
            const itSkillsContainer = document.getElementById('itSkillsContainer') || document.getElementById('skillsContainer');
            if (itSkillsContainer) {
                // Clear existing items except first
                while (itSkillsContainer.children.length > 1) {
                    itSkillsContainer.removeChild(itSkillsContainer.lastChild);
                }
                
                // Fill IT skills
                openAIData.itSkills.forEach((skill, index) => {
                    if (index === 0) {
                        const firstItem = itSkillsContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillITSkillItem(firstItem, skill);
                        }
                    } else {
                        addSkill();
                        const newItem = itSkillsContainer.children[itSkillsContainer.children.length - 1];
                        fillITSkillItem(newItem, skill);
                    }
                });
            }
        }
        
        // 9. IT Projects (if major is IT)
        if (openAIData.major === 'IT' && openAIData.itProjects && Array.isArray(openAIData.itProjects)) {
            const projectsContainer = document.getElementById('projectsContainer');
            if (projectsContainer) {
                // Clear existing items except first
                while (projectsContainer.children.length > 1) {
                    projectsContainer.removeChild(projectsContainer.lastChild);
                }
                
                // Fill projects
                openAIData.itProjects.forEach((project, index) => {
                    if (index === 0) {
                        const firstItem = projectsContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillProjectItem(firstItem, project);
                        }
                    } else {
                        addProject();
                        const newItem = projectsContainer.children[projectsContainer.children.length - 1];
                        fillProjectItem(newItem, project);
                    }
                });
            }
        }
        
        // 10. Medical Skills (if major is Medicine)
        if (openAIData.major === 'Medicine' && openAIData.medicalSkills && Array.isArray(openAIData.medicalSkills)) {
            const medicalSkillsContainer = document.getElementById('medicalSkillsContainer');
            if (medicalSkillsContainer) {
                // Clear existing items except first
                while (medicalSkillsContainer.children.length > 1) {
                    medicalSkillsContainer.removeChild(medicalSkillsContainer.lastChild);
                }
                
                // Fill medical skills
                openAIData.medicalSkills.forEach((skill, index) => {
                    if (index === 0) {
                        const firstItem = medicalSkillsContainer.querySelector('.dynamic-item');
                        if (firstItem) {
                            fillMedicalSkillItem(firstItem, skill);
                        }
                    } else {
                        addMedicalSkill();
                        const newItem = medicalSkillsContainer.children[medicalSkillsContainer.children.length - 1];
                        fillMedicalSkillItem(newItem, skill);
                    }
                });
            }
        }
        
        // Wait a bit to ensure all events are processed
        setTimeout(() => {
            // Scroll to top and show success message
            window.scrollTo({ top: 0, behavior: 'smooth' });
            showNotification('success', currentLang === 'ar' ? 'تم ملء الفورم بنجاح من بيانات AI!' : 'Form filled successfully from AI data!');
            
            console.log('✅ Form filled successfully!');
            
            // Trigger form validation check
            const form = document.getElementById('cvForm');
            if (form) {
                // Check if form is valid
                if (form.checkValidity()) {
                    console.log('✅ Form is valid and ready to submit');
                } else {
                    console.warn('⚠️ Form has validation errors, please review');
                }
            }
        }, 300);
        
    } catch (error) {
        console.error('❌ Error filling form:', error);
        showNotification('error', currentLang === 'ar' ? 'حدث خطأ أثناء ملء الفورم' : 'Error filling form');
    }
}

// Helper functions to fill individual items
function fillExperienceItem(item, exp) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const titleInput = item.querySelector('input[name="title[]"]');
    const companyInput = item.querySelector('input[name="company[]"]');
    const locationInput = item.querySelector('input[name="location[]"]');
    const startDateInput = item.querySelector('input[name="start_date[]"]');
    const endDateInput = item.querySelector('input[name="end_date[]"]');
    const descriptionTextarea = item.querySelector('textarea[name="description[]"]');
    const internshipCheckbox = item.querySelector('input[name="is_internship[]"]');
    
    setValueAndTrigger(titleInput, exp.title || '');
    setValueAndTrigger(companyInput, exp.company || '');
    setValueAndTrigger(locationInput, exp.location || '');
    setValueAndTrigger(startDateInput, exp.start_date || '');
    setValueAndTrigger(endDateInput, exp.end_date || '');
    setValueAndTrigger(descriptionTextarea, exp.description || '');
    if (internshipCheckbox) {
        internshipCheckbox.checked = exp.is_internship || false;
        internshipCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

function fillEducationItem(item, edu) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const degreeInput = item.querySelector('input[name="degree_name[]"]');
    const fieldInput = item.querySelector('input[name="field_of_study[]"]');
    const universityInput = item.querySelector('input[name="university_name[]"]');
    const startYearInput = item.querySelector('input[name="start_year[]"]');
    const endYearInput = item.querySelector('input[name="end_year[]"]');
    
    setValueAndTrigger(degreeInput, edu.degree_name || '');
    setValueAndTrigger(fieldInput, edu.field_of_study || '');
    setValueAndTrigger(universityInput, edu.university_name || '');
    setValueAndTrigger(startYearInput, edu.start_year || '');
    setValueAndTrigger(endYearInput, edu.end_year || '');
}

function fillCertificationItem(item, cert) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const nameInput = item.querySelector('input[name="certifications_name[]"]');
    const orgInput = item.querySelector('input[name="issuing_org[]"]');
    const issueDateInput = item.querySelector('input[name="issue_date[]"]');
    const expiryDateInput = item.querySelector('input[name="expiration_date[]"]');
    const linkInput = item.querySelector('input[name="link_driver[]"]');
    
    setValueAndTrigger(nameInput, cert.certifications_name || '');
    setValueAndTrigger(orgInput, cert.issuing_org || '');
    setValueAndTrigger(issueDateInput, cert.issue_date || '');
    setValueAndTrigger(expiryDateInput, cert.expiration_date || '');
    setValueAndTrigger(linkInput, cert.link_driver || '');
}

function fillActivityItem(item, activity) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const titleInput = item.querySelector('input[name="activity_title[]"]');
    const orgInput = item.querySelector('input[name="organization[]"]');
    const dateInput = item.querySelector('input[name="activity_date[]"]');
    const descriptionTextarea = item.querySelector('textarea[name="description_activity[]"]');
    const linkInput = item.querySelector('input[name="activity_link[]"]');
    
    setValueAndTrigger(titleInput, activity.activity_title || '');
    setValueAndTrigger(orgInput, activity.organization || '');
    setValueAndTrigger(dateInput, activity.activity_date || '');
    setValueAndTrigger(descriptionTextarea, activity.description_activity || '');
    setValueAndTrigger(linkInput, activity.activity_link || '');
}

function fillITSkillItem(item, skill) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const nameInput = item.querySelector('input[name="skill_name[]"]');
    const categorySelect = item.querySelector('select[name="category_id[]"]');
    
    setValueAndTrigger(nameInput, skill.skill_name || '');
    if (categorySelect) {
        const categoryId = skill.category_id;
        if (categoryId) {
            categorySelect.value = categoryId;
            categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
        } else {
            // Ensure a default is selected if no category_id is provided
            categorySelect.selectedIndex = 0; // Select the first option (value="1")
            categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
}

function fillProjectItem(item, project) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const titleInput = item.querySelector('input[name="project_title[]"]');
    const techInput = item.querySelector('input[name="technologies_used[]"]');
    const descriptionTextarea = item.querySelector('textarea[name="description_project[]"]');
    const linkInput = item.querySelector('input[name="link[]"]');
    
    setValueAndTrigger(titleInput, project.project_title || '');
    setValueAndTrigger(techInput, project.technologies_used || '');
    setValueAndTrigger(descriptionTextarea, project.description_project || '');
    setValueAndTrigger(linkInput, project.link || '');
}

function fillMedicalSkillItem(item, skill) {
    const setValueAndTrigger = (element, value) => {
        if (element && value !== undefined && value !== null) {
            element.value = value;
            element.dispatchEvent(new Event('input', { bubbles: true }));
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
    
    const nameInput = item.querySelector('input[name="medical_skill_name[]"]');
    const categorySelect = item.querySelector('select[name="medical_category_id[]"]');
    
    // استخدام medical_skill_name من الرد (ليس skill_name)
    setValueAndTrigger(nameInput, skill.medical_skill_name || skill.skill_name || '');
    // استخدام medical_category_id من الرد (ليس category_id)
    if (categorySelect) {
        const categoryId = skill.medical_category_id || skill.category_id;
        if (categoryId) {
            categorySelect.value = categoryId;
            categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
}

// Show notification
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                type === 'error' ? 'fas fa-exclamation-circle' : 
                type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="${icon} notification-icon"></i>
            <div>
                <div class="notification-title">${type === 'success' ? (currentLang === 'ar' ? 'نجح' : 'Success') : 
                                              type === 'error' ? (currentLang === 'ar' ? 'خطأ' : 'Error') : 
                                              type === 'warning' ? (currentLang === 'ar' ? 'تحذير' : 'Warning') : 
                                              (currentLang === 'ar' ? 'معلومات' : 'Info')}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
    
