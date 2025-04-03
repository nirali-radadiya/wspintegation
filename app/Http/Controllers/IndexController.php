<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;

class IndexController extends Controller
{

    public function showRegisterForm()
    {
        if(Session::get('user_id')){
            return view('verify-otp');
        }else{
            return view('register');
        }
    }

    public function register(Request $request)
    {
        if($request->otp_method == User::OTP_METHOD_SMS || $request->otp_method == User::OTP_METHOD_WHATSAPP){
            $request->validate([
                'phone' => 'required|regex:/^\+\d{10,15}$/',
            ],[
                'phone.regex' => 'Phone number must be in international format (e.g., +1234567890).',
            ]);
        }elseif($request->otp_method == User::OTP_METHOD_EMAIL){
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);
        }

        $otp = rand(100000, 999999);
        $hashedOtp = Hash::make($otp);

        $user = User::create([
            'phone' => $request->phone ?? null,
            'otp_method' => $request->otp_method,
            'email' => $request->email ?? null,
            'otp' => $hashedOtp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        Session::put('user_id', $user->id);

        if($request->otp_method == User::OTP_METHOD_SMS){
            $this->sendOTPViaSMS($request->phone, $otp);
        }elseif($request->otp_method == User::OTP_METHOD_EMAIL){
            $this->sendOTPViaEmail($request->email, $otp);
        } elseif($request->otp_method == User::OTP_METHOD_WHATSAPP){
            $this->sendOTPViaWhatsApp($request->phone, $otp);
        }

        return redirect()->route('otp.verify')->with('success', 'OTP sent via ' . ucfirst(User::OTP_METHOD_ARR[$request->otp_method]));
    }

    private function sendOTPViaSMS($phone, $otp)
    {
        $sid = env('ACCOUNT_SID');
        $token = env('ACCOUNT_AUTH_TOKEN');
        $from = env('ACCOUNT_PHONE');

        $twilio = new Client($sid, $token);
        $twilio->messages->create($phone, [
            'from' => $from,
            'body' => "Your OTP is: $otp"
        ]);
    }

    private function sendOTPViaEmail($email, $otp)
    {
        $user = User::where('email', $email)->first();

        $user->notify(new SendOtpNotification($otp));
    }

    private function sendOTPViaWhatsApp($phone, $otp)
    {
        $sid = env('ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER');

        $twilio = new Client($sid, $token);
        $twilio->messages->create("whatsapp:$phone", [
            'from' => $from,
            'body' => "Your OTP is: $otp"
        ]);
    }

    public function showOtpForm()
    {
        return view('verify-otp');
    }

    /**
     * Verify the OTP entered by the user.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('register.form')->with('error', 'Session expired. Please register again.');
        }

        $user = User::where('id', $userId)
            ->where('otp_expires_at', '>', now())
            ->first();

        if ($user) {
            if (Hash::check($request->otp, $user->otp)) {
                Session::forget('user_id');
                $user->update(['otp' => null, 'otp_expires_at' => null]);
                return redirect()->route('register')->with('success', 'Account verification successfully');
            } else {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    /**
     * Resend OTP if the user didn't receive it.
     */
    public function resendOtp()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('register.form')->with('error', 'Session expired. Please register again.');
        }

        // Generate a new OTP
        $otp = rand(100000, 999999);
        $hashedOtp = Hash::make($otp);
        $user = User::where('id',$userId)->first();
        $user->update([
          'otp' => $hashedOtp,
          'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);


        if($user->otp_method == User::OTP_METHOD_SMS){
            $this->sendOTPViaSMS($user->phone, $otp);
        }elseif($user->otp_method == User::OTP_METHOD_EMAIL){
            $this->sendOTPViaEmail($user->email, $otp);
        } elseif($user->otp_method == User::OTP_METHOD_WHATSAPP){
            $this->sendOTPViaWhatsApp($user->phone, $otp);
        }

        return back()->with('success', 'New OTP sent to your '.ucfirst(User::OTP_METHOD_ARR[$user->otp_method]));
    }
}
