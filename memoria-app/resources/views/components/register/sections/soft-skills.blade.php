{{-- Soft Skills --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المهارات الشخصية" data-en="Soft Skills">المهارات الشخصية</h2>
            <p class="section-description" data-ar="أضف مهاراتك الشخصية والاجتماعية" data-en="Add your personal and social skills">أضف مهاراتك الشخصية والاجتماعية</p>
        </div>
    </div>

    <div class="dynamic-container" id="softSkillsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>
        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                    </label>
                    <input type="text" name="soft_name[]" placeholder="التواصل، القيادة، العمل الجماعي" data-ar-placeholder="التواصل، القيادة، العمل الجماعي" data-en-placeholder="Communication, Leadership, Teamwork">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeSoftSkill(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addSoftSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة شخصية" data-en="Add Soft Skill">إضافة مهارة شخصية</span>
    </button>
</section>
