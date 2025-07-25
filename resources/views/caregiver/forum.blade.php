<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Forum</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8f8fb; }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem; }
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
    @include('caregiver.navbar', ['active' => 'forum'])
    <div class="container">
        <h1>Caregiver Forum</h1>
        <p>This is the forum page for caregivers.</p>
    </div>
</body>
</html> 