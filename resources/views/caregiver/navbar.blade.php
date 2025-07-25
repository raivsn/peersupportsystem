@php use Illuminate\Support\Str; @endphp
<nav class="navbar">
    <div class="header-content" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 1.5rem;">
        <div class="left-section" style="display: flex; align-items: center; gap: 0.7rem;">
            <a href="{{ route('caregiver.dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 0.7rem;">
                <img src="/nasom-logo.png" alt="NASOM Logo" class="logo-img">
                <span class="logo-text">PeerCare Forum</span>
            </a>
        </div>
        <div style="display: flex; align-items: center; gap: 2.2rem;">
            <div class="nav-links" style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="{{ route('caregiver.dashboard') }}" style="font-weight:{{ $active == 'dashboard' ? 'bold' : 'normal' }}; color:{{ $active == 'dashboard' ? '#3b3b6d' : 'inherit' }};">Dashboard</a>
                <a href="{{ route('caregiver.forum') }}" style="font-weight:{{ $active == 'forum' ? 'bold' : 'normal' }}; color:{{ $active == 'forum' ? '#3b3b6d' : 'inherit' }};">Forum</a>
                <a href="{{ route('caregiver.resources') }}" style="font-weight:{{ $active == 'resources' ? 'bold' : 'normal' }}; color:{{ $active == 'resources' ? '#3b3b6d' : 'inherit' }};">Resource Library</a>
                <a href="{{ route('caregiver.bookmarks') }}" style="font-weight:{{ $active == 'bookmarks' ? 'bold' : 'normal' }}; color:{{ $active == 'bookmarks' ? '#3b3b6d' : 'inherit' }};">Bookmarked Posts</a>
            </div>
            <div class="user-menu" id="user-menu">
                <span class="user-btn" onclick="toggleDropdown()" style="background: none; border: none; font-size: 1rem; cursor: pointer; color: #3b3b6d; font-weight: bold; font-family: Arial, sans-serif; padding: 0; margin: 0;">
                    {{ Str::limit(session('user_name'), 9, '...') }} &#x25BC;
                </span>
                <div class="dropdown">
                    <a href="{{ route('caregiver.profile') }}" style="font-weight:{{ $active == 'profile' ? 'bold' : 'normal' }}; color:{{ $active == 'profile' ? '#3b3b6d' : 'inherit' }};">Manage Profile</a>
                    <a href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
        <div class="nasom-contact" style="display: flex; flex-direction: row; align-items: flex-start; gap: 1.5rem; margin-left: 0; min-width: 0;">
            <div class="contact-block" style="text-align: center;">
                <div class="contact-label">EMAIL</div>
                <div class="contact-value"><a href="mailto:info@nasom.org.my" style="color:#3b3b6d;text-decoration:none;">info@nasom.org.my</a></div>
            </div>
            <div class="contact-block" style="text-align: center;">
                <div class="contact-label" style="margin-top:0;">Call Now</div>
                <div class="contact-value" style="font-weight:bold;">603-7832 1928</div>
            </div>
            <div class="contact-block" style="text-align: center;">
                <div class="contact-label" style="margin-top:0;">About NASOM</div>
                <div class="contact-value"><a href="https://www.nasom.org.my" target="_blank" style="color:#3b3b6d;text-decoration:none;">Learn More</a></div>
            </div>
        </div>
    </div>
</nav>
<script>
function toggleDropdown() {
    var menu = document.getElementById('user-menu');
    menu.classList.toggle('open');
}
</script>
<style>
.navbar {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f5f5fa;
    padding: 1rem 2rem;
    box-shadow: 0 2px 4px rgba(59,59,109,0.04);
    font-family: Arial, sans-serif;
}
.header-content {
    display: flex;
    align-items: center;
    gap: 2rem;
}
.left-section {
    display: flex;
    align-items: center;
    gap: 0.7rem;
}
.logo-img {
    height: 36px;
    width: auto;
}
.logo-text {
    font-weight: bold;
    font-size: 1.3rem;
    color: #3b3b6d;
}
.nav-links {
    display: flex;
    gap: 1.5rem;
}
.nav-links a {
    text-decoration: none;
    color: #222;
    font-size: 1rem;
    transition: color 0.2s;
    font-family: Arial, sans-serif;
}
.user-menu {
    position: relative;
}
.user-btn {
    background: none;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    color: #3b3b6d;
    font-weight: bold;
    font-family: Arial, sans-serif;
}
.dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 2.5rem;
    background: #fff;
    box-shadow: 0 2px 8px rgba(59,59,109,0.08);
    border-radius: 0.5rem;
    min-width: 160px;
    z-index: 10;
}
.dropdown a {
    display: block;
    padding: 0.75rem 1.5rem;
    color: #222;
    text-decoration: none;
    font-size: 1rem;
    font-family: Arial, sans-serif;
}
.dropdown a:hover { background: #f5f5fa; }
.user-menu.open .dropdown { display: block; }
.nasom-contact {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    gap: 2.5rem;
    margin-left: 2rem;
    min-width: 420px;
}
.contact-block {
    text-align: center;
}
.contact-label {
    font-size: 0.85em;
    color: #888;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.contact-value {
    font-size: 1em;
    color: #3b3b6d;
    font-weight: 500;
}
@media (max-width: 900px) {
    .nasom-contact {
        display: none;
    }
}
</style> 