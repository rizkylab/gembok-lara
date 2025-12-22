<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoucherPricing;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => \App\Models\VoucherPurchase::where('status', 'completed')->sum('amount') ?? 0,
            'total_vouchers' => \App\Models\VoucherPurchase::count() ?? 0,
            'pending_vouchers' => \App\Models\VoucherPurchase::where('status', 'pending')->count() ?? 0,
            'active_pricing' => VoucherPricing::where('is_active', true)->count() ?? 0,
        ];

        $recent_purchases = \App\Models\VoucherPurchase::latest()->limit(10)->get();

        return view('admin.vouchers.index', compact('stats', 'recent_purchases'));
    }

    public function pricing()
    {
        $pricings = VoucherPricing::orderBy('duration')->get();
        return view('admin.vouchers.pricing', compact('pricings'));
    }

    public function createPricing()
    {
        return view('admin.vouchers.pricing-create');
    }

    public function storePricing(Request $request)
    {
        $validated = $request->validate([
            'package_name' => 'required|string|max:255',
            'customer_price' => 'required|numeric|min:0',
            'agent_price' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        VoucherPricing::create($validated);

        return redirect()->route('admin.vouchers.pricing')->with('success', 'Pricing berhasil ditambahkan!');
    }

    public function updatePricing(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:voucher_pricing,id',
            'customer_price' => 'required|numeric|min:0',
            'agent_price' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
        ]);

        $pricing = VoucherPricing::find($request->id);
        $pricing->update([
            'customer_price' => $validated['customer_price'],
            'agent_price' => $validated['agent_price'],
            'commission_amount' => $validated['commission_amount'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'Pricing berhasil diupdate!');
    }

    public function deletePricing(VoucherPricing $pricing)
    {
        $pricing->delete();
        return redirect()->route('admin.vouchers.pricing')->with('success', 'Pricing berhasil dihapus!');
    }

    public function seedPricing()
    {
        $pricings = [
            ['package_name' => 'Voucher 1 Jam', 'customer_price' => 3000, 'agent_price' => 2500, 'commission_amount' => 500, 'duration' => 1],
            ['package_name' => 'Voucher 3 Jam', 'customer_price' => 5000, 'agent_price' => 4000, 'commission_amount' => 1000, 'duration' => 3],
            ['package_name' => 'Voucher 6 Jam', 'customer_price' => 8000, 'agent_price' => 6500, 'commission_amount' => 1500, 'duration' => 6],
            ['package_name' => 'Voucher 12 Jam', 'customer_price' => 12000, 'agent_price' => 10000, 'commission_amount' => 2000, 'duration' => 12],
            ['package_name' => 'Voucher 24 Jam', 'customer_price' => 20000, 'agent_price' => 17000, 'commission_amount' => 3000, 'duration' => 24],
            ['package_name' => 'Voucher 3 Hari', 'customer_price' => 50000, 'agent_price' => 42000, 'commission_amount' => 8000, 'duration' => 72],
            ['package_name' => 'Voucher 7 Hari', 'customer_price' => 100000, 'agent_price' => 85000, 'commission_amount' => 15000, 'duration' => 168],
            ['package_name' => 'Voucher 30 Hari', 'customer_price' => 350000, 'agent_price' => 300000, 'commission_amount' => 50000, 'duration' => 720],
        ];

        foreach ($pricings as $pricing) {
            VoucherPricing::updateOrCreate(
                ['package_name' => $pricing['package_name']],
                array_merge($pricing, ['is_active' => true])
            );
        }

        return redirect()->route('admin.vouchers.pricing')->with('success', '8 data pricing sample berhasil dibuat!');
    }

    public function purchases(Request $request)
    {
        $query = \App\Models\VoucherPurchase::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_phone', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchases = $query->latest()->paginate(20);

        return view('admin.vouchers.purchases', compact('purchases'));
    }

    public function generate()
    {
        $pricings = \App\Models\VoucherPricing::where('is_active', true)->get();
        return view('admin.vouchers.generate', compact('pricings'));
    }

    public function storeGenerate(Request $request)
    {
        $validated = $request->validate([
            'pricing_id' => 'required|exists:voucher_pricing,id',
            'quantity' => 'required|integer|min:1|max:100',
            'prefix' => 'nullable|string|max:5',
        ]);

        // Logic to generate vouchers would go here
        // For now we'll just simulate it
        
        return redirect()->route('admin.vouchers.index')
            ->with('success', $validated['quantity'] . ' vouchers generated successfully!');
    }

    // Public methods
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:voucher_pricing,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:midtrans,xendit,manual',
        ]);

        $voucherService = new \App\Services\VoucherService();

        try {
            $purchase = $voucherService->createPurchase([
                'pricing_id' => $validated['package_id'],
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'payment_method' => $validated['payment_method'],
            ]);

            // For manual payment or demo, activate immediately
            if ($validated['payment_method'] === 'manual') {
                $voucherService->manualActivate($purchase);
                return redirect()->route('voucher.success', $purchase->id);
            }

            // For payment gateway, create payment and redirect
            // TODO: Integrate with Midtrans/Xendit
            // For now, simulate successful payment
            $voucherService->processPayment($purchase, 'DEMO-' . time());
            
            return redirect()->route('voucher.success', $purchase->id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $purchase = \App\Models\VoucherPurchase::findOrFail($id);
        return view('voucher.success', compact('purchase'));
    }
}
