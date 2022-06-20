<?php
namespace  App\Http\Services;
    use App\Models\User;
    use App\Models\UserTokens;
    use App\Models\UserAddress;
    use App\Http\Controllers\MailController;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use Crypt;
    use Illuminate\Http\Request;
    use Validator;
    use Mail;
    use JWTAuth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Mail\Mailable;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Hash;
    class UserService{

        public function getAllUser(){
            $allUserData = User::all();
            return $allUserData ;
        }

        public function createUser($request,$otp){
        
            // Create new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->otp = $otp;
            $user->role_id = $request['role_id'];
            $user->firebase_token = ($request['firebase_token']) ? $request['firebase_token'] : NULL;
            $checkUserSave = $user->save(); 
            $lastUserId = $user->id;
            if($checkUserSave){
                //Generate jwt token
                $token = '';
                if($otp == null){
                    $customClaims = ['name'=>$request['name'],'email'=>$request['email'], 'role_id'=>$request['role_id'],'firebase_token' =>$request['firebase_token']];
                    $input = $request->only('email', 'password');
                    $token = JWTAuth::claims($customClaims)->attempt($input);
                }else{
                    $input = $request->only('email', 'password');
                    $token = JWTAuth::attempt($input);
                    dd($token); 
                    Mail::to($request->email)->send(new \App\Mail\VerifyMail(["url"=>$otp]));
                }
                // // Store token in user tokens table
                $saveToken = new UserTokens();
                $saveToken->user_id = $lastUserId;
                $saveToken->otp = $otp;
                $saveToken->token = $token;
                $saveToken->save();
                return $token;
            }

        }

        public function generateJwtToken($checkEmail){

            $customClaims = ['name'=>$checkEmail['name'],'email'=>$checkEmail['email'], 'role_id'=>$checkEmail['role_id'],'firebase_token' =>$checkEmail['firebase_token']];
            $input =[];
            $input['email']= $checkEmail['email'];
            $input['password']= null;
            // $input = $request->only('email', 'password');
            $token = JWTAuth::claims($customClaims)->attempt($input);
            if($token){
                $saveToken = new UserTokens();
                $saveToken->user_id = $checkEmail['id'];
                $saveToken->token = $token;
                $saveToken->save();
                return $token;
            }else{
                return false;
            }
        }

    
    
        public function checkEmail($email){
        return User::where('email',$email)->first();
        }

        public function sendVerificationCode($userId,$oldOtp,$request,$otp){

            $input = $request->only('email', 'password');
            $token = JWTAuth::attempt($input); 
            $updateUserOtp = User::where('id',$userId)->update(['otp'=>$otp]);
            if($updateUserOtp){
                $updateUserToken = UserTokens::where('user_id', $userId)->where('otp', $oldOtp)->update(['token'=>$token,'otp'=>$otp]);
                Mail::to($request->email)->send(new \App\Mail\VerifyMail(["url"=>$otp]));
                return $updateUserOtp;
            }
        }
        
        public function checkOtp($request){
            return User::where('otp', $request['otp'])->where('email',$request['email'])->first();
        }

        public function makeUserVerifiy($otp){
            return User::where('otp', $otp)->update(['is_varified'=>1]);
        }
        
        public function verifyEmailOtp($otp,$userId){

            return UserTokens::select('token')->where('user_id', $userId)->where('otp',$otp)->first();
        }
        
        public function insertNewOtp($otp,$userId,$oldOtp,$email){
            $check = UserTokens::where('otp',$oldOtp)->first();
            if($check){
                $updateResponse = User::where('id',$userId)->update(['otp'=>$otp]);
                if($updateResponse){
                    UserTokens::where('token',$check['token'])->update(['otp'=>$otp]);
                    Mail::to($email)->send(new \App\Mail\VerifyMail(["url"=>$otp]));
                    return $updateResponse;
                }
            }
        
        }
        
        public function checkPassword($password){
            return User::where('password', Hash::make($password));
        }

        public function updatePassword($request,$userId,$email){
            $updatePass = User::where('email',$email)->update(['password'=>Hash::make($request['new_password'])]);
            if($updatePass){
                // $input = $request->only('email', 'new_password');
                $input = [];
                $input['email'] = $email;
                $input['password'] = $request['new_password'];
                $token = JWTAuth::attempt($input); 
                // // Store token in user tokens table
                $saveToken = new UserTokens();
                $saveToken->user_id = $userId;
                $saveToken->token = $token;
                $saveToken->save();
                return $token;
            }else{
                return false;
            }
        }

        public function getUserById($userId){
            return User::where('id', $userId)->first();
        }

        public function userLogin($request,$oldOtp,$userId){
            if(!Hash::check($request['password'], $oldOtp)){
                return false;
            }else{
                $input = $request->only('email', 'password');
                $token = JWTAuth::attempt($input); 
                $saveToken = new UserTokens();
                $saveToken->user_id = $userId;
                $saveToken->token = $token;
                $saved= $saveToken->save();
                return $token;
            }
        }

        // public function updateUserOtp($email,$otp){

        //     $updateResponse = User::where('')
            
        // }

        public function forgetPassword($request){
            $checkEmail = User::select('email','id','is_varified')->where('email', $request['email'])->first();
            if($checkEmail){
                if ($checkEmail['is_varified']==1) {
                    $otp = rand(100000,999999);
                    User::where('id', $checkEmail['id'])->update(['password_reset_code' => $otp]);
                    $user = [ 'url' =>$otp];
                    $Email=Mail::to($request->email)->send(new \App\Mail\VerifyMail($user));
                    return "true";
                }else{
                    return false;
                }
            }else{
                return ;
            }
            
        }

            
        
        public function varifyResetpassword($request){
                $userDetails =  User::select('id','email','is_varified','password_reset_code')->where('password_reset_code',$request->verification_code)->first();
                if(!empty($userDetails)){
                    return response()->json([
                        'message' => 'reset password code verified',
                        'email' => $userDetails['email'],
                        'user_id' => $userDetails['id']
                    ], 200);
                }else{
                    return response()->json([
                        'message' => 'code not verified'
                    ], 200);
                }
        }
        
        public function changePassword($request)
            {
    
            $valdiation = Validator::make($request->all(),[
                
                'password' => 'required|min:6',
                'c_password'=>'required|same:password',]);
            
            if($valdiation->fails()){
            return response()->json($valdiation->errors(), 202);
        }
                User::where('id', $request->user_id)->update(['password' => Hash::make($request->password),'password_reset_code'=>NULL]);
    
                return response()->json(['message' => 'password updated successfully'],200);
            }

        public function updateData(Request $request)
        {  
            $header = $request->header('Authorization', '');
            if (Str::startsWith($header, 'Bearer ')) {
                $token = Str::substr($header, 7);
            }
        
            if($token){
                $user = Auth::user(); 
                $valdiation = Validator::make($request->all(),[
                    'name'=>'required',
                    'last_name'=>'required|nullable',
                    'profile_image' => 'required|nullable',
                    'phone'=>'string|max:13|',
                    'gender'=>'string',
                    'date_of_birth'=>'string',
                    'language'=>'string',
                    'whatsapp_chat_link' => 'required|nullable',
                    'telegram_id' => 'required|nullable',
                    'instagram_followers' => 'required|nullable',
                ]);
            
                if($valdiation->fails()){
                    return response()->json($valdiation->errors(), 202);
                }
                else{
                    $user = Auth::user();
            
            
                    $user = User::find(Auth::user()->id);
                    $user->name = $request->input('name');
                    $user->last_name = $request->input('last_name');
                    $user->gender = $request->input('gender');
                    $user->date_of_birth = $request->input('date_of_birth');
                    $user->language = $request->input('language');
                    $user->profile_image = $request->input('profile_image');
                    $user->description = $request->input('description');
                    $user->whatsapp_chat_link = $request->input('whatsapp_chat_link'); 
                    $user->telegram_id = $request->input('telegram_id');
                    $user->tiktok_followers = $request->input('tiktok_followers');
                    $user->instagram_followers = $request->input('instagram_followers');
            
            
                    $user->update();
                    return response()->json(['statusCode'=> 201,
                    'success'=> true,
                    'message'=> "record updated successfully",
                    'user'=>$user]);
                }
        }
    }

    public function addAdress($request){
    
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            $token = Str::substr($header, 7);
        if($token)
        {
            $user=Auth::user()->id;
            $id=$user;
        try{
        $data = new UserAddress();
        $data->user_id = $id;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->postal_code =$request->postal_code;
        $data->contry = $request->contry;
        $data->address_type = $request->address_type;
        $checkIfInserted = $data->save();
        return $checkIfInserted;
        }catch(Exception $e){
        throw new \Exception('Something Went Wrong, Please Try Again !');
        }
    }
    }
    }


        public function checkLogOut($request){

                // $header=$request->header();  
                $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            $token = Str::substr($header, 7);
        }
            
                if($token){
                    $expiredToken = UserTokens::select('token')->where('token', $token)->first();
                    if($expiredToken){
                        
                        UserTokens::where('token', $token)->delete();
                        return response()->json(['success' => true,'statusCode'=>200,'message' => 'User logged out successfully']);
                    }else{

                        return response()->json(['success' => false,'statusCode'=>true,'message' => 'Sorry, the user cannot be logged out']);
                    }        User::where('id', $request->user_id)->update(['password' => Hash::make($request->password),'password_reset_code'=>NULL]);

                    }
        }






            public function resetPassword($request){
                $header = $request->header('Authorization', '');
                if (Str::startsWith($header, 'Bearer ')) {
                    $token = Str::substr($header, 7);
                }
                // dd($token);
                if($token){
                    $valdiation = Validator::make($request->all(),[
                        'current_password'=> 'required|min:6',
                        'new_password' => 'required|min:6',
                        'confirm_new_password'=>'required|same:new_password',
                    ]
                );
                if($valdiation->fails()){
                    return response()->json($valdiation->errors(), 202);
                }
                User::select('password')->where('id', $request->user_id)->update(['password' => Hash::make($request->current_password)]);
                $currentUser = Auth::user();
            

                }
            }
            }