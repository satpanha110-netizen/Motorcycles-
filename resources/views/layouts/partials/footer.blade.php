<footer class="site-footer pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <a class="navbar-brand d-inline-flex align-items-center gap-2 mb-3" href="{{ route('home') }}">
                    <i class="bi bi-lightning-charge-fill text-accent fs-4"></i>
                    <span class="fw-black fs-4 text-white">ហាងលក់ម៉ូតូ<span class="brand-dot" style="color: #E8570C;"> រិទ្ធស្រីដា</span></span>
                </a>
                <p class="small pe-lg-4">
                    Your trusted marketplace to buy and sell motorcycles. Browse thousands of listings
                    from verified sellers, compare prices, and find your perfect ride.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="https://web.facebook.com/jvingvg1yv" class="social-btn text-white" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://t.me/LEN_G168" class="social-btn text-white" aria-label="Telegram"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="social-btn text-white" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn text-white" aria-label="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-btn text-white" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading mb-3">Explore</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="{{ route('motorcycles.index') }}">All Motorcycles</a></li>
                    <li><a href="{{ route('brands.index') }}">Brands</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="footer-heading mb-3">Top Brands</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    @foreach(['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Ducati'] as $brandName)
                        <li><a href="{{ route('motorcycles.index', ['q' => $brandName]) }}">{{ $brandName }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-3">Get in touch</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><i class="bi bi-geo-alt me-2 text-accent"></i>123 Speedway Ave, Phnom Penh</li>
                    <li><i class="bi bi-telephone me-2 text-accent"></i>012 418 912 /010 418 912 /088 359 3306</li>
                    <li><i class="bi bi-envelope me-2 text-accent"></i>hello@motomarket.test</li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
            <span>&copy; {{ date('Y') }} MotoMarket. All rights reserved.</span>
            <span>Made with <i class="bi bi-heart-fill text-danger"></i> for riders.</span>
        </div>
    </div>
</footer>
