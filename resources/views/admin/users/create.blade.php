@extends('layouts.dashboard')

@section('title', 'Add User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">Add User</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.users._form', ['submitLabel' => 'Create User'])
                </form>
            </div>
        </div>
    </div>
@endsection
