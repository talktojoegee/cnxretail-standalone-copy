<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
        $this->user = new User();
        $this->tenant = new Tenant();
        $this->adminnotification = new AdminNotification();
    }


    public function  createAccount(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name'=>'required',
            'first_name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'terms'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code'=> 400,
                'message' => $validator->errors()->first(),
                'data'=>""
            ]);
        }

        try {
            $tenant = $this->tenant->setNewTenant($request);
            $this->user->setNewUser($request, $tenant);

            $subject = "New registration";
            $body = "There's a new registration to CNX Retail. Kindly check it out.";
            $this->adminnotification->setNewAdminNotification($subject, $body, 'view-tenant', $tenant->slug, 1, 0);

            // Success Response
            return response()->json([
                'success'=> true,
                'code'=> 200,
                'message' => "Account Created!",
                'data'=>''
            ]);
        }
        catch (\Exception $exception){
            return response()->json([
                'success'=> false,
                'code'=> 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data'=>''
            ]);
        }

    }

}
