// CV Registration System - Enhanced JavaScript
// No section navigation, direct form submission

// Global Variables
let currentTheme = 'light';
let currentLang = 'ar';

// Language Translations
const translations = {
    ar: {
        success: 'نجح!',
        error: 'خطأ',
        uploading: 'جاري الإرسال...',
        fillRequired: 'يرجى ملء الحقول المطلوبة',
        submitting: 'جاري الإرسال...',
        submitSuccess: 'تم إرسال السيرة الذاتية بنجاح!',
        submitError: 'حدث خطأ أثناء الإرسال',
        connectionError: 'خطأ في الاتصال بالخادم',
        serverError: 'خطأ من الخادم',
        darkMode: 'الوضع الداكن',
        lightMode: 'الوضع الفاتح',
        arabic: 'العربية',
        english: 'English',
        imageUploadError: 'خطأ في رفع الصورة',
        invalidImageType: 'نوع الملف غير مدعوم. يرجى رفع صورة بصيغة JPG, PNG أو GIF',
        imageTooLarge: 'حجم الصورة كبير جداً. الحد الأقصى 5 ميجابايت',
        clickToUpload: 'انقر لرفع الصورة أو اسحبها هنا',
        changeImage: 'تغيير الصورة'
    },
    en: {
        success: 'Success!',
        error: 'Error',
        uploading: 'Uploading...',
        fillRequired: 'Please fill required fields',
        submitting: 'Submitting...',
        submitSuccess: 'CV submitted successfully!',
        submitError: 'Error occurred while submitting',
        connectionError: 'Connection error with server',
        serverError: 'Server error',
        darkMode: 'Dark Mode',
        lightMode: 'Light Mode',
        arabic: 'العربية',
        english: 'English',
        imageUploadError: 'Image upload error',
        invalidImageType: 'Unsupported file type. Please upload JPG, PNG or GIF image',
        imageTooLarge: 'Image size too large. Maximum 5MB allowed',
        clickToUpload: 'Click to upload or drag image here',
        changeImage: 'Change Image'
    }
};

// DOM Elements
const cvForm = document.getElementById('cvForm');
const submitBtn = document.getElementById('submitBtn');
const progressFill = document.getElementById('progressFill');
const progressText = document.getElementById('progressText');
const themeToggle = document.getElementById('themeToggle');
const langToggle = document.getElementById('langToggle');
const majorSelect = document.getElementById('major');

// Profile Image Elements
const profileImageInput = document.getElementById('profile_image');
const profileImageUploadArea = document.getElementById('profileImageUploadArea');
const profileImagePreview = document.getElementById('profileImagePreview');

// Initialize Application
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
    setupEventListeners();
    updateProgress();
    loadSavedData();
    setupProfileImageUpload();
});

// Initialize Application
function initializeApp() {
    // Load saved theme and language
    const savedTheme = localStorage.getItem('theme') || 'light';
    const savedLang = localStorage.getItem('language') || 'ar';

    setTheme(savedTheme);
    setLanguage(savedLang);

    // Initialize form validation
    initializeValidation();

    // Setup auto-save
    setupAutoSave();

    // Handle major change for dynamic sections
    handleMajorChange();
}

// Setup Event Listeners
function setupEventListeners() {
    // Theme and language toggles
    if (themeToggle) themeToggle.addEventListener('click', toggleTheme);
    if (langToggle) langToggle.addEventListener('click', toggleLanguage);

    // Form submission
    if (cvForm) cvForm.addEventListener('submit', handleFormSubmit);

    // Major selection change
    if (majorSelect) majorSelect.addEventListener('change', handleMajorChange);

    // Real-time validation and auto-save
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', handleInputChange);
        input.addEventListener('blur', validateField);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboardShortcuts);
}

// Profile Image Upload Setup
function setupProfileImageUpload() {
    if (!profileImageInput || !profileImageUploadArea || !profileImagePreview) return;

    // Click to upload
    profileImageUploadArea.addEventListener('click', () => {
        profileImageInput.click();
    });

    // File input change
    profileImageInput.addEventListener('change', handleImageSelect);

    // Drag and drop
    profileImageUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        profileImageUploadArea.style.borderColor = 'var(--primary-color)';
        profileImageUploadArea.style.backgroundColor = 'rgba(52, 152, 219, 0.1)';
    });

    profileImageUploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        profileImageUploadArea.style.borderColor = 'var(--border-color)';
        profileImageUploadArea.style.backgroundColor = 'var(--surface-color)';
    });

    profileImageUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        profileImageUploadArea.style.borderColor = 'var(--border-color)';
        profileImageUploadArea.style.backgroundColor = 'var(--surface-color)';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleImageFile(files[0]);
        }
    });
}

// Handle Image Selection
function handleImageSelect(event) {
    const file = event.target.files[0];
    if (file) {
        handleImageFile(file);
    }
}

// Handle Image File
function handleImageFile(file) {
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        showNotification(
            translations[currentLang].error,
            translations[currentLang].invalidImageType,
            'error'
        );
        return;
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        showNotification(
            translations[currentLang].error,
            translations[currentLang].imageTooLarge,
            'error'
        );
        return;
    }

    // Preview image
    const reader = new FileReader();
    reader.onload = function(e) {
        displayImagePreview(e.target.result);
    };
    reader.readAsDataURL(file);

    console.log('📷 Profile image selected:', file.name, 'Size:', (file.size / 1024).toFixed(2) + 'KB');
}

// Display Image Preview
function displayImagePreview(imageSrc) {
    if (!profileImagePreview) return;

    profileImagePreview.innerHTML = `<img src="${imageSrc}" alt="Profile Preview">`;
    
    // Update upload area text
    const uploadText = profileImageUploadArea.querySelector('.profile-image-text');
    if (uploadText) {
        uploadText.textContent = translations[currentLang].changeImage;
    }

    console.log('🖼️ Image preview updated');
}

// Theme Management
function toggleTheme() {
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

function setTheme(theme) {
    currentTheme = theme;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);

    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        const text = themeToggle.querySelector('span');
        if (icon && text) {
            if (theme === 'dark') {
                icon.className = 'fas fa-sun';
                text.textContent = translations[currentLang].lightMode;
            } else {
                icon.className = 'fas fa-moon';
                text.textContent = translations[currentLang].darkMode;
            }
        }
    }
}

// Language Management
function toggleLanguage() {
    const newLang = currentLang === 'ar' ? 'en' : 'ar';
    setLanguage(newLang);
}

function setLanguage(lang) {
    currentLang = lang;
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    localStorage.setItem('language', lang);

    if (langToggle) {
        const text = langToggle.querySelector('span');
        if (text) {
            text.textContent = lang === 'ar' ? 'English' : 'العربية';
        }
    }

    updateTexts();
    updateProgress();
}

function updateTexts() {
    // Update all elements with data-ar and data-en attributes
    const elements = document.querySelectorAll('[data-ar][data-en]');
    elements.forEach(element => {
        const text = currentLang === 'ar' ? element.getAttribute('data-ar') : element.getAttribute('data-en');
        if (text) {
            element.textContent = text;
        }
    });

    // Update submit button
    if (submitBtn) {
        const span = submitBtn.querySelector('span');
        if (span) {
            span.textContent = currentLang === 'ar' ? 'إرسال السيرة الذاتية' : 'Submit CV';
        }
    }

    // Update profile image upload text
    const uploadText = document.querySelector('.profile-image-text');
    if (uploadText && !profileImagePreview.querySelector('img')) {
        uploadText.textContent = translations[currentLang].clickToUpload;
    } else if (uploadText && profileImagePreview.querySelector('img')) {
        uploadText.textContent = translations[currentLang].changeImage;
    }

    // Update theme toggle
    setTheme(currentTheme);
}

// Dynamic Sections Management
function handleMajorChange() {
    const selectedMajor = majorSelect ? majorSelect.value : '';
    
    // Hide all dynamic sections first
    const dynamicSections = document.querySelectorAll('.dynamic-section');
    dynamicSections.forEach(section => {
        section.classList.remove('visible');
    });

    // Show sections for selected major
    if (selectedMajor) {
        const sectionsToShow = document.querySelectorAll(`[data-majors*="${selectedMajor}"]`);
        sectionsToShow.forEach(section => {
            section.classList.add('visible');
        });
    }

    // Update progress
    updateProgress();
    
    // Save form data
    saveFormData();
}

// Progress Management
function updateProgress() {
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    const filledFields = Array.from(requiredFields).filter(field => field.value.trim() !== '');
    
    const progress = requiredFields.length > 0 ? (filledFields.length / requiredFields.length) * 100 : 0;
    
    if (progressFill) progressFill.style.width = `${progress}%`;
    if (progressText) {
        const progressLabel = currentLang === 'ar' ? 'مكتمل' : 'Complete';
        progressText.textContent = `${Math.round(progress)}% ${progressLabel}`;
    }
}

// Form Validation
function initializeValidation() {
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        field.addEventListener('invalid', function (e) {
            e.preventDefault();
            showFieldError(field, translations[currentLang].fillRequired);
        });
    });
}

function validateForm() {
    let isValid = true;

    // Clear all previous errors
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.error').forEach(field => field.classList.remove('error'));

    // Validate required fields
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, translations[currentLang].fillRequired);
            isValid = false;
        }
    });

    // Validate email fields
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            showFieldError(field, currentLang === 'ar' ? 'يرجى إدخال بريد إلكتروني صحيح' : 'Please enter a valid email');
            isValid = false;
        }
    });

    // Validate URL fields
    const urlFields = document.querySelectorAll('input[type="url"]');
    urlFields.forEach(field => {
        if (field.value && !isValidURL(field.value)) {
            showFieldError(field, currentLang === 'ar' ? 'يرجى إدخال رابط صحيح' : 'Please enter a valid URL');
            isValid = false;
        }
    });

    if (!isValid) {
        showNotification(translations[currentLang].error, translations[currentLang].fillRequired, 'error');
        
        // Scroll to first error
        const firstError = document.querySelector('.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    return isValid;
}

function validateField(event) {
    const field = event.target;
    const value = field.value.trim();

    // Clear previous errors
    clearFieldError(field);

    // Required field validation
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, translations[currentLang].fillRequired);
        return false;
    }

    // Email validation
    if (field.type === 'email' && value && !isValidEmail(value)) {
        showFieldError(field, currentLang === 'ar' ? 'يرجى إدخال بريد إلكتروني صحيح' : 'Please enter a valid email');
        return false;
    }

    // URL validation
    if (field.type === 'url' && value && !isValidURL(value)) {
        showFieldError(field, currentLang === 'ar' ? 'يرجى إدخال رابط صحيح' : 'Please enter a valid URL');
        return false;
    }

    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);

    field.classList.add('error');
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    if (field.parentNode) {
        field.parentNode.appendChild(errorElement);
    }
}

function clearFieldError(field) {
    field.classList.remove('error');
    if (field.parentNode) {
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
}

// Validation Helper Functions
function isValidEmail(email) {
    const emailRegex = /^[^@]+@[^@]+\.[^@]+$/;
    return emailRegex.test(email);
}

function isValidURL(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Form Submission - WORKING VERSION WITH IMAGE UPLOAD
async function handleFormSubmit(event) {
    event.preventDefault();

    console.log('🚀 Form submission started');

    // Validate form
    if (!validateForm()) {
        console.log('❌ Form validation failed');
        return;
    }

    // Show loading state
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        const loadingText = translations[currentLang].submitting;
        submitBtn.innerHTML = `<div class="loading"></div> <span>${loadingText}</span>`;
        submitBtn.disabled = true;
    }

    try {
        // Collect form data using FormData (as expected by your server)
        const formData = new FormData(cvForm);
        
        console.log('📤 Submitting form data to server...');
        console.log('📋 Form data entries:');
        for (let [key, value] of formData.entries()) {
            if (key === 'profile_image' && value instanceof File) {
                console.log(`  ${key}: ${value.name} (${(value.size / 1024).toFixed(2)}KB)`);
            } else {
                console.log(`  ${key}: ${value}`);
            }
        }

        // Send data to Laravel endpoint
        const response = await fetch(cvForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        console.log(`📡 Response status: ${response.status} ${response.statusText}`);

        // Check if request was successful
        if (!response.ok) {
            // Attempt to read response as text to get more details on non-JSON errors
            const errorText = await response.text();
            console.error('❌ Server returned non-OK response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}, Response: ${errorText}`);
        }

        // Parse response as JSON
        const data = await response.json();
        
        console.log('📨 Server response received:', data);

        // Check if the response indicates success
        if (data.success) {
            // Handle successful submission
            const successMessage = translations[currentLang].submitSuccess;
            showNotification(translations[currentLang].success, successMessage, 'success');
            
            // Clear saved data
            localStorage.removeItem('cvFormData');
            
            console.log('✅ CV created successfully');
            console.log('📊 Response data:', data);
            
            // Update progress to 100%
            if (progressFill) progressFill.style.width = '100%';
            if (progressText) {
                const completeText = currentLang === 'ar' ? 'مكتمل' : 'Complete';
                progressText.textContent = `100% ${completeText}`;
            }
            
            // Redirect to profile
            setTimeout(() => {
                window.location.href = data.redirect_url || '/dashboard';
            }, 2000);
            
        } else {
            // Server returned a response but success is false
            console.error('❌ Server response indicates failure:', data.message);
            throw new Error(data.message || 'Server response indicates failure');
        }
        
    } catch (error) {
        console.error('❌ Error submitting form:', error);
        
        // Handle submission error
        let errorMessage;
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
            // Network error or server not reachable
            errorMessage = translations[currentLang].connectionError;
        } else if (error.message.includes('HTTP error')) {
            // Server returned an error status
            errorMessage = translations[currentLang].serverError;
        } else if (error.message.includes('JSON')) {
            // JSON parsing error
            errorMessage = currentLang === 'ar' 
                ? 'خطأ في تحليل استجابة الخادم. يرجى المحاولة مرة أخرى.' 
                : 'Error parsing server response. Please try again.';
        } else {
            // Generic error or server response doesn't indicate success
            errorMessage = error.message || translations[currentLang].submitError;
        }
        
        showNotification(translations[currentLang].error, errorMessage, 'error');
        
        // Save form data for retry
        saveFormData();
        
    } finally {
        // Reset button state
        if (submitBtn) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
}

// Auto-save functionality
function setupAutoSave() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', debounce(saveFormData, 1000));
    });
}

function saveFormData() {
    if (!cvForm) return;
    
    const formData = new FormData(cvForm);
    const data = {};

    for (let [key, value] of formData.entries()) {
        if (typeof value === 'string') {
            data[key] = value;
        }
    }

    localStorage.setItem('cvFormData', JSON.stringify(data));
    console.log('💾 Form data saved to localStorage');
}

function loadSavedData() {
    const savedData = localStorage.getItem('cvFormData');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);

            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'file') {
                    field.value = data[key];
                }
            });

            console.log('📂 Saved form data loaded');
            updateProgress();
            handleMajorChange();
        } catch (error) {
            console.error('Error loading saved data:', error);
        }
    }
}

// Input Change Handler
function handleInputChange(event) {
    const field = event.target;
    clearFieldError(field);
    updateProgress();
    
    // Handle major change
    if (field.id === 'major') {
        handleMajorChange();
    }
}

// Keyboard Shortcuts
function handleKeyboardShortcuts(event) {
    if (event.ctrlKey || event.metaKey) {
        switch (event.key) {
            case 's':
                event.preventDefault();
                saveFormData();
                showNotification(translations[currentLang].success, currentLang === 'ar' ? 'تم حفظ البيانات' : 'Data saved', 'success');
                break;
            case 'Enter':
                if (event.target.tagName !== 'TEXTAREA') {
                    event.preventDefault();
                    if (cvForm) {
                        cvForm.dispatchEvent(new Event('submit'));
                    }
                }
                break;
        }
    }
}

// Dynamic Field Management Functions
function addLanguage() {
    addDynamicItem('languagesContainer', 'language');
}

function removeLanguage(button) {
    removeDynamicItem(button, 'language');
}

function addSoftSkill() {
    addDynamicItem('softSkillsContainer', 'soft-skill');
}

function removeSoftSkill(button) {
    removeDynamicItem(button, 'soft-skill');
}

function addExperience() {
    addDynamicItem('experienceContainer', 'experience');
}

function removeExperience(button) {
    removeDynamicItem(button, 'experience');
}

function addSkill() {
    addDynamicItem('skillsContainer', 'skill');
}

function removeSkill(button) {
    removeDynamicItem(button, 'skill');
}

function addProject() {
    addDynamicItem('projectsContainer', 'project');
}

function removeProject(button) {
    removeDynamicItem(button, 'project');
}

function addMedicalSkill() {
    addDynamicItem('medicalSkillsContainer', 'medical-skill');
}

function removeMedicalSkill(button) {
    removeDynamicItem(button, 'medical-skill');
}

// Generic Dynamic Item Management
function addDynamicItem(containerId, itemType) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const template = container.querySelector('.dynamic-item');
    if (!template) return;
    
    const newItem = template.cloneNode(true);

    // Clear values
    newItem.querySelectorAll('input, textarea').forEach(input => {
        input.value = '';
        input.checked = false;
    });
    newItem.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    // Show remove button
    const removeBtn = newItem.querySelector('.remove-btn');
    if (removeBtn) {
        removeBtn.style.display = 'flex';
    }

    container.appendChild(newItem);
    animateNewItem(newItem);
    
    console.log(`➕ Added new ${itemType} item`);
}

function removeDynamicItem(button, itemType) {
    const item = button.closest('.dynamic-item');
    const container = item ? item.parentNode : null;

    if (container && container.children.length > 1) {
        animateRemoveItem(item);
        console.log(`➖ Removed ${itemType} item`);
    }
}

// Animation Functions
function animateNewItem(item) {
    if (!item) return;
    
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';

    setTimeout(() => {
        item.style.transition = 'all 0.3s ease';
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
    }, 10);
}

function animateRemoveItem(item) {
    if (!item) return;
    
    item.style.transition = 'all 0.3s ease';
    item.style.opacity = '0';
    item.style.transform = 'translateY(-20px)';

    setTimeout(() => {
        if (item.parentNode) {
            item.remove();
        }
    }, 300);
}

// Notification System
function showNotification(title, message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">
                ${getNotificationIcon(type)}
            </div>
            <div class="notification-text">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);

    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);

    console.log(`🔔 Notification: ${type} - ${title}: ${message}`);
}

function getNotificationIcon(type) {
    const icons = {
        success: '<i class="fas fa-check-circle"></i>',
        error: '<i class="fas fa-exclamation-circle"></i>',
        warning: '<i class="fas fa-exclamation-triangle"></i>',
        info: '<i class="fas fa-info-circle"></i>'
    };

    return icons[type] || icons.info;
}

// Debounce function for auto-save
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Console welcome message
console.log(`
🎉 CV Registration System Loaded Successfully!
📋 Features:
   ✅ Simplified form (no section navigation)
   ✅ Direct form submission
   ✅ Dynamic sections based on major
   ✅ Auto-save functionality
   ✅ Theme switching (Dark/Light)
   ✅ Language switching (Arabic/English)
   ✅ Form validation
   ✅ Progress tracking
   ✅ Profile image upload with drag & drop

🚀 Ready to create your professional CV!
`);
