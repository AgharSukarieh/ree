{{-- Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-handshake"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="العضويات المهنية" data-en="Professional Memberships">العضويات المهنية</h2>
            <p class="section-description" data-ar="أضف عضوياتك في المنظمات المهنية" data-en="Add your memberships in professional organizations">أضف عضوياتك في المنظمات المهنية</p>
        </div>
    </div>

    <div class="dynamic-container" id="membershipsContainer">
        <div class="dynamic-item">
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
                    <input type="date" name="start_date_membership[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                    </label>
                    <input type="date" name="end_date_membership[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="حالة العضوية" data-en="Membership Status">حالة العضوية</span>
                    </label>
                    <select name="membership_status[]">
        <option value="Active" data-ar="نشطة" data-en="Active">نشطة</option>
        <option value="Inactive" data-ar="غير نشطة" data-en="Inactive">غير نشطة</option>
        <option value="Expired" data-ar="منتهية" data-en="Expired">منتهية</option>
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeMembership(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addMembership()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة عضوية" data-en="Add Membership">إضافة عضوية</span>
    </button>
</section>
