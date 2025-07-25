<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage Profile</title>
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
        .navbar { background: #fff; }
        .container { max-width: 600px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
        label { display: block; margin-top: 1.2rem; font-weight: 500; }
        input { width: 100%; padding: 0.5rem; margin-top: 0.3rem; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 2rem; background: #3b3b6d; color: #fff; border: none; padding: 0.7rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #5a5ad1; }
        .profile-row { margin-bottom: 1.2rem; }
        .profile-label { font-weight: 500; color: #3b3b6d; }
        .profile-value { margin-left: 0.5rem; }
    </style>
    <script>
        function toggleEditMode(editing) {
            document.getElementById('profile-view').style.display = editing ? 'none' : 'block';
            document.getElementById('profile-edit').style.display = editing ? 'block' : 'none';
        }
        window.onload = function() {
            toggleEditMode(false);
        };
    </script>
</head>
<body>
    @include('admin.navbar', ['active' => 'profile'])
    <div class="container">
        <h1>Manage Profile</h1>
        <div id="profile-view">
            <div class="profile-row"><span class="profile-label">Name:</span><span class="profile-value">{{ $user->name }}</span></div>
            <div class="profile-row"><span class="profile-label">Role:</span><span class="profile-value">Administrator</span></div>
            <button onclick="toggleEditMode(true)">Edit</button>
        </div>
        <div id="profile-edit" style="display:none;">
            <form method="POST" action="/admin/profile">
                @csrf
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                <label for="role">Role</label>
                <input type="text" id="role" name="role" value="Administrator" disabled>
                <button type="submit">Save Changes</button>
                <button type="button" onclick="toggleEditMode(false)">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html> 