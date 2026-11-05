<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    use HasFactory;

    public function getAllConversations(){
        return $this->hasMany(Conversation::class, 'contact_id')->orderBy('id', 'DESC');
    }

    public function getAllContactInvoices(){
        return $this->hasMany(InvoiceMaster::class, 'contact_id')->orderBy('id', 'DESC');
    }

    public function getAllContactReceipts(){
        return $this->hasMany(ReceiptMaster::class, 'contact_id')->orderBy('id', 'DESC');
    }

    /*
     * Use-case methods
     */
    public function setNewContact(Request $request){
        $contact = new Contact();
        $contact->added_by = Auth::user()->id;
        $contact->tenant_id = Auth::user()->tenant_id;
        $contact->company_name = $request->company_name ?? '';
        $contact->company_address = $request->address ?? '';
        $contact->company_email = $request->company_email ?? '';
        $contact->company_phone = $request->company_phone_no ?? '';
        $contact->company_website = $request->website ?? '';
        $contact->contact_first_name = $request->first_name ?? '';
        $contact->contact_last_name = $request->last_name ?? '';
        $contact->contact_position = $request->position ?? '';
        $contact->contact_email = $request->email ?? '';
        $contact->contact_mobile = $request->phone_no ?? '';
        $contact->communication_channel = $request->communication_channel ?? '';
        $contact->whatsapp_contact = $request->whatsapp_contact ?? '';
        $contact->hear_about_us = $request->hear_about_us ?? '';
        $contact->preferred_time = $request->preferred_time ?? now();
        $contact->slug = substr(sha1(time()),23,40);
        $contact->description = $request->description ?? '';
        $contact->save();
    }

    public function updateContact(Request $request){
        $contact = Contact::find($request->contact);
        $contact->added_by = Auth::user()->id;
        $contact->tenant_id = Auth::user()->tenant_id;
        $contact->company_name = $request->company_name ?? '';
        $contact->company_address = $request->address ?? '';
        $contact->company_email = $request->company_email ?? '';
        $contact->company_phone = $request->company_phone_no ?? '';
        $contact->company_website = $request->website ?? '';
        $contact->contact_first_name = $request->first_name ?? '';
        $contact->contact_last_name = $request->last_name ?? '';
        $contact->contact_position = $request->position ?? '';
        $contact->contact_email = $request->email ?? '';
        $contact->contact_mobile = $request->phone_no ?? '';
        $contact->communication_channel = $request->communication_channel ?? '';
        $contact->whatsapp_contact = $request->whatsapp_contact ?? '';
        $contact->hear_about_us = $request->hear_about_us ?? '';
        $contact->preferred_time = $request->preferred_time ?? now();
        $contact->description = $request->description ?? '';
        $contact->save();
    }

    public function getContactBySlug($slug){
        return Contact::where('slug', $slug)->first();
    }

    public function getContactById($id){
        return Contact::find($id);
    }

    public function getContactsByTenantId($tenant_id){
        return Contact::where('tenant_id', $tenant_id)->orderBy('company_name', 'ASC')->get();
    }

    public function getContactArrayById($ids){
        return Contact::whereIn('id', $ids)->get();
    }

    public function getTenantContacts($tenant_id){
        return Contact::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
    }
}
