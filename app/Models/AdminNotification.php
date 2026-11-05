<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;



    public function setNewAdminNotification($subject, $body, $route_name, $route_param, $route_type, $user_id){
        $notify = new AdminNotification();
        $notify->subject = $subject ?? '';
        $notify->body = $body ?? '';
        $notify->route_name = $route_name ?? '';
        $notify->route_param = $route_param ?? '';
        $notify->route_type = $route_type ?? "" ;
        $notify->user_id = $user_id;
        $notify->is_read = 0; //not read
        $notify->save();
    }

    public function getNotifications(){
        return AdminNotification::orderBy('id', 'DESC')->get();
    }
}
