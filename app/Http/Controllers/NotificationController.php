<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
     public function index()
     {
          $user = Auth::user();

          return response()->json([
               'read' => $user->readNotifications,
               'unread' => $user->unreadNotifications,
          ]);
     }

     public function markAsRead(Request $request, $notificationId)
     {
          $user = Auth::user();
          $notification = $user->notifications()->findOrFail($notificationId);
          $notification->markAsRead();

          return response()->noContent();
     }
}
