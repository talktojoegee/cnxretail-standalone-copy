<?php

namespace App\Http\Controllers;

use App\Models\MailchimpSettings;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use MailchimpMarketing\ApiClient;
use Throwable;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->mailchimpsetting = new MailchimpSettings();
    }

    public function mailChimpConfig(){
        $setting = $this->mailchimpsetting->getTenantMailChimpFirstApiKey();
        if(!empty($setting)) {
            $mailchimp = new ApiClient();
            $server = substr($setting->mailchimp_api_key, 33);
            $mailchimp->setConfig([
                'apiKey' => $setting->mailchimp_api_key,
                'server' => $server
            ]);
            return $mailchimp;
        }else{
            return $mailchimp = new \stdClass();
        }
    }

    public function manageCampaigns(){
        $empty = (object) ['campaigns' => []];
        $mailchimp = $this->mailChimpConfig();
        if (! $mailchimp instanceof ApiClient) {
            session()->flash('error', 'Add a Mailchimp API key in Settings, then open this page again.');

            return view('campaign.manage-campaigns', ['request' => $empty]);
        }

        try {
            $request = $mailchimp->campaigns->list();

            return view('campaign.manage-campaigns', ['request' => $request]);
        } catch (Throwable $ex) {
            session()->flash('error', $this->describeMailchimpError($ex));

            return view('campaign.manage-campaigns', ['request' => $empty]);
        }
    }

    public function viewCampaign($web_id){
        $mailchimp = $this->mailChimpConfig();
        if((array)$mailchimp){
            try {
                $response = $mailchimp->campaigns->get($web_id);
                //return $response->settings->subject_line;
                //return $response;
                return view('campaign.view-campaign',['campaign'=>$response]);
            }catch (\Exception $ex){
                session()->flash("error", "Whoops! We couldn't establish connection to the remote server. Try again.");
                return back();
            }
        }
    }

    public function manageAudiences(){
        $empty = (object) ['lists' => []];
        $mailchimp = $this->mailChimpConfig();
        if (! $mailchimp instanceof ApiClient) {
            session()->flash('error', 'Add a Mailchimp API key in Settings, then open this page again.');

            return view('campaign.manage-audiences', ['audiences' => $empty]);
        }

        try {
            $audiences = $mailchimp->lists->getAllLists();

            return view('campaign.manage-audiences', ['audiences' => $audiences]);
        } catch (Throwable $ex) {
            session()->flash('error', $this->describeMailchimpError($ex));

            return view('campaign.manage-audiences', ['audiences' => $empty]);
        }
    }

    private function describeMailchimpError(Throwable $ex): string
    {
        if ($ex instanceof RequestException && $ex->getResponse()) {
            $body = (string) $ex->getResponse()->getBody();
            if (str_contains($body, 'User Disabled')) {
                return 'Mailchimp refused this account because it is disabled. Campaigns and audiences stay empty until that Mailchimp account is active again.';
            }
        }

        return "Mailchimp could not be reached. Check the API key in Settings and try again.";
    }

    public function viewAudience($id){
        $mailchimp = $this->mailChimpConfig();
        if((array)$mailchimp){
            try {
                $response = $mailchimp->lists->getList($id);
                //return $response;
                return view('campaign.view-audience',['audience'=>$response]);
            }catch (\Exception $ex){
                session()->flash("error", "Whoops! We couldn't establish connection to the remote server. Try again.");
                return back();
            }
        }
    }

    public function showAddNewAudienceForm(){
        return view('campaign.add-new-audience');
    }
}
