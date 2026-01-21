{{-- IT Skills Section --}}
<section class="form-section dynamic-section" id="itSkillsSection" data-majors="IT">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-code"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المهارات التقنية" data-en="Technical Skills">المهارات التقنية</h2>
            <p class="section-description" data-ar="أضف مهاراتك في البرمجة والتقنية" data-en="Add your programming and technical skills">أضف مهاراتك في البرمجة والتقنية</p>
        </div>
    </div>

    <div class="dynamic-container" id="skillsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                    </label>
                    <input type="text" name="skill_name[]" placeholder="JavaScript, Python, React" data-ar-placeholder="JavaScript, Python, React" data-en-placeholder="JavaScript, Python, React">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                    </label>
                    <select name="category_id[]">
        <option value="1">Programming Languages</option>
        <option value="2">Web Development</option>
        <option value="3">Mobile Development</option>
        <option value="4">Database Management</option>
        <option value="5">DevOps & Cloud</option>
        <option value="6">Data Science & Analytics</option>
        <option value="7">Machine Learning & AI</option>
        <option value="8">Cybersecurity</option>
        <option value="9">UI/UX Design</option>
        <option value="10">Project Management</option>
        <option value="11">Quality Assurance</option>
        <option value="12">System Administration</option>
        <option value="13">Network Administration</option>
        <option value="14">Game Development</option>
        <option value="15">Blockchain & Cryptocurrency</option>
        <option value="16">IoT Development</option>
        <option value="17">AR/VR Development</option>
        <option value="18">Microservices Architecture</option>
        <option value="19">API Development</option>
        <option value="20">Version Control</option>
        <option value="21">Testing Frameworks</option>
        <option value="22">Performance Optimization</option>
        <option value="23">Code Review</option>
        <option value="24">Documentation</option>
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeSkill(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addSkill()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مهارة تقنية" data-en="Add Technical Skill">إضافة مهارة تقنية</span>
    </button>
</section>
