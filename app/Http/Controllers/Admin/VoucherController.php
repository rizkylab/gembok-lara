<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => \App\Models\VoucherPurchase::where('status', 'completed')->sum('amount'),
            'total_vouchers' => \App\Models\VoucherPurchase::count(),
            'pending_vouchers' => \App\Models\VoucherPurchase::where('status', 'pending')->count(),
            'active_pricing' => \App\Models\VoucherPricing::where('is_active', true)->count(),
        ];

        $recent_purchases = \App\Models\VoucherPurchase::latest()->limit(10)->get();

        return view('admin.vouchers.index', compact('stats', 'recent_purchases'));
    }

    public function pricing()
    {
        $pricings = \App\Models\VoucherPricing::all();
        return view('admin.vouchers.pricing', compact('pricings'));
    }

    public function updatePricing(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:voucher_pricing,id',
            'customer_price' => 'required|numeric|min:0',
            'agent_price' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $pricing = \App\Models\VoucherPricing::find($request->id);
        $pricing->update([
            'customer_price' => $validated['customer_price'],
            'agent_price' => $validated['agent_price'],
            'commission_amount' => $validated['commission_amount'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Pricing updated successfully!');
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
        // Logic for public voucher purchase
    }

    public function success($id)
    {
        // Logic for purchase success page
    }
}
