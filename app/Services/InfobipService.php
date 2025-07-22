<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InfobipService
{
    protected $baseUrl;
    protected $token;
    protected $sender;

    public function __construct()
    {
        $this->baseUrl = config('services.infobip.base_url');
        $this->token = config('services.infobip.token');
        $this->sender = config('services.infobip.sender');
    }

    public function sendWhatsAppTemplate(string $to, string $nom): bool
    {
        $payload = [
            "messages" => [
                [
                    "from" => $this->sender,
                    "to" => $to,
                    "messageId" => Str::uuid()->toString(),
                    "content" => [
                        "templateName" => "test_whatsapp_template_en",
                        "templateData" => [
                            "body" => [
                                "placeholders" => [$nom]
                            ]
                        ],
                        "language" => "en"
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'App ' . $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/whatsapp/1/message/template", $payload);

        // Debug temporaire pour afficher le problème
        dd([
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        return $response->successful();
    }

    public function sendSms(string $to, string $message): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'App ' . $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/sms/2/text/advanced", [
            "messages" => [
                [
                    "from" => $this->sender,
                    "destinations" => [
                        ["to" => $to]
                    ],
                    "text" => $message
                ]
            ]
        ]);

        return $response->successful();
    }
}
