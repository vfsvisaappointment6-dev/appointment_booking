<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sign In — 3_Aura</title>
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
            .auth-form-wrapper { width: 100%; max-width: 450px; background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); }
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

            .remember-forgot { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; font-size: 0.9rem; }
            .remember-forgot a { color: #FF7F39; transition: color 0.3s ease; }
            .remember-forgot a:hover { color: #EA6C2F; }

            .checkbox-wrapper { display: flex; align-items: center; gap: 0.5rem; }
            .checkbox-wrapper input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; accent-color: #FF7F39; }

            .submit-btn { width: 100%; background: #FF7F39; color: white; padding: 0.85rem; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
            .submit-btn:hover { background: #EA6C2F; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255, 127, 57, 0.3); }
            .submit-btn:active { transform: translateY(0); }

            .divider { display: flex; align-items: center; margin: 2rem 0; color: #B0B0B0; font-size: 0.85rem; }
            .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #E0E0E0; }
            .divider span { padding: 0 1rem; }

            .social-login { display: flex; gap: 1rem; margin-bottom: 2rem; }
            .social-btn { flex: 1; padding: 0.75rem; border: 2px solid #E0E0E0; border-radius: 8px; background: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600; color: #0A0A0A; }
            .social-btn:hover { border-color: #FF7F39; color: #FF7F39; }
            .social-btn i { font-size: 1.1rem; }

            .auth-link { text-align: center; margin-top: 1.5rem; font-size: 0.95rem; color: #757575; }
            .auth-link a { color: #FF7F39; font-weight: 600; transition: color 0.3s ease; }
            .auth-link a:hover { color: #EA6C2F; }

            .footer { background: #0A0A0A; color: #D3D3D3; padding: 4rem 5% 2rem; text-align: center; border-top: 2px solid #FF7F39; }
            .footer-content { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem; text-align: left; }
            .footer-section h4 { color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; }
            .footer-section a { color: #D3D3D3; transition: color 0.3s ease; display: block; margin-bottom: 0.8rem; font-size: 0.9rem; }
            .footer-section a:hover { color: #FF7F39; }
            .footer-bottom { text-align: center; padding-top: 2rem; border-top: 1px solid #333333; font-size: 0.85rem; }

            @media (max-width: 1024px) {
                .auth-content { padding: 2.5rem 4%; }
                .auth-form-wrapper { max-width: 520px; }
            }

            @media (max-width: 768px) {
                .header-content { grid-template-columns: 1fr; padding: 0 1rem; gap: 0.5rem; }
                .logo { justify-self: start; }
                .nav { justify-self: center; flex-wrap: wrap; gap: 0.75rem; }
                .header-actions { justify-self: end; }

                .auth-container { padding-top: 70px; }
                .auth-content { padding: 2rem 20px; }
                .auth-form-wrapper { padding: 1.6rem; border-radius: 12px; width: 100%; max-width: 420px; }
                .auth-form-wrapper h2 { font-size: 1.5rem; }
                .form-group label { font-size: 0.95rem; }
                .submit-btn { width: 100%; }
                .social-login { flex-direction: column; }
                .social-btn { width: 100%; }
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
                    <h2><i class="fas fa-sign-in-alt"></i> Welcome Back</h2>
                    <p>Sign in to your 3_Aura account to book appointments</p>

                    @if ($errors->any())
                        <div style="background: #FFE5E5; color: #E74C3C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #E74C3C;">
                            <strong><i class="fas fa-exclamation-circle"></i> Login Failed</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
                        @csrf

                        <div class="form-group @error('email') error @enderror">
                            <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="jane@example.com"
                                required
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="none"
                                spellcheck="false"
                            >
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group @error('password') error @enderror">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Your password"
                                required
                                autocomplete="new-password"
                                autocorrect="off"
                                autocapitalize="none"
                                spellcheck="false"
                            >
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="remember-forgot">
                            <div class="checkbox-wrapper">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label for="remember" style="margin: 0; cursor: pointer;">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                    </form>

                    <div class="auth-link">
                        Don't have an account? <a href="{{ route('register') }}">Create one now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <x-footer />
    </body>
</html>
