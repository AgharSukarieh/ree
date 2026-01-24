<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Memoria Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .section-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .skill-tag {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin: 0.25rem;
        }
        .contact-link {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .contact-link:hover {
            color: #495057;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-qrcode me-2"></i>Memoria
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                    <h3 class="h5 mb-3">{{ $user->job_title }}</h3>
                    <p class="lead mb-4">{{ $user->profile_summary }}</p>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-light text-dark fs-6">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $user->city }}
                        </span>
                        <span class="badge bg-light text-dark fs-6">
                            <i class="fas fa-graduation-cap me-1"></i>{{ $user->major }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- About Section -->
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>About</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $user->profile_summary }}</p>
                    </div>
                </div>

                <!-- Experience Section -->
                @if($user->experiences->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Experience</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->experiences as $experience)
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ $experience->title }}</h6>
                            <p class="text-muted mb-1">{{ $experience->company }} - {{ $experience->location }}</p>
                            <p class="text-muted mb-2">
                                {{ $experience->start_date }} - {{ $experience->end_date ?? 'Present' }}
                                @if($experience->is_internship)
                                    <span class="badge bg-info ms-2">Internship</span>
                                @endif
                            </p>
                            <p class="card-text">{{ $experience->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Skills Section -->
                @php
                    // Ensure skills relationship is loaded with categories
                    if (!$user->relationLoaded('skills')) {
                        $user->load('skills.category');
                    } else {
                        // If already loaded, ensure categories are loaded
                        foreach ($user->skills as $skill) {
                            if (!$skill->relationLoaded('category')) {
                                $skill->load('category');
                            }
                        }
                    }
                    
                    $skillsCount = $user->skills->count();
                @endphp
                
                @if($skillsCount > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-code me-2"></i>Technical Skills</h5>
                    </div>
                    <div class="card-body">
                        @php
                            // Group skills by category name
                            $skillsByCategory = [];
                            foreach ($user->skills as $skill) {
                                $categoryName = 'Other Skills';
                                if ($skill->category && !empty($skill->category->category_name)) {
                                    $categoryName = $skill->category->category_name;
                                }
                                
                                if (!isset($skillsByCategory[$categoryName])) {
                                    $skillsByCategory[$categoryName] = [];
                                }
                                $skillsByCategory[$categoryName][] = $skill;
                            }
                            ksort($skillsByCategory);
                        @endphp
                        @foreach($skillsByCategory as $categoryName => $skills)
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="fas fa-tag me-1"></i>{{ $categoryName }}
                            </h6>
                            <div>
                                @foreach($skills as $skill)
                                    <span class="skill-tag">{{ $skill->skill_name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Medical Skills Section -->
                @if($user->medicalSkills->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Medical Skills</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->medicalSkills->groupBy('category.category_name') as $categoryName => $skills)
                        <div class="mb-3">
                            <h6 class="fw-bold text-success">{{ $categoryName }}</h6>
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ $skill->skill_name }}</span>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Projects Section -->
                @if($user->projects->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Projects</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->projects as $project)
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ $project->project_title }}</h6>
                            <p class="text-muted mb-2">{{ $project->technologies_used }}</p>
                            <p class="card-text">{{ $project->description }}</p>
                            @if($project->link)
                                <a href="{{ $project->link }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>View Project
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Activities Section -->
                @if($user->activities->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Activities</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->activities as $activity)
                        <div class="mb-3">
                            <h6 class="fw-bold">{{ $activity->activity_title }}</h6>
                            <p class="text-muted mb-1">{{ $activity->organization }} - {{ $activity->activity_date->format('M Y') }}</p>
                            <p class="card-text">{{ $activity->description }}</p>
                            @if($activity->activity_link)
                                <a href="{{ $activity->activity_link }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>Learn More
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Information -->
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:{{ $user->email }}" class="contact-link">{{ $user->email }}</a>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $user->phone }}" class="contact-link">{{ $user->phone }}</a>
                        </div>
                        @if($user->linkedin_profile)
                        <div class="mb-3">
                            <i class="fab fa-linkedin me-2"></i>
                            <a href="{{ $user->linkedin_profile }}" class="contact-link" target="_blank">LinkedIn Profile</a>
                        </div>
                        @endif
                        @if($user->github_profile)
                        <div class="mb-3">
                            <i class="fab fa-github me-2"></i>
                            <a href="{{ $user->github_profile }}" class="contact-link" target="_blank">GitHub Profile</a>
                        </div>
                        @endif
                        @if($user->profile_website)
                        <div class="mb-3">
                            <i class="fas fa-globe me-2"></i>
                            <a href="{{ $user->profile_website }}" class="contact-link" target="_blank">Personal Website</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Languages -->
                @if($user->languages->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-language me-2"></i>Languages</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->languages as $language)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $language->language_name }}</span>
                            <span class="badge bg-secondary">{{ $language->proficiency_level }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Soft Skills -->
                @if($user->softSkills->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Soft Skills</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->softSkills as $skill)
                            <span class="skill-tag">{{ $skill->soft_name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Interests -->
                @if($user->interests->count() > 0)
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Interests</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->interests as $interest)
                            <span class="skill-tag">{{ $interest->interest_name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Download CV -->
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-download me-2"></i>Download CV</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('download.pdf', $user->qr_id) }}" class="btn btn-danger w-100 mb-2">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF
                        </a>
                        <a href="{{ route('download.word', $user->qr_id) }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-file-word me-2"></i>Download Word
                        </a>
                        <a href="{{ route('download.wishes', $user->qr_id) }}" class="btn btn-success w-100">
                            <i class="fas fa-heart me-2"></i>Download Wishes Book
                        </a>
                    </div>
                </div>

                <!-- Share Profile -->
                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Share Profile</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                        <p class="text-muted mb-3">Share this profile with others</p>
                        <button class="btn btn-primary" onclick="shareProfile()">
                            <i class="fas fa-share me-1"></i>Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function shareProfile() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $user->name }} - Memoria Profile',
                    text: 'Check out {{ $user->name }}\'s professional profile',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Profile URL copied to clipboard!');
                });
            }
        }
    </script>
</body>
</html>
