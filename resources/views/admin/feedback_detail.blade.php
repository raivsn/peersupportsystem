<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Detail - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8f8fb; }
        .container { max-width: 800px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
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
        
        .feedback-detail {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            margin-top: 1rem;
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .user-info {
            font-weight: 600;
            color: #3b3b6d;
            font-size: 1.1rem;
        }
        
        .rating-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .meta-info {
            margin-bottom: 1.5rem;
        }
        
        .meta-item {
            margin-bottom: 0.5rem;
            color: #666;
        }
        
        .meta-label {
            font-weight: 600;
            color: #3b3b6d;
        }
        
        .content-section {
            margin-top: 1.5rem;
        }
        
        .content-label {
            font-weight: 600;
            color: #3b3b6d;
            margin-bottom: 0.5rem;
        }
        
        .content-text {
            background: white;
            padding: 1.5rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            line-height: 1.6;
            color: #333;
            white-space: pre-wrap;
        }
        
        .back-btn {
            display: inline-block;
            background: #3b3b6d;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .back-btn:hover {
            background: #5a5ad1;
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
        <a href="{{ route('admin.feedback') }}" class="back-btn">← Back to Feedback List</a>
        
        <h1>Feedback Detail</h1>
        
        <div class="feedback-detail">
            <div class="feedback-header">
                <div class="user-info">{{ $feedback->user->name ?? 'Unknown User' }}</div>
                @if($feedback->rating)
                    <div class="rating-badge">Rating: {{ $feedback->rating }}/5</div>
                @endif
            </div>
            
            <div class="meta-info">
                <div class="meta-item">
                    <span class="meta-label">Submitted:</span> {{ $feedback->created_at->format('F d, Y \a\t g:i A') }}
                </div>
                @if($feedback->category)
                    <div class="meta-item">
                        <span class="meta-label">Category:</span> {{ $feedback->category }}
                    </div>
                @endif
                <div class="meta-item">
                    <span class="meta-label">User ID:</span> {{ $feedback->user_id }}
                </div>
            </div>
            
            <div class="content-section">
                <div class="content-label">Feedback Content:</div>
                <div class="content-text">{{ $feedback->content }}</div>
            </div>
        </div>
    </div>
</body>
</html> 