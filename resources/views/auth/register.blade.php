<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create Account — 3_Aura</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|lato:300,400,700" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            :root { --primary-orange: #FF7F39; --secondary-orange: #EA6C2F; --dark-black: #0A0A0A; }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body { font-family: 'Lato', sans-serif; color: #0A0A0A; background: #000000; scroll-behavior: auto; }
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
            .cta-button { background: #FF7F39; color: white; padding: 0.75rem 2rem; border: 2px solid #FF7F39; border-radius: 50px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem; display: inline-block; }
            .cta-button:hover { background: #EA6C2F; border-color: #EA6C2F; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255, 127, 57, 0.3); }
            @media (max-width: 768px) { .nav { gap: 1rem; font-size: 0.85rem; } .logo { font-size: 1.1rem; } }

            /* Main Content Styles */
            .auth-container { display: flex; flex-direction: column; min-height: 100vh; padding-top: 80px; }
            .auth-content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 3rem 5%; background: #000000; }
            .auth-form-wrapper { width: 100%; max-width: 500px; background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); }
            .auth-form-wrapper h2 { font-size: 2rem; color: #0A0A0A; margin-bottom: 0.5rem; text-align: center; }
            .auth-form-wrapper p { font-size: 0.95rem; color: #757575; margin-bottom: 2rem; text-align: center; }

            .form-group { margin-bottom: 1.5rem; }
            .form-group label { display: block; font-weight: 600; color: #0A0A0A; margin-bottom: 0.5rem; font-size: 0.95rem; }
            .form-group input, .form-group select { width: 100%; padding: 0.75rem 1rem; border: 2px solid #E0E0E0; border-radius: 8px; font-size: 0.95rem; font-family: inherit; transition: all 0.3s ease; }
            .form-group input:focus, .form-group select:focus { outline: none; border-color: #FF7F39; box-shadow: 0 0 0 3px rgba(255, 127, 57, 0.1); }
            .form-group input::placeholder { color: #B0B0B0; }

            .form-group.error input, .form-group.error select { border-color: #E74C3C; }
            .error-message { color: #E74C3C; font-size: 0.85rem; margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
            .error-message i { font-size: 0.75rem; }

            .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
            .form-row .form-group { margin-bottom: 0; }

            .terms-wrapper { display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 2rem; font-size: 0.85rem; line-height: 1.4; }
            .terms-wrapper input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; accent-color: #FF7F39; margin-top: 0.2rem; flex-shrink: 0; }
            .terms-wrapper a { color: #FF7F39; font-weight: 600; }
            .terms-wrapper a:hover { color: #EA6C2F; }

            .submit-btn { width: 100%; background: #FF7F39; color: white; padding: 0.85rem; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
            .submit-btn:hover { background: #EA6C2F; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255, 127, 57, 0.3); }
            .submit-btn:active { transform: translateY(0); }

            .auth-link { text-align: center; margin-top: 1.5rem; font-size: 0.95rem; color: #757575; }
            .auth-link a { color: #FF7F39; font-weight: 600; transition: color 0.3s ease; }
            .auth-link a:hover { color: #EA6C2F; }

            .password-strength { margin-top: 0.5rem; }
            .strength-bar { height: 4px; background: #E0E0E0; border-radius: 2px; overflow: hidden; }
            .strength-fill { height: 100%; width: 0%; transition: all 0.3s ease; }
            .strength-text { font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600; }

            .footer { background: #0A0A0A; color: #D3D3D3; padding: 4rem 5% 2rem; text-align: center; border-top: 2px solid #FF7F39; }
            .footer-content { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem; text-align: left; }
            .footer-section h4 { color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; }
            .footer-section a { color: #D3D3D3; transition: color 0.3s ease; display: block; margin-bottom: 0.8rem; font-size: 0.9rem; }
            .footer-section a:hover { color: #FF7F39; }
            .footer-bottom { text-align: center; padding-top: 2rem; border-top: 1px solid #333333; font-size: 0.85rem; }

            @media (max-width: 1024px) {
                .auth-content { padding: 2.5rem 4%; }
                .auth-form-wrapper { max-width: 560px; }
            }

            @media (max-width: 768px) {
                .header-content { grid-template-columns: 1fr; padding: 0 1rem; gap: 0.5rem; }
                .logo { justify-self: start; }
                .nav { justify-self: center; flex-wrap: wrap; gap: 0.75rem; }
                .header-actions { justify-self: end; }

                .auth-container { padding-top: 70px; }
                .auth-content { padding: 2rem 20px; }
                .auth-form-wrapper { padding: 1.6rem; border-radius: 12px; width: 100%; max-width: 480px; }
                .auth-form-wrapper h2 { font-size: 1.5rem; }
                .form-group label { font-size: 0.95rem; }
                .submit-btn { width: 100%; }
                .form-row { grid-template-columns: 1fr; }
            }

            @media (max-width: 480px) {
                .auth-form-wrapper { padding: 1.2rem; }
                .auth-form-wrapper h2 { font-size: 1.25rem; }
                .form-group input, .form-group select { padding: 0.6rem 0.8rem; }
                .submit-btn { padding: 0.8rem; font-size: 0.95rem; }
            }
        </style>
    </head>
    <body>
        <!-- Header Navigation -->
        <x-header />

        <!-- Auth Container -->
        <div class="auth-container">
            <div class="auth-content">
                <div class="auth-form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Create Account</h2>
                    <p>Join 3_Aura to book amazing salon appointments</p>

                    @if ($errors->any())
                        <div style="background: #FFE5E5; color: #E74C3C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #E74C3C; font-size: 0.9rem;">
                            <strong><i class="fas fa-exclamation-circle"></i> Registration Failed</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group @error('name') error @enderror">
                            <label for="name"><i class="fas fa-user"></i> Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Alex Johnson"
                                value="{{ old('name') }}"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group @error('email') error @enderror">
                            <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="alex.johnson@example.com"
                                value="{{ old('email') }}"
                                required
                            >
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group @error('phone') error @enderror">
                                <label for="phone"><i class="fas fa-phone"></i> Phone</label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    placeholder="+233 55 123 4567"
                                    value="{{ old('phone') }}"
                                >
                                @error('phone')
                                    <div class="error-message">
                                        <i class="fas fa-times-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group @error('role') error @enderror">
                                <label for="role"><i class="fas fa-briefcase"></i> Role</label>
                                <select id="role" name="role">
                                    <option value="customer" selected>Customer</option>
                                    <option value="staff">Staff</option>
                                </select>
                                @error('role')
                                    <div class="error-message">
                                        <i class="fas fa-times-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group @error('password') error @enderror">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Choose a password (min 8 characters)"
                                required
                            >
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <div class="strength-text" id="strengthText"></div>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group @error('password_confirmation') error @enderror">
                            <label for="password_confirmation"><i class="fas fa-check-circle"></i> Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Re-enter your password"
                                required
                            >
                            @error('password_confirmation')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="terms-wrapper">
                            <input
                                type="checkbox"
                                id="terms"
                                name="terms_agreed"
                                value="1"
                                required
                            >
                            <label for="terms" style="margin: 0; cursor: pointer;">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-user-plus"></i> Create My Account
                        </button>
                    </form>

                    <div class="auth-link">
                        Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <x-footer />

        <script>
            // Password strength indicator
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                const strengthFill = document.getElementById('strengthFill');
                const strengthText = document.getElementById('strengthText');
                let strength = 0;
                let text = '';

                // Check password strength
                if (password.length >= 8) strength += 25;
                if (password.length >= 12) strength += 25;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
                if (/\d/.test(password) && /[!@#$%^&*]/.test(password)) strength += 25;

                if (password.length === 0) {
                    strength = 0;
                    text = '';
                } else if (strength <= 25) {
                    text = '<span style="color: #E74C3C;">Weak</span>';
                } else if (strength <= 50) {
                    text = '<span style="color: #F39C12;">Fair</span>';
                } else if (strength <= 75) {
                    text = '<span style="color: #27AE60;">Good</span>';
                } else {
                    text = '<span style="color: #27AE60;">Strong</span>';
                }

                strengthFill.style.width = strength + '%';
                strengthFill.style.backgroundColor =
                    strength <= 25 ? '#E74C3C' :
                    strength <= 50 ? '#F39C12' :
                    strength <= 75 ? '#3498DB' : '#27AE60';

                strengthText.innerHTML = text;
            });
        </script>
    </body>
</html>
