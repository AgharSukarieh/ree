{{-- Section --}}
<section class="form-section dynamic-section" id="businessCompetenciesSection" data-majors="Business">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-trophy"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الكفاءات الأساسية" data-en="Core Competencies">الكفاءات الأساسية</h2>
            <p class="section-description" data-ar="أضف كفاءاتك في القيادة والتخطيط" data-en="Add your leadership and planning competencies">أضف كفاءاتك في القيادة والتخطيط</p>
        </div>
    </div>

    <div class="dynamic-container" id="coreCompetenciesContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم الكفاءة" data-en="Competency Name">اسم الكفاءة</span>
                    </label>
                    <input type="text" name="competency_name[]" placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-ar-placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-en-placeholder="Leadership, Strategic Planning, Decision Making">
                </div>
                <div class="form-group full-width">
                    <label>
        <span data-ar="وصف الكفاءة" data-en="Competency Description">وصف الكفاءة</span>
                    </label>
                    <textarea name="competency_description[]" placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-ar-placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-en-placeholder="Write a detailed description of this competency and how you applied it"></textarea>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeCoreCompetency(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addCoreCompetency()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة كفاءة أساسية" data-en="Add Core Competency">إضافة كفاءة أساسية</span>
    </button>
</section>
