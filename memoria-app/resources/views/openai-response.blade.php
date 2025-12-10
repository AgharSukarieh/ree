@extends('layouts.app')

@section('title', 'نتيجة تعبئة الفورم - Form Fill Response')

@section('content')
<style>
    .response-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: var(--surface-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }
    
    .response-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--primary-color);
    }
    
    .response-header h1 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .success-badge {
        display: inline-block;
        background: var(--success-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        margin-top: 1rem;
    }
    
    .prompt-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
    }
    
    .prompt-section h3 {
        color: var(--text-color);
        margin-bottom: 1rem;
    }
    
    .prompt-text {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .data-section {
        margin-bottom: 2rem;
    }
    
    .data-section h2 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .data-item {
        background: white;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
    }
    
    .data-item h3 {
        color: var(--text-color);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .data-item p {
        color: var(--text-muted);
        margin: 0.5rem 0;
    }
    
    .data-item strong {
        color: var(--text-color);
        margin-left: 0.5rem;
    }
    
    .json-viewer {
        background: #282c34;
        color: #abb2bf;
        padding: 1.5rem;
        border-radius: 8px;
        overflow-x: auto;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-top: 1rem;
    }
    
    .json-key {
        color: #e06c75;
    }
    
    .json-string {
        color: #98c379;
    }
    
    .json-number {
        color: #d19a66;
    }
    
    .json-boolean {
        color: #56b6c2;
    }
    
    .json-null {
        color: #c678dd;
    }
    
    .actions {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--border-color);
    }
    
    .btn {
        display: inline-block;
        padding: 0.75rem 2rem;
        margin: 0 0.5rem;
        border-radius: var(--border-radius);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: var(--surface-color);
        color: var(--text-color);
        border: 2px solid var(--border-color);
    }
    
    .btn-secondary:hover {
        background: var(--border-color);
    }
    
    .array-item {
        background: #f8f9fa;
        padding: 1rem;
        margin: 0.5rem 0;
        border-radius: 8px;
        border-left: 3px solid var(--primary-color);
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0.25rem;
    }
    
    .badge-primary {
        background: var(--primary-color);
        color: white;
    }
    
    .badge-success {
        background: var(--success-color);
        color: white;
    }
</style>

<div class="response-container">
    <div class="response-header">
        <h1 data-ar="نتيجة تعبئة الفورم" data-en="Form Fill Response">نتيجة تعبئة الفورم</h1>
        <div class="success-badge">
            <i class="fas fa-check-circle"></i>
            <span data-ar="تم تعبئة الفورم بنجاح!" data-en="Form filled successfully!">تم تعبئة الفورم بنجاح!</span>
        </div>
    </div>

    @if(isset($prompt))
    <div class="prompt-section">
        <h3 data-ar="النص المدخل" data-en="Input Text">النص المدخل</h3>
        <div class="prompt-text">{{ $prompt }}</div>
    </div>
    @endif

    @if(isset($data))
    <div class="data-section">
        <h2 data-ar="البيانات المستخرجة" data-en="Extracted Data">البيانات المستخرجة</h2>
        
        {{-- Personal Information --}}
        <div class="data-item">
            <h3 data-ar="المعلومات الشخصية" data-en="Personal Information">المعلومات الشخصية</h3>
            <p><strong data-ar="الاسم" data-en="Name">الاسم:</strong> {{ $data['name'] ?? 'N/A' }}</p>
            <p><strong data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي:</strong> {{ $data['jop_title'] ?? 'N/A' }}</p>
            <p><strong data-ar="التخصص" data-en="Major">التخصص:</strong> <span class="badge badge-primary">{{ $data['major'] ?? 'N/A' }}</span></p>
            <p><strong data-ar="المدينة" data-en="City">المدينة:</strong> {{ $data['city'] ?? 'N/A' }}</p>
            @if(isset($data['phone']))
            <p><strong data-ar="الهاتف" data-en="Phone">الهاتف:</strong> {{ $data['phone'] }}</p>
            @endif
            @if(isset($data['email']))
            <p><strong data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني:</strong> {{ $data['email'] }}</p>
            @endif
            @if(isset($data['profile_summary']))
            <p><strong data-ar="الملخص المهني" data-en="Professional Summary">الملخص المهني:</strong></p>
            <p style="margin-top: 0.5rem;">{{ $data['profile_summary'] }}</p>
            @endif
        </div>

        {{-- Languages --}}
        @if(isset($data['languages']) && count($data['languages']) > 0)
        <div class="data-item">
            <h3 data-ar="اللغات" data-en="Languages">اللغات ({{ count($data['languages']) }})</h3>
            @foreach($data['languages'] as $lang)
            <div class="array-item">
                <p><strong>{{ $lang['language_name'] ?? 'N/A' }}</strong> - 
                <span class="badge badge-success">{{ $lang['proficiency_level'] ?? 'N/A' }}</span></p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Soft Skills --}}
        @if(isset($data['softSkills']) && count($data['softSkills']) > 0)
        <div class="data-item">
            <h3 data-ar="المهارات الشخصية" data-en="Soft Skills">المهارات الشخصية ({{ count($data['softSkills']) }})</h3>
            @foreach($data['softSkills'] as $skill)
            <span class="badge badge-primary">{{ $skill['soft_name'] ?? 'N/A' }}</span>
            @endforeach
        </div>
        @endif

        {{-- Experiences --}}
        @if(isset($data['experiences']) && count($data['experiences']) > 0)
        <div class="data-item">
            <h3 data-ar="الخبرات العملية" data-en="Work Experiences">الخبرات العملية ({{ count($data['experiences']) }})</h3>
            @foreach($data['experiences'] as $exp)
            <div class="array-item">
                <p><strong>{{ $exp['title'] ?? 'N/A' }}</strong> @ {{ $exp['company'] ?? 'N/A' }}</p>
                <p><small>{{ $exp['location'] ?? 'N/A' }} | {{ $exp['start_date'] ?? 'N/A' }} - {{ $exp['end_date'] ?? 'N/A' }}</small></p>
                @if(isset($exp['description']))
                <p style="margin-top: 0.5rem;">{{ $exp['description'] }}</p>
                @endif
                @if(isset($exp['is_internship']) && $exp['is_internship'])
                <span class="badge badge-success" data-ar="تدريب تعاوني" data-en="Internship">تدريب تعاوني</span>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Education --}}
        @if(isset($data['education']) && count($data['education']) > 0)
        <div class="data-item">
            <h3 data-ar="التعليم" data-en="Education">التعليم ({{ count($data['education']) }})</h3>
            @foreach($data['education'] as $edu)
            <div class="array-item">
                <p><strong>{{ $edu['degree_name'] ?? 'N/A' }}</strong> في {{ $edu['field_of_study'] ?? 'N/A' }}</p>
                <p>{{ $edu['university_name'] ?? 'N/A' }} | {{ $edu['start_year'] ?? 'N/A' }} - {{ $edu['end_year'] ?? 'N/A' }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Certifications --}}
        @if(isset($data['certifications']) && count($data['certifications']) > 0)
        <div class="data-item">
            <h3 data-ar="الشهادات" data-en="Certifications">الشهادات ({{ count($data['certifications']) }})</h3>
            @foreach($data['certifications'] as $cert)
            <div class="array-item">
                <p><strong>{{ $cert['certifications_name'] ?? 'N/A' }}</strong></p>
                <p>{{ $cert['issuing_org'] ?? 'N/A' }} | {{ $cert['issue_date'] ?? 'N/A' }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Activities --}}
        @if(isset($data['activities']) && count($data['activities']) > 0)
        <div class="data-item">
            <h3 data-ar="الأنشطة" data-en="Activities">الأنشطة ({{ count($data['activities']) }})</h3>
            @foreach($data['activities'] as $activity)
            <div class="array-item">
                <p><strong>{{ $activity['activity_title'] ?? 'N/A' }}</strong></p>
                <p>{{ $activity['organization'] ?? 'N/A' }} | {{ $activity['activity_date'] ?? 'N/A' }}</p>
                @if(isset($activity['description_activity']))
                <p style="margin-top: 0.5rem;">{{ $activity['description_activity'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- IT Skills --}}
        @if(isset($data['itSkills']) && count($data['itSkills']) > 0)
        <div class="data-item">
            <h3 data-ar="المهارات التقنية" data-en="IT Skills">المهارات التقنية ({{ count($data['itSkills']) }})</h3>
            @foreach($data['itSkills'] as $skill)
            <span class="badge badge-primary">{{ $skill['skill_name'] ?? 'N/A' }} (Category: {{ $skill['category_id'] ?? 'N/A' }})</span>
            @endforeach
        </div>
        @endif

        {{-- IT Projects --}}
        @if(isset($data['itProjects']) && count($data['itProjects']) > 0)
        <div class="data-item">
            <h3 data-ar="المشاريع" data-en="IT Projects">المشاريع ({{ count($data['itProjects']) }})</h3>
            @foreach($data['itProjects'] as $project)
            <div class="array-item">
                <p><strong>{{ $project['project_title'] ?? 'N/A' }}</strong></p>
                <p><small>{{ $project['technologies_used'] ?? 'N/A' }}</small></p>
                @if(isset($project['description_project']))
                <p style="margin-top: 0.5rem;">{{ $project['description_project'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- IT Analytical Skills --}}
        @if(isset($data['itAnalytical']) && count($data['itAnalytical']) > 0)
        <div class="data-item">
            <h3 data-ar="المهارات التحليلية" data-en="Analytical Skills">المهارات التحليلية ({{ count($data['itAnalytical']) }})</h3>
            @foreach($data['itAnalytical'] as $skill)
            <span class="badge badge-primary">{{ $skill['analytical_skill_name'] ?? 'N/A' }}</span>
            @endforeach
        </div>
        @endif

        {{-- Medical Skills --}}
        @if(isset($data['medicalSkills']) && count($data['medicalSkills']) > 0)
        <div class="data-item">
            <h3 data-ar="المهارات الطبية" data-en="Medical Skills">المهارات الطبية ({{ count($data['medicalSkills']) }})</h3>
            @foreach($data['medicalSkills'] as $skill)
            <span class="badge badge-primary">{{ $skill['medical_skill_name'] ?? 'N/A' }} (Category: {{ $skill['medical_category_id'] ?? 'N/A' }})</span>
            @endforeach
        </div>
        @endif

        {{-- Medical Research --}}
        @if(isset($data['medicalResearch']) && count($data['medicalResearch']) > 0)
        <div class="data-item">
            <h3 data-ar="الأبحاث الطبية" data-en="Medical Research">الأبحاث الطبية ({{ count($data['medicalResearch']) }})</h3>
            @foreach($data['medicalResearch'] as $research)
            <div class="array-item">
                <p><strong>{{ $research['research_title'] ?? 'N/A' }}</strong></p>
                <p><small>{{ $research['publication_year'] ?? 'N/A' }}</small></p>
                @if(isset($research['research_description']))
                <p style="margin-top: 0.5rem;">{{ $research['research_description'] }}</p>
                @endif
                @if(isset($research['research_link']))
                <p><a href="{{ $research['research_link'] }}" target="_blank">{{ $research['research_link'] }}</a></p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Business Skills --}}
        @if(isset($data['businessSkills']) && count($data['businessSkills']) > 0)
        <div class="data-item">
            <h3 data-ar="مهارات الأعمال" data-en="Business Skills">مهارات الأعمال ({{ count($data['businessSkills']) }})</h3>
            @foreach($data['businessSkills'] as $skill)
            <span class="badge badge-primary">{{ $skill['business_skill_name'] ?? 'N/A' }} (Category: {{ $skill['business_category_id'] ?? 'N/A' }})</span>
            @endforeach
        </div>
        @endif

        {{-- Business Competencies --}}
        @if(isset($data['businessCompetencies']) && count($data['businessCompetencies']) > 0)
        <div class="data-item">
            <h3 data-ar="الكفاءات الأساسية" data-en="Core Competencies">الكفاءات الأساسية ({{ count($data['businessCompetencies']) }})</h3>
            @foreach($data['businessCompetencies'] as $competency)
            <div class="array-item">
                <p><strong>{{ $competency['competency_name'] ?? 'N/A' }}</strong></p>
                @if(isset($competency['competency_description']))
                <p style="margin-top: 0.5rem;">{{ $competency['competency_description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Business Interests --}}
        @if(isset($data['businessInterests']) && count($data['businessInterests']) > 0)
        <div class="data-item">
            <h3 data-ar="الاهتمامات التجارية" data-en="Business Interests">الاهتمامات التجارية ({{ count($data['businessInterests']) }})</h3>
            @foreach($data['businessInterests'] as $interest)
            <span class="badge badge-primary">{{ $interest['interest_name'] ?? 'N/A' }}</span>
            @endforeach
        </div>
        @endif

        {{-- Engineering Skills --}}
        @if(isset($data['engineeringSkills']) && count($data['engineeringSkills']) > 0)
        <div class="data-item">
            <h3 data-ar="المهارات الهندسية" data-en="Engineering Skills">المهارات الهندسية ({{ count($data['engineeringSkills']) }})</h3>
            @foreach($data['engineeringSkills'] as $skill)
            <span class="badge badge-primary">{{ $skill['engineering_skill_name'] ?? 'N/A' }} (Category: {{ $skill['engineering_category_id'] ?? 'N/A' }})</span>
            @endforeach
        </div>
        @endif

        {{-- JSON Viewer --}}
        <div class="data-item">
            <h3 data-ar="البيانات الكاملة (JSON)" data-en="Full Data (JSON)">البيانات الكاملة (JSON)</h3>
            <div class="json-viewer">
                <pre>{!! json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}</pre>
            </div>
        </div>
    </div>
    @endif

    <div class="actions">
        <a href="{{ route('register') }}" class="btn btn-primary" data-ar="العودة للفورم" data-en="Back to Form">العودة للفورم</a>
        <button onclick="window.print()" class="btn btn-secondary" data-ar="طباعة" data-en="Print">طباعة</button>
    </div>
</div>
@endsection

