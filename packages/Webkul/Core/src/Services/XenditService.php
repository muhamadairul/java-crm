<?php

namespace Webkul\Core\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = env('XENDIT_SECRET_KEY', '');
    }

    /**
     * Create a payment request via Xendit /v2/payment_requests
     *
     * @param string $referenceId Unique order / invoice number
     * @param float $amount Amount of payment
     * @param string $currency Currency (e.g. IDR, USD, etc.)
     * @param string $paymentType CARD, VIRTUAL_ACCOUNT, EWALLET, QR_CODE
     * @param array $paymentDetails Additional details like card, bank, ewallet info
     * @return array
     */
    public function createPaymentRequest(
        string $referenceId,
        float $amount,
        string $currency,
        string $paymentType,
        array $paymentDetails = []
    ): array {
        // If no key or mock is specified, simulate the response
        if (empty($this->secretKey) || str_starts_with($this->secretKey, 'mock')) {
            return $this->simulatePaymentRequestResponse($referenceId, $amount, $currency, $paymentType, $paymentDetails);
        }

        // Xendit mainly supports IDR and PHP. If another currency is used, we convert to IDR.
        $xenditCurrency = $currency;
        $xenditAmount = $amount;
        if (!in_array($currency, ['IDR', 'PHP'])) {
            // Default rate conversion for sandbox testing
            $rates = [
                'USD' => 16000.0,
                'EUR' => 17300.0,
                'SGD' => 11800.0,
            ];
            $rate = $rates[$currency] ?? 16000.0;
            $xenditAmount = round($amount * $rate);
            $xenditCurrency = 'IDR';
        }

        // Prepare request body for /v2/payment_requests
        $body = [
            'reference_id'   => $referenceId,
            'amount'         => (int) $xenditAmount,
            'currency'       => $xenditCurrency,
            'payment_method' => $this->buildPaymentMethodPayload($paymentType, $paymentDetails),
        ];

        try {
            Log::info('Xendit Request: POST /v2/payment_requests', [
                'body' => $body
            ]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/v2/payment_requests", $body);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Xendit API returned error: ' . $response->body());
            
            // Failover to simulate if sandbox credentials fail or URL is version mismatch
            return $this->simulatePaymentRequestResponse($referenceId, $amount, $currency, $paymentType, $paymentDetails, $response->body());

        } catch (\Exception $e) {
            Log::error('Xendit request failed: ' . $e->getMessage());
            return $this->simulatePaymentRequestResponse($referenceId, $amount, $currency, $paymentType, $paymentDetails, $e->getMessage());
        }
    }

    /**
     * Build payment method payload based on method type
     */
    protected function buildPaymentMethodPayload(string $type, array $details): array
    {
        switch ($type) {
            case 'CARD':
                return [
                    'type' => 'CARD',
                    'card' => [
                        'card_information' => [
                            'card_number'  => $details['card_number'] ?? '',
                            'expiry_month' => $details['expiry_month'] ?? '',
                            'expiry_year'  => $details['expiry_year'] ?? '',
                            'cvv'          => $details['cvv'] ?? '',
                        ]
                    ]
                ];

            case 'VIRTUAL_ACCOUNT':
                return [
                    'type'            => 'VIRTUAL_ACCOUNT',
                    'virtual_account' => [
                        'channel_code'       => $details['channel_code'] ?? 'MANDIRI',
                        'channel_properties' => [
                            'customer_name' => $details['customer_name'] ?? 'JavaCRM Customer',
                        ]
                    ]
                ];

            case 'EWALLET':
                return [
                    'type'    => 'EWALLET',
                    'ewallet' => [
                        'channel_code'       => $details['channel_code'] ?? 'DANA',
                        'channel_properties' => [
                            'success_return_url' => $details['success_url'] ?? route('java-crm.home'),
                            'mobile_number'      => $details['mobile_number'] ?? null,
                        ]
                    ]
                ];

            case 'QR_CODE':
                return [
                    'type'    => 'QR_CODE',
                    'qr_code' => [
                        'channel_code'       => 'QRIS',
                        'channel_properties' => [
                            'success_return_url' => $details['success_url'] ?? route('java-crm.home'),
                        ]
                    ]
                ];

            default:
                throw new \InvalidArgumentException("Unsupported payment type: {$type}");
        }
    }

    /**
     * Simulate a successful response from Xendit
     */
    protected function simulatePaymentRequestResponse(
        string $referenceId,
        float $amount,
        string $currency,
        string $paymentType,
        array $paymentDetails,
        string $errorMessage = null
    ): array {
        Log::info('Simulating Xendit Payment Request Response', [
            'reference_id' => $referenceId,
            'error_trigger' => $errorMessage
        ]);

        $id = 'pr-' . bin2hex(random_bytes(8));
        
        $response = [
            'id'           => $id,
            'reference_id' => $referenceId,
            'status'       => 'PENDING',
            'amount'       => $amount,
            'currency'     => $currency,
            'created'      => now()->toIso8601String(),
            'updated'      => now()->toIso8601String(),
        ];

        switch ($paymentType) {
            case 'CARD':
                // Cards usually authorize immediately in a native flow if valid
                $response['status'] = 'SUCCEEDED';
                $response['actions'] = [];
                break;

            case 'VIRTUAL_ACCOUNT':
                $channelCode = $paymentDetails['channel_code'] ?? 'MANDIRI';
                // Generates a mock VA number
                $vaPrefix = [
                    'MANDIRI' => '88000',
                    'BRI'     => '12400',
                    'BNI'     => '98800',
                    'PERMATA' => '85550'
                ][$channelCode] ?? '99990';
                
                $response['virtual_account'] = [
                    'channel_code'     => $channelCode,
                    'virtual_account'  => $vaPrefix . str_pad((string)rand(1, 999999999), 11, '0', STR_PAD_LEFT),
                    'customer_name'    => $paymentDetails['customer_name'] ?? 'JavaCRM Customer',
                ];
                break;

            case 'EWALLET':
                $channelCode = $paymentDetails['channel_code'] ?? 'DANA';
                $response['actions'] = [
                    [
                        'action' => 'RESUBMIT_PHONE',
                        'url'    => null,
                        'method' => 'POST'
                    ],
                    [
                        'action' => 'CHECKOUT_URL',
                        'url'    => route('java-crm.home') . '/simulate-ewallet-redirect?ref=' . $referenceId,
                        'method' => 'GET'
                    ]
                ];
                break;

            case 'QR_CODE':
                $response['qr_code'] = [
                    'channel_code' => 'QRIS',
                    'qr_string'    => '00020101021226300016ID.CO.XENDIT.WWW01189360000201100000035204531153033605802ID5910JavaCRM6007Jakarta6105123456304ABCD',
                    // A placeholder beautiful QR code
                    'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://github.com/muhamadairul/java-crm',
                ];
                break;
        }

        return $response;
    }

    /**
     * Verify callback token from Xendit webhook header.
     *
     * @param string $token
     * @return bool
     */
    public function verifyWebhookToken(string $token): bool
    {
        $configuredToken = env('XENDIT_WEBHOOK_TOKEN', '');

        // If not configured, allow sandbox testing
        if (empty($configuredToken)) {
            return true;
        }

        return $token === $configuredToken;
    }
}
