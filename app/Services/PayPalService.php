<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $client;

    public function __construct()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        
        if (config('services.paypal.mode') === 'sandbox') {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($amount, $currency, $returnUrl, $cancelUrl)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => $currency,
                    "value" => $amount
                ]
            ]],
            "application_context" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl,
                "brand_name" => config('app.name'),
                "user_action" => "PAY_NOW"
            ]
        ];

        try {
            $response = $this->client->execute($request);
            return $response->result;
        } catch (HttpException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        
        try {
            $response = $this->client->execute($request);
            return $response->result;
        } catch (HttpException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
} 