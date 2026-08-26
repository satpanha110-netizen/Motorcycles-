@extends('layouts.dashboard')

@section('title', 'Edit User — ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">Edit User: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.users._form', ['submitLabel' => 'Save Changes'])
                </form>
            </div>
        </div>
    </div>
@endsection
