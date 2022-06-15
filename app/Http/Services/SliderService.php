<?php
namespace  App\Http\Services;
use App\Models\CreateHomeSlidersTable;
use App\Models\UserTokens;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Validator;
use Mail;
use JWTAuth;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class SliderService{
     public function testfucntion()
     {
        return view('admin.slider.index');
     }
    

}

