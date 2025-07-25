<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Library</title>
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
        .navbar { background: #fff; }
        h1 { color: #23234c; font-size: 2.2rem; margin-bottom: 2rem; font-weight: 700; text-align: center; }
        .block-list { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; }
        .block-item { background: #f5f5fa; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 2rem 2.5rem; min-width: 220px; min-height: 80px; display: flex; align-items: center; justify-content: center; transition: box-shadow 0.2s; text-align: center; }
        .block-item:hover { box-shadow: 0 4px 16px rgba(90,90,209,0.10); background: #f0f4ff; }
        .block-link { color: #23234c; text-decoration: none; font-weight: 700; font-size: 1.15rem; width: 100%; display: block; }
        .block-link:hover { color: #5a5ad1; text-decoration: underline; }
    </style>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'resources'])
    <div class="container">
        <h1>Resource Library</h1>
        <div class="resource-list" style="margin-top: 2rem;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @forelse($categories->flatten() as $article)
                    <li style="padding: 1rem 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ route('caregiver.resource_detail', ['id' => $article->id]) }}" style="color: #23234c; text-decoration: none; font-weight: 600; font-size: 1.08rem; display: block;">
                            {{ $article->title }}
                        </a>
                        <span style="color: #888; font-size: 0.98rem; min-width: 110px; text-align: right;">{{ \Carbon\Carbon::parse($article->created_at)->format('M d, Y') }}</span>
                    </li>
                @empty
                    <li style="color:#666; padding: 1rem 0;">No resources available yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html> 