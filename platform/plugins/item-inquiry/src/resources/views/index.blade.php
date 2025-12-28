@extends('core/base::layouts.master')
@section('content')
@php
use Illuminate\Support\Str;
use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;

@endphp
<div class="card">
    <div class="card-header">Product Inquiries</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{ $inquiry->id }}</td>
                    <td>
                        @php
                        $product = Product::find($inquiry->product_id);
                        @endphp
                        @if ($product && $product->image)
                        <img class="default-img" src="{{ RvMedia::getImageUrl($product->image, 'product-thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" width="50">


                        @else
                        <img src="{{ asset('images/placeholder.png') }}" alt="No image" width="50">
                        @endif


                    </td>
                    <td>{{ $inquiry->name }}</td>
                    <td>{{ $inquiry->email }}</td>
                    <td>{{ Str::limit($inquiry->message, 20) }}</td>
                    <td>{{ $inquiry->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('item-inquiry.detail', $inquiry->id) }}" class="btn btn-info btn-sm">

                                <svg class="icon  svg-icon-ti-ti-eye" data-bs-toggle="tooltip" data-bs-title="View Detail" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                </svg>

                                <span class="sr-only">View Details</span>
                            </a>
                            <form action="{{ route('item-inquiry.destroy', $inquiry->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $inquiries->links() }}
    </div>
</div>
@endsection