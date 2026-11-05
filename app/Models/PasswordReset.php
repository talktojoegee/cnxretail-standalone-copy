<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PasswordReset extends Model
{
    use HasFactory;

    public $timestamps = false;
    /*
     * Use-case methods
     */
    public function setNewPasswordResetRequest(Request $request){
        $record = $this->checkForExistingRequest($request->email);
        if(empty($record)){
            return $this->createNewRecord($request);
        }else{
            PasswordReset::where('email', $request->email)->delete();
            return $this->createNewRecord($request);
        }

    }

    public function checkForExistingRequest($email){
        return PasswordReset::where('email', $email)->first();
    }

    public function createNewRecord(Request $request){
        $token = $this->createToken();
        $reset = new PasswordReset();
        $reset->token = $token;
        $reset->email = $request->email;
        $reset->created_at = now();
        $reset->save();
        return $token;
    }

    public function createToken(){
        return substr(sha1(time()),13,40);
    }

    public function getPasswordResetRequestByToken($token){
        return PasswordReset::where('token', $token)->first();
    }
}
