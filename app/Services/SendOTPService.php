<?php

namespace App\Services;

use Twilio\Rest\Client;

class SendOTPService
{
    public static function sendOTP($phone, $otp)
    {
        $twilio = new Client(env("ACCOUNT_SID"),env('ACCOUNT_AUTH_TOKEN'));
        $twilio->messages->create("$phone", [
            'from' => env("ACCOUNT_PHONE"),
            'body' => "Your OTP is: $otp"
        ]);
    }
}
