<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        Notification::where('id', $id)->where('user_id', auth()->id())->first()->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('readed', false)
            ->get();

            foreach ($notifications as $notification) {
                $notification->markAsRead();
            }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Notification::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        Notification::where('user_id', auth()->id())
                    ->delete();

        return back()->with('success', 'All notifications deleted.');
    }
}