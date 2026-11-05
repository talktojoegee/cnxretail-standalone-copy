<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Reminder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->contact = new Contact();
        $this->conversation = new Conversation();
        $this->reminder = new Reminder();
    }

    public function allContacts(Request $request){
        return $this->listByType($request, 0);
    }

    public function allLeads(Request $request){
        return $this->listByType($request, 1);
    }

    public function allDeals(Request $request){
        return $this->listByType($request, 2);
    }

    private function listByType(Request $request, int $type)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ], [
            'to.after_or_equal' => 'The end date must be on or after the start date.',
        ]);

        $perPage = (int) $request->input('per_page', 10);
        $scoped = $this->scopedContacts($request, $type);

        $contacts = (clone $scoped)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => (clone $scoped)->count(),
            'this_month' => (clone $scoped)->where('created_at', '>=', now()->startOfMonth())->count(),
            'with_phone' => (clone $scoped)->where(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->whereNotNull('company_phone')->where('company_phone', '!=', '');
                })->orWhere(function (Builder $query) {
                    $query->whereNotNull('contact_mobile')->where('contact_mobile', '!=', '');
                });
            })->count(),
            'with_email' => (clone $scoped)->where(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->whereNotNull('company_email')->where('company_email', '!=', '');
                })->orWhere(function (Builder $query) {
                    $query->whereNotNull('contact_email')->where('contact_email', '!=', '');
                });
            })->count(),
        ];

        return view('contacts.index', [
            'contacts' => $contacts,
            'stats' => $stats,
            'type' => $type,
        ]);
    }

    private function scopedContacts(Request $request, int $type): Builder
    {
        return Contact::query()
            ->where('tenant_id', Auth::user()->tenant_id)
            ->where('contact_type', $type)
            ->when($request->filled('from'), function (Builder $query) use ($request) {
                $query->whereDate('created_at', '>=', $request->input('from'));
            })
            ->when($request->filled('to'), function (Builder $query) use ($request) {
                $query->whereDate('created_at', '<=', $request->input('to'));
            })
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $term = '%'.$request->input('q').'%';
                $query->where(function (Builder $query) use ($term) {
                    $query->where('contact_first_name', 'like', $term)
                        ->orWhere('contact_last_name', 'like', $term)
                        ->orWhere('company_name', 'like', $term)
                        ->orWhere('company_phone', 'like', $term)
                        ->orWhere('contact_mobile', 'like', $term)
                        ->orWhere('company_email', 'like', $term)
                        ->orWhere('contact_email', 'like', $term);
                });
            });
    }

    public function showAddNewContactForm(){
        return view('contacts.add-new-contact');
    }

    public function saveContact(Request $request){
        $this->validate($request,[
           'company_name'=>'required',
           'company_phone_no'=>'required',
           'company_email'=>'required',
           //'website'=>'required',
           'address'=>'required',
           'first_name'=>'required',
           'phone_no'=>'required',
           'last_name'=>'required',
           'email'=>'required',
           //'position'=>'required',
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
        $this->contact->setNewContact($request);
        session()->flash("success", "Your new contact was registered successfully.");
        return back();
    }


    public function viewContact($slug){
        $contact = $this->contact->getContactBySlug($slug);
        if(!empty($contact)){
            return view('contacts.view',['contact'=>$contact]);
        }else{
            session()->flash("error", "Whoops! Record not found");
            return back();
        }
    }


    public function showEditContactForm($slug){
        $contact = $this->contact->getContactBySlug($slug);
        if(!empty($contact)){
            return view('contacts.edit-contact',['contact'=>$contact]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function editContact(Request $request){
        $this->validate($request,[
            'company_name'=>'required',
            'company_phone_no'=>'required',
            'company_email'=>'required',
            'address'=>'required',
            'first_name'=>'required',
            'phone_no'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'contact'=>'required'
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
        $this->contact->updateContact($request);
        session()->flash("success", "Your changes were saved successfully.");
        return back();
    }

    public function newConversation(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'conversation'=>'required',
            'contact_id'=>'required',
            //'remind_at'=>'required|date'
        ],[
            'subject.required'=>'Enter a subject for this conversation',
            'conversation.required'=>'What was the conversation about?',
            //'remind_at.required'=>"This field is required"
        ]);
        $this->conversation->setNewConversation($request);
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
        session()->flash("success", "Your conversation was recorded.");
        return back();
    }
}
