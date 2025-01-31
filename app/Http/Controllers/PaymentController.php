<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class PaymentController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function createPayment(Request $request)
    {
        $amount = $request->amount;
        $currency = config('services.paypal.currency');
        $returnUrl = route('payment.success');
        $cancelUrl = route('payment.cancel');

        $order = $this->paypalService->createOrder($amount, $currency, $returnUrl, $cancelUrl);

        if ($order && isset($order->links)) {
            foreach ($order->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect($link->href);
                }
            }
        }

        return redirect()->back()->with('error', 'Payment creation failed');
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->token;
        $order = $this->paypalService->captureOrder($orderId);

        if ($order && $order->status === 'COMPLETED') {
            // Handle successful payment
            return view('payment.success');
        }

        return redirect()->route('payment.cancel')->with('error', 'Payment execution failed');
    }

    public function paymentCancel()
    {
        return view('payment.cancel');
    }
} 