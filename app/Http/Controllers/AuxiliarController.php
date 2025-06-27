<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Notification;
use App\Models\User;

class AuxiliarController extends Controller
{
    public function getAdminUserId(){
        $adminUser = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();
        return $adminUser ? $adminUser->id : null;
    }

    public function generateNotification($user_id, $subject, $text, $urls = null, $type = null, $type_id = null){
        $notification = new Notification();
        $notification->user_id = $user_id;
        $notification->subject = $subject;
        $notification->text = $text;
        $notification->urls = $urls;
        $notification->type = $type;
        $notification->type_id = $type_id;
        $notification->readed = false; // Default to unread
        $notification->save();
    }
}