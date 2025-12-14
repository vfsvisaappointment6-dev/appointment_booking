<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>3_Aura — Luxury Unisex Salon Booking</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|lato:300,400,700" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            :root { --primary-orange: #FF7F39; --secondary-orange: #EA6C2F; --dark-black: #0A0A0A; }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body { font-family: 'Lato', sans-serif; color: #0A0A0A; background: #FFFFFF; scroll-behavior: smooth; }
            h1, h2, h3, h4, h5, h6 { font-family: 'Playfair Display', serif; font-weight: 700; }
            a { color: inherit; text-decoration: none; }
            img { display: block; max-width: 100%; height: auto; }

            /* Header Styles */
            .header { position: fixed; top: 0; left: 0; right: 0; background: rgba(10, 10, 10, 0.85); backdrop-filter: blur(8px); z-index: 1000; padding: 1rem 5%; border-bottom: 1px solid rgba(255, 127, 57, 0.15); }
            .header-content { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 2rem; }
            .logo { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #FF7F39; letter-spacing: 0.05em; }
            .nav { display: flex; gap: 2.5rem; align-items: center; justify-self: center; }
            .nav a { color: #D3D3D3; font-size: 0.95rem; transition: color 0.3s ease; position: relative; }
            .nav a::after { content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 2px; background: #FF7F39; transition: width 0.3s ease; }
            .nav a:hover { color: #FF7F39; }
            .nav a:hover::after { width: 100%; }
            /* Ensure CTA buttons in the nav don't get the underline treatment */
            .nav .cta-button::after { display: none !important; }
            .nav .cta-button { text-decoration: none !important; color: #ffffff !important; background: #FF7F39 !important; border-color: #FF7F39 !important; box-shadow: 0 10px 30px rgba(255, 127, 57, 0.25); }
            @media (max-width: 768px) { .nav { gap: 1rem; font-size: 0.85rem; } .logo { font-size: 1.1rem; } }

            .hero { min-height: 90vh; display: grid; grid-template-columns: 1fr 480px; align-items: center; gap: 3rem; padding: 120px 5% 6rem; background: #000000; color: #FFFFFF; position: relative; }
            .hero::after { content: ''; position: absolute; inset: 0; background: transparent; pointer-events: none; }
            .hero-content { position: relative; z-index: 2; max-width: 780px; padding: 1rem 2rem; }
            .hero h1 { font-size: 3.6rem; color: #FFFFFF; margin-bottom: 1rem; line-height: 1.02; text-shadow: 0 12px 40px rgba(0,0,0,0.6); letter-spacing: 0.02em; }
            .hero-highlight { color: var(--primary-orange); }
            .hero p { font-size: 1.15rem; color: rgba(243,243,243,0.92); margin-bottom: 2rem; line-height: 1.6; max-width: 640px; }
            .hero-buttons { display: flex; gap: 1rem; align-items: center; }

            .cta-button { background: #FF7F39; color: white; padding: 0.75rem 2rem; border: 2px solid #FF7F39; border-radius: 50px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem; }
            .cta-button:hover { background: #EA6C2F; border-color: #EA6C2F; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255, 127, 57, 0.3); }
            .cta-button-secondary { background: transparent; color: #FF7F39; border: 2px solid #FF7F39; padding: 0.75rem 2rem; border-radius: 50px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem; }
            .cta-button-secondary:hover { background: #FF7F39; color: white; transform: translateY(-2px); }

            .services { padding: 6rem 5%; background: #FFFFFF; }
            .services-container { max-width: 1400px; margin: 0 auto; }
            .section-header { text-align: center; margin-bottom: 4rem; }
            .section-header h2 { font-size: 3.5rem; color: #0A0A0A; margin-bottom: 1rem; }
            .section-header .highlight { color: #FF7F39; }
            .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; }
            .service-card { background: #F5F5F5; padding: 3rem 2rem; border-radius: 10px; text-align: center; transition: all 0.3s ease; border: 2px solid transparent; cursor: pointer; position: relative; overflow: hidden; }
            .service-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #FF7F39, #EA6C2F); transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease; }
            .service-card:hover::before { transform: scaleX(1); }
            .service-card:hover { border-color: #FF7F39; box-shadow: 0 20px 50px rgba(255, 127, 57, 0.15); transform: translateY(-10px); }
            .service-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #FF7F39, #EA6C2F); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; }
            .service-card h3 { font-size: 1.5rem; color: #0A0A0A; margin-bottom: 1rem; }
            .service-card p { color: #757575; line-height: 1.6; font-size: 0.95rem; }

            .why-choose { padding: 6rem 5%; background: linear-gradient(135deg, #0A0A0A 0%, #1F1F1F 100%); color: #FFFFFF; }
            .why-choose-container { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
            .why-choose h2 { font-size: 3.5rem; margin-bottom: 2rem; line-height: 1.2; }
            .why-choose .highlight { color: #FF7F39; }
            .features-list { display: flex; flex-direction: column; gap: 2rem; }
            .feature-item { display: flex; gap: 1.5rem; align-items: flex-start; }
            .feature-icon { width: 50px; height: 50px; background: #FF7F39; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
            .feature-content h3 { font-size: 1.2rem; margin-bottom: 0.5rem; color: #FFFFFF; }
            .feature-content p { color: #D3D3D3; font-size: 0.95rem; line-height: 1.5; }
            .why-choose-image { display: flex; align-items: center; justify-content: center; }
            .why-choose-image img { max-width: 100%; border-radius: 15px; box-shadow: 0 30px 60px rgba(255, 127, 57, 0.2); }

            .cta-section { padding: 5rem; background: linear-gradient(135deg, #FF7F39, #EA6C2F); text-align: center; border-radius: 20px; margin: 6rem 5%; max-width: 1200px; margin-left: auto; margin-right: auto; color: white; }
            .cta-section h2 { font-size: 2.5rem; margin-bottom: 1.5rem; color: white; }
            .cta-section p { font-size: 1.1rem; margin-bottom: 2.5rem; max-width: 500px; margin-left: auto; margin-right: auto; color: rgba(255, 255, 255, 0.95); }
            .cta-button-light { background: white; color: #FF7F39; padding: 1rem 3rem; border: 2px solid white; border-radius: 50px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 1rem; display: inline-block; }
            .cta-button-light:hover { background: #FF7F39; color: white; transform: translateY(-2px); }

            .footer { background: #0A0A0A; color: #D3D3D3; padding: 4rem 5% 2rem; text-align: center; border-top: 2px solid #FF7F39; }
            .footer-content { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem; text-align: left; }
            .footer-section h4 { color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; }
            .footer-section a { color: #D3D3D3; transition: color 0.3s ease; display: block; margin-bottom: 0.8rem; font-size: 0.9rem; }
            .footer-section a:hover { color: #FF7F39; }
            .footer-bottom { text-align: center; padding-top: 2rem; border-top: 1px solid #333333; font-size: 0.85rem; }

            @media (max-width: 1024px) {
                .hero { padding: 100px 4% 3rem; }
                .hero h1 { font-size: 2.6rem; }
                .hero p { font-size: 1.05rem; }
            }

            @media (max-width: 768px) {
                .header-content { grid-template-columns: 1fr; padding: 0 1rem; gap: 0.5rem; }
                .logo { justify-self: start; }
                .nav { justify-self: center; flex-wrap: wrap; gap: 0.75rem; }
                .header-actions { justify-self: end; }

                .hero { grid-template-columns: 1fr; padding: 80px 20px 2rem; margin-top: 0; }
                .hero h1 { font-size: 2.2rem; }
                .hero p { font-size: 1rem; }
                .hero-buttons { flex-direction: column; align-items: stretch; }
                .hero-buttons a { width: 100%; text-align: center; }

                .services, .why-choose { padding: 4rem 20px; }
                .section-header h2 { font-size: 2rem; }
                .why-choose-container { grid-template-columns: 1fr; gap: 2rem; }
                .cta-section { padding: 3rem 20px; margin: 4rem 20px; }
            }

            @media (max-width: 480px) {
                .hero { padding: 70px 16px 2rem; }
                .hero h1 { font-size: 1.9rem; }
                .hero p { font-size: 0.95rem; }
                .cta-button { padding: 0.7rem 1rem; }
                .cta-button-secondary { padding: 0.7rem 1rem; }
            }
        </style>
    </head>
    <body>
        <!-- Header Navigation -->
        <x-header />

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1><span class="hero-highlight">3_Aura</span> — Luxury Unisex Salon</h1>
                <p>Book salon appointments effortlessly. Connect with verified professionals, secure your preferred time slots, and enjoy premium services with transparent pricing and instant confirmations.</p>
                <div class="hero-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-button"><i class="fas fa-sign-in-alt"></i> Get Started</a>
                    @else
                        <a href="{{ route('register') }}" class="cta-button"><i class="fas fa-user-plus"></i> Create Account</a>
                        <a href="{{ route('login') }}" class="cta-button-secondary"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services" id="services">
            <div class="services-container">
                <div class="section-header">
                    <h2>Available <span class="highlight">Services</span></h2>
                    <p>Browse and book from our extensive range of professional salon and grooming services</p>
                </div>

                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-cut"></i></div>
                        <h3>Haircuts & Styling</h3>
                        <p>Precision cuts and modern styling services for all hair types.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-palette"></i></div>
                        <h3>Color & Highlights</h3>
                        <p>Expert coloring, balayage, and highlights with premium products.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-scissors"></i></div>
                        <h3>Barber & Grooming</h3>
                        <p>Classic barbering including shaves, trims, fades, and beard care.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-spa"></i></div>
                        <h3>Nails & Manicure</h3>
                        <p>Manicures, pedicures, and nail art treatments.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-face-smile"></i></div>
                        <h3>Facials & Skincare</h3>
                        <p>Personalized facials and advanced skin treatments.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-fire"></i></div>
                        <h3>Waxing & Threading</h3>
                        <p>Professional hair removal services for smooth, lasting results.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-wand-magic-sparkles"></i></div>
                        <h3>Makeup & Events</h3>
                        <p>Professional makeup for events, bridal, and special occasions.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-ring"></i></div>
                        <h3>Bridal & Packages</h3>
                        <p>Complete packages for weddings and milestone celebrations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Section -->
        <section class="why-choose" id="why-choose">
            <div class="why-choose-container">
                <div>
                    <h2>Why Choose <span class="highlight">Us</span></h2>
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                            <div class="feature-content">
                                <h3>Instant Booking</h3>
                                <p>Book appointments in seconds with real-time availability and instant confirmations</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-star"></i></div>
                            <div class="feature-content">
                                <h3>Verified Professionals</h3>
                                <p>All staff are certified and background-checked for your safety</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-lock"></i></div>
                            <div class="feature-content">
                                <h3>Secure Payments</h3>
                                <p>SSL-encrypted transactions with multiple payment options for maximum security</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-headset"></i></div>
                            <div class="feature-content">
                                <h3>Dedicated Support</h3>
                                <p>Live chat and email support to assist with bookings and service issues</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-comments"></i></div>
                            <div class="feature-content">
                                <h3>Verified Reviews</h3>
                                <p>Honest client reviews and ratings to help you choose the best professionals</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="why-choose-image">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80" alt="Professional salon environment with modern styling" loading="lazy" />
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section" id="contact">
            <h2><i class="fas fa-star"></i> Reserve Your Seat at <span class="highlight">3_Aura</span></h2>
            <p>Book a tailored salon experience — effortless scheduling and trusted professionals.</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="cta-button-light"><i class="fas fa-arrow-right"></i> Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="cta-button-light"><i class="fas fa-calendar-plus"></i> Book Your First Appointment</a>
            @endauth
        </section>

        <!-- Footer -->
        <x-footer />


        <script>
            // Smooth scroll behavior for navigation links
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

            // Header scroll effect
            const header = document.querySelector('.header');
            let lastScrollTop = 0;

            window.addEventListener('scroll', () => {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > 100) {
                    header.style.boxShadow = '0 5px 30px rgba(0, 0, 0, 0.2)';
                } else {
                    header.style.boxShadow = 'none';
                }
                lastScrollTop = scrollTop;
            });
        </script>
    </body>
</html>
