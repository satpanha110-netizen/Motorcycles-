@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1 class="fw-black mb-2">Contact Us</h1>
            <p class="text-white-50 mb-0">Questions? We'd love to hear from you.</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <h3 class="section-title h4 mb-4">Get in Touch</h3>
                <p class="text-muted">Have a question about a listing, selling your bike, or anything else?
                    Send us a message on Telegram and we'll respond as quickly as possible.</p>

                <div class="d-grid gap-3 mt-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(0,136,204,.12); color: #0088cc;">
                            <i class="bi bi-telegram"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Telegram</div>
                            <small class="text-muted"><a href="https://t.me/LEN_G168" target="_blank" class="text-muted text-decoration-none">@LEN_G168</a></small>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(234,88,12,.12); color: var(--mm-accent);">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Visit Us</div>
                            <small class="text-muted">ភូមិតាបែក​ ស្រុកបាធាយ​​​​ ខេត្តកំពង់ចាម</small>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(234,88,12,.12); color: var(--mm-accent);">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Call Us</div>
                            <small class="text-muted">012 418 912 /010 418 912 /088 359 3306</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bg-white border rounded-4 p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:#0088cc;">
                            <i class="bi bi-telegram text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Contact us on Telegram</h5>
                        <p class="text-muted small mb-0">Send us a message directly on Telegram for a fast response.</p>
                    </div>
                    <a href="https://t.me/LEN_G168" target="_blank" rel="noopener noreferrer"
                       class="btn btn-lg w-100 py-3 fw-bold text-white rounded-pill"
                       style="background:#0088cc;border:none;">
                        <i class="bi bi-telegram me-2 fs-5"></i> Open Telegram
                    </a>
                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="bi bi-person-circle me-1"></i> @LEN_G168
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h3 class="section-title h4 mb-4">Find Us</h3>
                <div class="map-wrapper rounded-4 overflow-hidden border" style="box-shadow: 0 10px 30px rgba(15,23,42,.08);">
                    <div class="position-relative" style="padding-top: 56.25%;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1537.1774114206416!2d104.91747662241994!3d11.889825804513336!3m2!1i1024!2i768!4f13.1!5e1!3m2!1skm!2skh!4v1787648805048!5m2!1skm!2skh"
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            title="MotoMarket Location">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
