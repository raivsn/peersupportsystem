<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
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
        .category-list { margin-top: 2rem; }
        .category-item { 
            padding: 1rem; 
            border-bottom: 1px solid #eee; 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            gap: 1rem;
        }
        .category-info { flex: 1; }
        .category-title { color: #3b3b6d; font-size: 1.1rem; text-decoration: none; font-weight: 500; margin-bottom: 0.5rem; display: block; }
        .category-summary { color: #666; font-size: 0.9rem; line-height: 1.4; }
        .category-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }
        .edit-btn, .delete-btn { 
            padding: 0.3rem 0.8rem; 
            border: none; 
            border-radius: 4px; 
            font-size: 0.9rem; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
        }
        .edit-btn { background: #3498db; color: #fff; }
        .edit-btn:hover { background: #2980b9; }
        .delete-btn { background: #e74c3c; color: #fff; }
        .delete-btn:hover { background: #c0392b; }
        .add-btn { background: #3b3b6d; color: #fff; border: none; padding: 0.5rem 1.5rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-bottom: 1rem; }
        .add-btn:hover { background: #5a5ad1; }
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.2); }
        .modal-content { background: #fff; margin: 10% auto; padding: 2rem; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .close { float: right; font-size: 1.5rem; cursor: pointer; color: #888; }
        .close:hover { color: #3b3b6d; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
        .form-group input, .form-group textarea { 
            width: 100%; 
            padding: 0.5rem; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: 1rem; 
            box-sizing: border-box;
        }
        .form-group textarea { 
            min-height: 80px; 
            resize: vertical; 
        }
        .btn-group { display: flex; gap: 0.5rem; margin-top: 1rem; }
        .btn-cancel { background: #888; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        .btn-cancel:hover { background: #666; }
    </style>
</head>
<body>
    @if($role === 'admin')
        @include('admin.navbar', ['active' => 'forum'])
    @else
        @include('caregiver.navbar', ['active' => 'forum'])
    @endif
    
    <!-- Add Category Modal -->
    @if($role === 'admin')
    <div id="addCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addCategoryModal')">&times;</span>
            <h2>Add Forum Category</h2>
            <form method="POST" action="{{ route('forum.category.add') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="summary">Category Summary</label>
                    <textarea id="summary" name="summary" placeholder="Enter a brief description of this category..."></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="add-btn">Add Category</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('addCategoryModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editCategoryModal')">&times;</span>
            <h2>Edit Forum Category</h2>
            <form id="editCategoryForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_name">Category Name</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit_summary">Category Summary</label>
                    <textarea id="edit_summary" name="summary" placeholder="Enter a brief description of this category..."></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="add-btn">Update Category</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('editCategoryModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Category Modal -->
    <div id="deleteCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteCategoryModal')">&times;</span>
            <h2>Delete Forum Category</h2>
            <p>Are you sure you want to delete this category? This action cannot be undone.</p>
            <form id="deleteCategoryForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="btn-group">
                    <button type="submit" class="delete-btn">Yes, Delete</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteCategoryModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    
    <div class="container">
        <h1>Forum Categories</h1>
        @if($role === 'admin')
        <button class="add-btn" onclick="openModal('addCategoryModal')">Add Forum Category</button>
        @endif
        <div class="category-list">
            @forelse($categories as $category)
                <div class="category-item">
                    <div class="category-info">
                        <a class="category-title" href="{{ route('forum.category', ['id' => $category->id]) }}">{{ $category->name }}</a>
                        @if($category->summary)
                            <div class="category-summary">{{ $category->summary }}</div>
                        @endif
                    </div>
                    @if($role === 'admin')
                        <div class="category-actions">
                            <button class="edit-btn" onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->summary ?? '' }}')">Edit</button>
                            <button class="delete-btn" onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}')">Delete</button>
                        </div>
                    @endif
                </div>
            @empty
                <p>No forum categories yet.</p>
            @endforelse
        </div>
    </div>
    
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function openEditModal(categoryId, name, summary) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_summary').value = summary;
            document.getElementById('editCategoryForm').action = '/forum/category/' + categoryId + '/edit';
            openModal('editCategoryModal');
        }
        
        function openDeleteModal(categoryId, name) {
            document.getElementById('deleteCategoryForm').action = '/forum/category/' + categoryId + '/delete';
            openModal('deleteCategoryModal');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>
</body>
</html> 