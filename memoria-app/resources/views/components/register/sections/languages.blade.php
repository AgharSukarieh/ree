{{-- Languages Section --}}
<section class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-language"></i>
        </div>
        <div>
            <h2 class="section-title" data-ar="اللغات" data-en="Languages">اللغات</h2>
            <p class="section-description" data-ar="أضف اللغات التي تتقنها" data-en="Add languages you speak">أضف اللغات التي تتقنها</p>
        </div>
    </div>

    <div class="dynamic-container" id="languagesContainer">
        <div class="dynamic-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>
        <span data-ar="اسم اللغة" data-en="Language Name">اسم اللغة</span>
                    </label>
                    <input type="text" name="language_name[]" placeholder="العربية، الإنجليزية، الفرنسية" data-ar-placeholder="العربية، الإنجليزية، الفرنسية" data-en-placeholder="Arabic, English, French">
                </div>
                <div class="form-group">
                    <label>
        <span data-ar="مستوى الإتقان" data-en="Proficiency Level">مستوى الإتقان</span>
                    </label>
                    <select name="proficiency_level[]">
        <option value="Beginner" data-ar="مبتدئ" data-en="Beginner">مبتدئ</option>
        <option value="Intermediate" data-ar="لسي" data-en="Intermediate">متوسط</option>
        <option value="Advanced" data-ar="متقدم" data-en="Advanced">متقدم</option>
        <option value="Native" data-ar="لغة أم" data-en="Native">لغة أم</option>
                    </select>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeLanguage(this)" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <button type="button" class="add-btn" onclick="addLanguage()">
        <i class="fas fa-plus"></i>
        <span data-ar="إضافة لغة" data-en="Add Language">إضافة لغة</span>
    </button>
</section>
