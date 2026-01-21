{{-- Section --}}
<section class="form-section dynamic-section" id="businessSkillsSection" data-majors="Business">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="مهارات الأعمال" data-en="Business Skills">مهارات الأعمال</h2>
            <p class="section-description" data-ar="أضف مهاراتك في إدارة الأعمال والتسويق" data-en="Add your business management and marketing skills">أضف مهاراتك في إدارة الأعمال والتسويق</p>
        </div>
    </div>

    <div class="dynamic-container" id="businessSkillsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                    </label>
                    <input type="text" name="business_skill_name[]" placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-ar-placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-en-placeholder="Project Management, Digital Marketing, Financial Analysis">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                    </label>
                    <select name="business_category_id[]">
        <option value="25">Legal Research</option>
        <option value="26">Case Analysis</option>
        <option value="27">Accounting Software</option>
        <option value="28">Financial Reporting</option>
        <option value="29">Business Strategy</option>
        <option value="30">Market Analysis</option>
        <option value="31">Human Resource Management</option>
        <option value="32">Teaching Skills</option>
        <option value="33">Educational Planning</option>
        <option value="34">Negotiation & Conflict Resolution</option>
        <option value="35">Leadership & Management</option>
        <option value="36">Project Coordination</option>
        <option value="37">Public Speaking</option>
        <option value="38">Time Management</option>
        <option value="39">Critical Thinking</option>
        <option value="24">Other</option>
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeBusinessSkill(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addBusinessSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة أعمال" data-en="Add Business Skill">إضافة مهارة أعمال</span>
    </button>
</section>
