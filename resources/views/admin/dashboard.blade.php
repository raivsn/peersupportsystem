<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum statistics</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .container { max-width: 1200px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        .navbar { background: #fff; }
        h1 { color: #3b3b6d; margin-bottom: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #f5f5fa; padding: 1.5rem; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #3b3b6d; margin-bottom: 0.5rem; }
        .stat-label { color: #666; font-size: 0.9rem; }
        .section { margin-bottom: 2rem; }
        .section h2 { color: #3b3b6d; font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th { background: #f5f5fa; padding: 0.7rem; text-align: left; color: #23234c; font-weight: 600; }
        .table td { padding: 0.7rem; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f9f9f9; }
        .rating { color: #ffa500; font-weight: bold; }
        .category-tag { background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; }
        .user-role { font-size: 0.8rem; color: #666; }
        .activity-count { font-weight: bold; color: #3b3b6d; }
    </style>
</head>
<body>
    @include('admin.navbar', ['active' => 'dashboard'])
    <div class="container">
        <h1>Forum statistics</h1>
        
        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalCaregivers }}</div>
                <div class="stat-label">Caregivers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalAdmins }}</div>
                <div class="stat-label">Administrators</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalForumPosts }}</div>
                <div class="stat-label">Forum Posts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalResources }}</div>
                <div class="stat-label">Resource Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalFeedback }}</div>
                <div class="stat-label">Feedback Received</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($averageRating, 1) }}</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>

        <!-- Most Active Forum Posts -->
        <div class="section">
            <h2>Most Active Forum Posts</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Replies</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mostActivePosts as $post)
                    <tr>
                        <td>
                            <a href="{{ route('forum.post', ['postId' => $post->id]) }}" style="color: #3b3b6d; text-decoration: none; font-weight: 600;">{{ $post->title }}</a>
                        </td>
                        <td><span class="category-tag">{{ $post->category_name ?? 'Uncategorized' }}</span></td>
                        <td>
                            {{ $post->user_name }}
                            <div class="user-role">{{ ($post->user_role ?? '') === 'admin' ? 'Administrator' : ($post->caregiver_status ?? 'User') }}</div>
                        </td>
                        <td><span class="activity-count">{{ $post->replies_count }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Most Active Users -->
        <!-- (Removed as per user request) -->

        <!-- Feedback Categories -->
        <!-- (Removed as per user request) -->
    </div>
</body>
</html> 