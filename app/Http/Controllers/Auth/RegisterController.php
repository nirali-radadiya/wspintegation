<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Role;
use App\Models\User;
use App\Services\SendOTPService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'phone' => ['required','regex:/^\+\d{10,15}$/'],
            'role_id' => ['nullable'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'otp' => ['nullable'],
            'otp_expires_at' => ['nullable'],
        ],[
            'phone.regex' => 'Phone number must be in international format (e.g., +1234567890).',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $otp = rand(100000, 999999);
        $hashedOtp = Hash::make($otp);
        $phone = $data['phone'];

        try{
            SendOTPService::sendOTP($phone, $otp);
        }catch(\Exception $e){
            throw new \Exception("OTP could not be sent: " . $e->getMessage());
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role_id' => Role::first()->id,
            'password' => Hash::make($data['password']),
            'otp' => $hashedOtp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);
    }

    public function register(Request $request)
    {
        try {
            $user = $this->create($request->all());
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
