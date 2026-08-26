@extends('layouts.dashboard')

@section('title', 'Messages')

@section('content')
    <h3 class="fw-black mb-4">Contact Messages</h3>

    @if($messages->isEmpty())
        <div class="bg-white border rounded-4 p-5 text-center text-muted">
            <i class="bi bi-envelope-open fs-1 d-block mb-2"></i>
            No messages yet.
        </div>
    @else
        <div class="d-grid gap-3">
            @foreach($messages as $msg)
                <div class="bg-white border rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <span class="fw-bold">{{ $msg->name }}</span>
                            <x-status-badge :status="$msg->status" class="ms-2" />
                            @if($msg->seller)
                                <small class="text-muted d-block mt-1">To seller: {{ $msg->seller->name }}</small>
                            @endif
                            @if($msg->motorcycle)
                                <small class="text-muted d-block">
                                    <i class="bi bi-bicycle me-1"></i>
                                    <a href="{{ route('motorcycles.show', $msg->motorcycle) }}" class="text-decoration-none fw-semibold">
                                        {{ Str::limit($msg->motorcycle->title, 40) }}
                                    </a>
                                </small>
                            @endif
                        </div>
                        <small class="text-muted">{{ $msg->created_at->format('M d, Y H:i') }}</small>
                    </div>

                    <p class="mb-3">{{ $msg->message }}</p>

                    <div class="d-flex flex-wrap gap-2 align-items-center small text-muted">
                        <span><i class="bi bi-envelope me-1"></i>{{ $msg->email }}</span>
                        <span><i class="bi bi-telephone me-1"></i>{{ $msg->phone ?? '—' }}</span>

                        <div class="ms-auto d-inline-flex gap-2">
                            @if($msg->status === 'new')
                                <form action="{{ route('admin.messages.read', $msg) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark as read</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $messages->withQueryString()->links() }}
        </div>
    @endif
@endsection
