<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\UserAccess;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for current user
     */
    public function index()
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            return redirect()->route('user')->with('error', 'Please login first.');
        }

        $userCode = session('userAccess')->userCode;

        // Get all notifications for this user
        $notifications = NotificationRecipient::where('recipient_user_code', $userCode)
            ->with(['notification.sender'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notification count for AJAX
     */
    public function getUnreadCount()
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            \Log::error('Notification getUnreadCount - No userAccess in session');
            return response()->json(['error' => 'Not authenticated', 'count' => 0], 401);
        }

        $userCode = session('userAccess')->userCode;

        // Debug user info
        \Log::info('Notification getUnreadCount - User: ' . $userCode);

        $unreadCount = NotificationRecipient::where('recipient_user_code', $userCode)
            ->where('status', 'unread')
            ->count();

        \Log::info('Notification getUnreadCount - Count: ' . $unreadCount);

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Get recent notifications for dashboard
     */
    public function getRecentNotifications()
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            \Log::error('Notification getRecentNotifications - No userAccess in session');
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $userCode = session('userAccess')->userCode;

        // Debug user info
        \Log::info('Notification getRecentNotifications - User: ' . $userCode);

        $recentNotifications = NotificationRecipient::where('recipient_user_code', $userCode)
            ->with(['notification.sender'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        \Log::info('Notification getRecentNotifications - Count: ' . $recentNotifications->count());

        return response()->json($recentNotifications);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $userCode = session('userAccess')->userCode;

        $notificationRecipient = NotificationRecipient::where('id', $id)
            ->where('recipient_user_code', $userCode)
            ->first();

        if ($notificationRecipient) {
            $notificationRecipient->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $userCode = session('userAccess')->userCode;

        NotificationRecipient::where('recipient_user_code', $userCode)
            ->where('status', 'unread')
            ->update([
                'status' => 'read',
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * View notification details
     */
    public function show($id)
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            \Log::error('Notification show - No userAccess in session');
            return redirect()->route('user')->with('error', 'Please login first.');
        }

        $userCode = session('userAccess')->userCode;

        \Log::info('Notification show - User: ' . $userCode . ', Notification ID: ' . $id);

        $notificationRecipient = NotificationRecipient::where('id', $id)
            ->where('recipient_user_code', $userCode)
            ->with(['notification.sender'])
            ->first();

        if (!$notificationRecipient) {
            \Log::error('Notification show - Notification not found for user: ' . $userCode . ', ID: ' . $id);
            return redirect()->route('notifications.index')->with('error', 'Notification not found.');
        }

        \Log::info('Notification show - Found notification: ' . $notificationRecipient->notification->title);

        // Mark as read if not already read
        if ($notificationRecipient->status === 'unread') {
            $notificationRecipient->markAsRead();
            \Log::info('Notification show - Marked as read');
        }

        return view('notifications.show', compact('notificationRecipient'));
    }

    /**
     * Simple test method to verify the endpoint is working
     */
    public function simpleTest()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Notification endpoint is working',
            'timestamp' => now()
        ]);
    }

    /**
     * Debug method to check session and authentication
     */
    public function debug()
    {
        $sessionData = [
            'has_userAccess' => session()->has('userAccess'),
            'userAccess_data' => session('userAccess'),
            'all_session' => session()->all(),
            'auth_check' => Auth::check(),
            'auth_user' => Auth::user()
        ];

        return response()->json($sessionData);
    }

    /**
     * Test method to create a sample notification (for debugging)
     */
    public function testNotification()
    {
        // Check if user is authenticated via session
        if (!session('userAccess')) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $userCode = session('userAccess')->userCode;

        // Create a test notification
        $notification = \App\Models\Notification::create([
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the system is working.',
            'priority' => 'medium',
            'sent_by' => 'Admin=349262938',
            'recipient_type' => 'teachers',
            'recipient_count' => 1
        ]);

        // Create recipient record
        \App\Models\NotificationRecipient::create([
            'notification_id' => $notification->id,
            'recipient_user_code' => $userCode,
            'status' => 'unread'
        ]);

        return response()->json(['success' => true, 'message' => 'Test notification created']);
    }
} 