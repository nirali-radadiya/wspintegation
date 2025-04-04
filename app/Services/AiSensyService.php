<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AiSensyService
{
    protected $client;
    protected $apiKey;
    protected $projectId;
    protected $templateName;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('AISENSY_API_KEY');
        $this->projectId = env('AISENSY_PROJECT_ID');
        $this->templateName = env('AISENSY_TEMPLATE_NAME');
    }

    public function sendOtp($mobile, $otp)
    {
        try {
            $phone = '+918128639045';
            $cleanPhone = ltrim($phone, '+');
            $response = $this->client->post("https://backend.aisensy.com/campaign/t1/api/v2/sendTemplateMessage", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->apiKey
                ],
                'json' => [
                    'projectID' => $this->projectId,
                    'templateName' => $this->templateName,
                    'destination' => [$cleanPhone],
                    'parameters' => [
                        ["name" => "otp_code", "value" => $otp]
                    ]
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('AiSensy WhatsApp OTP Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
