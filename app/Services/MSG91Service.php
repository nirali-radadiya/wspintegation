<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MSG91Service
{
    protected $client;
    protected $authKey;
    protected $senderId;
    protected $route;
    protected $country;
    protected $templateId;

    public function __construct()
    {
        $this->client = new Client();
        $this->authKey = env('MSG91_AUTH_KEY');
        $this->senderId = env('MSG91_SENDER_ID');
        $this->route = env('MSG91_ROUTE');
        $this->country = env('MSG91_COUNTRY', 91);
        $this->templateId = env('MSG91_TEMPLATE_ID');
    }

    public function sendSms($mobile, $message)
    {
        try {
            $response = $this->client->post('https://control.msg91.com/api/v5/flow/', [
                'headers' => [
                    'authkey' => $this->authKey,
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'template_id' => $this->templateId,
                    'sender' => $this->senderId,
                    'route' => $this->route,
                    'country' => $this->country,
                    'sms' => [
                        [
                            'message' => $message,  // This should match the template
                            'to' => [$mobile],
                        ],
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('MSG91 SMS Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
