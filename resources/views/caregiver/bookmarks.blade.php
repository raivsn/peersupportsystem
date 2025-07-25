<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked Posts</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            min-height: 100vh;
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f5f5fa;
            padding: 0.5rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        .navbar .logo { font-weight: bold; font-size: 1.3rem; color: #3b3b6d; }
        .navbar .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        .navbar .nav-links a {
            text-decoration: none;
            color: #3b3b6d;
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar .nav-links a:hover { color: #5a5ad1; }
        .navbar .user-menu {
            position: relative;
            display: inline-block;
        }
        .navbar .user-btn {
            background: none;
            border: none;
            font-weight: 600;
            color: #3b3b6d;
            cursor: pointer;
            font-size: 1rem;
        }
        .navbar .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: #fff;
            min-width: 150px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 6px;
            z-index: 10;
        }
        .navbar .dropdown a {
            display: block;
            padding: 0.75rem 1rem;
            color: #3b3b6d;
            text-decoration: none;
            font-weight: 500;
        }
        .navbar .dropdown a:hover { background: #f5f5fa; }
        .navbar .user-menu.open .dropdown { display: block; }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        h1 { color: #23234c; font-size: 2.2rem; margin-bottom: 2rem; font-weight: 700; }
        .bookmark-list { margin-top: 1.5rem; }
        .bookmark-item { padding: 1.2rem 0; border-bottom: 1px solid #eee; }
        .bookmark-link { color: #23234c; text-decoration: none; font-weight: 600; font-size: 1.1rem; }
        .bookmark-link:hover { color: #5a5ad1; text-decoration: underline; }
        .bookmark-meta { color: #888; font-size: 0.95rem; margin-top: 0.3rem; }
    </style>
    <script>
        function toggleDropdown() {
            var menu = document.getElementById('user-menu');
            menu.classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            var menu = document.getElementById('user-menu');
            if (menu && !menu.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'bookmarks'])
    <div class="container">
        <h1>Bookmarked Posts</h1>
        @if(isset($bookmarks) && count($bookmarks) > 0)
            <div class="bookmark-list">
                @foreach($bookmarks as $bookmark)
                    <div class="bookmark-item" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <a href="{{ route('forum.post', ['postId' => $bookmark->id]) }}" class="bookmark-link">{{ $bookmark->title }}</a>
                            <div class="bookmark-meta">Category: {{ $bookmark->category_name }}</div>
                            <div class="bookmark-meta">by <span style="color:#3b3b6d; font-weight:600;">{{ $bookmark->user_name ?? 'Unknown User' }}</span>
    <span style="color:#888; font-size:0.95em; font-weight:400;">({{ $bookmark->caregiver_status ?? 'Unknown Role' }})</span>
</div>
                        </div>
                        <div style="color:#222; font-size:1em; min-width:140px; text-align:right;">Bookmarked {{ \Carbon\Carbon::parse($bookmark->bookmarked_at)->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color:#666; margin-top:1rem;">No bookmarked posts yet. Start bookmarking posts from the forum!</p>
        @endif
    </div>
    <!-- Removed user profile modal and related JS as per user request -->
    <style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }
    .modal-overlay[style*="display: flex"] {
        display: flex !important;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 2px 16px rgba(0,0,0,0.15);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .modal-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #3b3b6d;
    }
    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }
    </style>
</body>
</html> 