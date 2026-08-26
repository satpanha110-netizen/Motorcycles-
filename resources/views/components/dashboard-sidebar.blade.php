@php
    $area = request()->segment(1); // 'seller' or 'admin'

    $sections = [
        'seller' => [
            ['label' => 'Menu', 'items' => [
                ['route' => 'seller.dashboard', 'icon' => 'speedometer2', 'label' => 'Dashboard'],
            ]],
            ['label' => 'Listings', 'items' => [
                ['route' => 'seller.motorcycles.index', 'icon' => 'bicycle', 'label' => 'My Motorcycles'],
                ['route' => 'seller.motorcycles.create', 'icon' => 'plus-circle', 'label' => 'Add Motorcycle'],
            ]],
            ['label' => 'Sales', 'items' => [
                ['route' => 'seller.orders', 'icon' => 'bag-check', 'label' => 'Orders'],
                ['route' => 'seller.messages', 'icon' => 'chat-dots', 'label' => 'Messages'],
            ]],
        ],
        'admin' => [
            ['label' => 'Menu', 'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'speedometer2', 'label' => 'Dashboard'],
            ]],
            ['label' => 'Management', 'items' => [
                ['route' => 'admin.users.index', 'icon' => 'people', 'label' => 'Users'],
                ['route' => 'admin.motorcycles.index', 'icon' => 'bicycle', 'label' => 'Motorcycles'],
                ['route' => 'admin.brands.index', 'icon' => 'award', 'label' => 'Brands'],
                ['route' => 'admin.categories.index', 'icon' => 'tags', 'label' => 'Categories'],
            ]],
            ['label' => 'Transactions', 'items' => [
                ['route' => 'admin.orders.index', 'icon' => 'bag-check', 'label' => 'Orders'],
                ['route' => 'admin.messages.index', 'icon' => 'envelope', 'label' => 'Messages'],
            ]],
        ],
    ][$area] ?? [];

    $user = auth()->user();
@endphp

<aside class="dash-sidebar d-flex flex-column">
    {{-- Brand --}}
    <a class="dash-brand g-5 " href="{{ route('home') }}">
        <span class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
        <span class="fw-black fs-6 text-light" >ហាងលក់ម៉ូតូ
        <span class="brand-dot"style="color: #EA580C;">រិទ្ធស្រីដា</span></span>
    </a>

    {{-- Nav sections --}}
    <nav class="flex-grow-1 overflow-auto px-2 pb-3">
        @foreach($sections as $section)
            <div class="dash-section-label">{{ $section['label'] }}</div>
            <ul class="nav flex-column gap-1">
                @foreach($section['items'] as $link)
                    <li class="nav-item">
                        <a class="dash-link {{ request()->routeIs($link['route']) ? 'active' : '' }}"
                           href="{{ route($link['route']) }}">
                            <i class="bi bi-{{ $link['icon'] }}"></i>
                            <span>{{ $link['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </nav>

    {{-- Account block pinned to bottom --}}
    <div class="dash-account">
        <div class="d-flex align-items-center gap-2 mb-2">
            @if($user->profile_image)
                <img src="{{ StorageHelper::url($user->profile_image) }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;" alt="">
            @else
                <span class="dash-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            @endif
            <div class="min-w-0">
                <div class="small fw-bold text-white text-truncate">{{ $user->name }}</div>
                <div class="text-white-50" style="font-size: .72rem; text-transform: capitalize;">{{ $user->role }}</div>
            </div>
        </div>

        <a class="dash-link dash-link-sm" href="{{ route('profile.edit') }}">
            <i class="bi bi-gear"></i><span>Profile Settings</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dash-link dash-link-sm dash-link-danger w-100 border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i><span>Logout</span>
            </button>
        </form>
    </div>
</aside>
