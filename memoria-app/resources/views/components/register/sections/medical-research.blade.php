{{-- Section --}}
<section class="form-section dynamic-section" id="medicalResearchSection" data-majors="Medicine">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-microscope"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الأبحاث الطبية" data-en="Medical Research">الأبحاث الطبية</h2>
            <p class="section-description" data-ar="أضف أبحاثك ومنشوراتك الطبية" data-en="Add your medical research and publications">أضف أبحاثك ومنشوراتك الطبية</p>
        </div>
    </div>

    <div class="dynamic-container" id="medicalResearchContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="عنوان البحث" data-en="Research Title">عنوان البحث</span>
                    </label>
                    <input type="text" name="research_title[]" placeholder="دراسة حول علاج السكري" data-ar-placeholder="دراسة حول علاج السكري" data-en-placeholder="Study on Diabetes Treatment">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="سنة النشر" data-en="Publication Year">سنة النشر</span>
                    </label>
                    <input type="number" name="publication_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="وصف البحث" data-en="Research Description">وصف البحث</span>
                    </label>
                    <textarea name="research_description[]" placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-ar-placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-en-placeholder="Write a detailed description of the research and its results"></textarea>
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="رابط البحث" data-en="Research Link">رابط البحث</span>
                    </label>
                    <input type="url" name="research_link[]" placeholder="https://research-link.com" data-ar-placeholder="https://research-link.com" data-en-placeholder="https://research-link.com">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeResearch(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addResearch()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة بحث طبي" data-en="Add Medical Research">إضافة بحث طبي</span>
    </button>
</section>
