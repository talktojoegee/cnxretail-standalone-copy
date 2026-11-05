<?php

namespace App\Http\Controllers;

use App\Models\BulkMessage;
use App\Models\BulkSmsAccount;
use App\Models\Contact;
use App\Models\PhoneGroup;
use App\Models\Tenant;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yabacon\Paystack;
use Yabacon\Paystack\Fee;

class SMSController extends Controller
{
    public $api;
    public function __construct()
    {
        $this->api = "AFlh8eh7ALXfcRxt7RWshxUKt5BrMVtMyvtFfPXO19uQeb4GaNhpEIHHRerm";

        $this->middleware('auth');
        $this->phonegroup = new PhoneGroup();
        $this->bulksmsaccount = new BulkSmsAccount();
        $this->contact = new Contact();
        $this->bulkmessage = new BulkMessage();
        $this->tenant = new Tenant();

    }

    public function showPhoneGroupForm(){

        return view('sms.phone-group',[
            'groups'=>$this->phonegroup->getAllTenantPhoneGroup()
        ]);
    }

    public function setNewPhoneGroup(Request $request){
        $this->validate($request,[
            'group_name'=>'required|unique:phone_groups,group_name',
            'phone_numbers'=>'required'
        ],[
            'group_name.required'=>'Enter a phone group name',
            'group_name.unique'=>"There's an existing phone group with this name",
            'phone_numbers.required'=>'Enter phone numbers'
        ]);
       $filtered_numbers = [];
       $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
        for($i = 0; $i<count($phone_number_array); $i++){
            $number = $this->appendCountryCode($phone_number_array[$i]);
            array_push($filtered_numbers,$number);
        }
        $filter = array_unique($filtered_numbers);
        $phone_numbers = implode(",",$filter);
        $this->phonegroup->setNewPhoneGroup($request, $phone_numbers);
        session()->flash("success", "Your phone group was successfully published.");
        return back();
    }

    public function showTopUpForm(){
        return view('sms.top-up',[
            'transactions'=>$this->bulksmsaccount->getBulkSmsTransactions()
        ]);
    }

    public function processTopUpRequest(Request $request){
        $this->validate($request,[
            'units'=>'required',
        ],[
          'units.required'=>"Enter the number/quantity of units you'll want to buy."  ,
            //'units.min'=>"Whoops! The minimum number of units that can be bought is 500"
            ]);
        $units = str_replace(",", "", $request->units);
        //$settings = $this->tenant->getTenantPaymentGatewaySettings();
        //if(!empty($settings->secret_key) && !empty($settings->public_key)){
        try{
                $paystack = new Paystack(config('app.paystack_secret_key'));
                $cost = $units * 3;
                $builder = new Paystack\MetadataBuilder();
                $builder->withCost($cost);
                /*
                 * Transaction Type:
                 *  1 = New tenant subscription
                 *  2 = Subscription Renewal
                 *  3 = Invoice Payment
                 *  4 = SMS Top-up
                 */
                $builder->withTransaction(4);
                $metadata = $builder->build();
                $charge = ceil($cost*1.5)/100;
                $tranx = $paystack->transaction->initialize([
                    'amount'=>($cost+$charge)*100,       // in kobo
                    'email'=>Auth::user()->email,         // unique to customers
                    'reference'=>substr(sha1(time()),23,40), // unique to transactions
                    'metadata'=>$metadata
                ]);
                return redirect()->to($tranx->data->authorization_url)->send();
            }catch (Paystack\Exception\ApiException $exception){
                //print_r($exception->getResponseObject());
                //die($exception->getMessage());
                session()->flash("error", "Whoops! Something went wrong. Try again.");
                return back();
            }
        /*}else{
            session()->flash("error", "Whoops! Quickly setup your payment gateway settings");
            return redirect()->route('app-settings');
        }*/
    }

    public function showComposeMessageForm(){
        if(empty(Auth::user()->getTenant->sender_id)){
            session()->flash("error", "You'll need to quickly setup your <strong>sender ID</strong> in order to use this service. Click the <strong>Bulk SMS</strong> tab below.");
            return redirect()->route('app-settings');
        }else{
            return view('sms.compose-message',[
                'contacts'=>$this->contact->getContactsByTenantId(Auth::user()->tenant_id),
                'phonegroups'=>$this->phonegroup->getAllTenantPhoneGroup()
            ]);
        }
    }

    public function previewMessage(Request $request){
        $this->validate($request, [
            'message'=>'required'
        ],[
            'message.required'=>'Enter the content of your message in the box provided above.'
        ]);
        $list = [];
        if(empty($request->contact) && empty($request->phonegroup) && empty($request->phone_numbers)){
            session()->flash("error", "Whoops! Kindly select the source of your contact or enter phone numbers in the box provided; separating them with comma.");
            return back();
        }else{
            #Contacts
            if(!empty($request->contact)){
                $contacts = $this->contact->getContactArrayById($request->contact);
                foreach ($contacts as $contact){
                    array_push($list, $contact->company_phone);
                }
            }
            #Phonegroup
            if(!empty($request->phonegroup)){
                $phonegroups = $this->phonegroup->getPhoneGroupArrayById($request->phonegroup);
                $contact_array = [];
                foreach ($phonegroups as $phonegroup ){
                    $ex = explode(",", $phonegroup->phone_numbers);
                    for($i = 0; $i<count($ex); $i++){
                        array_push($list, $ex[$i]);
                    }
                }
            }
            #Phone numbers
            if(!empty($request->phone_numbers)){
                $filtered_numbers = [];
                $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
                for($i = 0; $i<count($phone_number_array); $i++){
                    $number = $this->appendCountryCode($phone_number_array[$i]);
                    array_push($list,$number);
                }
            }
            $filter = array_unique($list);
            $phone_numbers = implode(",",$filter);
            $persons = count($filter);
            $no_of_pages = round(strlen($request->message)/160) < 1 ? 1 : round(strlen($request->message)/160);
            $cost = round($no_of_pages*3*$persons);
            return view('sms.preview-message',[
                'cost'=>$cost,
                'persons'=>$persons,
                'transactions'=>$this->bulksmsaccount->getBulkSmsTransactions(),
                'pages'=>$no_of_pages,
                'message'=>$request->message,
                'phone_numbers'=>$phone_numbers
            ]);
        }

    }

    public function sendTextMessage(Request $request){
        $this->validate($request,[
            'message'=>'required',
            'cost'=>'required',
            'pages'=>'required',
            'persons'=>'required',
            'phone_numbers'=>'required'
        ]);
        try {
            $sender_id = Auth::user()->getTenant->sender_id;
            $units = round($request->persons);
            $url = "https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=".$this->api."&from=JAG&to=".$request->phone_numbers."&body=".$request->message."&dnd=2";
            $client = new Client();
            $response = $client->get($url);
            if($response->getStatusCode() == 200){
                $this->bulksmsaccount->debitAccount(substr(sha1(time()),27,40), $request->cost, $units);
                $this->bulkmessage->setNewMessage($request->message, $request->phone_numbers);
                session()->flash("success", "Your text message was sent successfully.");
                return redirect()->route('compose-message');
            }else{
                session()->flash("error", "Something went wrong. Please try again later or contact admin.");
                return redirect()->route('compose-message');
            }
        }catch (\Exception $exception){
            session()->flash("error", "Something went wrong. Please try again later or contact admin.");
            return redirect()->route('compose-message');
        }

    }

    public function getBulkMessages(){
        return view('sms.bulk-messages',[
            'messages'=>$this->bulkmessage->getTenantMessages()
        ]);
    }

    public function viewBulkMessage($slug){
        $message = $this->bulkmessage->getTenantMessageBySlug($slug);
        if(!empty($message)){
            return view('sms.view-bulk-sms', ['message'=>$message]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function checkFirstDigit($number){
        $digit = substr($number, 0,1);
        if($digit == '0'){
            return '0'; //first number starts with 0
        }elseif($digit == '+'){
            return '+';
        }else{
            return 's'; //some #
        }
    }

    public function appendCountryCode($number){
        $country_code = "234";
        $phone_no = "";
        $digit = $this->checkFirstDigit($number);
        $length = strlen($number);
        if($digit == '0'){
            #Remove the 0
            $stripped_phone = substr($number,1,$length - 1);
            $phone_no = $country_code.$stripped_phone;
            return $phone_no;
        }elseif( $digit == 's'){ //2348032404359, 08032889972, 7036005031, +2349023849871
            if(substr($number,0,3) == '234'){
                return $number;
            }else{
                return $country_code.$number;
            }
        }elseif($digit == '+'){
            return substr($number,1,$length-1);
        }
    }
}
