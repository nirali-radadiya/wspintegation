<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSendWhatsappRequest;
use App\Models\SendWhatsappUser;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

class SendWhatsappMessageController extends Controller
{
    public function showForm()
    {
        return view('send_whatsapp_message');
    }

    public function sendWhatsAppMessage(StoreSendWhatsappRequest $request)
    {
        $validatedData = $request->validated();

        $data = $validatedData;

        $recipientPhone = 'whatsapp:' . $data['phone'];
        $message = "Hello " . $data['name'] . ", your registration is successful!";

        try {
            $twilio = new Client(env('ACCOUNT_SID'), env('ACCOUNT_AUTH_TOKEN'));
            $message = $twilio->messages->create($recipientPhone, [
                'from' => env('ACCOUNT_WHATSAPP_FROM'),
                'body' => $message
            ]);

            SendWhatsappUser::create($data);

            return back()->with('success', 'WhatsApp message sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}
