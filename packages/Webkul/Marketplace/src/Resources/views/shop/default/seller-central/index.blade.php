@php
    $channel = core()->getCurrentChannel();
@endphp

@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

<x-marketplace::shop.layouts.full>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        <div class="md:mt-8 grid gap-16">
            <!-- Banner -->
            <div class="grid md:flex max-sm:flex-row-reverse justify-between items-center bg-[#E6E9EE]">
                <div class="w-full md:w-[480px] max-sm:px-4 max-sm:mt-8 rtl:md:mr-9 ltr:md:ml-9 grid gap-y-5">
                    <h1 class="text-5xl leading-[68px] font-normal font-dmserif text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.banner_title') }}
                    </h1>
    
                    <h2 class="text-base font-medium text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.banner_description') }}
                    </h2>
                    
                    @if (auth()->guard('seller')->check())
                        <a
                            href="{{route('shop.marketplace.seller.account.dashboard.index')}}"
                            class="primary-button flex gap-2.5 items-center"
                        >
                            @lang('marketplace::app.shop.seller-central.index.visit-shop')
                            <span class="icon-arrow-right-stylish text-2xl cursor-pointer"></span>
                        </a>
                    @else
                        <a
                            href="{{route('marketplace.seller.register.create')}}"
                            class="primary-button flex gap-2.5 items-center"
                        >
                            {{ core()->getConfigData('marketplace.settings.landing_page.banner_btn_title') }}
                            <span class="icon-arrow-right-stylish text-2xl cursor-pointer"></span>
                        </a>
                    @endif
                </div>

                <img
                    src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.banner_image')) }}"
                    class="mt-6 md:mr-14"
                    alt="marketplace banner"
                    width="556"
                    height="779"
                />
            </div>
    
            <!-- Banner Bottom Content -->
            <div class="grid md:flex gap-[130px] max-sm:gap-4 justify-items-center justify-center max-lg:flex-wrap">
                <div class="grid">
                    <p class="text-5xl font-normal font-dmserif text-center text-navyBlue leading-[70px]">
                        {{ core()->getConfigData('marketplace.settings.landing_page.community_count') }}+
                    </p>
    
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.seller-central.index.seller-community')
                    </p>
                </div>
    
                <div class="grid">
                    <p class="text-5xl font-medium font-dmserif text-center text-navyBlue leading-[70px]">
                        {{ core()->getConfigData('marketplace.settings.landing_page.business_hour') }}
                    </p>
    
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.seller-central.index.online-business')
                    </p>
                </div>
    
                <div class="grid">
                    <p class="text-5xl font-medium font-dmserif text-center text-navyBlue leading-[70px]">
                        {{ core()->getConfigData('marketplace.settings.landing_page.payment_duration') }}
                    </p>
    
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.seller-central.index.days-payment')
                    </p>
                </div>
    
                <div class="grid">
                    <p class="text-5xl font-medium font-dmserif text-center text-navyBlue leading-[70px]">
                        {{ core()->getConfigData('marketplace.settings.landing_page.serviceable_pincode') }}+
                    </p>
    
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.seller-central.index.serviceable-pincode')
                    </p>
                </div>
            </div>
    
            <!-- Featured Section -->
            <div class="max-sm:p-4 grid gap-6">
                <div class="max-w-[848px] grid md:gap-6">
                    <h2 class="text-2xl font-medium leading-10">
                        {{ core()->getConfigData('marketplace.settings.landing_page.feature_title') }}
                    </h2>
                    
                    <p class="text-base font-normal leading-7 mt-2.5">
                        {{ core()->getConfigData('marketplace.settings.landing_page.feature_description') }}
                    </p>
                </div>
        
                <div class="grid md:flex gap-6 2xl:gap-12 items-end">
                    <div class="max-w-[848px] grid md:grid-cols-2 gap-6">
                        @foreach(collect(['box1', 'box2', 'box3', 'box4']) as $item)
                            <div class="grid gap-2 content-start border border-[#E9E9E9] rounded-md p-4">
                                <div class="flex w-16 min-w-16 h-16 min-h-16 p-5 bg-[#F1EADF] rounded-full">
                                    <img
                                        class=""
                                        src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_icon')) }}"
                                        alt="vector icon"
                                        width="24"
                                        height="24"
                                    >
                                </div>
            
                                <h2 class="text-xl font-medium leading-10">
                                    {{ core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_title') }}
                                </h2> 
            
                                <p class="text-base font-normal leading-7 mt-px">
                                    {{ core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_desc') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <x-shop::media.images.lazy
                        :src="Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_image'))"
                        class="w-full"
                        alt="women holding flowers"
                        width="417"
                        height="608"
                    >
                    </x-shop::media.images.lazy>
                </div>
            </div>
        </div>
    
        <!-- Populer Sellers -->
        <v-popular-sellers></v-popular-sellers>

        <div class="grid gap-6 mt-20 py-24 px-16 max-sm:mt-8 bg-[#F5F5F5]">
            <div class="grid">
                <h2 class="text-2xl font-medium leading-10 text-center">
                    {{ core()->getConfigData('marketplace.settings.landing_page.journey_title') }}
                </h2>
        
                <p class="text-base font-normal leading-7 mt-2.5 mb-7 text-center">
                    {{ core()->getConfigData('marketplace.settings.landing_page.journey_description') }}
                </p>
            </div> 
    
            <div class="grid md:grid-cols-5 gap-5 text-center">
                @foreach (collect(['step1', 'step2', 'step3', 'step4', 'step5']) as $key => $step)
                    <div class="grid gap-2 justify-items-center">
                        <div class="flex w-20 h-20 p-5 bg-[#F1EADF] rounded-full">
                            <img
                                class=""
                                src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_icon')) }}"
                                alt="profile icon"
                                width="40"
                                height="40"
                            >
                        </div>
    
                        <p class="text-base font-normal leading-8 text-[#6E6E6E]">
                            @lang('marketplace::app.shop.seller-central.index.step', ['count' => ++$key])
                        </p>
    
                        <h3 class="text-xl font-medium text-navyBlue leading-8">
                            {{ core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_title') }}
                        </h3>
    
                        <p class="text-base font-normal leading-6 text-[#6E6E6E]">
                            {{ core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_desc') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-popular-sellers-template">
            <div
                class="mt-10 md:mt-20"
                v-if="sellers.length"
            >
                <div class="grid gap-6">
                    <div class="max-w-[800px] relative max-1180:w-full max-1180:max-w-full">
                        <h2 class="text-2xl font-medium leading-10">
                            @lang('marketplace::app.shop.seller-central.index.featured-seller')
                        </h2> 
                        <p class="text-base font-normal leading-7 mt-2.5">
                            @lang('marketplace::app.shop.seller-central.index.start-selling')
                        </p> 
                    </div>
    
                    <div class="relative max-1180:w-full max-1180:max-w-full">
                        <div class="grid md:grid-cols-3 gap-6 max-sm:justify-items-center max-sm:gap-4">                            
                            <div
                                class="grid gap-2 content-start border border-[#E9E9E9] rounded-md p-4 max-sm:min-w-full"
                                v-for="seller in sellers"
                            >
                                <x-shop::media.images.lazy
                                    ::src="seller.logo_url ?? `{{ bagisto_asset('images/default-logo.webp', 'marketplace') }}`"
                                    class="min-w-20 min-h-20 max-w-20 max-h-20 border border-[#E9E9E9] rounded-xl"
                                    alt="marketplace banner"
                                    width="80"
                                    height="80"
                                >
                                </x-shop::media.images.lazy>

                                <a
                                    :href="`{{route('marketplace.seller.show', '')}}/${seller.url}`"
                                    class="text-xl font-medium"
                                    v-text="seller.shop_title"
                                >
                                </a>

                                <p class="text-base font-medium text-[#6E6E6E]">
                                    @{{seller.address1}}, @{{seller.city}}, @{{seller.state}} - @{{seller.postcode}} (@{{seller.country}})
                                </p>

                                <div class="flex gap-2.5 items-center">
                                    <x-shop::products.star-rating 
                                        ::value="seller.avg_rating"
                                        :is-editable=false
                                    >
                                    </x-shop::products.star-rating>
                            
                                    <div class="flex gap-2.5 items-center">
                                        <p class="text-[#6E6E6E] text-sm">
                                            @{{ "@lang('marketplace::app.shop.seller-central.index.reviews', ['count' => ':count'])".replace(':count', seller.total_rating) }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex gap-2.5 items-center flex-wrap"
                                    v-if="seller.allowed_categories.length > 0"
                                >
                                    <p class="text-[#6E6E6E] text-base leading-6 font-medium">
                                        @lang('marketplace::app.shop.seller-central.index.deals-in')
                                    </p>

                                    <div
                                        class="flex bg-[#F1EADF] py-0.5 px-2.5 rounded-xl"
                                        v-for="(category, index) in seller.allowed_categories"
                                    >
                                        @{{category}}
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-popular-sellers', {
                template: '#v-popular-sellers-template',

                data() {
                    return {
                        sellers: {},
                    }
                },

                mounted() {
                    this.get();
                },
    
                methods: {
                    get() {
                        this.$axios.get("{{route('marketplace.seller_central.popular_sellers')}}")
                            .then((response) => {
                                this.sellers = response.data;
                            })
                            .catch(error => {
                            });
                    },
                }
            });
        </script>
    @endPushOnce
</x-marketplace::shop.layouts.full>