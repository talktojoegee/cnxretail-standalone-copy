<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantNotification extends Model
{
    use HasFactory;


    public function getNotifications(){
        return TenantNotification::orderBy('id', 'DESC')->get();
    }
}
