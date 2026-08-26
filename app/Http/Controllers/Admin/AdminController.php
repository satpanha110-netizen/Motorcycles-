<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::where('role', 'seller')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_motorcycles' => Motorcycle::count(),
            'pending_motorcycles' => Motorcycle::where('status', 'pending')->count(),
            'sold_motorcycles' => Motorcycle::where('status', 'sold')->count(),
            'total_orders' => Order::count(),
            'revenue' => Order::whereIn('status', ['completed', 'processing'])->sum('price'),
            'new_messages' => Contact::where('status', 'new')->count(),
        ];

        // Last 12 months of activity for the charts.
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $chartLabels = $months->map(fn ($m) => $m->format('M Y'));

        $salesData = $months->map(
            fn ($m) => Order::whereIn('status', ['confirmed', 'processing', 'completed'])
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count()
        );

        $revenueData = $months->map(
            fn ($m) => (float) Order::whereIn('status', ['processing', 'completed'])
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->sum('price')
        );

        $newUsersData = $months->map(
            fn ($m) => User::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count()
        );

        $listingsData = $months->map(
            fn ($m) => Motorcycle::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count()
        );

        $recentOrders = Order::with(['motorcycle', 'user'])->latest()->take(6)->get();
        $pendingListings = Motorcycle::with(['brand', 'seller'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'chartLabels', 'salesData', 'revenueData',
            'newUsersData', 'listingsData', 'recentOrders', 'pendingListings'
        ));
    }
}
