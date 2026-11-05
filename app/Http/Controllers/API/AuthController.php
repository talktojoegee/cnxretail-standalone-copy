<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pricing;
use App\Models\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->user = new User();
        $this->tenant = new Tenant();
    }

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
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

        //Request is validated
        try {
            //throw new \Exception("Some error message");
            //Create token
            //$token =  auth('api')->attempt($credentials);//attempt($credentials);
            $myTTL = 10080; //minutes
            JWTAuth::factory()->setTTL($myTTL);
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Login credentials are invalid.",
                    'data' => ''
                ]);
            }
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Could not create token",
                'data' => ''
            ]);
        }

        $users = $this->user->getAllTenantUsersByTenantId(Auth::user()->tenant_id);
        if(strtotime(now())  > strtotime(Auth::user()->getTenant->end_date)){
            foreach($users as $user){
                $user->account_status = 0; //inactive
                $user->save();
            }
            JWTAuth::invalidate($token);

            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => "Renew Subscription",
                'data' => ''
            ]);

        }else{
            foreach($users as $user){
                $user->account_status = 1; //active
                $user->save();
            }
            //Token created, return with success response and jwt token
            $auth_user = Auth::user();
            $auth_user->profile_image = url("/assets/drive/" . $auth_user->avatar);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Login successful",
                'data' => ["token" => $token, 'user' => $auth_user]
            ]);
        }

    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
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
        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'User has been logged out',
                'data' => ""
            ]);
        } catch (JWTException $exception) {

            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Sorry, user cannot be logged out',
                'data' => ""
            ]);

        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::setToken($request->token)->authenticate();
        return response()->json(['user' => $user]);
    }
}
