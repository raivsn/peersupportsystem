<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Manage Profile</title>
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
        input, select { width: 100%; padding: 0.5rem; margin-top: 0.3rem; border: 1px solid #ccc; border-radius: 4px; }
        .child-age-group { margin-top: 1rem; }
        button { margin-top: 2rem; background: #3b3b6d; color: #fff; border: none; padding: 0.7rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #5a5ad1; }
        .profile-row { margin-bottom: 1.2rem; }
        .profile-label { font-weight: 500; color: #3b3b6d; }
        .profile-value { margin-left: 0.5rem; }
        .section-divider { 
            border-top: 2px solid #e0e0e0; 
            margin: 2rem 0; 
            padding-top: 2rem; 
        }
        .danger-zone { 
            background: #fff5f5; 
            border: 1px solid #fed7d7; 
            border-radius: 8px; 
            padding: 1.5rem; 
            margin-top: 2rem; 
        }
        .danger-zone h3 { 
            color: #c53030; 
            margin-bottom: 1rem; 
        }
        .btn-danger { 
            background: #e53e3e; 
            color: #fff; 
        }
        .btn-danger:hover { 
            background: #c53030; 
        }
        .btn-secondary { 
            background: #718096; 
            color: #fff; 
            margin-left: 0.5rem; 
        }
        .btn-secondary:hover { 
            background: #4a5568; 
        }
        .modal { 
            display: none; 
            position: fixed; 
            z-index: 100; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background: rgba(0,0,0,0.2); 
        }
        .modal-content { 
            background: #fff; 
            margin: 10% auto; 
            padding: 2rem; 
            border-radius: 8px; 
            max-width: 400px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
        }
        .close { 
            float: right; 
            font-size: 1.5rem; 
            cursor: pointer; 
            color: #888; 
        }
        .close:hover { 
            color: #3b3b6d; 
        }
        .password-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        .password-section h3 {
            color: #2d3748;
            margin-bottom: 1rem;
        }
    </style>
    <script>
        function toggleEditMode(editing) {
            document.getElementById('profile-view').style.display = editing ? 'none' : 'block';
            document.getElementById('profile-edit').style.display = editing ? 'block' : 'none';
        }
        function onRoleChange() {
            var role = document.getElementById('role').value;
            var numChildrenDiv = document.getElementById('num-children-div');
            var childrenAgesDiv = document.getElementById('children-ages-div');
            if (role === 'parent' || role === 'caretaker') {
                numChildrenDiv.style.display = 'block';
                onNumChildrenChange();
            } else {
                numChildrenDiv.style.display = 'none';
                childrenAgesDiv.innerHTML = '';
            }
        }
        function onNumChildrenChange() {
            var num = parseInt(document.getElementById('num_autism_children').value) || 0;
            var childrenAgesDiv = document.getElementById('children-ages-div');
            childrenAgesDiv.innerHTML = '';
            var existingAges = JSON.parse(document.getElementById('autism_children_ages_json').value || '[]');
            for (var i = 1; i <= num; i++) {
                var label = document.createElement('label');
                label.innerText = 'Age of Child ' + i;
                label.className = 'child-age-group';
                var select = document.createElement('select');
                select.name = 'autism_children_ages[]';
                select.required = true;
                select.innerHTML = '<option value="">Select age group</option>' +
                    '<option value="<12">Below 12 years old</option>' +
                    '<option value="13-18">13-18 years old</option>' +
                    '<option value=">18">Over 18 years old</option>';
                if (existingAges[i-1]) select.value = existingAges[i-1];
                childrenAgesDiv.appendChild(label);
                childrenAgesDiv.appendChild(select);
            }
        }
        function showPasswordModal() {
            document.getElementById('passwordModal').style.display = 'block';
        }
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        window.onload = function() {
            toggleEditMode(false);
        };
        // Close modals when clicking outside
        window.onclick = function(event) {
            var passwordModal = document.getElementById('passwordModal');
            var deleteModal = document.getElementById('deleteModal');
            if (event.target == passwordModal) {
                passwordModal.style.display = "none";
            }
            if (event.target == deleteModal) {
                deleteModal.style.display = "none";
            }
        }
    </script>
</head>
<body>
    @include('caregiver.navbar', ['active' => 'profile'])
    <div class="container">
        <h1>Manage Profile</h1>
        
        <!-- Profile Information Section -->
        <div id="profile-view">
            <div class="profile-row"><span class="profile-label">Name:</span><span class="profile-value">{{ $user->name }}</span></div>
            <div class="profile-row"><span class="profile-label">Role:</span><span class="profile-value">{{ ucfirst($user->caregiver_status) }}</span></div>
            @if($user->caregiver_status === 'parent' || $user->caregiver_status === 'caretaker')
                <div class="profile-row"><span class="profile-label">Number of Autistic Children:</span><span class="profile-value">{{ $user->num_autism_children }}</span></div>
                @php $ages = json_decode($user->autism_children_ages ?? '[]', true); @endphp
                @if($ages)
                    @foreach($ages as $i => $age)
                        <div class="profile-row"><span class="profile-label">Age of Child {{ $i+1 }}:</span><span class="profile-value">
                            @if($age == '<12') Below 12 years old @elseif($age == '13-18') 13-18 years old @else Over 18 years old @endif
                        </span></div>
                    @endforeach
                @endif
            @endif
            <button onclick="toggleEditMode(true)">Edit Profile</button>
        </div>
        
        <div id="profile-edit" style="display:none;">
            <form method="POST" action="/caregiver/profile">
                @csrf
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required>

                <label for="role">Role</label>
                <select id="role" name="role" onchange="onRoleChange()" required>
                    <option value="parent" {{ $user->caregiver_status == 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="caretaker" {{ $user->caregiver_status == 'caretaker' ? 'selected' : '' }}>Caretaker</option>
                    <option value="non-caregiver" {{ $user->caregiver_status == 'non-caregiver' ? 'selected' : '' }}>Non-caregiver</option>
                </select>

                <div id="num-children-div" style="display:none;">
                    <label for="num_autism_children">Number of Autistic Children</label>
                    <input type="number" id="num_autism_children" name="num_autism_children" min="1" max="10" value="{{ $user->num_autism_children ?? 1 }}" onchange="onNumChildrenChange()">
                    <input type="hidden" id="autism_children_ages_json" value='{{ $user->autism_children_ages ?? '[]' }}'>
                    <div id="children-ages-div"></div>
                </div>

                <button type="submit">Save Changes</button>
                <button type="button" onclick="toggleEditMode(false)">Cancel</button>
            </form>
        </div>

        <div class="section-divider"></div>

        <!-- Password Change Section -->
        <div class="password-section">
            <h3>Change Password</h3>
            <p style="color: #666; margin-bottom: 1rem;">Update your account password to keep your account secure.</p>
            <button onclick="showPasswordModal()" class="btn-secondary">Change Password</button>
        </div>

        <div class="section-divider"></div>

        <!-- Account Deletion Section -->
        <div class="danger-zone">
            <h3>Danger Zone</h3>
            <p style="color: #666; margin-bottom: 1rem;">Once you delete your account, there is no going back. Please be certain.</p>
            <button onclick="showDeleteModal()" class="btn-danger">Delete Account</button>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('passwordModal')">&times;</span>
            <h2>Change Password</h2>
            <form method="POST" action="/caregiver/change-password">
                @csrf
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
                
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                
                <button type="submit" style="margin-top: 1rem;">Change Password</button>
                <button type="button" onclick="closeModal('passwordModal')" class="btn-secondary">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteModal')">&times;</span>
            <h2>Delete Account</h2>
            <p style="color: #e53e3e; margin-bottom: 1rem;"><strong>Warning:</strong> This action cannot be undone. All your data will be permanently deleted.</p>
            <form method="POST" action="/caregiver/delete-account">
                @csrf
                <label for="delete_password">Enter your password to confirm</label>
                <input type="password" id="delete_password" name="password" required>
                
                <label for="delete_confirm">Type "DELETE" to confirm</label>
                <input type="text" id="delete_confirm" name="confirm" placeholder="DELETE" required>
                
                <button type="submit" class="btn-danger" style="margin-top: 1rem;">Permanently Delete Account</button>
                <button type="button" onclick="closeModal('deleteModal')" class="btn-secondary">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Initialize edit form fields if in edit mode
        document.getElementById('role').addEventListener('change', onRoleChange);
        document.getElementById('num_autism_children').addEventListener('change', onNumChildrenChange);
        // Pre-fill fields if needed
        if ({{ json_encode($user->caregiver_status == 'parent' || $user->caregiver_status == 'caretaker') }}) {
            document.getElementById('num-children-div').style.display = 'block';
            onNumChildrenChange();
        }
    </script>
</body>
</html> 