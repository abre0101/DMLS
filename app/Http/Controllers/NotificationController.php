<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Get all notifications for the authenticated user, ordered by newest first
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->get();

        // Return the notifications index view with the notifications data
        return view('notifications.index', compact('notifications'));
    }
}
