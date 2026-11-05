<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\TenantNotification;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->reminder = new Reminder();
        $this->tenantnotification = new TenantNotification();
    }

    public function showReminders(){
        return view('reminder.calendar');
    }

    public function addNewReminder(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'conversation'=>'required',
            'remind_at'=>'required|date'
        ],[
            'subject.required'=>'Enter a subject for this conversation',
            'conversation.required'=>'What was the conversation about?',
            'remind_at.required'=>"This field is required"
        ]);
        $color = $this->reminder->generateRandomColorString();
        $this->reminder->setNewReminder($request->subject, $request->conversation, $request->remind_at, $color, 2);
        session()->flash("success", "Your reminder was scheduled successfully.");
        return back();
    }

    public function notifications(){
        return view('reminder.notifications',['notifications'=>$this->tenantnotification->getNotifications()]);
    }
}
