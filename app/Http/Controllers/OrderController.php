<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Motorcycle;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Order creation is disabled: customers contact sellers directly
     * via Telegram. Guards below block any new order creation through
     * direct URL or POST requests, even if the route is restored.
     */
    public function create(Motorcycle $motorcycle): View
    {
        abort(403, 'Online ordering is disabled. Please contact the seller via Telegram on the motorcycle page.');
    }

    public function store(StoreOrderRequest $request, Motorcycle $motorcycle): RedirectResponse
    {
        abort(403, 'Online ordering is disabled. Please contact the seller via Telegram on the motorcycle page.');
    }

    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with(['motorcycle.brand', 'seller'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorizeOrder($order);

        $order->load(['motorcycle.brand', 'motorcycle.images', 'seller']);

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if (! in_array($order->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Your order has been cancelled.');
    }

    protected function authorizeOrder(Order $order): void
    {
        $user = auth()->user();
        $allowed = $user->isAdmin() || $order->user_id === $user->id || $order->seller_id === $user->id;

        abort_unless($allowed, 403);
    }
}
