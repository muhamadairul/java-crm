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
     * Create a payment request via Xendit API (/v3/payment_requests).
     *
     * @param  string  $referenceId     Unique order / invoice number
     * @param  float   $amount          Payment amount
     * @param  array   $paymentDetails  Payment channel details
     */
    public function createPaymentRequest(
        string $referenceId,
        float $amount,
        array $paymentDetails = []
    ): array {
        // Fallback to simulation if secret key is empty or set to mock
        if (empty($this->secretKey) || str_starts_with($this->secretKey, 'mock')) {
            return $this->simulatePaymentRequestResponse($referenceId, $amount, $paymentDetails);
        }

        $body = $this->buildPaymentBodyPayload($referenceId, $amount, $paymentDetails);

        try {
            Log::info('Xendit Request: POST /v3/payment_requests', [
                'body' => $body,
            ]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'api-version'  => '2024-11-11',
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/v3/payment_requests", $body);

            Log::info('Xendit Response: ' . $response->body());

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Xendit API returned error: ' . $response->body());

            return [
                'status'  => 'error',
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Xendit request failed: ' . $e->getMessage());

            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build payment body payload for Xendit /v3/payment_requests.
     */
    protected function buildPaymentBodyPayload(string $referenceId, float $amount, array $details): array
    {
        $channelCode = strtoupper($details['channel_code'] ?? 'QRIS');

        // Map Virtual Account channel codes (e.g. BCA -> BCA_VIRTUAL_ACCOUNT)
        if (in_array($channelCode, ['BCA', 'MANDIRI', 'BRI', 'BNI', 'PERMATA'])) {
            $channelCode = $channelCode . '_VIRTUAL_ACCOUNT';
        }

        $payload = [
            'reference_id'   => $referenceId,
            'type'           => 'PAY',
            'country'        => 'ID',
            'request_amount' => $amount,
            'currency'       => 'IDR',
            'reusability'    => 'ONE_TIME_USE',
            'channel_code'   => $channelCode,
            'channel_properties' => [
                'expires_at'         => now()->addMinutes(10)->format('Y-m-d\TH:i:s.u\Z'),
                'success_return_url' => $details['success_url'] ?? route('java-crm.home'),
            ],
        ];

        if (isset($details['customer_name'])) {
            $payload['channel_properties']['display_name'] = $details['customer_name'];
        }

        if (isset($details['mobile_number'])) {
            $payload['channel_properties']['mobile_number'] = $details['mobile_number'];
        }

        return $payload;
    }

    /**
     * Simulate a successful response from Xendit when testing without live keys.
     */
    protected function simulatePaymentRequestResponse(
        string $referenceId,
        float $amount,
        array $paymentDetails
    ): array {
        Log::info('Simulating Xendit Payment Request Response', [
            'reference_id' => $referenceId,
        ]);

        $id = 'pr-' . bin2hex(random_bytes(8));
        $channelCode = strtoupper($paymentDetails['channel_code'] ?? 'QRIS');

        $response = [
            'id'           => $id,
            'reference_id' => $referenceId,
            'status'       => 'PENDING',
            'amount'       => $amount,
            'currency'     => 'IDR',
            'created'      => now()->toIso8601String(),
            'updated'      => now()->toIso8601String(),
        ];

        if (in_array($channelCode, ['BCA', 'MANDIRI', 'BRI', 'BNI', 'PERMATA', 'BCA_VIRTUAL_ACCOUNT', 'MANDIRI_VIRTUAL_ACCOUNT', 'BRI_VIRTUAL_ACCOUNT', 'BNI_VIRTUAL_ACCOUNT', 'PERMATA_VIRTUAL_ACCOUNT'])) {
            $baseBank = str_replace('_VIRTUAL_ACCOUNT', '', $channelCode);
            $vaPrefix = [
                'MANDIRI' => '88000',
                'BCA'     => '80000',
                'BRI'     => '12400',
                'BNI'     => '98800',
                'PERMATA' => '85550',
            ][$baseBank] ?? '99990';

            $response['virtual_account'] = [
                'channel_code'    => $channelCode,
                'virtual_account' => $vaPrefix . str_pad((string) rand(1, 999999999), 11, '0', STR_PAD_LEFT),
                'customer_name'   => $paymentDetails['customer_name'] ?? 'JavaCRM Customer',
            ];
        } elseif (in_array($channelCode, ['OVO', 'DANA', 'SHOPEEPAY'])) {
            $response['actions'] = [
                [
                    'action' => 'CHECKOUT_URL',
                    'url'    => route('java-crm.home') . '/simulate-ewallet-redirect?ref=' . $referenceId,
                    'method' => 'GET',
                ],
            ];
        } elseif ($channelCode === 'QRIS') {
            $response['qr_code'] = [
                'channel_code' => 'QRIS',
                'qr_string'    => '00020101021226300016ID.CO.XENDIT.WWW01189360000201100000035204531153033605802ID5910JavaCRM6007Jakarta6105123456304ABCD',
                'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode(route('java-crm.home')),
            ];
        }

        return $response;
    }

    /**
     * Verify callback token from Xendit webhook header.
     */
    public function verifyWebhookToken(string $token): bool
    {
        $configuredToken = env('XENDIT_WEBHOOK_TOKEN', '');

        if (empty($configuredToken)) {
            return true;
        }

        return $token === $configuredToken;
    }
}

