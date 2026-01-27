<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masarak - بناء سيرتك الذاتية، عرض أعمالك وحفظ ذكريات التخرج</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            color: #333;
            line-height: 1.6;
            background: #fff;
        }

        /* Header Styles */
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: "M";
            background: #2563eb;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 1rem;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #2563eb;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 5rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.25rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .btn-hero {
            background: #2563eb;
            color: white;
            padding: 1.1rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-hero:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            transform: rotate(2deg);
            transition: transform 0.3s;
        }

        .hero-image:hover img {
            transform: rotate(0deg);
        }

        .floating-badge {
            position: absolute;
            background: white;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 500;
            z-index: 10;
            animation: float 3s ease-in-out infinite;
        }

        .badge-1 {
            top: 8%;
            left: -8%;
            background: #f3f4f6;
        }

        .badge-2 {
            bottom: 15%;
            right: -8%;
            background: #fef3c7;
        }

        .badge-2 i {
            color: #f59e0b;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Section Styles */
        section {
            padding: 6rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.25rem;
            color: #6b7280;
            margin-bottom: 4rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Services Section */
        .services-section {
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 50%);
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .services-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(240, 249, 255, 0.8) 0%, transparent 100%);
            z-index: 0;
        }

        .services-section .container {
            position: relative;
            z-index: 1;
        }

        .service-item {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            margin-bottom: 6rem;
        }

        .service-item:last-child {
            margin-bottom: 0;
        }

        .service-item:nth-child(even) {
            direction: rtl;
        }

        .service-item:nth-child(even) .service-content {
            direction: ltr;
        }

        .service-content h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: #1f2937;
        }

        .service-content ul {
            list-style: none;
        }

        .service-content ul li {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
            color: #4b5563;
        }

        .service-content ul li i {
            color: #2563eb;
            font-size: 1.3rem;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .service-image {
            position: relative;
        }

        .service-image img {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .service-image:hover img {
            transform: scale(1.02);
        }

        /* Why Choose Section */
        .why-choose {
            background: #f9fafb;
            padding: 6rem 0;
        }

        .why-choose .section-title span {
            color: #2563eb;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: white;
            padding: 0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .feature-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 0;
        }

        .feature-card-content {
            padding: 2rem;
        }

        .feature-card-content h4 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
            padding-top: 1rem;
            border-top: 3px solid #2563eb;
        }

        .feature-card-content p {
            color: #6b7280;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Who is MASARAK for Section */
        .who-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .who-content h2 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        .who-content h2 span {
            color: #2563eb;
        }

        .who-content p {
            font-size: 1.25rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .who-image img {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        /* Testimonials Section */
        .testimonials-section {
            background: #ffffff;
            padding: 6rem 0;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .testimonial-header img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
        }

        .testimonial-info h5 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #1f2937;
            font-size: 1.1rem;
        }

        .testimonial-info p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .testimonial-text {
            color: #4b5563;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Contact Section */
        .contact-section {
            background: #f9fafb;
            padding: 6rem 0;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: start;
        }

        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .contact-form h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .contact-form p {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 1rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .contact-info img {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-detail {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-size: 1.1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .contact-detail i {
            color: #2563eb;
            font-size: 1.8rem;
        }

        .contact-detail span {
            color: #374151;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            padding: 2.5rem 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
            color: #9ca3af;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .service-item,
            .who-section,
            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .features-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            nav ul {
                gap: 1rem;
                font-size: 0.9rem;
            }

            .floating-badge {
                position: relative;
                top: auto;
                right: auto;
                left: auto;
                bottom: auto;
                margin: 1rem 0;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .contact-form {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="#" class="logo">Masarak</a>
            <nav>
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="#services">Our Services</a></li>
                    <li><a href="#why-us">Why Us</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="{{ route('register') }}" class="btn-primary">Get started</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Build Your CV, Showcase Your Work & Keep Your Graduation Memories</h1>
            <p>Create a professional CV, craft a stunning Portfolio, and capture your graduation moments in a digital Notebook—all in one place, simple and interactive.</p>
            <a href="{{ route('register') }}" class="btn-hero">Get Started Now <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=800&h=1000&fit=crop" alt="Professional CV Template">
            <div class="floating-badge badge-1">
                <i class="fas fa-file-alt"></i>
                <span>Professionally Designed CV</span>
            </div>
            <div class="floating-badge badge-2">
                <i class="fas fa-star"></i>
                <span>Step By Step Expert Tips</span>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title">Our services</h2>
            
            <!-- Service 1: Portfolio -->
            <div class="service-item">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop" alt="Portfolio Design">
                </div>
                <div class="service-content">
                    <h3>Design a Portfolio That Represents You</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Present your projects, skills, and experience clearly and professionally.</li>
                        <li><i class="fas fa-check-circle"></i> Share your work confidently with recruiters and peers.</li>
                        <li><i class="fas fa-check-circle"></i> Make it easy for others to understand the value you bring.</li>
                    </ul>
                </div>
            </div>

            <!-- Service 2: CV -->
            <div class="service-item">
                <div class="service-content">
                    <h3>Create Your Perfect CV in Minutes</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Generate a professional, ATS-friendly CV in under 10 minutes.</li>
                        <li><i class="fas fa-check-circle"></i> Highlight your skills and experience clearly.</li>
                        <li><i class="fas fa-check-circle"></i> Boost your chances of landing interviews by up to 80%.</li>
                        <li><i class="fas fa-check-circle"></i> Fast, simple, and tailored to your career goals.</li>
                    </ul>
                </div>
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&h=600&fit=crop" alt="CV Creation">
                </div>
            </div>

            <!-- Service 3: Graduation Memories -->
            <div class="service-item">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop" alt="Graduation Memories">
                </div>
                <div class="service-content">
                    <h3>Capture Your Graduation Memories</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Create a digital graduation notebook for your special day.</li>
                        <li><i class="fas fa-check-circle"></i> Let friends write messages and share unforgettable memories.</li>
                        <li><i class="fas fa-check-circle"></i> Celebrate your journey together in one place.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose MASARAK Section -->
    <section id="why-us" class="why-choose">
        <div class="container">
            <h2 class="section-title">Why choose <span>MASARAK</span></h2>
            <p class="section-subtitle">Smart, simple, and ready to impress.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400&fit=crop" alt="Everything in One Place">
                    <div class="feature-card-content">
                        <h4>Everything in One Place</h4>
                        <p>Enter your information once, and we turn it into a complete CV, portfolio, and graduation notebook—simple, organized, and easy to use.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&h=400&fit=crop" alt="Smart Forms">
                    <div class="feature-card-content">
                        <h4>Smart Forms, Less Effort</h4>
                        <p>If long forms feel overwhelming, just share your details with our AI chatbot, it fills the form for you, so you can review and submit with confidence.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=400&fit=crop" alt="Graduation Memories">
                    <div class="feature-card-content">
                        <h4>Graduation Memories That Last</h4>
                        <p>Create a graduation notebook where friends can write messages, share memories, and celebrate your journey—something meaningful you can revisit.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who is MASARAK for Section -->
    <section class="container">
        <div class="who-section">
            <div class="who-content">
                <h2>Who is <span>MASARAK</span> for?</h2>
                <p>Students and graduates who want to create professional CVs, portfolios, and graduation memories easily.</p>
                <a href="{{ route('register') }}" class="btn-hero">Get Started Now <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="who-image">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=800&fit=crop" alt="Graduate Student">
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">What our users say</h2>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop" alt="Youssef Hassan">
                        <div class="testimonial-info">
                            <h5>Youssef Hassan</h5>
                            <p>UI/UX Designer</p>
                        </div>
                    </div>
                    <p class="testimonial-text">"Masarak made creating my CV and portfolio effortless. The AI helped me organize all my projects clearly, and I finally have a professional showcase I'm proud of."</p>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop" alt="Sara Mahmoud">
                        <div class="testimonial-info">
                            <h5>Sara Mahmoud</h5>
                            <p>Fullstack Engineer</p>
                        </div>
                    </div>
                    <p class="testimonial-text">"I loved how simple and fast it was to set up my graduation notebook and portfolio. Everything is in one place, and the results are super clean and professional."</p>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop" alt="Omar Kamal">
                        <div class="testimonial-info">
                            <h5>Omar Kamal</h5>
                            <p>Mobile App Developer</p>
                        </div>
                    </div>
                    <p class="testimonial-text">"Filling the forms used to be tedious, but the AI assistant made it smooth and quick. My CV and portfolio are now ready to impress recruiters, without any stress."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">Contact us</h2>
            
            <div class="contact-container">
                <div class="contact-form">
                    <h3>Get in touch with us</h3>
                    <p>We're here to answer your questions and help you get started.</p>
                    
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                        </div>
                        <div class="form-group">
                            <label for="second_name">Second Name</label>
                            <input type="text" id="second_name" name="second_name" placeholder="Enter your second name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Leave a message" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem;">Send Message</button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=500&fit=crop" alt="Contact">
                    <div class="contact-details">
                        <div class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <span>Email Us: Masarak123@gmail.com</span>
                        </div>
                        <div class="contact-detail">
                            <i class="fas fa-phone"></i>
                            <span>Call Us: Phone: +962 79 123 4567</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Masarak. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active link highlighting
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('nav a[href^="#"]');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
