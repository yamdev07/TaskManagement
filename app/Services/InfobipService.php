<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InfobipWhatsappTemplateService
{
    protected $baseUrl;
    protected $apiKey;
    protected $sender;
    protected $template;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.infobip.base_url'), '/') . '/whatsapp/1/message/template';
        $this->apiKey = config('services.infobip.api_key');
        $this->sender = config('services.infobip.sender');
        $this->template = config('services.infobip.template_name');
    }

    public function sendTemplate($to, $placeholder = 'client')
    {
        $payload = [
            'messages' => [
                [
                    'from' => $this->sender,
                    'to' => $to,
                    'messageId' => uniqid(), // unique ID
                    'content'    => [
                        'templateName' => $this->template,
                        'templateData' => [
                            'body' => [
                                'placeholders' => [$placeholder]
                            ]
                        ],
                        'language' => 'en',
                    ],
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl, $payload);

        return $response->json();
    }
}
