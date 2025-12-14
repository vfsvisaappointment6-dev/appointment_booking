<header class="header">
    <div class="header-content">
        <div class="logo">3_Aura</div>
        <nav class="nav">
            <a href="#services">Services</a>
            <a href="#why-choose">Why Us</a>
            <a href="#contact">Contact</a>
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @endauth
        </nav>
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="cta-button">Dashboard</a>
            @else
                <a href="{{ url('/api/auth/login') }}" class="cta-button">Book Now</a>
            @endauth
        </div>
    </div>
</header>
