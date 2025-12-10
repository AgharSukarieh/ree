{{-- Section --}}
<section class="form-section dynamic-section" id="itAnalyticalSection" data-majors="IT">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المهارات التحليلية" data-en="Analytical Skills">المهارات التحليلية</h2>
            <p class="section-description" data-ar="أضف مهاراتك في التحليل وحل المشكلات" data-en="Add your analysis and problem-solving skills">أضف مهاراتك في التحليل وحل المشكلات</p>
        </div>
    </div>

    <div class="dynamic-container" id="analyticalSkillsContainer">
        <div class="dynamic-item">
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
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addAnalyticalSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة تحليلية" data-en="Add Analytical Skill">إضافة مهارة تحليلية</span>
    </button>
</section>
