<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Check for unread notifications.
     */
    public function check(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Count unread notifications
        $unreadCount = $user->unreadNotifications->count();

        // Return JSON response
        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read and redirect.
     */
    public function read(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();

            // Redirect based on data
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'อ่านทั้งหมดเรียบร้อยแล้ว');
    }
}