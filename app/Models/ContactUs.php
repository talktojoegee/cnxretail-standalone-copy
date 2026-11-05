<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ContactUs extends Model
{
    use HasFactory;


    public function setNewContactUs(Request $request){
        $contact = new ContactUs();
        $contact->first_name = $request->first_name;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->mobile_no = $request->mobile_no;
        $contact->message = $request->message;
        $contact->save();

    }

    public function getAllContactUsRequests(){
        return ContactUs::orderBy('id', 'DESC')->get();
    }
}
