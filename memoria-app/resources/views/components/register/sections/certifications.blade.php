{{-- Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-certificate"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الشهادات" data-en="Certifications">الشهادات</h2>
            <p class="section-description" data-ar="أضف شهاداتك المهنية والتقنية" data-en="Add your professional and technical certifications">أضف شهاداتك المهنية والتقنية</p>
        </div>
    </div>

    <div class="dynamic-container" id="certificationsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم الشهادة" data-en="Certification Name">اسم الشهادة</span>
                    </label>
                    <input type="text" name="certifications_name[]" placeholder="AWS Certified, PMP, CISSP" data-ar-placeholder="AWS Certified, PMP, CISSP" data-en-placeholder="AWS Certified, PMP, CISSP">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="الجهة المانحة" data-en="Issuing Organization">الجهة المانحة</span>
                    </label>
                    <input type="text" name="issuing_org[]" placeholder="Amazon, PMI, ISC2" data-ar-placeholder="Amazon, PMI, ISC2" data-en-placeholder="Amazon, PMI, ISC2">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="تاريخ الإصدار" data-en="Issue Date">تاريخ الإصدار</span>
                    </label>
                    <input type="date" name="issue_date[]" placeholder="تاريخ الإصدار" data-ar-placeholder="تاريخ الإصدار" data-en-placeholder="Issue Date">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="تاريخ الانتهاء" data-en="Expiration Date">تاريخ الانتهاء</span>
                    </label>
                    <input type="date" name="expiration_date-disable" placeholder="تاريخ الانتهاء" data-ar-placeholder="تاريخ الانتهاء" data-en-placeholder="Expiration Date">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="رابط الشهادة" data-en="Certificate Link">رابط الشهادة</span>
                    </label>
                    <input type="url" name="link_driver[]" placeholder="https://certificate-link.com" data-ar-placeholder="https://certificate-link.com" data-en-placeholder="https://certificate-link.com">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeCertification(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addCertification()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة شهادة" data-en="Add Certification">إضافة شهادة</span>
    </button>
</section>
