<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Forum</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            min-height: 100vh;
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
        }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        .navbar { background: #fff; }
        h1 { color: #3b3b6d; }
        .post-list { margin-top: 2rem; }
        .post-item { padding: 1rem; border-bottom: 1px solid #eee; }
        .post-title { font-size: 1.1rem; font-weight: 600; color: #3b3b6d; }
        .post-meta { font-size: 0.9rem; color: #888; margin-bottom: 0.5rem; }
        .post-content { margin: 0.5rem 0 0.5rem 0; }
        .add-btn { background: #3b3b6d; color: #fff; border: none; padding: 0.5rem 1.5rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-bottom: 1rem; }
        .add-btn:hover { background: #5a5ad1; }
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.2); }
        .modal-content { background: #fff; margin: 10% auto; padding: 2rem; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .close { float: right; font-size: 1.5rem; cursor: pointer; color: #888; }
        .close:hover { color: #3b3b6d; }
    </style>
</head>
<body>
    @if($role === 'admin')
        @include('admin.navbar', ['active' => 'forum'])
    @else
        @include('caregiver.navbar', ['active' => 'forum'])
    @endif
    <div class="container">
        <h1>{{ $category->name }}</h1>
        <button class="add-btn" onclick="document.getElementById('addPostModal').style.display='block'">Add Post</button>
        <div class="post-list">
            @forelse($posts as $post)
                <div class="post-item" style="border-bottom: 1px solid #eee; padding: 0.7rem 0;">
                    <div style="display: flex; align-items: stretch; justify-content: space-between; gap: 1.5rem;">
                        <div style="flex: 1 1 0; min-width:0; display:flex; align-items:center;">
                            <a class="post-title" href="{{ route('forum.post', ['postId' => $post->id]) }}" style="font-weight:600; color:#23234c; text-decoration:none; word-break:break-word; display:block;">{{ $post->title }}</a>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:center; min-width:80px;">
                            <span style="font-size:1.1rem; font-weight:600; color:#23234c;">{{ $post->replies_count }}</span>
                            <span style="font-size:0.92rem; color:#888;">{{ $post->replies_count == 1 ? 'reply' : 'replies' }}</span>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; min-width:120px;">
                            <span style="font-size:0.93rem; color:#888;">Latest {{ \Carbon\Carbon::parse($post->latest_reply_time)->diffForHumans() }}</span>
                            <span style="font-size:0.93rem; color:#23234c; font-weight:600;">by {{ $post->latest_reply_user }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p>No posts in this category yet.</p>
            @endforelse
        </div>
    </div>
    <!-- Add Post Modal -->
    <div id="addPostModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('addPostModal').style.display='none'">&times;</span>
            <h2>Add Post</h2>
            @if($errors->has('badwords'))
                <div style="color: #e74c3c; margin-bottom: 1rem; font-weight: bold;">{{ $errors->first('badwords') }}</div>
            @endif
            <form method="POST" action="{{ route('forum.post.add', ['id' => $category->id]) }}">
                @csrf
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required style="width:100%;padding:0.5rem;margin-top:0.5rem;" value="{{ old('title') }}">
                <label for="content" style="margin-top:1rem;">Content</label>
                <textarea id="content" name="content" required style="width:100%;padding:0.5rem;margin-top:0.5rem;min-height:100px;">{{ old('content') }}</textarea>
                <button type="submit" class="add-btn" style="margin-top:1rem;">Post</button>
            </form>
        </div>
    </div>
    <script>
        window.onclick = function(event) {
            var addModal = document.getElementById('addPostModal');
            if (addModal && event.target == addModal) addModal.style.display = "none";
        }
        // Auto-open modal if there are validation errors
        @if($errors->has('badwords'))
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addPostModal').style.display = 'block';
            });
        @endif
    </script>
</body>
</html> 