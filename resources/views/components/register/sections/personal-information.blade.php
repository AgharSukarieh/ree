{{-- Basic Personal Information Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المعلومات الشخصية الأساسية" data-en="Basic Personal Information">المعلومات الشخصية الأساسية</h2>
            <p class="section-description" data-ar="أدخل معلوماتك الشخصية الأساسية" data-en="Enter your basic personal information">أدخل معلوماتك الشخصية الأساسية</p>
        </div>
    </div>

    <div class="form-grid">
        {{-- Profile Image Upload --}}
        <div class="form-group full-width">
            <label for="profile_image">
                <span data-ar="الصورة الشخصية" data-en="Profile Picture">الصورة الشخصية</span>
            </label>
            <div class="profile-image-upload" id="profileImageUploadArea">
                <div class="profile-image-preview" id="profileImagePreview">
                    <i class="fas fa-user-circle"></i>
                </div>
                <p class="profile-image-text" data-ar="انقر لرفع الصورة أو اسحبها هنا" data-en="Click to upload or drag image here"></p>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="profile-image-input">
            </div>
        </div>

        <div class="form-group">
            <label for="name">
                <span data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</span>
                <span class="required">*</span>
            </label>
            <input type="text" id="name" name="name" required placeholder="أدخل اسمك الكامل" data-ar-placeholder="أدخل اسمك الكامل" data-en-placeholder="Enter your full name">
        </div>

        <div class="form-group">
            <label for="jop_title">
                <span data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي</span>
                <span class="required">*</span>
            </label>
            <input type="text" id="jop_title" name="jop_title" required placeholder="مطور برمجيات، طبيب، مهندس" data-ar-placeholder="مطور برمجيات، طبيب، مهندس" data-en-placeholder="Software Developer, Doctor, Engineer">
        </div>

        <div class="form-group">
            <label for="phone">
                <span data-ar="رقم الهاتف" data-en="Phone Number">رقم الهاتف</span>
            </label>
            <input type="tel" id="phone" name="phone" placeholder="+966 50 123 4567" data-ar-placeholder="+966 50 123 4567" data-en-placeholder="+1 234 567 8900">
        </div>

        <div class="form-group">
            <label for="email">
                <span data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</span>
            </label>
            <input type="email" id="email" name="email" placeholder="example@email.com" data-ar-placeholder="example@email.com" data-en-placeholder="example@email.com">
        </div>

        <div class="form-group">
            <label for="city">
                <span data-ar="المدينة" data-en="City">المدينة</span>
            </label>
            <input type="text" id="city" name="city" placeholder="الرياض، جدة، الدمام" data-ar-placeholder="الرياض، جدة، الدمام" data-en-placeholder="Riyadh, Jeddah, Dammam">
        </div>

        <div class="form-group">
            <label for="major">
                <span data-ar="التخصص الرئيسي" data-en="Major Field">التخصص الرئيسي</span>
                <span class="required">*</span>
            </label>
            <select id="major" name="major" required>
                <option value="" data-ar="اختر التخصص" data-en="Select Major">اختر التخصص</option>
                <option value="IT" data-ar="تقنية المعلومات" data-en="Information Technology">تقنية المعلومات</option>
                <option value="Medicine" data-ar="الطب" data-en="Medicine">الطب</option>
                <option value="Business" data-ar="إدارة الأعمال" data-en="Business">إدارة الأعمال</option>
                <option value="Engineering" data-ar="الهندسة" data-en="Engineering">الهندسة</option>
            </select>
        </div>

        <div class="form-group">
            <label for="linkedin_profile">
                <span data-ar="ملف LinkedIn" data-en="LinkedIn Profile">ملف LinkedIn</span>
            </label>
            <input type="url" id="linkedin_profile" name="linkedin_profile" placeholder="https://linkedin.com/in/yourprofile" data-ar-placeholder="https://linkedin.com/in/yourprofile" data-en-placeholder="https://linkedin.com/in/yourprofile">
        </div>

        <div class="form-group">
            <label for="github_profile">
                <span data-ar="ملف GitHub" data-en="GitHub Profile">ملف GitHub</span>
            </label>
            <input type="url" id="github_profile" name="github_profile" placeholder="https://github.com/yourusername" data-ar-placeholder="https://github.com/yourusername" data-en-placeholder="https://github.com/yourusername">
        </div>

        <div class="form-group full-width">
            <label for="profile_summary">
                <span data-ar="الملخص المهني" data-en="Professional Summary">الملخص المهني</span>
            </label>
            <textarea id="profile_summary" name="profile_summary" placeholder="اكتب ملخصاً مهنياً عن خبراتك ومهاراتك" data-ar-placeholder="اكتب ملخصاً مهنياً عن خبراتك ومهاراتك" data-en-placeholder="Write a professional summary about your experience and skills"></textarea>
        </div>
    </div>
</section>
