<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        .navbar { background: #fff; }
        h1 { color: #3b3b6d; }
        
        /* Feedback Modal Styles */
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
            z-index: 1000;
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
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-title {
            font-size: 1.5rem;
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
        
        .feedback-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
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
        
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
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
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'dashboard'])
    <div style="display: flex; justify-content: center; align-items: flex-start; gap: 1.2rem; margin: 2rem auto; max-width: 1100px;">
        <!-- Main dashboard content -->
        <div class="container" style="flex: 1 1 0; min-width: 0; max-width: 650px; margin: 0;">
            <!-- Removed Caregiver Dashboard and Welcome message -->

            @if(isset($recently_active_posts) && count($recently_active_posts) > 0)
            <div style="margin-top:2.5rem;">
                <h2 style="color:#3b3b6d; font-size:1.3rem; margin-bottom:1.2rem;">Recent active posts</h2>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f5f5fa;">
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem; width:60%;">Title</th>
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem; width:20%;">Author</th>
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem; width:20%;">Latest</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recently_active_posts as $post)
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:0.7rem 0.5rem; word-break:break-word; font-weight:600;">
                                <a href="{{ route('forum.post', ['postId' => $post->id]) }}" style="color:#3b3b6d; text-decoration:underline;">{{ $post->title }}</a>
                            </td>
                            <td style="padding:0.7rem 0.5rem; color:#555;">{{ $post->latest_reply_user }}</td>
                            <td style="padding:0.7rem 0.5rem; color:#555;">{{ \Carbon\Carbon::parse($post->latest_reply_time)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if(isset($recently_posted_articles) && count($recently_posted_articles) > 0)
            <div style="margin-top:2.5rem;">
                <h2 style="color:#3b3b6d; font-size:1.3rem; margin-bottom:1.2rem;">Recent articles</h2>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f5f5fa;">
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem;">Title</th>
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem;">Author</th>
                            <th style="text-align:left; padding:0.7rem 0.5rem; color:#23234c; font-size:1.05rem;">Posted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recently_posted_articles as $article)
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:0.7rem 0.5rem;">
                                <a href="{{ route('caregiver.resource_detail', ['id' => $article->id]) }}" style="color:#3b3b6d; font-weight:600; text-decoration:underline;">{{ $article->title }}</a>
                            </td>
                            <td style="padding:0.7rem 0.5rem; color:#555;">
                                <span style="color:#3b3b6d; font-weight:600;">{{ $article->author_name ?? 'Unknown User' }}</span> 
                                <span style="color:#222;">({{ ($article->author_role ?? '') === 'admin' ? 'Administrator' : ($article->author_caregiver_status ?? 'Unknown Role') }})</span>
                            </td>
                            <td style="padding:0.7rem 0.5rem; color:#555;">{{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        <!-- Right column: Forum Categories and Feedback boxes -->
        <div style="display: flex; flex-direction: column; gap: 1.2rem; width: 240px; min-width: 180px; margin: 0;">
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem;">
                <h2 style="color: #3b3b6d; font-size: 1.1rem; margin-bottom: 1rem; text-align: center;">Forum Categories</h2>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @forelse($forum_categories as $cat)
                        <li style="margin-bottom: 0.7rem; text-align: center;">
                            <a href="{{ route('forum.category', ['id' => $cat->id]) }}" style="color: #23234c; text-decoration: none; font-weight: 600; border-radius: 4px; display: block; padding: 0.5rem 0; transition: background 0.2s; background: #f5f5fa;">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @empty
                        <li style="color: #888; text-align: center;">No categories found.</li>
                    @endforelse
                </ul>
            </div>
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.5rem 1rem;">
                <h2 style="color: #3b3b6d; font-size: 1.1rem; margin-bottom: 1rem; text-align: center;">Feedback on the Online Community</h2>
                <p style="color: #23234c; font-size: 1rem; text-align: left; margin-bottom: 0.7rem;">If you have any questions, feedback or technical issues on the online community that you would like the moderation team to know about. The only way to ensure that your enquiry will be seen is to email <a href='mailto:CommunityManager@nasom.org.my' style='color:#3b3b6d; text-decoration:underline;'>CommunityManager@nasom.org.my</a> and someone will get back to you.</p>
            </div>
        </div>
    </div>
    
    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Share Your Feedback</h2>
                <button class="close-btn" onclick="closeFeedbackModal()">&times;</button>
            </div>
            <form id="feedbackForm" class="feedback-form">
                @csrf
                <div class="form-group">
                    <label for="rating">How would you rate your experience? (1-5)</label>
                    <select name="rating" id="rating">
                        <option value="">Select rating (optional)</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="category">Category (Optional)</label>
                    <select name="category" id="category">
                        <option value="">Select category</option>
                        <option value="General">General</option>
                        <option value="Technical">Technical</option>
                        <option value="Content">Content</option>
                        <option value="User Interface">User Interface</option>
                        <option value="Support">Support</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="content">Your Feedback</label>
                    <textarea name="content" id="content" rows="6" placeholder="Please share your thoughts about the platform..." required></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    <button type="button" class="btn btn-secondary" onclick="closeFeedbackModal()">Close</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Check if feedback prompt should be shown
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("caregiver.feedback.should-show") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.should_show) {
                        document.getElementById('feedbackModal').style.display = 'flex';
                    }
                })
                .catch(error => console.error('Error checking feedback status:', error));
        });
        
        function closeFeedbackModal() {
            document.getElementById('feedbackModal').style.display = 'none';
        }
        
        // Handle feedback form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("caregiver.feedback.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Feedback submitted successfully!');
                    closeFeedbackModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting feedback. Please try again.');
            });
        });
    </script>
</body>
</html> 