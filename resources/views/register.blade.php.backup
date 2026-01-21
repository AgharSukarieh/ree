@extends('layouts.app')

@section('title', 'نظام السيرة الذاتية المتقدم - CV Registration System')

@section('content')
    <!-- Main Content -->
    <main class="container">
        <!-- Robot Chat Component -->
        <div class="robot-section">
            @include('components.robot-chat.robot-chat')
        </div>
        
        <!-- Form Container -->
        <div class="form-container">
            <form id="cvForm" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                @csrf
                
                {{-- Basic Personal Information --}}
                @include('components.register.sections.personal-information')
                
                {{-- Languages --}}
                @include('components.register.sections.languages')
                
                {{-- Soft Skills --}}
                @include('components.register.sections.soft-skills')
                
                {{-- Experiences --}}
                @include('components.register.sections.experiences')
                
                {{-- Dynamic Sections Based on Major --}}
                {{-- IT Skills --}}
                @include('components.register.sections.it-skills')
                
                {{-- IT Projects --}}
                @include('components.register.sections.it-projects')
                
                {{-- Medicine Medical Skills --}}
                @include('components.register.sections.medical-skills')
                
                {{-- Medical Research --}}
                @include('components.register.sections.medical-research')
                
                {{-- Business Skills --}}
                @include('components.register.sections.business-skills')
                
                {{-- Business Core Competencies --}}
                @include('components.register.sections.business-competencies')
                
                {{-- Business Interests --}}
                @include('components.register.sections.business-interests')
                
                {{-- Engineering Skills --}}
                @include('components.register.sections.engineering-skills')
                
                {{-- Education --}}
                @include('components.register.sections.education')
                
                {{-- Certifications --}}
                @include('components.register.sections.certifications')
                
                {{-- Memberships --}}
                @include('components.register.sections.memberships')
                
                {{-- Activities --}}
                @include('components.register.sections.activities')
                
                {{-- IT Analytical Skills --}}
                @include('components.register.sections.it-analytical-skills')
                
                {{-- Submit Button --}}
                <div class="submit-container">
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        <span data-ar="إرسال السيرة الذاتية" data-en="Submit CV">إرسال السيرة الذاتية</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
