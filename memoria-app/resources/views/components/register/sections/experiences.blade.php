{{-- Experiences --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الخبرات العملية" data-en="Work Experience">الخبرات العملية</h2>
            <p class="section-description" data-ar="أضف خبراتك العملية والوظائف السابقة" data-en="Add your work experience and previous jobs">أضف خبراتك العملية والوظائف السابقة</p>
        </div>
    </div>

    <div class="dynamic-container" id="experienceContainer">
        <div class="dynamic-item">
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
                <div class="form-group full-width">
                    <label>
        <span data-ar="وصف العمل" data-en="Job Description">وصف العمل</span>
                    </label>
                    <textarea name="description[]" placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-ar-placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-en-placeholder="Write a detailed description of your tasks and responsibilities"></textarea>
                </div>
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
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addExperience()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة خبرة عملية" data-en="Add Work Experience">إضافة خبرة عملية</span>
    </button>
</section>
