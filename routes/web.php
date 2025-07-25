<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\BadWord;

// Redirect root URL to login
Route::get('/', function () {
    return redirect('/login');
});

// Show login form
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login form submission (demo: hardcoded role)
Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    // Simple authentication for demo
    $user = DB::table('users')->where('email', $email)->first();
    
    if ($user && Hash::check($password, $user->password)) {
        // Manually set session for demo
        session(['user_id' => $user->id, 'user_name' => $user->name, 'user_role' => $user->role]);
        
        if ($user->role === 'admin') {
            return redirect('/dashboard/admin');
        } else {
            return redirect('/dashboard/caregiver');
        }
    }
    
    return back()->withErrors(['email' => 'Invalid credentials']);
});

// Show register form
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Handle register form submission
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
    DB::table('users')->insert([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'caregiver', // default role
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect('/login')->with('success', 'Registration successful! Please log in.');
});

// Logout route
Route::get('/logout', function () {
    session()->forget(['user_id', 'user_name', 'user_role', 'feedback_prompt_shown']);
    return redirect('/login');
})->name('logout');

// Caregiver dashboard
Route::get('/dashboard/caregiver', function () {
    // Get recently active forum posts (latest responded, fallback to latest posted)
    $recently_active_posts = DB::table('forum_posts as p')
        ->leftJoin('forum_categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin(DB::raw('(SELECT forum_post_id, COUNT(*) as replies_count FROM forum_replies GROUP BY forum_post_id) as r'), 'p.id', '=', 'r.forum_post_id')
        ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        ->select('p.*', 'c.name as category_name', DB::raw('COALESCE(r.replies_count, 0) as replies_count'), 'u.name as user_name', 'u.caregiver_status', 'u.role as user_role', 'u.id as user_id')
        ->orderByDesc(
            DB::raw('(SELECT MAX(created_at) FROM forum_replies WHERE forum_post_id = p.id)')
        )
        ->orderByDesc('p.created_at')
        ->limit(20)
        ->get();
    // For each post, get latest reply info
    foreach ($recently_active_posts as $post) {
        $latestReply = DB::table('forum_replies as r')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->where('r.forum_post_id', $post->id)
            ->orderBy('r.created_at', 'desc')
            ->select('r.created_at', 'u.name as user_name', 'u.caregiver_status', 'u.role as user_role')
            ->first();
        if ($latestReply) {
            $post->latest_reply_time = $latestReply->created_at;
            $post->latest_reply_user = $latestReply->user_name;
            $post->latest_reply_user_role = $latestReply->user_role;
            $post->latest_reply_user_caregiver_status = $latestReply->caregiver_status;
        } else {
            $post->latest_reply_time = $post->created_at;
            $post->latest_reply_user = $post->user_name;
            $post->latest_reply_user_role = $post->user_role;
            $post->latest_reply_user_caregiver_status = $post->caregiver_status;
        }
    }
    
    // Get recently posted articles from resources
    $recently_posted_articles = DB::table('resources as r')
        ->leftJoin('users as u', 'r.created_by', '=', 'u.id')
        ->select('r.*', 'u.name as author_name', 'u.role as author_role', 'u.caregiver_status as author_caregiver_status')
        ->orderBy('r.created_at', 'desc')
        ->limit(10)
        ->get();
    
    // Get all forum categories
    $forum_categories = DB::table('forum_categories')->orderBy('name')->get();
    
    return view('caregiver.dashboard', [
        'recently_active_posts' => $recently_active_posts,
        'recently_posted_articles' => $recently_posted_articles,
        'forum_categories' => $forum_categories
    ]);
})->name('caregiver.dashboard');

// Caregiver forum (redirect to shared forum)
Route::get('/caregiver/forum', function () {
    return redirect()->route('forum.home');
})->name('caregiver.forum');

// Caregiver resource library
Route::get('/caregiver/resources', function () {
    $resources = DB::table('resources')->orderBy('category')->orderBy('created_at', 'desc')->get();
    $categories = $resources->groupBy('category');
    return view('caregiver.resources', compact('categories'));
})->name('caregiver.resources');

// Resource detail
Route::get('/caregiver/resources/{id}', function ($id) {
    $resource = DB::table('resources')->where('id', $id)->first();
    if (!$resource) abort(404);
    $author = DB::table('users')->where('id', $resource->created_by)->first();
    $resource->author_name = $author->name ?? 'Unknown User';
    $resource->author_role = ($author->role ?? null) === 'admin' ? 'admin' : ($author->caregiver_status ?? ($author->role ?? 'Unknown Role'));
    return view('caregiver.resource_detail', compact('resource'));
})->name('caregiver.resource_detail');

// Caregiver manage profile (GET)
Route::get('/caregiver/profile', function () {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
    return view('caregiver.profile', compact('user'));
})->name('caregiver.profile');

// Caregiver manage profile (POST)
Route::post('/caregiver/profile', function (\Illuminate\Http\Request $request) {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'role' => 'required|in:parent,caretaker,non-caregiver',
        'num_autism_children' => 'nullable|integer|min:1|max:10',
        'autism_children_ages' => 'nullable|array',
        'autism_children_ages.*' => 'in:<12,13-18,>18',
    ]);
    $data = [
        'name' => $validated['name'],
        'caregiver_status' => $validated['role'],
        'num_autism_children' => ($validated['role'] === 'parent' || $validated['role'] === 'caretaker') ? ($validated['num_autism_children'] ?? null) : null,
        'autism_children_ages' => ($validated['role'] === 'parent' || $validated['role'] === 'caretaker') ? json_encode($validated['autism_children_ages'] ?? []) : null,
    ];
    \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->update($data);
    session(['user_name' => $validated['name']]);
    return redirect()->back()->with('success', 'Profile updated successfully!');
});

// Caregiver change password
Route::post('/caregiver/change-password', function (\Illuminate\Http\Request $request) {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8',
        'confirm_password' => 'required|same:new_password',
    ]);
    
    $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
    
    if (!password_verify($validated['current_password'], $user->password)) {
        return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }
    
    \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->update([
        'password' => password_hash($validated['new_password'], PASSWORD_DEFAULT),
        'updated_at' => now(),
    ]);
    
    return redirect()->back()->with('success', 'Password changed successfully!');
})->name('caregiver.change-password');

// Caregiver delete account
Route::post('/caregiver/delete-account', function (\Illuminate\Http\Request $request) {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    
    $validated = $request->validate([
        'password' => 'required',
        'confirm' => 'required|in:DELETE',
    ]);
    
    $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
    
    if (!password_verify($validated['password'], $user->password)) {
        return redirect()->back()->withErrors(['password' => 'Password is incorrect.']);
    }
    
    // Delete user's data
    \Illuminate\Support\Facades\DB::table('bookmarks')->where('user_id', $userId)->delete();
    \Illuminate\Support\Facades\DB::table('feedbacks')->where('user_id', $userId)->delete();
    \Illuminate\Support\Facades\DB::table('forum_replies')->where('user_id', $userId)->delete();
    \Illuminate\Support\Facades\DB::table('forum_posts')->where('user_id', $userId)->delete();
    \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->delete();
    
    // Clear session and redirect to login
    session()->flush();
    return redirect('/login')->with('success', 'Your account has been permanently deleted.');
})->name('caregiver.delete-account');

// Caregiver bookmarked posts
Route::get('/caregiver/bookmarks', function () {
    $userId = session('user_id');
    $bookmarks = DB::table('bookmarks as b')
        ->join('forum_posts as p', 'b.forum_post_id', '=', 'p.id')
        ->join('forum_categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        ->where('b.user_id', $userId)
        ->select('p.*', 'c.name as category_name', 'b.created_at as bookmarked_at', 'u.name as user_name', 'u.id as user_id', 'u.caregiver_status')
        ->orderBy('b.created_at', 'desc')
        ->get();
    // For each post, get latest reply info (optional, not used in view now)
    return view('caregiver.bookmarks', ['bookmarks' => $bookmarks]);
})->name('caregiver.bookmarks');

Route::post('/forum/post/{postId}/bookmark', function ($postId) {
    $userId = session('user_id');
    $existing = DB::table('bookmarks')->where('user_id', $userId)->where('forum_post_id', $postId)->first();
    
    if ($existing) {
        DB::table('bookmarks')->where('user_id', $userId)->where('forum_post_id', $postId)->delete();
        return response()->json(['bookmarked' => false]);
    } else {
        DB::table('bookmarks')->insert([
            'user_id' => $userId,
            'forum_post_id' => $postId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['bookmarked' => true]);
    }
})->name('forum.post.bookmark');

// Admin dashboard with analytics
Route::get('/dashboard/admin', function () {
    // Total user count
    $totalUsers = DB::table('users')->count();
    $totalCaregivers = DB::table('users')->where('role', '!=', 'admin')->count();
    $totalAdmins = DB::table('users')->where('role', 'admin')->count();
    
    // Most active forum posts (top 10)
    $mostActivePosts = DB::table('forum_posts as p')
        ->leftJoin('forum_categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin(DB::raw('(SELECT forum_post_id, COUNT(*) as replies_count FROM forum_replies GROUP BY forum_post_id) as r'), 'p.id', '=', 'r.forum_post_id')
        ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        ->select('p.*', 'c.name as category_name', DB::raw('COALESCE(r.replies_count, 0) as replies_count'), 'u.name as user_name', 'u.caregiver_status', 'u.role as user_role')
        ->orderByDesc(DB::raw('COALESCE(r.replies_count, 0)'))
        ->limit(10)
        ->get();
    
    // Forum statistics
    $totalForumPosts = DB::table('forum_posts')->count();
    $totalForumReplies = DB::table('forum_replies')->count();
    $totalForumCategories = DB::table('forum_categories')->count();
    
    // Resource library statistics
    $totalResources = DB::table('resources')->count();
    $resourceCategories = DB::table('resources')->select('category')->distinct()->count();
    
    // Feedback analytics
    $totalFeedback = DB::table('feedbacks')->count();
    $averageRating = DB::table('feedbacks')->whereNotNull('rating')->avg('rating');
    $feedbackCategories = DB::table('feedbacks')->select('category')->distinct()->pluck('category')->filter()->values();
    
    // Recent feedback (last 5)
    $recentFeedback = DB::table('feedbacks as f')
        ->leftJoin('users as u', 'f.user_id', '=', 'u.id')
        ->select('f.*', 'u.name as user_name')
        ->orderBy('f.created_at', 'desc')
        ->limit(5)
        ->get();
    
    // User activity (users with most posts)
    $mostActiveUsers = DB::table('users as u')
        ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as post_count FROM forum_posts GROUP BY user_id) as p'), 'u.id', '=', 'p.user_id')
        ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as reply_count FROM forum_replies GROUP BY user_id) as r'), 'u.id', '=', 'r.user_id')
        ->select('u.*', DB::raw('COALESCE(p.post_count, 0) as post_count'), DB::raw('COALESCE(r.reply_count, 0) as reply_count'), DB::raw('COALESCE(p.post_count, 0) + COALESCE(r.reply_count, 0) as total_activity'))
        ->orderByDesc('total_activity')
        ->limit(10)
        ->get();
    
    return view('admin.dashboard', compact(
        'totalUsers', 'totalCaregivers', 'totalAdmins',
        'mostActivePosts', 'totalForumPosts', 'totalForumReplies', 'totalForumCategories',
        'totalResources', 'resourceCategories',
        'totalFeedback', 'averageRating', 'feedbackCategories', 'recentFeedback',
        'mostActiveUsers'
    ));
})->name('dashboard.admin'); 

// Admin manage profile (GET)
Route::get('/admin/profile', function () {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
    return view('admin.profile', compact('user'));
})->name('admin.profile');

// Admin manage profile (POST)
Route::post('/admin/profile', function (\Illuminate\Http\Request $request) {
    $userId = session('user_id');
    if (!$userId) {
        return redirect('/login');
    }
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);
    \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->update(['name' => $validated['name']]);
    session(['user_name' => $validated['name']]);
    return redirect()->back()->with('success', 'Profile updated successfully!');
}); 

// Resource Library Management (Admin)
Route::get('/admin/resources', function () {
    $resources = DB::table('resources')->orderBy('category')->orderBy('created_at', 'desc')->get();
    return view('admin.resources', compact('resources'));
})->name('admin.resources');

Route::get('/admin/resources/create', function () {
    return view('admin.resource_create');
})->name('admin.resource_create');

Route::post('/admin/resources', function (\Illuminate\Http\Request $request) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category' => 'nullable|string|max:255',
    ]);
    DB::table('resources')->insert([
        'title' => $request->title,
        'content' => $request->content,
        'category' => $request->category,
        'created_by' => session('user_id'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect()->route('admin.resources');
})->name('admin.resource_store');

Route::get('/admin/resources/{id}/edit', function ($id) {
    $resource = DB::table('resources')->where('id', $id)->first();
    if (!$resource) abort(404);
    return view('admin.resource_edit', compact('resource'));
})->name('admin.resource_edit');

Route::post('/admin/resources/{id}/update', function (\Illuminate\Http\Request $request, $id) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category' => 'nullable|string|max:255',
    ]);
    DB::table('resources')->where('id', $id)->update([
        'title' => $request->title,
        'content' => $request->content,
        'category' => $request->category,
        'updated_at' => now(),
    ]);
    return redirect()->route('admin.resources');
})->name('admin.resource_update');

Route::post('/admin/resources/{id}/delete', function ($id) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    DB::table('resources')->where('id', $id)->delete();
    return redirect()->route('admin.resources');
})->name('admin.resource_delete');

// Admin feedback routes
Route::get('/admin/feedback', [App\Http\Controllers\AdminFeedbackController::class, 'index'])->name('admin.feedback');
Route::get('/admin/feedback/{id}', [App\Http\Controllers\AdminFeedbackController::class, 'show'])->name('admin.feedback.show');
Route::post('/admin/feedback/update-settings', [App\Http\Controllers\AdminFeedbackController::class, 'updateSettings'])->name('admin.feedback.update-settings');

// Caregiver feedback routes
Route::get('/caregiver/feedback/should-show', [App\Http\Controllers\CaregiverFeedbackController::class, 'shouldShowPrompt'])->name('caregiver.feedback.should-show');
Route::post('/caregiver/feedback', [App\Http\Controllers\CaregiverFeedbackController::class, 'store'])->name('caregiver.feedback.store');

// Shared Forum Routes

Route::get('/forum', function () {
    $userId = session('user_id');
    $userRole = session('user_role');
    $categories = DB::table('forum_categories')->get();
    return view('forum.index', [
        'categories' => $categories,
        'role' => $userRole,
        'userId' => $userId,
    ]);
})->name('forum.home');

Route::post('/forum/category/add', function (\Illuminate\Http\Request $request) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    $request->validate([
        'name' => 'required|string|max:255',
        'summary' => 'nullable|string'
    ]);
    DB::table('forum_categories')->insert([
        'name' => $request->name,
        'summary' => $request->summary,
        'created_by' => session('user_id'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect()->route('forum.home');
})->name('forum.category.add');

Route::put('/forum/category/{id}/edit', function (\Illuminate\Http\Request $request, $id) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    $request->validate([
        'name' => 'required|string|max:255',
        'summary' => 'nullable|string'
    ]);
    DB::table('forum_categories')->where('id', $id)->update([
        'name' => $request->name,
        'summary' => $request->summary,
        'updated_at' => now(),
    ]);
    return redirect()->route('forum.home');
})->name('forum.category.edit');

Route::delete('/forum/category/{id}/delete', function ($id) {
    $userRole = session('user_role');
    if ($userRole !== 'admin') abort(403);
    DB::table('forum_categories')->where('id', $id)->delete();
    return redirect()->route('forum.home');
})->name('forum.category.delete');

// Forum category
Route::get('/forum/category/{id}', function ($id) {
    $userId = session('user_id');
    $userRole = session('user_role');
    $category = DB::table('forum_categories')->where('id', $id)->first();
    $posts = DB::table('forum_posts as p')
        ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        ->leftJoin(DB::raw('(SELECT forum_post_id, COUNT(*) as replies_count, MAX(created_at) as last_reply_time FROM forum_replies GROUP BY forum_post_id) as r'), 'p.id', '=', 'r.forum_post_id')
        ->where('p.category_id', $id)
        ->select('p.*', 'u.name as user_name', 'u.caregiver_status', 'u.role as user_role', 'u.id as user_id', DB::raw('COALESCE(r.replies_count, 0) as replies_count'), DB::raw('COALESCE(r.last_reply_time, p.created_at) as latest_activity_time'))
        ->orderByDesc(DB::raw('COALESCE(r.last_reply_time, p.created_at)'))
        ->get();
    foreach ($posts as $post) {
        $latestReply = DB::table('forum_replies as r')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->where('r.forum_post_id', $post->id)
            ->orderBy('r.created_at', 'desc')
            ->select('r.created_at', 'u.name as user_name', 'u.id as user_id', 'u.caregiver_status', 'u.role as user_role')
            ->first();
        if ($latestReply) {
            $post->latest_reply_time = $latestReply->created_at;
            $post->latest_reply_user = $latestReply->user_name;
            $post->latest_reply_user_id = $latestReply->user_id;
            $post->latest_reply_user_role = $latestReply->user_role;
            $post->latest_reply_user_caregiver_status = $latestReply->caregiver_status;
        } else {
            $post->latest_reply_time = $post->created_at;
            $post->latest_reply_user = $post->user_name;
            $post->latest_reply_user_id = $post->user_id;
            $post->latest_reply_user_role = $post->user_role;
            $post->latest_reply_user_caregiver_status = $post->caregiver_status;
        }
    }
    return view('forum.category', [
        'category' => $category,
        'posts' => $posts,
        'role' => $userRole,
        'userId' => $userId,
    ]);
})->name('forum.category');

// --- BAD WORD FILTER HELPER ---
function checkBadWords($text, $badWords) {
    $found = [];
    foreach ($badWords as $word) {
        // Match whole word, case-insensitive
        if (preg_match('/\\b' . preg_quote($word, '/') . '\\b/i', $text)) {
            $found[] = $word;
        }
    }
    return $found;
}

// Profanity Filter Admin Page
Route::middleware(['web'])->group(function () {
    Route::get('/admin/profanity-filter', function () {
        $userRole = session('user_role');
        if ($userRole !== 'admin') abort(403);
        $badWords = BadWord::orderBy('word')->get();
        return view('admin.profanity-filter', ['badWords' => $badWords]);
    })->name('admin.profanity');

    Route::post('/admin/profanity-filter/add', function (\Illuminate\Http\Request $request) {
        $userRole = session('user_role');
        if ($userRole !== 'admin') abort(403);
        $request->validate([
            'word' => 'required|string|max:255|unique:bad_words,word',
        ]);
        BadWord::create(['word' => strtolower($request->word)]);
        return redirect()->route('admin.profanity')->with('success', 'Bad word added.');
    })->name('admin.profanity.add');

    Route::post('/admin/profanity-filter/delete/{id}', function ($id) {
        $userRole = session('user_role');
        if ($userRole !== 'admin') abort(403);
        BadWord::where('id', $id)->delete();
        return redirect()->route('admin.profanity')->with('success', 'Bad word deleted.');
    })->name('admin.profanity.delete');
});

Route::post('/forum/category/{id}/add-post', function (\Illuminate\Http\Request $request, $id) {
    $userId = session('user_id');
    $badWords = BadWord::pluck('word')->toArray();
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);
    $badInTitle = checkBadWords($request->title, $badWords);
    $badInContent = checkBadWords($request->content, $badWords);
    $allBad = array_merge($badInTitle, $badInContent);
    if (!empty($allBad)) {
        return back()->withInput()->withErrors(['badwords' => 'Blocked. Reason: post contains bad word(s): ' . implode(', ', array_unique($allBad))]);
    }
    DB::table('forum_posts')->insert([
        'category_id' => $id,
        'user_id' => $userId,
        'title' => $request->title,
        'content' => $request->content,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect()->route('forum.category', ['id' => $id]);
})->name('forum.post.add');

Route::post('/forum/post/{postId}/delete', function ($postId) {
    $userId = session('user_id');
    $userRole = session('user_role');
    $post = DB::table('forum_posts')->where('id', $postId)->first();
    if (!$post) abort(404);
    if ($userRole !== 'admin' && $post->user_id != $userId) abort(403);
    $categoryId = $post->category_id;
    DB::table('forum_posts')->where('id', $postId)->delete();
    return redirect()->route('forum.category', ['id' => $categoryId]);
})->name('forum.post.delete'); 

// View a single forum post
Route::get('/forum/post/{postId}', function ($postId) {
    $userId = session('user_id');
    $userRole = session('user_role');
    $post = DB::table('forum_posts as p')
        ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        ->select('p.*', 'u.name as user_name', 'u.role as user_role', 'u.caregiver_status as caregiver_status', 'u.id as user_id')
        ->where('p.id', $postId)
        ->first();
    if (!$post) abort(404);
    $category = DB::table('forum_categories')->where('id', $post->category_id)->first();
    $allReplies = DB::table('forum_replies as r')
        ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
        ->where('r.forum_post_id', $postId)
        ->orderBy('r.created_at', 'asc')
        ->select('r.*', 'u.name as user_name', 'u.role as user_role', 'u.caregiver_status as caregiver_status', 'u.id as user_id')
        ->get();
    // Build a nested tree of replies
    $repliesByParent = [];
    foreach ($allReplies as $reply) {
        $parentKey = $reply->parent_id === null ? 'root' : $reply->parent_id;
        $repliesByParent[$parentKey][] = $reply;
    }
    function buildReplyTree($parentId, $repliesByParent) {
        $key = $parentId === null ? 'root' : $parentId;
        $tree = [];
        foreach ($repliesByParent[$key] ?? [] as $reply) {
            $children = buildReplyTree($reply->id, $repliesByParent);
            $reply->children = $children;
            $tree[] = $reply;
        }
        return $tree;
    }
    $nestedReplies = buildReplyTree(null, $repliesByParent);
    return view('forum.post', [
        'post' => $post,
        'category' => $category,
        'role' => $userRole,
        'userId' => $userId,
        'replies' => $nestedReplies,
    ]);
})->name('forum.post'); 

Route::post('/forum/post/{postId}/reply', function (\Illuminate\Http\Request $request, $postId) {
    $userId = session('user_id');
    if (!$userId) return redirect('/login');
    $badWords = BadWord::pluck('word')->toArray();
    $request->validate([
        'body' => 'required|string',
        'parent_id' => 'nullable|integer|exists:forum_replies,id',
    ]);
    $badInBody = checkBadWords($request->body, $badWords);
    if (!empty($badInBody)) {
        $parentId = $request->parent_id ?? null;
        return back()->withInput()->withErrors(['badwords' => 'Blocked. Reason: post contains bad word(s): ' . implode(', ', array_unique($badInBody))])->with('reply_parent_id', $parentId);
    }
    DB::table('forum_replies')->insert([
        'forum_post_id' => $postId,
        'user_id' => $userId,
        'body' => $request->body,
        'parent_id' => $request->parent_id ?? null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect()->route('forum.post', ['postId' => $postId]);
})->name('forum.reply.add'); 

Route::post('/forum/reply/{replyId}/delete', function ($replyId) {
    $userId = session('user_id');
    $reply = DB::table('forum_replies')->where('id', $replyId)->first();
    if (!$reply) abort(404);
    if ($reply->user_id != $userId) abort(403);
    // Recursive delete function
    function deleteReplyAndChildren($id) {
        $children = DB::table('forum_replies')->where('parent_id', $id)->pluck('id');
        foreach ($children as $childId) {
            deleteReplyAndChildren($childId);
        }
        DB::table('forum_replies')->where('id', $id)->delete();
    }
    DB::beginTransaction();
    try {
        deleteReplyAndChildren($replyId);
        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
        abort(500, 'Failed to delete reply');
    }
    return back();
})->name('forum.reply.delete'); 

Route::get('/user/profile/{id}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('user.profile.info'); 

// Removed /caregiver/myposts route as per user request 