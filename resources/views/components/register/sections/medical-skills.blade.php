{{-- Section --}}
<section class="form-section dynamic-section" id="medicalSkillsSection" data-majors="Medicine">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-stethoscope"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المهارات الطبية" data-en="Medical Skills">المهارات الطبية</h2>
            <p class="section-description" data-ar="أضف مهاراتك الطبية المتخصصة" data-en="Add your specialized medical skills">أضف مهاراتك الطبية المتخصصة</p>
        </div>
    </div>

    <div class="dynamic-container" id="medicalSkillsContainer">
        <div class="dynamic-item">
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
        <option value="1">Clinical Skills</option>
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
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeMedicalSkill(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addMedicalSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة طبية" data-en="Add Medical Skill">إضافة مهارة طبية</span>
    </button>
</section>
