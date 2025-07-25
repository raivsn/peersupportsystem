<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8f8fb; }
        .container { max-width: 600px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem; }
        h1 { color: #23234c; font-size: 2rem; margin-bottom: 2rem; font-weight: 700; }
        label { display: block; margin-top: 1.2rem; color: #23234c; font-weight: 600; }
        input[type="text"], textarea { width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; margin-top: 0.5rem; }
        textarea { min-height: 140px; }
        .form-btns { margin-top: 2rem; display: flex; gap: 1rem; }
        .submit-btn { background: #3b3b6d; color: #fff; border: none; padding: 0.6rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        .submit-btn:hover { background: #5a5ad1; }
        .cancel-btn { background: #888; color: #fff; border: none; padding: 0.6rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .cancel-btn:hover { background: #aaa; }
    </style>
</head>
<body>
    @include('admin.navbar', ['active' => 'resources'])
    <div class="container">
        <h1>Edit Article</h1>
        <form method="POST" action="{{ route('admin.resource_update', ['id' => $resource->id]) }}">
            @csrf
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ $resource->title }}" required>
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="{{ $resource->category }}" placeholder="e.g. Therapy, Emotional Support">
            <label for="content">Content</label>
            <textarea id="content" name="content" required>{{ $resource->content }}</textarea>
            <div class="form-btns">
                <button type="submit" class="submit-btn">Update Article</button>
                <a href="{{ route('admin.resources') }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html> 