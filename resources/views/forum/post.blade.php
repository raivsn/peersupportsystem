<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title }} - Forum</title>
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
        .container { max-width: 1000px; margin: 2.5rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem 2.5rem 2rem 2.5rem; }
        .navbar { background: #fff; }
        h1 { color: #3b3b6d; margin-bottom: 0.5rem; }
        .category-link { color: #5a5ad1; text-decoration: none; font-size: 1rem; display: inline-block; margin-bottom: 1.5rem; }
        .post-header, .reply-header { display: flex; align-items: center; justify-content: space-between; gap: 1.1rem; margin-bottom: 0.2rem; margin-top: 1.2rem; }
        .post-header { margin-top: 1.2rem; }
        .post-author { font-weight: bold; color: #3b3b6d; font-size: 1.1rem; }
        .post-you { color: #888; font-size: 1.05em; margin-left: 0.5rem; font-weight: 400; }
        .post-meta { color: #888; font-size: 1.05em; margin-left: 0.7rem; font-weight: 400; }
        .post-content { margin-top: 1.2rem; font-size: 1.1rem; color: #222; margin-bottom: 2.2rem; }
        .reply-btn { background: #3b3b6d; color: #fff; border: none; padding: 0.4rem 1.2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-left: 1rem; display: none; }
        .reply-btn:hover { background: #5a5ad1; }
        .reply-form, .reply-form-inline { display: flex; margin-top: 1.2rem; margin-bottom: 0; align-items: center; gap: 0.7rem; }
        .reply-form input, .reply-form-inline input { flex: 1; min-width: 0; padding: 0.5rem 0.8rem; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; }
        .reply-form button, .reply-form-inline button { background: #3b3b6d; color: #fff; border: none; padding: 0.5rem 1.2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-top: 0; margin-bottom: 0; transition: background 0.2s, color 0.2s; }
        .reply-form button:disabled, .reply-form-inline button:disabled { background: #ccc; color: #888; cursor: not-allowed; }
        .reply-form button:hover:enabled, .reply-form-inline button:hover:enabled { background: #5a5ad1; }
        .post-separator { border: none; border-top: 1px solid #e0e0e0; margin: 2.2rem 0 2.2rem 0; }
        .replies-list { margin-top: 2.7rem; }
        .reply-item-wrapper { display: flex; align-items: stretch; min-height: 64px; }
        .reply-indent { width: 18px; min-width: 18px; position: relative; }
        .reply-indent::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            width: 3px;
            height: 100%;
            background: #e0e0e0;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        .reply-item { flex: 1; margin-bottom: 2.7rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee; }
        .reply-item.level-1 { margin-left: 0.5rem; }
        .reply-header-content { display: flex; align-items: center; gap: 0.7rem; }
        .reply-author { font-weight: bold; color: #5a5ad1; }
        .reply-you { color: #888; font-size: 1.05em; margin-left: 0.5rem; font-weight: 400; }
        .reply-meta { color: #888; font-size: 0.97em; margin-left: 0.5rem; }
        .reply-content { font-size: 1.05rem; color: #222; margin-top: 1.2rem; margin-bottom: 1.2rem; }
        .dots-divider { display: flex; justify-content: center; align-items: center; margin: 1.2rem 0 0.5rem 0; }
        .dots-btn { background: none; border: none; color: #888; font-size: 1.6rem; cursor: pointer; padding: 0 0.5rem; border-radius: 50%; transition: background 0.2s; }
        .dots-btn:hover { background: #f0f0f0; color: #3b3b6d; }
        .bookmark-btn {
            background: #888;
            color: #fff;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .bookmark-btn.bookmarked {
            background: #ffd700;
            color: #333;
        }
        .bookmark-btn:hover {
            background: #666;
        }
        .bookmark-btn.bookmarked:hover {
            background: #ffed4e;
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
    </style>
    <script>
        window.onload = function() {
            // For all reply forms (main and inline)
            document.querySelectorAll('.reply-form, .reply-form-inline').forEach(function(form) {
                var input = form.querySelector('input[type="text"]');
                var btn = form.querySelector('button[type="submit"]');
                if (input && btn) {
                    btn.disabled = true;
                    input.addEventListener('input', function() {
                        btn.disabled = input.value.trim() === '';
                    });
                }
            });
            
            // Bookmark functionality
            var bookmarkBtn = document.getElementById('bookmarkBtn');
            if (bookmarkBtn) {
                bookmarkBtn.addEventListener('click', function() {
                    var postId = this.getAttribute('data-post-id');
                    var isBookmarked = this.getAttribute('data-bookmarked') === 'true';
                    
                    fetch('/forum/post/' + postId + '/bookmark', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.bookmarked) {
                            this.textContent = 'Bookmarked';
                            this.classList.add('bookmarked');
                            this.setAttribute('data-bookmarked', 'true');
                        } else {
                            this.textContent = 'Bookmark?';
                            this.classList.remove('bookmarked');
                            this.setAttribute('data-bookmarked', 'false');
                        }
                    });
                });
            }
        };
    </script>
</head>
<body>
    @if($role === 'admin')
        @include('admin.navbar', ['active' => 'forum'])
    @else
        @include('caregiver.navbar', ['active' => 'forum'])
    @endif
    <div class="container">
        <a class="category-link" href="{{ route('forum.category', ['id' => $category->id]) }}">&larr; Back to {{ $category->name }}</a>
        <h1>{{ $post->title }}</h1>
        <div class="post-header">
            <div>
                <span class="post-author">
                    <span style="color:#3b3b6d; font-weight:bold;">{{ $post->user_name }}</span>
                    <span style="color:#222; font-size:0.95em; font-weight:400;">({{ ($post->user_role ?? '') === 'admin' ? 'Administrator' : ($post->caregiver_status ?? 'Unknown Role') }})</span>
                    @if($userId == $post->user_id)<span class="post-you">(You)</span>@endif
                </span>
                <span class="post-meta">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @if($role !== 'admin')
                    @php
                        $isBookmarked = DB::table('bookmarks')->where('user_id', $userId)->where('forum_post_id', $post->id)->exists();
                    @endphp
                    <button id="bookmarkBtn" class="bookmark-btn{{ $isBookmarked ? ' bookmarked' : '' }}" data-post-id="{{ $post->id }}" data-bookmarked="{{ $isBookmarked ? 'true' : 'false' }}">
                        {{ $isBookmarked ? 'Bookmarked' : 'Bookmark?' }}
                    </button>
                @endif
                @if($role === 'admin' || $post->user_id == $userId)
                    <button class="delete-btn" onclick="showDeleteModal()">Delete</button>
                @endif
            </div>
        </div>
        <div class="post-content">{{ $post->content }}</div>
        @if($errors->has('badwords') && session('reply_parent_id') === null)
            <div style="color: #e74c3c; margin-bottom: 1rem; font-weight: bold;">{{ $errors->first('badwords') }}</div>
        @endif
        <form id="main-reply-form" class="reply-form" method="POST" action="{{ route('forum.reply.add', ['postId' => $post->id]) }}">
            @csrf
            <input type="text" name="body" placeholder="Write a reply..." required value="{{ session('reply_parent_id') === null ? old('body') : '' }}" />
            <button type="submit">Reply</button>
        </form>
        <div class="dots-divider"><button class="dots-btn" type="button">&#8230;</button></div>
        <hr class="post-separator" />
        <div class="replies-list">
            @include('forum.partials.reply-tree', ['replies' => $replies, 'postId' => $post->id, 'userId' => $userId, 'level' => 0])
        </div>
    </div>
    
    <!-- Delete Post Modal -->
    <div id="deletePostModal" class="modal" style="display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.2);">
        <div class="modal-content" style="background: #fff; margin: 10% auto; padding: 2rem; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <span class="close" onclick="document.getElementById('deletePostModal').style.display='none'" style="float: right; font-size: 1.5rem; cursor: pointer; color: #888;">&times;</span>
            <h2>Delete Post</h2>
            <p>Are you sure you want to delete this post?</p>
            <form id="deletePostForm" method="POST" action="{{ route('forum.post.delete', ['postId' => $post->id]) }}">
                @csrf
                <button type="submit" class="delete-btn">Yes, Delete</button>
                <button type="button" class="add-btn" style="background:#888; margin-left: 0.5rem;" onclick="document.getElementById('deletePostModal').style.display='none'">No</button>
            </form>
        </div>
    </div>
    
    <script>
        function showDeleteModal() {
            document.getElementById('deletePostModal').style.display = 'block';
        }
        
        window.onclick = function(event) {
            var modal = document.getElementById('deletePostModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        // Scroll to the reply form with error after validation failure
        @if($errors->has('badwords'))
            document.addEventListener('DOMContentLoaded', function() {
                var parentId = @json(session('reply_parent_id'));
                var el;
                if (parentId === null) {
                    el = document.getElementById('main-reply-form');
                } else {
                    el = document.getElementById('reply-form-inline-' + parentId);
                }
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.querySelector('input[name="body"]').focus();
                }
            });
        @endif
    </script>
</body>
</html> 