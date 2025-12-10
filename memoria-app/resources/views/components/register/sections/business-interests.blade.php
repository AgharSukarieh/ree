{{-- Section --}}
<section class="form-section dynamic-section" id="businessInterestsSection" data-majors="Business">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="الاهتمامات التجارية" data-en="Business Interests">الاهتمامات التجارية</h2>
            <p class="section-description" data-ar="أضف اهتماماتك في مجال الأعمال" data-en="Add your interests in business field">أضف اهتماماتك في مجال الأعمال</p>
        </div>
    </div>

    <div class="dynamic-container" id="interestsContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>
        <span data-ar="اسم الاهتمام" data-en="Interest Name">اسم الاهتمام</span>
                    </label>
                    <input type="text" name="interest_name[]" placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-ar-placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-en-placeholder="Entrepreneurship, Investment, E-commerce">
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeInterest(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addInterest()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة اهتمام" data-en="Add Interest">إضافة اهتمام</span>
    </button>
</section>
