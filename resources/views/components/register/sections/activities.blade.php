{{-- Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الأنشطة" data-en="Activities">الأنشطة</h2>
            <p class="section-description" data-ar="أضف أنشطتك التطوعية والمجتمعية" data-en="Add your volunteer and community activities">أضف أنشطتك التطوعية والمجتمعية</p>
        </div>
    </div>

    <div class="dynamic-container" id="activitiesContainer">
        <div class="dynamic-item">
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
                    <input type="date" name="activity_date[]" placeholder="تاريخ النشاط" data-ar-placeholder="تاريخ النشاط" data-en-placeholder="Activity Date">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="وصف النشاط" data-en="Activity Description">وصف النشاط</span>
                    </label>
                    <textarea name="description_activity[]" placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-ar-placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-en-placeholder="Write a detailed description of the activity and your role in it"></textarea>
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="رابط النشاط" data-en="Activity Link">رابط النشاط</span>
                    </label>
                    <input type="url" name="activity_link[]" placeholder="https://activity-link.com" data-ar-placeholder="https://activity-link.com" data-en-placeholder="https://activity-link.com">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeActivity(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addActivity()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة نشاط" data-en="Add Activity">إضافة نشاط</span>
    </button>
</section>
