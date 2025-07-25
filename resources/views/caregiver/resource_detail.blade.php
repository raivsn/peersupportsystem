<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resource->title }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem; }
        .navbar { background: #fff; }
        .category { color: #5a5ad1; font-size: 1rem; margin-bottom: 0.7rem; }
        .title { color: #23234c; font-size: 2rem; font-weight: 700; margin-bottom: 1.2rem; }
        .content { color: #222; font-size: 1.08rem; line-height: 1.7; margin-top: 1.5rem; }
        .back-link { color: #5a5ad1; text-decoration: none; font-size: 1rem; display: inline-block; margin-bottom: 1.5rem; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'resources'])
    <div class="container">
        <a href="{{ route('caregiver.resources') }}" class="back-link">&larr; Back to Resource Library</a>
        <div class="author-meta" style="margin-bottom:0.7rem; color:#888; font-size:1rem;">
            by <a href="#" onclick="showUserProfile({{ $resource->created_by }})" style="color:#3b3b6d; text-decoration:underline; cursor:pointer; font-weight:bold;">{{ $resource->author_name ?? 'Unknown User' }}</a>
            <span style="color:#222; font-size:0.95em; font-weight:400;">({{ ($resource->author_role ?? '') === 'admin' ? 'Administrator' : ($resource->author_role ?? 'Unknown Role') }})</span>
        </div>
        <div class="category">{{ $resource->category ?: 'Uncategorized' }}</div>
        <div class="title">{{ $resource->title }}</div>
        @php
            function make_links_clickable($text) {
                $pattern = '/(https?:\/\/[\w\-\.\/?#=&;%+~:@,]+[\w\/#=&;%+~@])/i';
                return preg_replace_callback($pattern, function ($matches) {
                    $url = $matches[0];
                    $display = strlen($url) > 60 ? substr($url, 0, 57) . '...' : $url;
                    return '<a href="' . e($url) . '" target="_blank" rel="noopener noreferrer">' . e($display) . '</a>';
                }, e($text));
            }
        @endphp
        <div class="content">{!! nl2br(make_links_clickable($resource->content)) !!}</div>
    </div>
</body>
</html> 