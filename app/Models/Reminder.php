<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Reminder extends Model
{
    use HasFactory;


    public function setNewReminder($subject, $note, $remind_at, $color, $priority){
        $reminder = new Reminder();
        $reminder->tenant_id = Auth::user()->tenant_id;
        $reminder->set_by = Auth::user()->id;
        $reminder->reminder_name = $subject;
        $reminder->remind_at = $remind_at;
        $reminder->note = $note ?? '';
        $reminder->priority = $priority ?? 1;
        $reminder->active_color =  $color;
        $reminder->save();
    }

    public function getAllTenantReminders(){
        return Reminder::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
    }

    public function generateRandomColorString(){
        return '#'.str_pad(dechex(mt_rand(0,0xFFFFFF)),6,'0', STR_PAD_LEFT);
    }
}
