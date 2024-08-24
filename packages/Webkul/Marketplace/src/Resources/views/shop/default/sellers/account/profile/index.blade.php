@php
    $seller = auth()->guard('seller')->user();
@endphp

<x-marketplace::shop.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.manage-profile.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_profile" />
    @endSection

    <!-- Profile Edit Form -->
    <x-marketplace::shop.form
        :action="route('shop.marketplace.seller.account.profile.update', $seller->id)"
        enctype="multipart/form-data"
    >
        @method('PUT')

        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.shop.sellers.account.manage-profile.title')
            </p>

            <div class="flex gap-x-2.5 items-center text-center">
                <!-- Collection Button -->
                <a
                    href="{{route('marketplace.seller.show', $seller->url)}}"
                    class="secondary-button px-5 py-2.5"
                >
                    @lang('marketplace::app.shop.sellers.account.manage-profile.collection-page')
                </a>

                <!-- Seller Button -->
                <a
                    href="{{route('marketplace.seller_central.index')}}"
                    class="secondary-button px-5 py-2.5"
                >
                    @lang('marketplace::app.shop.sellers.account.manage-profile.seller-page')
                </a>

                <!-- Update Button -->
                <button class="primary-button px-5 py-2.5">
                    @lang('marketplace::app.shop.sellers.account.manage-profile.save-btn')
                </button>
            </div>
        </div>

        <v-seller-banner-logo>
            <!-- Banner -->
            <div class="h-[250px] md:h-[306px] mt-8 mb-7">
                <x-shop::media.images.lazy
                    class="h-[250px] md:h-[306px] w-full object-cover md:rounded-lg"
                    alt="marketplace banner"
                    width="1140"
                    height="306"
                />
            </div>

            <!-- Logo -->
            <div class="flex gap-2">
                <div class="relative border border-[#E9E9E9] rounded-xl max-h-20 min-w-20 max-w-20">
                    <x-shop::media.images.lazy
                        class="h-20 max-h-20 min-w-20 max-w-20 rounded-xl object-cover"
                        alt="seller logo"
                        width="80"
                        height="80"
                    />
                </div> 
                <div class="grid">
                    <h1 class="text-3xl leading-[48px] font-medium">
                        {{$seller->shop_title}}
                    </h1>
                    <h6 class="text-base leading-6 text-[#757575] font-medium">
                        {{ $seller->address1 ? $seller->address1.',' : ''}}
                        {{ $seller->address2 ? $seller->address2.',' : '' }}
                        {{ $seller->city ? $seller->city.',' : '' }}
                        {{ $seller->state ? $seller->state.',' : '' }}
                        {{ $seller->postcode ? $seller->postcode.',' : '' }}
                        {{ $seller->country ? '('.$seller->country.')' : '' }}
                    </h6>
                </div>
            </div>
        </v-seller-banner-logo>

        <!-- Full Pannel -->
        <div class="flex gap-6 mt-3.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-col gap-6 flex-1 max-xl:flex-auto">
                <!-- Shop Information -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.general-info')
                    </h3>
                
                    <!-- Shop Title -->
                    <div class="flex gap-4 max-sm:flex-wrap">
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.shop-title')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="shop_title"
                                :value="old('shop_title') ?: $seller->shop_title"
                                rules="required"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.shop-title')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.shop-title')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="shop_title" />
                        </x-marketplace::shop.form.control-group>
                    </div>

                    <!-- Shop URL -->
                    <div class="flex gap-4 max-sm:flex-wrap">
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.url')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="url"
                                :value="old('url') ?: $seller->url"
                                rules="required"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.url')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.url')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="url" />
                        </x-marketplace::shop.form.control-group>
                    </div>

                    <div class="flex gap-4 max-sm:flex-wrap">
                        <!-- Name -->
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.name')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name') ?: $seller->name"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.name')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.name')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="name" />
                        </x-marketplace::shop.form.control-group>
                    </div>

                    <div class="flex gap-4 max-sm:flex-wrap">
                        <!-- Email -->
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.email')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="email"
                                rules="required"
                                :value="old('email') ?: $seller->email"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.email')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.email')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="email" />
                        </x-marketplace::shop.form.control-group>
                    </div>

                    <!-- Phone -->
                    <div class="flex gap-4 max-sm:flex-wrap">
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.phone-number')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="phone"
                                :value="old('phone') ?: $seller->phone"
                                rules="required"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.phone-number')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.phone-number')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="phone" />
                        </x-marketplace::shop.form.control-group>
                    </div>
                </div>

                <!-- Description -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.about-store')
                    </h3>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.manage-profile.shop.about-store')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description') ?: $seller->description"
                            id="content"
                            rules="required"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.about-store')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.about-store')"
                            :tinymce="true"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="description" />
                    </x-marketplace::shop.form.control-group>
                </div>

                <!-- Meta Information -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.meta-info.title')
                    </h3>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-title')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="meta_title"
                            id="meta_title"
                            :value="old('meta_title') ?: $seller->meta_title"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-title')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-title')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="meta_title" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-keyword')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="meta_keywords"
                            id="meta_keywords"
                            :value="old('meta_keywords') ?: $seller->meta_keywords"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-keyword')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-keyword')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="meta_keywords" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-description')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="textarea"
                            name="meta_description"
                            id="meta_description"
                            :value="old('meta_description') ?: $seller->meta_description"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-description')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.meta-info.meta-description')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="meta_description" />
                    </x-marketplace::shop.form.control-group>
                </div>

                <!-- Policy -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">
                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.policy.title')
                    </h3>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.policy.privacy')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="textarea"
                            name="privacy_policy"
                            id="privacy_policy"
                            :value="old('privacy_policy') ?: $seller->privacy_policy"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.policy.privacy')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.policy.privacy')"
                            :tinymce="true"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="privacy_policy" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.policy.shipping')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="textarea"
                            name="shipping_policy"
                            id="shipping_policy"
                            :value="old('privacy_policy') ?: $seller->privacy_policy"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.policy.shipping')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.policy.shipping')"
                            :tinymce="true"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="shipping_policy" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.policy.return')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="textarea"
                            name="return_policy"
                            id="return_policy"
                            :value="old('return_policy') ?: $seller->return_policy"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.policy.return')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.policy.return')"
                            :tinymce="true"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="return_policy" />
                    </x-marketplace::shop.form.control-group>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex flex-col gap-6 max-xl:flex-auto w-[360px] max-w-full">
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.store-address')
                    </h3>

                    <!-- Address1 -->
                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.manage-profile.shop.address1')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="address1"
                            :value="old('address1') ?: $seller->address1"
                            rules="required"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.address1')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.address1')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="address1" />
                    </x-marketplace::shop.form.control-group>

                    <!-- Address2 -->
                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.shop.address2')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="address2"
                            :value="old('address2') ?: $seller->address2"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.address2')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.address2')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="address2" />
                    </x-marketplace::shop.form.control-group>

                    <div class="flex gap-4 max-sm:flex-wrap">
                        <!-- Postcode -->
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.postcode')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="postcode"
                                :value="old('postcode') ?: $seller->postcode"
                                rules="required"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.postcode')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.postcode')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="postcode" />
                        </x-marketplace::shop.form.control-group>
                    </div>
                    
                    <!-- City -->
                    <div class="flex gap-4 max-sm:flex-wrap">
                        <x-marketplace::shop.form.control-group class="w-full">
                            <x-marketplace::shop.form.control-group.label class="required">
                                @lang('marketplace::app.shop.sellers.account.manage-profile.shop.city')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="city"
                                :value="old('city') ?: $seller->city"
                                rules="required"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.city')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.city')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="city" />
                        </x-marketplace::shop.form.control-group>
                    </div>

                    <v-seller-country-state>
                        <div class="flex gap-4 max-sm:flex-wrap">
                            <x-marketplace::shop.form.control-group class="w-full">
                                <x-marketplace::shop.form.control-group.label class="required">
                                    @lang('marketplace::app.shop.sellers.account.manage-profile.shop.country')
                                </x-marketplace::shop.form.control-group.label>

                                <x-marketplace::shop.form.control-group.control
                                    type="select"
                                    name="country"
                                >
                                    <option value="">
                                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.select')
                                    </option>
                                </x-marketplace::shop.form.control-group.control>
                            </x-marketplace::shop.form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- State -->
                            <x-marketplace::shop.form.control-group class="w-full">
                                <x-marketplace::shop.form.control-group.label class="required">
                                    @lang('marketplace::app.shop.sellers.account.manage-profile.shop.state')
                                </x-marketplace::shop.form.control-group.label>
    
                                <x-marketplace::shop.form.control-group.control
                                    type="text"
                                    name="state"
                                    :value="old('state') ?: $seller->state"
                                    :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.state')"
                                />
                            </x-marketplace::shop.form.control-group>
                        </div>
                    </v-seller-country-state>
                </div>

                <!-- Social Links -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.profile.social-link-title')
                    </h3>

                    @php
                        $socialLinks = ['facebook', 'twitter', 'pinterest', 'linkedin']
                    @endphp

                    @foreach($socialLinks as $socialLink)
                        <x-marketplace::shop.form.control-group>
                            <x-marketplace::shop.form.control-group.label>
                                @lang('marketplace::app.shop.sellers.account.manage-profile.profile.social-links', ['name' => Str::title($socialLink)])
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="{{ $socialLink }}"
                                :value="old($socialLink) ?: $seller->$socialLink"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.profile.social-links', ['name' => $socialLink])"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.profile.social-links', ['name' => $socialLink])"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="{{ $socialLink }}" />
                        </x-marketplace::shop.form.control-group>
                    @endforeach
                </div>
                
                @if (core()->getConfigData('marketplace.settings.general.enable_minimum_order_amount'))
                    <!-- Minimum Order Amount -->
                    <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                        <h3 class="text-xl text-navyBlue leading-8 font-medium mb-6">
                            @lang('marketplace::app.shop.sellers.account.manage-profile.minimum-order-amount.title')
                        </h3>

                        <x-marketplace::shop.form.control-group>
                            <x-marketplace::shop.form.control-group.label>
                                @lang('marketplace::app.shop.sellers.account.manage-profile.minimum-order-amount.title')
                            </x-marketplace::shop.form.control-group.label>

                            <x-marketplace::shop.form.control-group.control
                                type="text"
                                name="min_order_amount"
                                :value="old('min_order_amount') ?: $seller->min_order_amount"
                                :label="trans('marketplace::app.shop.sellers.account.manage-profile.minimum-order-amount.title')"
                                :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.minimum-order-amount.title')"
                            />

                            <x-marketplace::shop.form.control-group.error control-name="min_order_amount" />
                        </x-marketplace::shop.form.control-group>
                    </div>
                @endif

                <!-- Google Analytics Id -->
                <div class="p-5 bg-white rounded-xl box-shadow border border-[#E9E9E9]">

                    <p class="text-xl text-navyBlue leading-8 font-medium mb-6">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.google-analytics-id.title')
                    </p>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.manage-profile.google-analytics-id.title')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="google_analytics_id"
                            :value="old('google_analytics_id') ?: $seller->google_analytics_id"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.google-analytics-id.title')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.google-analytics-id.title')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="google_analytics_id" />
                    </x-marketplace::shop.form.control-group>                  
                </div>
            </div>
        </div>
    </x-marketplace::shop.form>
    
    @pushOnce('scripts')
        <script type="text/x-template" id="v-seller-country-state-template">
            <!-- Country -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <x-marketplace::shop.form.control-group class="w-full">
                    <x-marketplace::shop.form.control-group.label class="required">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.country')
                    </x-marketplace::shop.form.control-group.label>

                    <x-marketplace::shop.form.control-group.control
                        type="select"
                        name="country"
                        rules="required"
                        ::value="country"
                        v-model="country"
                        :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.country')"
                    >
                        <option value="">
                            @lang('marketplace::app.shop.sellers.account.manage-profile.shop.select')
                        </option>

                        @foreach (core()->countries() as $country)
                            <option 
                                {{ $country->code === config('app.default_country') ? 'selected' : '' }}  
                                value="{{ $country->code }}"
                            >
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </x-marketplace::shop.form.control-group.control>

                    <x-marketplace::shop.form.control-group.error control-name="country" />
                </x-marketplace::shop.form.control-group>
            </div>

            <!-- State -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <x-marketplace::shop.form.control-group class="w-full">
                    <x-marketplace::shop.form.control-group.label class="required">
                        @lang('marketplace::app.shop.sellers.account.manage-profile.shop.state')
                    </x-marketplace::shop.form.control-group.label>

                    <template v-if="currentCountryStates?.length">
                        <x-marketplace::shop.form.control-group.control
                            type="select"
                            name="state"
                            rules="required"
                            ::value="state"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.state')"
                        >
                            <option 
                                v-for='state in currentCountryStates'
                                :value="state.code"
                                v-text="state.default_name"
                            >
                            </option>
                        </x-marketplace::shop.form.control-group.control>
                    </template>

                    <template v-else>
                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="state"
                            ::value="state"
                            rules="required"
                            :label="trans('marketplace::app.shop.sellers.account.manage-profile.shop.state')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.manage-profile.shop.state')"
                        />
                    </template>

                    <x-marketplace::shop.form.control-group.error control-name="state" />
                </x-marketplace::shop.form.control-group>
            </div>
        </script>

        <script type="module">
            app.component('v-seller-country-state', {
                template: '#v-seller-country-state-template',

                data() {
                    return {
                        country: @json(old('country') ?: $seller->country),

                        state: @json(old('state') ?: $seller->state),

                        currentCountryStates: [],
                        
                        allCountryStates: @json(core()->groupedStatesByCountries()),
                    }
                },

                mounted() {
                    this.updateStates();
                },

                watch: {
                    country(newVal, oldVal) {
                        this.currentCountryStates = this.allCountryStates[newVal];
                    }
                },

                methods: {
                    updateStates() {
                        this.currentCountryStates = this.allCountryStates[this.country];
                    },
                }
            })
        </script>

        <script type="text/x-template" id="v-seller-banner-logo-template">
            <!-- Banner -->
            <div class="relative h-[250px] md:h-[306px] mt-8 mb-7">
                <img
                    class="h-[250px] md:h-[306px] w-full object-cover md:rounded-lg"
                    :src="images.banner_url ? images.banner_url : default_images.banner"
                    alt="Seller banner"
                    width="1140"
                    height="306"
                >

                <div class="absolute right-0 top-8 transform -translate-x-2.5 -translate-y-3.5">
                    <x-shop::dropdown>
                        <x-slot:toggle>
                            <div class="w-11 h-11 flex items-center justify-center bg-[#FFFFFF] bg-opacity-50 rounded-full">
                                <span class="icon-more text-2xl cursor-pointer"></span>
                            </div>
                        </x-slot:toggle>

                        <x-slot:content class="!p-0">
                            <div class="grid w-[374px] max-h-[258px] max-w-[374px]">
                                <div class="p-5 border-b">
                                    <div class="flex justify-between">
                                        <p class="text-2xl text-[#151515] leading-9 font-medium">
                                            @lang('marketplace::app.shop.sellers.account.manage-profile.profile.banner')
                                        </p>
                                        <span class="mp-cancel-icon text-2xl cursor-pointer"></span>
                                    </div>
    
                                    <p class="text-base leading-5 font-normal">
                                        @lang('marketplace::app.shop.sellers.account.manage-profile.profile.banner-description')
                                    </p>
                                </div>

                                <div class="px-5 hover:bg-gray-100 cursor-pointer">
                                    <label
                                        for="banner"
                                        class="flex items-center gap-4 py-5"
                                    >
                                        <span class="mp-upload-icon text-2xl"></span>

                                        <p class="text-lg text-[#3D2D2D] font-medium">
                                            @lang('marketplace::app.shop.sellers.account.manage-profile.profile.upload-new-banner')
                                        </p>
                                    </label>
                                </div>

                                <input
                                    type="hidden"
                                    name="banner[]"
                                    v-if="! uploadedFiles.bannerPicked"
                                />

                                <input
                                    type="file"
                                    class="hidden"
                                    id="banner"
                                    name="banner[]"
                                    accept="image/*"
                                    ref="banner"
                                    @change="setBanner()"
                                >

                                <div class="px-5 hover:bg-gray-100 cursor-pointer">
                                    <button
                                        type="button"
                                        class="w-full flex items-center gap-4 py-5"
                                        :disabled="! hasBanner"
                                        @click="removeImage('banner')"
                                    >
                                        <span class="mp-delete-icon text-2xl"></span>

                                        <p class="text-lg text-[#3D2D2D] font-medium">
                                            @lang('marketplace::app.shop.sellers.account.manage-profile.profile.delete-banner')
                                        </p>
                                    </button>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-shop::dropdown>
                </div>
            </div>

            <!-- Logo -->
            <div class="flex gap-2">
                <div class="relative border border-[#E9E9E9] rounded-xl max-h-20 min-w-20 max-w-20">
                    <img
                        class="h-20 max-h-20 min-w-20 max-w-20 rounded-xl object-cover"
                        :src="images.logo_url ? images.logo_url : default_images.logo"
                        alt="seller logo"
                        width="80"
                        height="80"
                    >
                    
                    <div class="absolute top-[70px] left-[70px] transform -translate-x-4 -translate-y-5">
                        <x-shop::dropdown position="bottom-left">
                            <x-slot:toggle>
                                <div class="flex items-center justify-center bg-[#FFFFFF] bg-opacity-50 rounded-full">
                                    <span class="icon-more text-2xl cursor-pointer"></span>
                                </div>
                            </x-slot:toggle>

                            <x-slot:content class="!p-0">
                                <div class="grid w-[374px] max-h-[258px] max-w-[374px]">
                                    <div class="p-5 border-b">
                                        <div class="flex justify-between">
                                            <p class="text-2xl text-[#151515] leading-9 font-medium">
                                                @lang('marketplace::app.shop.sellers.account.manage-profile.profile.logo')
                                            </p>
                                            <span class="mp-cancel-icon text-2xl cursor-pointer"></span>
                                        </div>
        
                                        <p class="text-base leading-5 font-normal">
                                            @lang('marketplace::app.shop.sellers.account.manage-profile.profile.logo-description')
                                        </p>
                                    </div>
    
                                    <div class="px-5 hover:bg-gray-100 cursor-pointer">
                                        <label
                                            for="logo"
                                            class="flex items-center gap-4 py-5"
                                        >
                                            <span class="mp-upload-icon text-2xl"></span>
    
                                            <p class="text-lg text-[#3D2D2D] font-medium">
                                                @lang('marketplace::app.shop.sellers.account.manage-profile.profile.upload-new-logo')
                                            </p>
                                        </label>
                                    </div>
    
                                    <input
                                        type="hidden"
                                        name="logo[]"
                                        v-if="! uploadedFiles.logoPicked"
                                    />
    
                                    <input
                                        type="file"
                                        class="hidden"
                                        id="logo"
                                        name="logo[]"
                                        accept="image/*"
                                        ref="logo"
                                        @change="setLogo()"
                                    >
    
                                    <div class="px-5 hover:bg-gray-100 cursor-pointer">
                                        <button
                                            type="button"
                                            class="w-full flex items-center gap-4 py-5"
                                            :disabled="! hasLogo"
                                            @click="removeImage('logo')"
                                        >
                                            <span class="mp-delete-icon text-2xl"></span>
    
                                            <p class="text-lg text-[#3D2D2D] font-medium">
                                                @lang('marketplace::app.shop.sellers.account.manage-profile.profile.delete-logo')
                                            </p>
                                        </button>
                                    </div>
                                </div>
                            </x-slot:content>
                        </x-shop::dropdown>
                    </div>
                </div> 
                <div class="grid">
                    <h1 class="text-3xl leading-[48px] font-medium">
                        {{$seller->shop_title}}
                    </h1>
                    <h6 class="text-base leading-6 text-[#757575] font-medium">
                        {{ $seller->address1 ? $seller->address1.',' : ''}}
                        {{ $seller->address2 ? $seller->address2.',' : '' }}
                        {{ $seller->city ? $seller->city.',' : '' }}
                        {{ $seller->state ? $seller->state.',' : '' }}
                        {{ $seller->postcode ? $seller->postcode.',' : '' }}
                        {{ $seller->country ? '('.$seller->country.')' : '' }}
                    </h6>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-seller-banner-logo', {
                template: '#v-seller-banner-logo-template',

                data() {
                    return {
                        images: {
                            logo_url: @json($seller->logo_url),
                            banner_url: @json($seller->banner_url),
                        },

                        uploadedFiles: {
                            logoPicked: false,
                            bannerPicked: false,
                        },

                        default_images: {
                            banner: "{{bagisto_asset('images/default-banner.webp', 'marketplace')}}",
                            logo: "{{bagisto_asset('images/default-logo.webp', 'marketplace')}}",
                        },
                    }
                },

                computed: {
                    hasBanner() {
                        return this.images.banner_url;
                    },

                    hasLogo() {
                        return this.images.logo_url;
                    }
                },

                methods: {
                    setLogo() {
                        const file = this.$refs.logo.files[0];

                        console.log(file);

                        let url = window.URL.createObjectURL(file);

                        console.log(url);

                        this.images.logo_url = url;

                        this.uploadedFiles.logoPicked = true;
                    },

                    setBanner() {
                        const file = this.$refs.banner.files[0];

                        let url = window.URL.createObjectURL(file);

                        this.images.banner_url = url;

                        this.uploadedFiles.bannerPicked = true;
                    },

                    removeImage(type) {
                        if (type == 'logo') {
                            this.images.logo_url = null;

                            this.$refs.logo.val = null;

                            this.uploadedFiles.logoPicked = true;
                        } else {
                            this.images.banner_url = null;

                            this.$refs.banner.val = null;

                            this.uploadedFiles.bannerPicked = true;
                        }
                    }
                }
            })
        </script>
    @endPushOnce
</x-marketplace::shop.layouts>
