<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all notifications paginated
        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);
        
        // Get unread count
        $unreadCount = $user->unreadNotificationsCount();
        
        return view('Employer.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read and redirect to action
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Mark as read
        $notification->markAsRead();
        
        // Parse action URL and add highlight parameter
        if ($notification->action_url) {
            $actionUrl = $notification->action_url;
            
            // Extract application/user ID from metadata if exists
            $highlightId = null;
            if ($notification->metadata) {
                $metadata = is_string($notification->metadata) 
                    ? json_decode($notification->metadata, true) 
                    : $notification->metadata;
                
                // Try to get application_id or user_id from metadata
                $highlightId = $metadata['application_id'] ?? $metadata['user_id'] ?? null;
            }
            
            // Add highlight parameter to URL
            if ($highlightId) {
                $separator = strpos($actionUrl, '?') !== false ? '&' : '?';
                $actionUrl .= $separator . 'highlight=' . $highlightId;
            }
            
            return redirect($actionUrl);
        }
        
        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->markAllNotificationsAsRead();
        
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $notification->delete();
        
        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Delete all read notifications
     */
    public function clearRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', true)
            ->delete();
        
        return back()->with('success', 'Read notifications cleared.');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotificationsCount();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (for AJAX dropdown)
     */
    public function getLatest()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }
}