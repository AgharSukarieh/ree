<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memoria - Digital Portfolio Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .qr-demo {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-qrcode me-2"></i>Memoria
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Digital Portfolios Made Simple</h1>
                    <p class="lead mb-4">Create and share your professional portfolio with a simple QR code. Perfect for students, professionals, and anyone who wants to showcase their skills and achievements.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-eye me-2"></i>تصفح الملفات
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>إنشاء ملف شخصي
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="qr-demo">
                        <i class="fas fa-qrcode fa-5x text-primary mb-3"></i>
                        <h5>Scan QR Code to View Portfolio</h5>
                        <p class="text-muted">Share your profile instantly with anyone</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">Why Choose Memoria?</h2>
                    <p class="lead text-muted">Everything you need to create a professional digital portfolio</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Student-Friendly</h5>
                            <p class="card-text">Perfect for students to showcase their projects, skills, and academic achievements.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-briefcase fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Professional Ready</h5>
                            <p class="card-text">Ideal for professionals to display their experience, certifications, and expertise.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt fa-3x text-info mb-3"></i>
                            <h5 class="card-title">QR Code Access</h5>
                            <p class="card-text">Share your portfolio instantly with a simple QR code scan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sample Profiles Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">Sample Profiles</h2>
                    <p class="lead text-muted">See how others are using Memoria</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Ahmed Hassan</h6>
                                    <small class="text-muted">Software Developer</small>
                                </div>
                            </div>
                            <p class="card-text">IT professional showcasing web development skills and projects.</p>
                            <a href="{{ route('profile', 'USER001') }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-success text-white rounded-circle me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Dr. Sara Mohamed</h6>
                                    <small class="text-muted">Medical Doctor</small>
                                </div>
                            </div>
                            <p class="card-text">Medical professional highlighting clinical skills and experience.</p>
                            <a href="{{ route('profile', 'USER002') }}" class="btn btn-outline-success btn-sm">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-qrcode me-2"></i>Memoria</h5>
                    <p class="mb-0">Digital Portfolio Platform</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Memoria. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>