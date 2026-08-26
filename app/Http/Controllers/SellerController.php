<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Motorcycle;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function dashboard(): View
    {
        $sellerId = auth()->id();

        $stats = [
            'total_motorcycles' => Motorcycle::where('seller_id', $sellerId)->count(),
            'active_listings' => Motorcycle::where('seller_id', $sellerId)->approved()->count(),
            'sold' => Motorcycle::where('seller_id', $sellerId)->where('status', 'sold')->count(),
            'pending_requests' => Order::where('seller_id', $sellerId)->where('status', 'pending')->count(),
            'total_inquiries' => Contact::where('seller_id', $sellerId)->count(),
            'revenue' => Order::where('seller_id', $sellerId)->whereIn('status', ['completed'])->sum('price'),
        ];

        $recentOrders = Order::with(['motorcycle', 'user'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->take(5)
            ->get();

        $myMotorcycles = Motorcycle::with('brand')
            ->where('seller_id', $sellerId)
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'recentOrders', 'myMotorcycles'));
    }

    public function orders(Request $request): View
    {
        $orders = Order::with(['motorcycle.brand', 'user'])
            ->where('seller_id', auth()->id())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.orders', compact('orders'));
    }

    public function messages(): View
    {
        $messages = Contact::with(['motorcycle', 'user'])
            ->where('seller_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('seller.messages', compact('messages'));
    }

    public function markMessageRead(Contact $contact): \Illuminate\Http\RedirectResponse
    {
        abort_unless($contact->seller_id === auth()->id() || auth()->user()->isAdmin(), 403);

        $contact->update(['status' => 'read']);

        return back()->with('success', 'Message marked as read.');
    }

    public function updateOrderStatus(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        abort_unless($order->seller_id === auth()->id() || auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $order->update($validated);

        if ($order->status === 'completed') {
            Motorcycle::whereKey($order->motorcycle_id)->update(['status' => 'sold']);
        }

        return back()->with('success', "Order #{$order->id} marked as {$order->status}.");
    }
}
