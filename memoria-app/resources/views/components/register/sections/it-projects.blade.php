{{-- Section --}}
<section class="form-section dynamic-section" id="itProjectsSection" data-majors="IT">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المشاريع" data-en="Projects">المشاريع</h2>
            <p class="section-description" data-ar="أضف مشاريعك التقنية والبرمجية" data-en="Add your technical and programming projects">أضف مشاريعك التقنية والبرمجية</p>
        </div>
    </div>

    <div class="dynamic-container" id="projectsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="عنوان المشروع" data-en="Project Title">عنوان المشروع</span>
                    </label>
                    <input type="text" name="project_title[]" placeholder="نظام إدارة المحتوى" data-ar-placeholder="نظام إدارة المحتوى" data-en-placeholder="Content Management System">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="التقنيات المستخدمة" data-en="Technologies Used">التقنيات المستخدمة</span>
                    </label>
                    <input type="text" name="technologies_used[]" placeholder="React, Node.js, MongoDB" data-ar-placeholder="React, Node.js, MongoDB" data-en-placeholder="React, Node.js, MongoDB">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="وصف المشروع" data-en="Project Description">وصف المشروع</span>
                    </label>
                    <textarea name="description_project[]" placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-ar-placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-en-placeholder="Write a detailed description of the project and its objectives"></textarea>
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="رابط المشروع" data-en="Project Link">رابط المشروع</span>
                    </label>
                    <input type="url" name="link[]" placeholder="https://github.com/username/project" data-ar-placeholder="https://github.com/username/project" data-en-placeholder="https://github.com/username/project">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="صورة المشروع" data-en="Project Image">صورة المشروع</span>
                    </label>
                    <div class="project-image-upload" style="display: flex; flex-direction: column; align-items: center; gap: 1rem; padding: 1.5rem; border: 2px dashed var(--border-color); border-radius: var(--border-radius); background-color: var(--surface-color); cursor: pointer; transition: var(--transition);">
                        <div class="project-image-preview" style="width: 120px; height: 120px; border-radius: 8px; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid var(--primary-color);">
                            <i class="fas fa-image" style="font-size: 2rem; color: var(--text-muted);"></i>
                        </div>
                        <p class="project-image-text" style="color: var(--text-color); font-weight: 600; text-align: center; margin: 0;" data-ar="انقر لرفع صورة المشروع" data-en="Click to upload project image">انقر لرفع صورة المشروع</p>
                        <input type="file" name="project_image[]" accept="image/*" class="project-image-input" style="display: none;">
                    </div>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeProject(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addProject()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مشروع" data-en="Add Project">إضافة مشروع</span>
    </button>
</section>
