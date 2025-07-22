<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $twilio;
    protected $from;
    protected $whatsappFrom;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->from = config('services.twilio.from');
        $this->whatsappFrom = config('services.twilio.whatsapp_from');
    }

    /**
     * Envoyer un SMS
     */
    public function sendSMS($to, $message)
    {
        try {
            // Formater le numéro (ajouter +229 si nécessaire)
            $to = $this->formatPhoneNumber($to);
            
            $message = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            Log::info('SMS envoyé avec succès', [
                'to' => $to,
                'sid' => $message->sid
            ]);

            return [
                'success' => true,
                'sid' => $message->sid,
                'message' => 'SMS envoyé avec succès'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer un message WhatsApp
     */
    public function sendWhatsApp($to, $message)
    {
        try {
            // Formater le numéro pour WhatsApp
            $to = 'whatsapp:' . $this->formatPhoneNumber($to);
            
            $message = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->whatsappFrom,
                    'body' => $message
                ]
            );

            Log::info('WhatsApp envoyé avec succès', [
                'to' => $to,
                'sid' => $message->sid
            ]);

            return [
                'success' => true,
                'sid' => $message->sid,
                'message' => 'Message WhatsApp envoyé avec succès'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur envoi WhatsApp', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formater le numéro de téléphone
     */
    private function formatPhoneNumber($number)
    {
        // Nettoyer le numéro
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Ajouter +229 si le numéro fait 8 chiffres (Bénin)
        if (strlen($number) === 8) {
            $number = '+229' . $number;
        } elseif (strlen($number) === 11 && substr($number, 0, 3) === '229') {
            $number = '+' . $number;
        } elseif (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }
        
        return $number;
    }

    /**
     * Vérifier le statut d'un message
     */
    public function getMessageStatus($messageSid)
    {
        try {
            $message = $this->twilio->messages($messageSid)->fetch();
            return [
                'success' => true,
                'status' => $message->status,
                'error_code' => $message->errorCode,
                'error_message' => $message->errorMessage
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}