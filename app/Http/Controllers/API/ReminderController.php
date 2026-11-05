<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\TenantNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{

    public function __construct()
    {
        $this->reminder = new Reminder();
        $this->tenantnotification = new TenantNotification();
    }


    public function createReminders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required',
            'title' => 'required',
        ], [
            'date.required' => 'Date is required',
            'time.required' => 'Time is required',
            'title.required' => 'Title is required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $note = $request->note??"";
            $time = $request->time;
            $date = $request->date;
            $title = $request->title;
            $date_time = strtotime("$date $time");
            $_date = date('Y-m-d H:i:s',$date_time);
            $reminder = $this->reminder->setNewReminder($title,$note,$_date);
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => "Reminder created successfully",
            'data' => [
                "reminder" => $reminder
            ]
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }

    public function getReminders(Request $request)
    {
        try{
            $reminders = $this->reminder->getAllTenantReminders();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Reminders",
                'data' => [
                    "reminder" => $reminders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
