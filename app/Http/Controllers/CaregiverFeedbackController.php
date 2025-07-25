<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CaregiverFeedbackController extends Controller
{
    public function shouldShowPrompt(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['should_show' => false]);

        $today = now()->format('Y-m-d');
        if (session('feedback_prompt_shown_date') === $today) {
            return response()->json(['should_show' => false]);
        }

        // Mark as shown for today in this session
        session(['feedback_prompt_shown_date' => $today]);
        return response()->json(['should_show' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:1|max:5',
            'category' => 'nullable|string|max:255',
        ]);

        $userId = session('user_id');
        
        Feedback::create([
            'user_id' => $userId,
            'content' => $request->content,
            'rating' => $request->rating,
            'category' => $request->category,
        ]);

        return response()->json(['success' => true]);
    }
} 