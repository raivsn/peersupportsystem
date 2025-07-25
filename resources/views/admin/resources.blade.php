<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resource Library</title>
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
        .container { max-width: 800px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem; }
        h1 { color: #23234c; font-size: 2.2rem; margin-bottom: 2rem; font-weight: 700; }
        .add-btn { background: #3b3b6d; color: #fff; border: none; padding: 0.5rem 1.5rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-bottom: 1.5rem; text-decoration: none; display: inline-block; }
        .add-btn:hover { background: #5a5ad1; text-decoration: underline; }
        .resource-list { margin-top: 1.5rem; }
        .resource-item { padding: 1.2rem 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .resource-title { color: #23234c; text-decoration: none; font-weight: 600; font-size: 1.08rem; }
        .resource-meta { color: #888; font-size: 0.95rem; margin-top: 0.3rem; }
        .action-btns { display: flex; gap: 0.7rem; }
        .edit-btn { background: #3498db; color: #fff; border: none; padding: 0.3rem 1.1rem; border-radius: 4px; font-size: 0.97rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .edit-btn:hover { background: #2980b9; text-decoration: underline; }
        .delete-btn { background: #e74c3c; color: #fff; border: none; padding: 0.3rem 1.1rem; border-radius: 4px; font-size: 0.97rem; cursor: pointer; }
        .delete-btn:hover { background: #c0392b; }
    </style>
</head>
<body>
    @include('admin.navbar', ['active' => 'resources'])
    <div class="container">
        <h1>Manage Resource Library</h1>
        <a href="{{ route('admin.resource_create') }}" class="add-btn">+ Add Article</a>
        <div class="resource-list">
            @forelse($resources as $resource)
                <div class="resource-item">
                    <div>
                        <a href="{{ route('caregiver.resource_detail', ['id' => $resource->id]) }}" class="resource-title">{{ $resource->title }}</a>
                        <div class="resource-meta">Category: {{ $resource->category ?: 'Uncategorized' }}</div>
                    </div>
                    <div class="action-btns">
                        <a href="{{ route('admin.resource_edit', ['id' => $resource->id]) }}" class="edit-btn">Edit</a>
                        <form method="POST" action="{{ route('admin.resource_delete', ['id' => $resource->id]) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="delete-btn" onclick="return confirm('Delete this article?')">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color:#666;">No resources available yet.</p>
            @endforelse
        </div>
    </div>
</body>
</html> 