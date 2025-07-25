<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $data = [
            'name' => $user->name,
            'role' => $user->role,
            'caregiver_status' => $user->caregiver_status,
            'num_autism_children' => $user->num_autism_children,
            'autism_children_ages' => $user->autism_children_ages ? json_decode($user->autism_children_ages, true) : [],
        ];
        return response()->json($data);
    }
} 