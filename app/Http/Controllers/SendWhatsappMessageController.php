<?php

namespace App\Http\Controllers;

use App\Models\SendWhatsappUser;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

class SendWhatsappMessageController extends Controller
{
    public function index()
    {
        return view('send_whatsapp_message');
    }

    public function sendWhatsAppMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|regex:/^\+\d{10,15}$/',
            'address' => 'required',
        ]);

      /*  $apiKey = env('AISENSY_API_KEY');
        $recipientPhone = $request->phone;
        $message = "Hello " . $request->name . ", your registration is successful!";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://backend.aisensy.com/campaign/t1/api/send', [
            'mobile' => $recipientPhone,
            'template_id' => 'YOUR_TEMPLATE_ID', // Replace with actual template ID
            'parameters' => [
                ['name' => 'name', 'value' => $request->name],
                ['name' => 'message', 'value' => $message],
            ],
        ]);

        if ($response->successful()) {
            return back()->with('success', 'WhatsApp message sent successfully.');
        } else {
            return back()->with('error', 'Failed to send WhatsApp message: ' . $response->body());
        }*/

//        $recipientPhone = 'whatsapp:' . $request->phone;
        $recipientPhone = 'whatsapp:' . '+918128639045';
        $message = "Hello " . $request->name . ", your registration is successful!";

        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $message = $twilio->messages->create($recipientPhone, [
                'from' => env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]);

            dd($message);

            SendWhatsappUser::create([
                'name' => $request->phone,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            return back()->with('success', 'WhatsApp message sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}
