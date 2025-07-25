<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('user');

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort by rating if requested
        if ($request->filled('sort_rating')) {
            if ($request->sort_rating === 'asc') {
                $query->orderBy('rating', 'asc');
            } elseif ($request->sort_rating === 'desc') {
                $query->orderBy('rating', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $feedbacks = $query->get();
        $settings = FeedbackSetting::first();
        $categories = Feedback::select('category')->distinct()->pluck('category')->filter()->values();
        return view('admin.feedback', compact('feedbacks', 'settings', 'categories'));
    }

    public function show($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('admin.feedback_detail', compact('feedback'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'interval_days' => 'required|integer|min:1',
        ]);

        $settings = FeedbackSetting::first();
        if (!$settings) {
            $settings = new FeedbackSetting();
        }
        
        $settings->interval_days = $request->interval_days;
        $settings->save();

        return redirect()->route('admin.feedback')->with('success', 'Feedback interval updated successfully!');
    }
} 