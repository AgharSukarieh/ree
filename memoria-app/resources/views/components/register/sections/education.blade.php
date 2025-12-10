{{-- Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="المؤهلات الأكاديمية" data-en="Academic Qualifications">المؤهلات الأكاديمية</h2>
            <p class="section-description" data-ar="أضف مؤهلاتك الأكاديمية والتعليمية" data-en="Add your academic and educational qualifications">أضف مؤهلاتك الأكاديمية والتعليمية</p>
        </div>
    </div>

    <div class="dynamic-container" id="educationContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم الدرجة" data-en="Degree Name">اسم الدرجة</span>
                    </label>
                    <input type="text" name="degree_name[]" placeholder="بكالوريوس، ماجستير، دكتوراه" data-ar-placeholder="بكالوريوس، ماجستير، دكتوراه" data-en-placeholder="Bachelor's, Master's, PhD">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="مجال الدراسة" data-en="Field of Study">مجال الدراسة</span>
                    </label>
                    <input type="text" name="field_of_study[]" placeholder="علوم الحاسوب، الطب، الهندسة" data-ar-placeholder="علوم الحاسوب، الطب، الهندسة" data-en-placeholder="Computer Science, Medicine, Engineering">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="اسم الجامعة" data-en="University Name">اسم الجامعة</span>
                    </label>
                    <input type="text" name="university_name[]" placeholder="جامعة الملك سعود" data-ar-placeholder="جامعة الملك سعود" data-en-placeholder="King Saud University">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="سنة البداية" data-en="Start Year">سنة البداية</span>
                    </label>
                    <input type="date" name="start_year[]" min="1950" max="2030" placeholder="2020" data-ar-placeholder="2020" data-en-placeholder="2020">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="سنة التخرج" data-en="End Year">سنة التخرج</span>
                    </label>
                    <input type="date" name="end_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeEducation(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addEducation()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة مؤهل أكاديمي" data-en="Add Academic Qualification">إضافة مؤهل أكاديمي</span>
    </button>
</section>
