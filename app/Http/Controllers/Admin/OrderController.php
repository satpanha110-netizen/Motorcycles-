<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with(['motorcycle.brand', 'user', 'seller'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(fn ($q) => $q
                    ->where('customer_name', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term)
                    ->orWhereHas('motorcycle', fn ($m) => $m->where('title', 'like', $term)));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['motorcycle.brand', 'motorcycle.images', 'user', 'seller']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $order->update($validated);

        if ($validated['status'] === 'completed') {
            $order->motorcycle()->update(['status' => 'sold']);
        }

        return back()->with('success', "Order #{$order->id} updated to \"{$order->status}\".");
    }

    public function cancel(Order $order): RedirectResponse
    {
        $order->update(['status' => 'cancelled']);

        return back()->with('success', "Order #{$order->id} cancelled.");
    }
}
