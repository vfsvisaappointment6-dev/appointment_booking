<footer style="background-color: #0A0A0A; color: #D3D3D3; padding: 4rem 5% 2rem; border-top: 2px solid #FF7F39;">
    <div style="max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h4 style="color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-comments" style="margin-right: 0.5rem;"></i> About 3_Aura
            </h4>
            <p style="color: #999; font-size: 0.9rem; line-height: 1.6;">Premier appointment booking platform connecting clients with verified salon and grooming professionals. Easy booking, secure payments, trusted service.</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h4 style="color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-link" style="margin-right: 0.5rem;"></i> Quick Links
            </h4>
            <nav style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                <a href="#services" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Browse Services</a>
                <a href="#why-choose" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Why Choose Us</a>
                <a href="#contact" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Book Now</a>
                @guest
                    <a href="{{ route('login') }}" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Sign In</a>
                @endguest
            </nav>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h4 style="color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i> For Professionals
            </h4>
            <nav style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Join as Provider</a>
                <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Provider Portal</a>
                <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Partner Benefits</a>
            </nav>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h4 style="color: #FF7F39; margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-phone" style="margin-right: 0.5rem;"></i> Contact Us
            </h4>
            <nav style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                <a href="mailto:support@3aura.com" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">support@3aura.com</a>
                <a href="tel:+1234567890" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">+1 (234) 567-890</a>
                <span style="color: #D3D3D3;">Mon-Sun 9AM-9PM</span>
            </nav>
        </div>
    </div>

    <div style="border-top: 1px solid #333; padding-top: 2rem; text-align: center; color: #D3D3D3; font-size: 0.85rem;">
        <p>&copy; 2025 3_Aura. All rights reserved. |
            <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Privacy Policy</a> |
            <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Terms of Service</a> |
            <a href="#" style="color: #D3D3D3; text-decoration: none; transition: color 0.3s;">Cookie Policy</a>
        </p>
    </div>

    <style>
        footer a:hover { color: #FF7F39 !important; }
    </style>
</footer>
