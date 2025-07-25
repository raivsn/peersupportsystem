<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Viewer</title>
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
        .container { max-width: 1200px; margin: 2rem auto; background: rgba(255,255,255,0.92); border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        h1 { color: #3b3b6d; }
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
        
        .settings-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .settings-form {
            display: flex;
            gap: 1rem;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            color: #3b3b6d;
        }
        
        .form-group input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-primary {
            background: #3b3b6d;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a5ad1;
        }
        
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .feedback-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            transition: box-shadow 0.2s;
        }
        
        .feedback-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .user-info {
            font-weight: 600;
            color: #3b3b6d;
        }
        
        .rating {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .date {
            color: #666;
            font-size: 0.875rem;
        }
        
        .category {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .content-preview {
            color: #333;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        
        .view-detail {
            color: #3b3b6d;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .view-detail:hover {
            text-decoration: underline;
        }
        
        .no-feedback {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 2rem;
        }
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
    @include('admin.navbar', ['active' => 'feedback'])
    <div class="container">
        <h1>Feedback Viewer</h1>
        
        <!-- Settings Section -->
        <!-- (Removed as per user request) -->
        
        <!-- Feedback Filters -->
        <form method="GET" action="" style="margin-bottom: 1.5rem; display: flex; gap: 1.5rem; align-items: flex-end;">
            <div>
                <label for="category" style="font-weight:600; color:#3b3b6d;">Filter by Category:</label>
                <select name="category" id="category" onchange="this.form.submit()" style="padding:0.5rem; border-radius:6px; border:1px solid #ccc; min-width:120px;">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort_rating" style="font-weight:600; color:#3b3b6d;">Sort by Rating:</label>
                <select name="sort_rating" id="sort_rating" onchange="this.form.submit()" style="padding:0.5rem; border-radius:6px; border:1px solid #ccc; min-width:120px;">
                    <option value="">Newest</option>
                    <option value="desc" @if(request('sort_rating') == 'desc') selected @endif>Highest to Lowest</option>
                    <option value="asc" @if(request('sort_rating') == 'asc') selected @endif>Lowest to Highest</option>
                </select>
            </div>
        </form>
        
        <!-- Feedback List -->
        <div class="feedback-list">
            <h2>All Feedback Submissions</h2>
            @if($feedbacks->count() > 0)
                @foreach($feedbacks as $feedback)
                    <div class="feedback-item">
                        <div class="feedback-header">
                            <div class="user-info">
                                <a href="#" onclick="showUserProfile({{ $feedback->user->id }})" style="color:#3b3b6d; text-decoration:underline; cursor:pointer;">
                                    {{ $feedback->user->name ?? 'Unknown User' }}
                                </a>
                                <span class="user-role" style="color:#888; font-size:0.95em; font-weight:400;">
                                    ({{ $feedback->user->caregiver_status ?? 'Unknown Role' }})
                                </span>
                            </div>
                            @if($feedback->rating)
                                <div class="rating">Rating: {{ $feedback->rating }}/5</div>
                            @endif
                        </div>
                        <div class="date">Submitted on {{ $feedback->created_at->format('M d, Y \a\t g:i A') }}</div>
                        @if($feedback->category)
                            <div class="category"><strong>Category:</strong> {{ $feedback->category }}</div>
                        @endif
                        <div class="content-preview">{{ Str::limit($feedback->content, 200) }}</div>
                        <a href="{{ route('admin.feedback.show', $feedback->id) }}" class="view-detail">View Full Feedback</a>
                    </div>
                @endforeach
            @else
                <div class="no-feedback">No feedback submissions found.</div>
            @endif
        </div>
    </div>

<!-- User Profile Modal -->
<div id="userProfileModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:400px;">
        <div class="modal-header">
            <h2 class="modal-title">User Profile</h2>
            <button class="close-btn" onclick="closeUserProfileModal()">&times;</button>
        </div>
        <div id="userProfileContent">
            <!-- Profile info will be loaded here -->
        </div>
    </div>
</div>

<script>
function showUserProfile(userId) {
    fetch('/user/profile/' + userId)
        .then(response => response.json())
        .then(data => {
            let html = '';
            html += `<div><strong>Name:</strong> ${data.name}</div>`;
            html += `<div><strong>Role:</strong> ${data.caregiver_status ? data.caregiver_status : data.role}</div>`;
            if (data.caregiver_status && data.caregiver_status !== 'admin') {
                html += `<div><strong>Number of autistic children:</strong> ${data.num_autism_children ?? '-'}</div>`;
                html += `<div><strong>Ages:</strong> ${(data.autism_children_ages && data.autism_children_ages.length > 0) ? data.autism_children_ages.join(', ') : '-'}</div>`;
            }
            document.getElementById('userProfileContent').innerHTML = html;
            document.getElementById('userProfileModal').style.display = 'flex';
        });
}
function closeUserProfileModal() {
    document.getElementById('userProfileModal').style.display = 'none';
}
document.addEventListener('click', function(e) {
    const modal = document.getElementById('userProfileModal');
    if (modal && e.target === modal) {
        closeUserProfileModal();
    }
});
</script>
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