<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
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
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem; }
        h1 { color: #23234c; font-size: 2.2rem; margin-bottom: 2rem; font-weight: 700; text-align: center; }
        .post-list { margin-top: 1.5rem; }
        .post-item { padding: 1rem 0; border-bottom: 1px solid #eee; }
        .post-link { color: #23234c; text-decoration: none; font-weight: 600; font-size: 1.08rem; display: block; }
        .post-link:hover { color: #5a5ad1; text-decoration: underline; }
        .no-posts { color: #888; text-align: center; margin-top: 2rem; }
    </style>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'myposts'])
    <div class="container">
        <h1>My Posts</h1>
        <div class="post-list">
            @forelse($my_posts as $post)
                <div class="post-item">
                    <a href="{{ route('forum.post', ['postId' => $post->id]) }}" class="post-link">{{ $post->title }}</a>
                </div>
            @empty
                <div class="no-posts">You have not posted anything yet.</div>
            @endforelse
        </div>
    </div>
</body>
</html> 