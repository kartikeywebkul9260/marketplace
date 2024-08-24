@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productRepository', 'Webkul\Marketplace\Repositories\ProductRepository')
@inject ('reviewRepository', 'Webkul\Marketplace\Repositories\ReviewRepository')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $baseProduct = $product->parent_id ? $product->parent : $product;

    $productBaseImage = product_image()->getProductBaseImage($product);
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-marketplace::shop.layouts.full>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    <!-- Breadcrumbs -->
    <div class="flex justify-center max-lg:hidden">
        <x-shop::breadcrumbs name="product" :entity="$product"></x-shop::breadcrumbs>
    </div>

    <div class="w-full flex gap-10 p-16">
        <div class="">
            <img
                src="{{$productBaseImage['medium_image_url']}}"
                class="w-[560px] min-w-[560px] max-w-[560px] h-[609px] max-h-[609px] top-[-0.5] rounded"
                alt="Product Image"
                width="560"
                height="609"
            />
        </div>

        <div class="w-full grid gap-5 items-baseline content-baseline">
            <div class="grid gap-5">
                <h1 class="text-3xl text-[#000000] font-medium">
                    {{ $baseProduct->name }}
                </h1>
    
                @php $attributes = []; @endphp
    
                @if ($baseProduct->type == 'configurable')
                    <div class="text-base font-normal text-navyBlue">
                        @php $options = []; @endphp
    
                        @foreach ($baseProduct->super_attributes as $attribute)
                            @foreach ($attribute->options as $option)
                                @if ($product->{$attribute->code} == $option->id)
                                    @php
                                        $attributes[$attribute->id] = $option->id;
    
                                        array_push($options, $attribute->name . ' : ' . $option->label);
                                    @endphp
                                @endif
                            @endforeach
                        @endforeach
    
                        {{ implode(', ', $options) }}
    
                    </div>
                @endif
    
                <div class="flex gap-2.5 items-center">
                    <x-marketplace::shop.products.star-rating 
                        :value="$avgRatings"
                        :is-editable=false
                    />
    
                    <p class="text-[#6E6E6E] text-sm">
                        ({{ $product->approvedReviews->count() }} @lang('reviews'))
                    </p>
                </div>
            </div>

            <p class="text-lg text-navyBlue">
                @lang('marketplace::app.shop.products.seller-count', ['count' => $sellerCount])
            </p>

            <v-more-seller></v-more-seller>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-more-seller-template">
            @foreach ($productRepository->getSellerProducts($product) as $key => $sellerProduct)
                <x-shop::form action="{{ route('marketplace.cart.add', $sellerProduct->product_id) }}">

                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $sellerProduct->id }}"
                    >

                    <input
                        type="hidden"
                        name="seller_info[product_id]"
                        value="{{ $sellerProduct->id }}"
                    >

                    <input
                        type="hidden"
                        name="seller_info[seller_id]"
                        value="{{ $sellerProduct->seller->id }}"
                    >

                    <input
                        type="hidden"
                        name="seller_info[is_owner]"
                        value="0"
                    >

                    @if ($baseProduct->type == 'configurable')
                        <input
                            type="hidden"
                            name="selected_configurable_option"
                            value="{{ $product->id }}"
                        >

                        @foreach ($attributes as $attributeId => $optionId)
                            <input
                                type="hidden"
                                name="super_attribute[{{$attributeId}}]"
                                value="{{$optionId}}"
                            />
                        @endforeach
                    @endif

                    <div class="p-5 border rounded-md">
                        <div class="flex gap-2.5 justify-between">
                            <div class="grid gap-2">
                                <div class="grid gap-2">
                                    <a
                                        href="{{route('marketplace.seller.show', $sellerProduct->seller->url)}}"
                                        class="text-xl text-[#000000D4] font-medium"
                                    >
                                        {{ $sellerProduct->seller->shop_title}}
                                    </a>

                                    <x-shop::products.star-rating 
                                        :value="$reviewRepository->getAverageRating($sellerProduct->seller)"
                                        :is-editable=false
                                    >
                                    </x-shop::products.star-rating>
                                </div>
                                
                                <p class="text-2xl font-medium">
                                    {{ core()->currency($sellerProduct->price) }}
                                </p>

                                <p class="text-base font-normal">
                                    @lang('marketplace::app.shop.products.delivery-info')
                                </p>
                            </div>

                            <div class="grid content-between justify-items-end">
                                <div class="flex gap-2.5">
                                    @if ($product->type != 'bundle')
                                        @if ($product->type == "booking")
                                            <x-shop::quantity-changer
                                                name="booking[qty][1]"
                                                value="1"
                                                class="flex gap-2.5 p-1 border border-navyBlue rounded-2xl"
                                                readonly="true"
                                            >
                                            </x-shop::quantity-changer>
                                        @else
                                            <x-shop::quantity-changer
                                                name="quantity"
                                                value="1"
                                                class="flex gap-2.5 p-1 border border-navyBlue rounded-2xl"
                                            >
                                            </x-shop::quantity-changer>
                                        @endif
                                    @endif

                                    <button
                                        type="submit"
                                        class="primary-button !py-3 !px-4"
                                        {{ ! $sellerProduct->isSaleable(1) ? 'disabled' : '' }}
                                    >
                                        @lang('marketplace::app.shop.products.add-to-cart')
                                    </button>
                                </div>

                                <p
                                    class="text-base text-navyBlue font-medium cursor-pointer"
                                    @click="showHide({{$key}})"
                                >
                                    @lang('marketplace::app.shop.products.more-info')
                                </p>
                            </div>
                        </div>

                        <div
                            class="grid gap-y-6"
                            v-if="showMore[{{$key}}]"
                        >
                            <div class="grid grid-cols-5 gap-5 mt-6">
                                @forelse($sellerProduct->images as $image)
                                    <img
                                        class="rounded aspect-square"
                                        src="{{ Storage::url($image->path) }}"
                                        height="110"
                                        width="110"
                                    />
                                @empty
                                    <img
                                        src="{{ bagisto_asset('images/small-product-placeholder-64b7f208.webp', 'marketplace') }}"
                                        height="110"
                                        width="110"
                                    >
                                @endforelse
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                @foreach($sellerProduct->videos as $video)
                                    <video controls width="100%">
                                        <source
                                            src="{{ Storage::url($video->path) }}"
                                            type="video/mp4"
                                        />
                                    </video>
                                @endforeach
                            </div>
    
                            <p class="text-base text-[#000000]">
                                {{ $sellerProduct->description }}
                            </p>
    
                            <hr>
    
                            <p class="text-base text-[#757575]">
                                @lang('marketplace::app.shop.products.seller-info')
                                <br>
                                {{$sellerProduct->seller->shop_title}}
                                <br>
                                {{ $sellerProduct->seller->address1 ? $sellerProduct->seller->address1.',' : ''}}
                                {{ $sellerProduct->seller->address2 ? $sellerProduct->seller->address2.',' : '' }}
                                {{ $sellerProduct->seller->city ? $sellerProduct->seller->city.',' : '' }}
                                {{ $sellerProduct->seller->state ? $sellerProduct->seller->state.',' : '' }}
                                {{ $sellerProduct->seller->postcode ? $sellerProduct->seller->postcode.',' : '' }}
                                {{ $sellerProduct->seller->country ? '('.$sellerProduct->seller->country.')' : '' }}
                            </p>
                        </div>
                    </div>                    
                </x-shop::form>
            @endforeach
        </script>

        <script type="module">
            app.component('v-more-seller', {
                template: '#v-more-seller-template',

                data() {
                    return {
                        showMore: {}
                    }
                },

                methods: {
                    showHide(key) {
                        this.showMore[key] = !this.showMore[key];
                        event.target.innerText = this.showMore[key] ? "@lang('marketplace::app.shop.products.hide')" : "@lang('marketplace::app.shop.products.more-info')";
                    }
                }
            });
        </script>
    @endPushOnce
</x-marketplace::shop.layouts.full>