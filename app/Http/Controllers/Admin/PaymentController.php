<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\PaymentGatewayService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentGateway;
    protected $whatsapp;

    public function __construct(PaymentGatewayService $paymentGateway, WhatsAppService $whatsapp)
    {
        $this->paymentGateway = $paymentGateway;
        $this->whatsapp = $whatsapp;
    }

    /**
     * Show payment gateway settings
     */
    public function index()
    {
        // Get Duitku settings from app_settings
        $duitkuEnabled = \App\Models\AppSetting::getValue('duitku_enabled', 'false') === 'true';
        $duitkuProduction = \App\Models\AppSetting::getValue('duitku_production', 'false') === 'true';
        
        $settings = [
            'midtrans' => [
                'enabled' => !empty(config('services.midtrans.server_key')),
                'is_production' => config('services.midtrans.is_production', false),
            ],
            'xendit' => [
                'enabled' => !empty(config('services.xendit.secret_key')),
            ],
            'duitku' => [
                'enabled' => $duitkuEnabled,
                'is_production' => $duitkuProduction,
            ],
            'default_gateway' => config('services.payment.default_gateway', 'midtrans'),
        ];

        return view('admin.payment.index', compact('settings'));
    }

    /**
     * Create payment link for invoice
     */
    public function createPayment(Request $request, Invoice $invoice)
    {
        $gateway = $request->input('gateway', config('services.payment.default_gateway'));
        $customer = $invoice->customer;

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $result = $this->paymentGateway->createPayment($invoice, $customer, $gateway);

        if ($result['success']) {
            // Update invoice with payment info
            $invoice->update([
                'payment_gateway' => $gateway,
                'payment_order_id' => $result['order_id'],
            ]);

            return response()->json([
                'success' => true,
                'payment_url' => $result['payment_url'],
                'order_id' => $result['order_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to create payment'
        ], 500);
    }

    /**
     * Get Snap token for Midtrans
     */
    public function getSnapToken(Invoice $invoice)
    {
        $customer = $invoice->customer;

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $result = $this->paymentGateway->createSnapToken($invoice, $customer);

        return response()->json($result);
    }

    /**
     * Check payment status
     */
    public function checkStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $gateway = $request->input('gateway', 'midtrans');

        $status = $this->paymentGateway->checkStatus($orderId, $gateway);

        if ($status) {
            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to check status'
        ], 500);
    }

    /**
     * Send payment link via WhatsApp
     */
    public function sendPaymentLink(Invoice $invoice)
    {
        $customer = $invoice->customer;

        if (!$customer || !$customer->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Customer phone not found'
            ], 404);
        }

        // Create payment first
        $result = $this->paymentGateway->createPayment($invoice, $customer);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment link'
            ], 500);
        }

        // Send via WhatsApp
        $message = "Halo *{$customer->name}*,\n\n";
        $message .= "Berikut link pembayaran untuk tagihan Anda:\n\n";
        $message .= "📋 *Invoice:* {$invoice->invoice_number}\n";
        $message .= "💰 *Total:* Rp " . number_format($invoice->amount, 0, ',', '.') . "\n\n";
        $message .= "🔗 *Link Pembayaran:*\n{$result['payment_url']}\n\n";
        $message .= "Link ini berlaku selama 24 jam.\n\n";
        $message .= "Terima kasih,\n";
        $message .= "*" . companyName() . "*";

        $waResult = $this->whatsapp->send($customer->phone, $message);

        return response()->json([
            'success' => $waResult['success'],
            'message' => $waResult['success'] ? 'Payment link sent via WhatsApp' : 'Failed to send WhatsApp'
        ]);
    }
}
