<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ContactsController extends Controller
{
    public function __construct()
    {
        $this->contact = new Contact();
        $this->conversation = new Conversation();
        $this->reminder = new Reminder();
    }

    public function allContacts(Request $request){
        try{
            $contacts = $this->contact->getTenantContacts(Auth::user()->tenant_id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success contacts loaded",
                'data' => [
                    "contact"=>$contacts
                ]
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }


    public function createContact(Request $request){
        $validator = Validator::make($request->all(),[
            'company_name'=>'required',
            'company_phone_no'=>'required',
            'company_email'=>'required',
            //'website'=>'required',
            'address'=>'required',
            'first_name'=>'required',
            'phone_no'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'position'=>'required',
        ],[
            'company_name.required'=>'Enter company name',
            'company_phone_no.required'=>'Enter company phone number',
            'company_email.required'=>'Enter a valid company email address',
            'address.required'=>'Enter the company office address',
            'first_name.required'=>'Enter contact person first name',
            'phone_no.required'=>'Enter contact person phone number',
            'last_name.required'=>'Enter contact person last name',
            'email.required'=>'Enter a valid email address for the contact person'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
           $contact =  $this->contact->setNewContact($request);
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Success Contact Created",
                    'data' => [
                        'contact'=>$contact
                    ]
                ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }


    public function viewContact(Request $request){
       try{
           $contact = $this->contact->getContactById($request->id);
           if(!empty($contact)){
               return response()->json([
                   'success' => false,
                   'code' => 200,
                   'message' => "Success view contact",
                   'data' => [
                       'contact'=>$contact
                   ]
               ]);
           }else{
               return response()->json([
                   'success' => false,
                   'code' => 400,
                   'message' => "No contacts found",
                   'data' => ""
               ]);
           }
       }catch (\Exception $e){
           return response()->json([
               'success' => false,
               'code' => 500,
               'message' => "Oops something bad happened, Please try again! ",
               'data' => ''
           ]);
       }
    }

    public function newConversation(Request $request){
        $validator = Validator::make($request->all(),[
            'subject'=>'required',
            'conversation'=>'required',
            'contact_id'=>'required',
            //'remind_at'=>'required|date'
        ],[
            'subject.required'=>'Enter a subject for this conversation',
            'conversation.required'=>'What was the conversation about?',
            //'remind_at.required'=>"This field is required"
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }

        try{
            $conversations = $this->conversation->setNewConversation($request);
            #Set reminder
            if(!empty($request->remind_at)){
                $color = $this->reminder->generateRandomColorString();
                $this->reminder->setNewReminder($request->subject, $request->conversation, $request->remind_at, $color, 2);
            }

            #Upgrade contact to lead
            $contact = $this->contact->getContactById($request->contact_id);
            if(!empty($contact)){
                if($contact->contact_type == 0){
                    $contact->contact_type = 1; //i.e lead
                    $contact->save();
                }
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Saved",
                'data' => [
                    "conversations" => $conversations,
                ]
            ]);

        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }



    public function getContactConversations(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_id'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
            $contact = $request->contact_id;
            $conversations = $this->conversation->getContactConversations($contact);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Conversations",
                'data' => [
                    "conversations" => $conversations,
                ]
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }
}
