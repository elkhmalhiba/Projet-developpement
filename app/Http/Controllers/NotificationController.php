<?php

namespace App\Http\Controllers;

class NotificationController extends Controller{
    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }
}
