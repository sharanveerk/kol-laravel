<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Mail;
use JWTAuth;
use App\Models\UserTokens;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\AuthController;
use App\Http\Services\UserService;
class ResetPasswordController extends Controller
{
    //
    private $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function forgetPassword(Request $request)
    {

        $response = $this->userService->forgetPassword($request);
        return $response;
    }

    public function varifyResetpassword(Request $request)
    {
        $response = $this->userService->varifyResetpassword($request);
        return $response;
         }

         public function changePassword(Request $request )
         {  
            $response = $this->userService->changePassword($request);
            return $response; 

         }


         public function resetPassword(Request $request)
         {
             
            $response = $this->userService->resetPassword($request);
            return $response; 

            }
         

}
