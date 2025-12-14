<header class="header">
    <div class="header-content">
        <div class="logo">{{ $logoText ?? '3_Aura' }}</div>

        <!-- Mobile menu toggle -->
        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false" style="display:none; background:none; border:none; color:inherit; font-size:1.25rem;">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="nav" role="navigation">
            <a href="{{ url('/') }}">Home</a>
            <a href="#services">Services</a>
            <a href="#why-choose">Why Us</a>
            <a href="#contact">Contact</a>
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @endauth

            {{-- allow pages to inject extra nav items via component slot --}}
            {{ $slot }}
        </nav>

        <div class="header-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="cta-button">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-link nav-signin">Sign In</a>
                <a href="{{ route('register') }}" class="cta-button nav-register">Register</a>
            @endauth
        </div>
    </div>
</header>
<!-- Header mobile styles & toggle script (kept here so header behaves consistently across pages) -->
<style>
    /* Header core polish */
    .header { transition: background 200ms ease, backdrop-filter 200ms ease, padding 160ms ease; color: #D3D3D3; }
    .header-content { align-items: center; }
    /* Force high-contrast link colors inside the header so they remain visible on dark pages */
    .header .nav a.nav-link, .header .nav a { color: #FFFFFF !important; padding: 0.35rem 0.25rem; position: relative; font-weight:600; }
    .header .nav a.nav-link:hover { color: #FF7F39 !important; }
    .header .nav a::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 0; height: 2px; background: #FF7F39; transition: width 0.25s ease; }
    .header .nav a:hover::after { width: 100%; }
    .cta-button { border-radius: 999px; padding: 0.6rem 1.5rem; font-weight:700; }
    .nav-register { color:#fff; background:var(--primary-orange); border:2px solid var(--primary-orange); box-shadow:0 8px 20px rgba(255,127,57,0.15); }
    /* Make the Sign In link in header-actions explicitly visible */
    .header-actions .nav-link { color: #FFFFFF !important; background: transparent; border: none; padding: 0.4rem 0.5rem; }
    /* Extra safeguard: target the sign-in specific class to overcome any cascade issues */
    .nav-signin, .header-actions a.nav-signin { color: #FFFFFF !important; }

    /* Header actions (right) */
    .header-actions { display:flex; gap:1rem; align-items:center; justify-self:end; }

    /* Mobile menu (animated) */
    @media (max-width: 768px) {
        .nav-toggle { display: inline-flex; align-items:center; justify-content:center; width:44px; height:44px; background:transparent; border-radius:8px; color: #FFFFFF; }
        .nav { display:flex; position:absolute; left:0; right:0; top:100%; background: rgba(10,10,10,0.98); padding: 0.75rem 1rem; flex-direction:column; gap:0.5rem; z-index:1100; max-height:0; overflow:hidden; opacity:0; transform-origin:top; transition: max-height 280ms cubic-bezier(.2,.9,.3,1), opacity 220ms ease, transform 220ms ease; }
        .nav.open { max-height: 500px; opacity:1; transform: translateY(0); }
        .nav a { color: #FFFFFF; padding: 0.65rem 0.5rem; border-radius:6px; }
        .nav a:hover { background: rgba(255,127,57,0.06); }
        .nav .cta-button { align-self:flex-start; }
        .header-content { position:relative; grid-template-columns: 1fr auto; }
        .logo { z-index: 1200; }
    }

    /* Small screens polish */
    @media (max-width: 420px) {
        .nav a { font-size: 0.95rem; }
        .nav-toggle { width:40px; height:40px; }
    }
</style>

<script>
    (function(){
        function initNavToggle(){
            var btn = document.querySelector('.nav-toggle');
            var nav = document.querySelector('.nav');
            if(!btn || !nav) return;
            btn.addEventListener('click', function(e){
                var open = nav.classList.toggle('open');
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                // set accessible label
                btn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                // toggle icon safely
                var icon = btn.querySelector('i');
                if(icon){ icon.className = open ? 'fas fa-times' : 'fas fa-bars'; }
            });
            // close nav when pressing Escape
            document.addEventListener('keydown', function(e){
                if(e.key === 'Escape' && nav.classList.contains('open')){
                    nav.classList.remove('open');
                    btn.setAttribute('aria-expanded','false');
                    var icon = btn.querySelector('i'); if(icon) icon.className='fas fa-bars';
                }
            });
            // close nav when clicking outside
            document.addEventListener('click', function(e){
                if(!nav.classList.contains('open')) return;
                if(!nav.contains(e.target) && !btn.contains(e.target)){
                    nav.classList.remove('open');
                    btn.setAttribute('aria-expanded','false');
                    var icon = btn.querySelector('i'); if(icon) icon.className='fas fa-bars';
                }
            });
        }
        if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initNavToggle); else initNavToggle();
    })();
</script>
