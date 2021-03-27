<?php

namespace DownGrade\Http\Controllers\Auth;

use DownGrade\User;
use DownGrade\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use DownGrade\Models\Members;
use DownGrade\Models\Settings;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
	public function register(Request $request)
    {
         
		 $name = $request->input('name');
		 $username = $request->input('username');
         $email = $request->input('email');
		 $user_type = $request->input('user_type');
		 $password = bcrypt($request->input('password'));
		 if(!empty($request->input('earnings')))
		 {
		 $earnings = $request->input('earnings');
         }
		 else
		 {
		   $earnings = 0;
		 }
		 
		
		$request->validate([
							'name' => 'required',
							'username' => 'required',
							'email' => 'required|email',
							'password' => ['required', 'min:6'],
							'g-recaptcha-response' => 'required|captcha',
							
							
         ]);
		 $rules = array(
				'username' => ['required', 'regex:/^[\w-]*$/', 'max:255', Rule::unique('users') -> where(function($sql){ $sql->where('drop_status','=','no');})],
				'email' => ['required', 'email', 'max:255', Rule::unique('users') -> where(function($sql){ $sql->where('drop_status','=','no');})],
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
		
		  $verified = 0;
		  $user_token = $this->generateRandomString();
		 
		$data = array('name' => $name, 'username' => $username, 'email' => $email, 'user_type' => $user_type, 'password' => $password, 'earnings' => $earnings, 'verified' => $verified, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'user_token' => $user_token);
		
		
		$sid = 1;
	    $setting['setting'] = Settings::editGeneral($sid);
		$from_name = $setting['setting']->sender_name;
        $from_email = $setting['setting']->sender_email;
		
		Members::insertData($data);
			
		Mail::send('register_mail', $data, function($message) use ($from_name, $from_email, $email, $name, $user_token) {
			$message->to($email, $name)
					->subject('Email Confirmation For Registration');
			$message->from($from_email,$from_name);
		});
 
            
          
		  return redirect('login')->with('success','We sent you an activation code. Check your email and click on the link to verify.');
		  
		 }

       

        
    }
	
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'username' => ['required', 'regex:/^[\w-]*$/', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
			'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \DownGrade\User
     */
	public function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    } 
	 
    protected function create(array $data)
    {
	    
		 
        $token = $this->generateRandomString();
		return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
			'username' => $data['username'],
            'password' => Hash::make($data['password']),
			'user_token' => $token,
			'earnings' => 0,
			'user_type' => $data['user_type'],
			'g-recaptcha-response' => 'required|captcha',
			    
        ]);
    }
}
