<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;


    /*
     * Use-case methods
     */
    public function setNewActivity($tenant, $subject, $log){
        $log = new ActivityLog();
        $log->tenant_id = $tenant;
        $log->subject = $subject;
        $log->log = $log;
        $log->save();
    }
}
