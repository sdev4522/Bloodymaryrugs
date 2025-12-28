@extends('core/base::layouts.master')
@section('content')

@php
use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;

$product = Product::find($inquiry->product_id);

@endphp
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary mb-3">← Back</a>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Inquiry #{{ $inquiry->id ?? '—' }}</h5>
            <small class="text-muted">
                @if(isset($inquiry->created_at))
                {{ $inquiry->created_at->format('Y-m-d H:i') }}
                @endif
            </small>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Name</strong>
                    <div>{{ $inquiry->name ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Email</strong>
                    <div>
                        @if(!empty($inquiry->email))
                        <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                        @else
                        —
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Phone</strong>
                    <div>{{ $inquiry->phone ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Status</strong>
                    <div>
                        @php
                        $status = $inquiry->status ?? 'unknown';
                        @endphp
                        <span class="badge
                            @if($status === 'new') badge-primary
                            @elseif($status === 'read') badge-secondary
                            @elseif($status === 'closed') badge-success
                            @else badge-light @endif">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="mb-2">
                    <strong>Related Item</strong>
                </div>

                <div class="d-flex flex-col gap-3">
                    <div>
                        <img class="default-img" src="{{ RvMedia::getImageUrl($product->image, 'product-thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" width="50">
                    </div>
                    <div>
                        <p><strong>{{ $product->name }}</strong></p>
                        <a href="{{ $product->url }}" target="_blank"><span>View Product</span></a>
                    </div>
                </div>

            </div>

            <div class="mb-3">
                <strong>Message</strong>
                <div class="border p-3 bg-light">
                    {!! nl2br(e($inquiry->message ?? '—')) !!}
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <div>
                <a href="mailto:{{ $inquiry->email }}" class="btn btn-sm btn-primary">Reply</a>
            </div>
            <small class="text-muted">ID: {{ $inquiry->id ?? '—' }}</small>
        </div>
    </div>
</div>
@endsection