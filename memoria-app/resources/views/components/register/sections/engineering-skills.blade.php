{{-- Section --}}
<section class="form-section dynamic-section" id="engineeringSkillsSection" data-majors="Engineering">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-cogs"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المهارات الهندسية" data-en="Engineering Skills">المهارات الهندسية</h2>
            <p class="section-description" data-ar="أضف مهاراتك الهندسية والتقنية" data-en="Add your engineering and technical skills">أضف مهاراتك الهندسية والتقنية</p>
        </div>
    </div>

    <div class="dynamic-container" id="engineeringSkillsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                    </label>
                    <input type="text" name="engineering_skill_name[]" placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-ar-placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-en-placeholder="AutoCAD, SolidWorks, Structural Analysis">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                    </label>
                    <select name="engineering_category_id[]">
        <option value="8">CAD Software</option>
        <option value="9">3D Modeling</option>
        <option value="10">Simulation & Analysis</option>
        <option value="11">Technical Drawing</option>
        <option value="12">Manufacturing Tools</option>
        <option value="13">Control Systems</option>
        <option value="14">Building Information Modeling (BIM)</option>
        <option value="15">Robotics & Automation</option>
        <option value="16">Electrical Design Tools</option>
        <option value="24">Other</option>
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeEngineeringSkill(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addEngineeringSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة هندسية" data-en="Add Engineering Skill">إضافة مهارة هندسية</span>
    </button>
</section>
