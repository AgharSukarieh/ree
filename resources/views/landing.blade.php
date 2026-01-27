<!DOCTYPE html>
<html lang="ar" dir="rtl">
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
        }

        /* Header Styles */
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
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
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #2563eb;
            text-decoration: underline;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .btn-hero {
            background: #2563eb;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn-hero:hover {
            background: #1d4ed8;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .floating-badge {
            position: absolute;
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .badge-1 {
            top: 10%;
            right: -10%;
        }

        .badge-2 {
            bottom: 20%;
            left: -10%;
        }

        /* Section Styles */
        section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #000;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 3rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Services Section */
        .service-item {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            margin-bottom: 5rem;
        }

        .service-item:nth-child(even) {
            direction: ltr;
        }

        .service-item:nth-child(even) .service-content {
            direction: rtl;
        }

        .service-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #000;
        }

        .service-content ul {
            list-style: none;
        }

        .service-content ul li {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .service-content ul li i {
            color: #2563eb;
            font-size: 1.2rem;
            margin-top: 0.2rem;
        }

        .service-image img {
            width: 100%;
            border-radius: 12px;
        }

        /* Why Choose Section */
        .why-choose {
            background: #f8f9fa;
        }

        .why-choose .section-title span {
            color: #2563eb;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .feature-card img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .feature-card h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #000;
        }

        .feature-card p {
            color: #666;
            line-height: 1.8;
        }

        /* Who is MASARAK for Section */
        .who-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .who-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .who-content h2 span {
            color: #2563eb;
        }

        .who-content p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .who-image img {
            width: 100%;
            border-radius: 12px;
        }

        /* Testimonials Section */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }

        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .testimonial-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .testimonial-info h5 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .testimonial-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .testimonial-text {
            color: #666;
            line-height: 1.8;
        }

        /* Contact Section */
        .contact-section {
            background: #f8f9fa;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        .contact-form h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .contact-form p {
            color: #666;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-info img {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-detail {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.1rem;
        }

        .contact-detail i {
            color: #2563eb;
            font-size: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .service-item,
            .who-section,
            .contact-container {
                grid-template-columns: 1fr;
            }

            .features-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            nav ul {
                gap: 1rem;
                font-size: 0.9rem;
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
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="#" class="logo">M Masarak</a>
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
            <a href="{{ route('register') }}" class="btn-hero">Get Started Now →</a>
        </div>
        <div class="hero-image">
            <img src="https://via.placeholder.com/600x800/2563eb/ffffff?text=CV+Template" alt="CV Template">
            <div class="floating-badge badge-1">
                <i class="fas fa-chart-bar"></i>
                <span>Professionally Designed CV</span>
            </div>
            <div class="floating-badge badge-2">
                <i class="fas fa-star"></i>
                <span>Step By Step Expert Tips</span>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="container">
        <h2 class="section-title">Our services</h2>
        
        <!-- Service 1: Portfolio -->
        <div class="service-item">
            <div class="service-image">
                <img src="https://via.placeholder.com/500x400/2563eb/ffffff?text=Portfolio+Screens" alt="Portfolio Design">
            </div>
            <div class="service-content">
                <h3>Design a Portfolio That Represents You</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Present your projects, skills, and experience clearly and professionally.</li>
                    <li><i class="fas fa-check"></i> Share your work confidently with recruiters and peers.</li>
                    <li><i class="fas fa-check"></i> Make it easy for others to understand the value you bring.</li>
                </ul>
            </div>
        </div>

        <!-- Service 2: CV -->
        <div class="service-item">
            <div class="service-content">
                <h3>Create Your Perfect CV in Minutes</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Generate a professional, ATS-friendly CV in under 10 minutes.</li>
                    <li><i class="fas fa-check"></i> Highlight your skills and experience clearly.</li>
                    <li><i class="fas fa-check"></i> Boost your chances of landing interviews by up to 80%.</li>
                    <li><i class="fas fa-check"></i> Fast, simple, and tailored to your career goals.</li>
                </ul>
            </div>
            <div class="service-image">
                <img src="https://via.placeholder.com/500x400/2563eb/ffffff?text=CV+Creation" alt="CV Creation">
            </div>
        </div>

        <!-- Service 3: Graduation Memories -->
        <div class="service-item">
            <div class="service-image">
                <img src="https://via.placeholder.com/500x400/2563eb/ffffff?text=Graduation+Messages" alt="Graduation Memories">
            </div>
            <div class="service-content">
                <h3>Capture Your Graduation Memories</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Create a digital graduation notebook for your special day.</li>
                    <li><i class="fas fa-check"></i> Let friends write messages and share unforgettable memories.</li>
                    <li><i class="fas fa-check"></i> Celebrate your journey together in one place.</li>
                </ul>
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
                    <img src="https://via.placeholder.com/400x300/2563eb/ffffff?text=Everything+in+One+Place" alt="Everything in One Place">
                    <h4>Everything in One Place</h4>
                    <p>Consolidate your CV, portfolio, and graduation notebook in one convenient platform. No need to juggle multiple tools or services.</p>
                </div>
                <div class="feature-card">
                    <img src="https://via.placeholder.com/400x300/2563eb/ffffff?text=Smart+Forms" alt="Smart Forms">
                    <h4>Smart Forms, Less Effort</h4>
                    <p>Our AI chatbot helps you fill out forms quickly and accurately. Save time and focus on what matters most.</p>
                </div>
                <div class="feature-card">
                    <img src="https://via.placeholder.com/400x300/2563eb/ffffff?text=Graduation+Memories" alt="Graduation Memories">
                    <h4>Graduation Memories That Last</h4>
                    <p>Create a beautiful digital graduation notebook where friends can leave messages and share memories that you'll treasure forever.</p>
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
                <a href="{{ route('register') }}" class="btn-hero">Get Started Now →</a>
            </div>
            <div class="who-image">
                <img src="https://via.placeholder.com/500x600/2563eb/ffffff?text=Graduate+Student" alt="Graduate Student">
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="container">
        <h2 class="section-title">What our users say</h2>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://via.placeholder.com/60x60/2563eb/ffffff?text=YH" alt="Youssef Hassan">
                    <div class="testimonial-info">
                        <h5>Youssef Hassan</h5>
                        <p>UI/UX Designer</p>
                    </div>
                </div>
                <p class="testimonial-text">Masarak made creating my CV and portfolio effortless. The AI assistance is incredible and the results are professional.</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://via.placeholder.com/60x60/2563eb/ffffff?text=SM" alt="Sara Mahmoud">
                    <div class="testimonial-info">
                        <h5>Sara Mahmoud</h5>
                        <p>Fullstack Engineer</p>
                    </div>
                </div>
                <p class="testimonial-text">The graduation notebook and portfolio features are amazing. Simple, fast, and the results look incredibly professional.</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://via.placeholder.com/60x60/2563eb/ffffff?text=OK" alt="Omar Kamal">
                    <div class="testimonial-info">
                        <h5>Omar Kamal</h5>
                        <p>Mobile App Developer</p>
                    </div>
                </div>
                <p class="testimonial-text">The AI assistant made filling out forms so smooth and quick. My CV and portfolio are ready for recruiters without any stress.</p>
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
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="second_name">Second Name</label>
                            <input type="text" id="second_name" name="second_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Send Message</button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <img src="https://via.placeholder.com/500x400/2563eb/ffffff?text=Contact+Image" alt="Contact">
                    <div class="contact-details">
                        <div class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <span>Email Us: Masarak12@gmail.com</span>
                        </div>
                        <div class="contact-detail">
                            <i class="fas fa-phone"></i>
                            <span>Call Us: Phone: +962 7 9 123 4567</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #1f2937; color: white; padding: 2rem 0; text-align: center;">
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
    </script>
</body>
</html>

