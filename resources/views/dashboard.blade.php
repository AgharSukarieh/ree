<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Memoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-card:hover {
            transform: translateY(-5px);
        }
        .major-badge {
            font-size: 0.75rem;
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
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('register') }}">
                    <i class="fas fa-user-plus me-1"></i>إنشاء ملف شخصي
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold">User Dashboard</h1>
                <p class="lead text-muted">Browse all active profiles in the system</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($users as $user)
            <div class="col-md-6 col-lg-4">
                <div class="card profile-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">{{ $user->name }}</h5>
                                <p class="card-text text-muted mb-1">{{ $user->job_title }}</p>
                                <span class="badge bg-secondary major-badge">{{ $user->major }}</span>
                            </div>
                        </div>
                        
                        <p class="card-text">{{ Str::limit($user->profile_summary, 100) }}</p>
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $user->city }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('profile', $user->qr_id) }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>View Profile
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="shareProfile('{{ $user->qr_id }}')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No profiles found</h4>
                    <p class="text-muted">There are no active profiles in the system yet.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Share Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-qrcode fa-4x text-primary"></i>
                    </div>
                    <p>Share this QR code or the profile link below:</p>
                    <div class="input-group">
                        <input type="text" class="form-control" id="profileUrl" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function shareProfile(qrId) {
            const profileUrl = window.location.origin + '/profile/' + qrId;
            document.getElementById('profileUrl').value = profileUrl;
            new bootstrap.Modal(document.getElementById('shareModal')).show();
        }

        function copyToClipboard() {
            const urlInput = document.getElementById('profileUrl');
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Show feedback
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-secondary');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }
    </script>
</body>
</html>
