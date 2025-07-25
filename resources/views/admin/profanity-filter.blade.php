<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profanity Filter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('/wallpaper-peersupport.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>
@extends('admin.layout')
@section('title', 'Profanity Filter')
@section('content')
<div class="container" style="max-width: 600px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 2rem;">
    <h1 style="color: #3b3b6d;">Profanity Filter</h1>
    @if(session('success'))
        <div style="color: #27ae60; margin-bottom: 1rem; font-weight: bold;">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.profanity.add') }}" style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
        @csrf
        <input type="text" name="word" placeholder="Add new bad word" required style="flex:1; padding:0.5rem; border-radius:4px; border:1px solid #ccc;">
        <button type="submit" class="add-btn">Add</button>
    </form>
    <h2 style="font-size:1.2rem; color:#23234c; margin-bottom:1rem;">Current Bad Words</h2>
    <ul style="list-style:none; padding:0;">
        @forelse($badWords as $badWord)
            <li style="display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid #eee;">
                <span style="font-size:1.1rem; color:#3b3b6d;">{{ $badWord->word }}</span>
                <form method="POST" action="{{ route('admin.profanity.delete', ['id' => $badWord->id]) }}" onsubmit="return confirm('Delete this word?')">
                    @csrf
                    <button type="submit" class="delete-btn" style="background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:0.2rem 0.8rem; font-size:0.95rem; cursor:pointer;">Delete</button>
                </form>
            </li>
        @empty
            <li style="color:#888;">No bad words found.</li>
        @endforelse
    </ul>
</div>
@endsection
@php $active = 'profanity'; @endphp 