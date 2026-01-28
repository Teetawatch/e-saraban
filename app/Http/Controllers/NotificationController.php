<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function check(Request $request)
    {
        $user = Auth::user();
        
        // Count unread notifications
        $unreadCount = $user->unreadNotifications->count();
        
        // Return JSON response
        return response()->json([
            'unread_count' => $unreadCount,
            // We can also return HTML partials if we want to update the dropdown content dynamically
            // For now, let's just focus on the badge
        ]);
    }
    
    public function read($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if($notification) {
            $notification->markAsRead();
            
            // Redirect based on data
            if(isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }
        
        return back();
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'อ่านทั้งหมดเรียบร้อยแล้ว');
    }
}