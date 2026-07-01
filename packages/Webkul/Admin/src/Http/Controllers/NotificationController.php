<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get unread notifications for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $user = auth()->guard('user')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $notifications = $user->unreadNotifications()
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id'         => $notification->id,
                    'title'      => $notification->data['title'] ?? 'Notifikasi Baru',
                    'message'    => $notification->data['message'] ?? '',
                    'type'       => $notification->data['type'] ?? 'info',
                    'action_url' => $notification->data['action_url'] ?? '#',
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'count'         => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all unread notifications of the user as read.
     */
    public function readAll(): JsonResponse
    {
        $user = auth()->guard('user')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
