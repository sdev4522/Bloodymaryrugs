@php
$layout = MetaBox::getMetaData($product, 'layout', true);
$layout = ($layout && in_array($layout, array_keys(get_product_single_layouts()))) ? $layout : 'product-right-sidebar';
Theme::layout($layout);

Theme::asset()->usePath()->add('lightGallery-css', 'plugins/lightGallery/css/lightgallery.min.css');
Theme::asset()->container('footer')->usePath()
->add('lightGallery-js', 'plugins/lightGallery/js/lightgallery.min.js', ['jquery']);
Theme::asset()->usePath()->add('bootstrap-icons', 'vendors/bootstrap-icons/bootstrap-icons.css');
@endphp

<div class="product-detail accordion-detail velvet-theme">
    <div class="row g-4">

        {{-- =================================================================
             COLUMN 1: IMAGES & CROSS-SELL (Left)
        ================================================================= --}}
        <div class="col-lg-4 col-md-12 col-12">
            <div class="detail-gallery sticky-top" style="top: 20px; z-index: 10;">
                <div class="product-image-slider border rounded mb-3 bg-white p-2">
                    @foreach ($productImages as $img)
                    <figure class="m-0">
                        <a href="{{ RvMedia::getImageUrl($img) }}">
                            <img class="img-fluid w-100" src="{{ RvMedia::getImageUrl($img, 'medium') }}" alt="{{ $product->name }}">
                        </a>
                    </figure>
                    @endforeach
                </div>
                <div class="slider-nav-thumbnails row g-2">
                    @foreach ($productImages as $img)
                    <div class="col-3 cursor-pointer">
                        <div class="border rounded p-1 hover-border-primary">
                            <img src="{{ RvMedia::getImageUrl($img, 'thumb') }}" alt="{{ $product->name }}" class="img-fluid">
                        </div>
                    </div>
                    @endforeach
                </div>

                @php $crossSell = get_cross_sale_products($product, 2); @endphp
                @if (count($crossSell) > 0)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold mb-3 small text-uppercase">{{ __('Frequently Bought Together') }}</h6>
                    <div class="d-flex align-items-center gap-2">
                        <div class="position-relative">
                            <img src="{{ RvMedia::getImageUrl($product->image, 'thumb') }}" class="rounded border" style="width: 60px;">
                        </div>
                        <span class="text-muted">+</span>
                        @foreach($crossSell as $cs)
                        <a href="{{ $cs->url }}" title="{{ $cs->name }}">
                            <img src="{{ RvMedia::getImageUrl($cs->image, 'thumb') }}" class="rounded border" style="width: 60px;">
                        </a>
                        @if(!$loop->last) <span class="text-muted">+</span> @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- =================================================================
             COLUMN 2: INFO, PRICE, FORM (Center)
        ================================================================= --}}
        <div class="col-lg-5 col-md-12 col-12">
            <div class="detail-info ps-lg-2 pe-lg-2">

                <h1 class="h4 fw-bold mb-2 text-dark">{{ $product->name }}</h1>
                <div class="d-flex align-items-center mb-3">
                    @if (EcommerceHelper::isReviewEnabled())
                    <div class="product-detail-rating">
                        <div class="product-rate-cover text-end">
                            <div class="rating_wrap">
                                <span class="d-inline-block">
                                    @php $rating = round($product->reviews_avg, 1); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $rating >= $i ? 'bi-star-fill text-warning' : 'bi-star text-muted' }} fs-6"></i>
                                        @endfor
                                </span>
                                <span class="rating_num fw-semibold text-primary ms-1">
                                    ({{ __(':count reviews', ['count' => $product->reviews_count]) }})
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- PRICE BLOCK --}}
                <div class="product-price primary-color mb-3">

                    @if ($product->front_sale_price !== $product->price)
                    <p class="fs-11 text-success fw-semibold mb-1">
                        {{ __('Special Offer') }}
                    </p>
                    @endif

                    <h3 class="fw-semibold mb-0">
                        <ins>
                            <span class="text-brand">
                                {{ format_price($product->front_sale_price_with_taxes) }}
                            </span>
                        </ins>

                        @if ($product->front_sale_price !== $product->price)
                        <span class="old-price text-muted ms-2">
                            {{ format_price($product->price_with_taxes) }}
                        </span>
                        @endif
                    </h3>

                    {{-- Sale badge (JS safe) --}}
                    @if ($product->front_sale_price !== $product->price)
                    <span class="save-price d-none">
                        <span class="percentage-off">
                            {{ get_sale_percentage($product->price, $product->front_sale_price) }}
                        </span>
                    </span>

                    <span class="badge bg-danger mt-1">
                        {{ get_sale_percentage($product->price, $product->front_sale_price) }} {{ __('Off') }}
                    </span>
                    @endif

                </div>

                @if ($product->tax_description)
                <div class="product-tax-description mt-1 mb-3">
                    <small class="text-secondary">{{ $product->tax_description }}</small>
                </div>
                @endif

                <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                    @csrf

                    {{-- Variations / Swatches --}}
                    @if ($product->has_variation)
                    <div class="pr_switch_wrap mb-3">
                        {!! render_product_swatches($product, [
                        'selected' => $selectedAttrs,
                        'view' => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
                        ]) !!}
                    </div>
                    {{-- Stock Warning --}}
                    <div class="number-items-available mb-2" style="@if (!$product->isOutOfStock()) display: none; @endif">
                        @if ($product->isOutOfStock())
                        <span class="text-danger fw-bold">({{ __('Out of stock') }})</span>
                        @endif
                    </div>
                    @endif

                    {!! render_product_options($product) !!}
                    {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}

                    <input type="hidden" name="id" class="hidden-product-id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}" />

                    <div class="detail-extralink d-flex flex-wrap align-items-center gap-3 mb-4 mt-3">

                        {{-- Quantity Input (Original Structure) --}}
                        @if (EcommerceHelper::isCartEnabled())
                        <div class="detail-qty border radius d-flex align-items-center justify-content-center" style="max-width: 100px;">
                            <a href="#" class="qty-down"><i class="fa fa-caret-down"></i></a>
                            <input type="number" min="1" value="1" name="qty" class="qty-val qty-input text-center border-0 shadow-none" />
                            <a href="#" class="qty-up"><i class="fa fa-caret-up"></i></a>
                        </div>
                        @endif

                        {{-- Action Buttons (Original Classes + Velvet Styling) --}}
                        <div class="product-extra-link2 d-flex flex-wrap gap-2 flex-grow-1 @if (EcommerceHelper::isQuickBuyButtonEnabled()) has-buy-now-button @endif">
                            @if (EcommerceHelper::isCartEnabled())

                            {{-- Add to Cart (Green) --}}
                            <button type="submit" class="button button-add-to-cart btn btn-success btn-lg text-white fw-bold flex-grow-1 @if ($product->isOutOfStock()) btn-disabled @endif" @if ($product->isOutOfStock()) disabled @endif>
                                {{ __('Add to cart') }}
                            </button>

                            {{-- Buy Now (Red) --}}
                            @if (EcommerceHelper::isQuickBuyButtonEnabled())
                            <button class="button button-buy-now btn btn-danger btn-lg text-white fw-bold flex-grow-1 @if ($product->isOutOfStock()) btn-disabled @endif" type="submit" name="checkout" @if ($product->isOutOfStock()) disabled @endif>
                                {{ __('Buy Now') }}
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>

                    {{-- Wishlist / Compare --}}
                    <div class="d-flex gap-3 text-muted mb-4 small">
                        @if (EcommerceHelper::isWishlistEnabled())
                        <a aria-label="{{ __('Add To Wishlist') }}" class="action-btn hover-up js-add-to-wishlist-button text-muted text-decoration-none" data-url="{{ route('public.wishlist.add', $product->id) }}" href="#">
                            <i class="far fa-heart me-1"></i> {{ __('Add to Wishlist') }}
                        </a>
                        @endif
                        @if (EcommerceHelper::isCompareEnabled())
                        <a aria-label="{{ __('Add To Compare') }}" href="#" class="action-btn hover-up js-add-to-compare-button text-muted text-decoration-none" data-url="{{ route('public.compare.add', $product->id) }}">
                            <i class="far fa-exchange-alt me-1"></i> {{ __('Compare') }}
                        </a>
                        @endif
                    </div>

                </form>

                <div class="offers-box mb-4 bg-light p-3 rounded border border-dashed">
                    <h6 class="fw-bold text-success mb-2 fs-6">
                        <i class="bi bi-tag-fill me-1"></i> {{ __('Best Offers') }}
                    </h6>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-2 d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span><strong>Bank Offer:</strong> Flat 10% off on specific cards</span>
                        </li>
                        <li class="mb-1 d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span><strong>No Cost EMI:</strong> Avail No Cost EMI on select cards</span>
                        </li>
                    </ul>
                </div>

                <div class="accordion accordion-flush border rounded" id="productAccordion">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingDesc">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDesc" aria-expanded="true" aria-controls="collapseDesc">
                                {{ __('Description') }}
                            </button>
                        </h2>
                        <div id="collapseDesc" class="accordion-collapse collapse show" aria-labelledby="headingDesc" data-bs-parent="#productAccordion">
                            <div class="accordion-body text-muted small ck-content">
                                {!! BaseHelper::clean($product->content) !!}
                            </div>
                        </div>
                    </div>

                    @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecs">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecs" aria-expanded="false" aria-controls="collapseSpecs">
                                {{ __('Specifications') }}
                            </button>
                        </h2>
                        <div id="collapseSpecs" class="accordion-collapse collapse" aria-labelledby="headingSpecs" data-bs-parent="#productAccordion">
                            <div class="accordion-body p-0">
                                @include(EcommerceHelper::viewPath('includes.product-specification'))
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (EcommerceHelper::isReviewEnabled())
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingReviews">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                                {{ __('Reviews') }} ({{ $product->reviews_count }})
                            </button>
                        </h2>
                        <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="headingReviews" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                @include('plugins/ecommerce::themes.includes.reviews', ['reviewButtonClass' => 'btn btn-sm btn-primary'])
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>

        {{-- =================================================================
             COLUMN 3: SIDEBAR HIGHLIGHTS (Right)
             - Hidden on mobile
        ================================================================= --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar-wrapper ps-2">

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Features') }} :</h6>
                    <div class="text-secondary small ul-bullets-grey">
                        @if($product->description)
                        {!! BaseHelper::clean($product->description) !!}
                        @else
                        <ul class="ps-3">
                            <li>{{ __('High quality material') }}</li>
                            <li>{{ __('Modern design') }}</li>
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Product Details') }} :</h6>
                    <table class="table table-sm table-borderless small text-muted">
                        @if($product->brand->id)
                        <tr>
                            <td class="fw-bold text-dark ps-0" style="width: 40%">{{ __('Brand') }}</td>
                            <td>{{ $product->brand->name }}</td>
                        </tr>
                        @endif
                        @if($product->sku)
                        <tr>
                            <td class="fw-bold text-dark ps-0">{{ __('Model') }}</td>
                            <td>{{ $product->sku }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="fw-bold text-dark ps-0">{{ __('Availability') }}</td>
                            <td class="text-success">{!! BaseHelper::clean($product->stock_status_html) !!}</td>
                        </tr>
                    </table>
                </div>

                <div class="service-badges bg-light rounded p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-truck fs-4 text-primary"></i>
                        <div><span class="d-block fw-bold small">{{ __('Free Shipping') }}</span></div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-shield-check fs-4 text-primary"></i>
                        <div><span class="d-block fw-bold small">{{ __('1 Year Warranty') }}</span></div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-arrow-counterclockwise fs-4 text-primary"></i>
                        <div><span class="d-block fw-bold small">{{ __('7 Days Return') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================
         RELATED PRODUCTS
    ================================================================= --}}


</div>
@php
$crossSellProducts = get_cross_sale_products($product, $layout == 'product-full-width' ? 4 : 3);
@endphp
@if (count($crossSellProducts) > 0)
<div class="row mt-60">
    <div class="col-12">
        <h3 class="section-title style-1 mb-30">{{ __('You may also like') }}</h3>
    </div>
    @foreach($crossSellProducts as $crossProduct)
    <div class="col-lg-{{ 12 / ($layout == 'product-full-width' ? 4 : 3) }} col-md-4 col-12 col-sm-6">
        @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item', ['product' => $crossProduct])
    </div>
    @endforeach
</div>
@endif

@php
$relatedProducts = get_related_products($product, 6);
@endphp

@if (count($relatedProducts) > 0)
<div class="row mt-60" id="related-products">
    <div class="col-12">
        <h3 class="section-title style-1 mb-30">{{ __('Related products') }}</h3>
    </div>
    @foreach($relatedProducts as $relatedProduct)
    <div class="col-lg-{{ 12 / ($layout == 'product-full-width' ? 3 : 4) }} col-md-4 col-6 col-sm-6">
        @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item', ['product' => $relatedProduct])
    </div>
    @endforeach
</div>
@endif