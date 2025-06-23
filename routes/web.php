<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    // The 'auth' middleware should ensure a user is logged in here.
    // However, if you're getting an error, a direct check can prevent it.

    // Use Auth::id() for a safe way to get the current user's ID
    $currentUserId = Auth::id();

    // If for some reason $currentUserId is null (which shouldn't happen *after* 'auth' middleware),
    // you might want to handle it, though technically the middleware would redirect.
    if (!$currentUserId) {
        // This scenario indicates a deeper issue or middleware misconfiguration.
        // For now, let's assume it's always available due to 'auth' middleware.
        return redirect()->route('login'); // Or throw an exception
    }

    $users = User::where('id', '!=', $currentUserId)->get();

    // You need to return a view here and pass the data.
    return view('dashboard', compact('users'));
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');




Route::get('/chat/{id}', function ($id) {
    $currentUserId = Auth::id();
    if (!$currentUserId) {
        return redirect()->route('login');
    }

    // You should probably check if $id is a valid user ID here as well.
    $chatPartner = User::find($id);
    if (!$chatPartner) {
        // Handle case where chat partner doesn't exist, e.g., redirect back
        return redirect()->route('dashboard')->with('error', 'User not found.');
    }

    // IMPORTANT: Make sure your Livewire Chat component is properly included in this view.
    // We will render the Livewire Chat component in resources/views/chat.blade.php
    return view('chat', [
        'id' => $id // This 'id' is the $conversationPartnerId for your Livewire component
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('chat'); // <-- CHANGE THIS NAME TO 'chat'


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
