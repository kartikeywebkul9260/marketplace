<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('marketplace::app.shop.sellers.account.reset-password.title')"/>

    <meta name="keywords" content="@lang('marketplace::app.shop.sellers.account.reset-password.title')"/>
@endPush

<x-marketplace::shop.layouts.full
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.reset-password.title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5">
        
        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('marketplace::app.shop.sellers.account.reset-password.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>
        </div>

        <!-- Form Container -->
        <div
            class="w-full max-w-[870px] m-auto px-[90px] p-16 border border-[#E9E9E9] rounded-xl max-md:px-8 max-md:py-8"
        >
            <h1 class="text-4xl font-dmserif max-sm:text-2xl">
                @lang('marketplace::app.shop.sellers.account.reset-password.title')
            </h1>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('marketplace.seller.reset_password.store')" >
                    <x-shop::form.control-group.control
                        type="hidden"
                        name="token"
                        :value="$token"
                    />

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.reset-password.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            id="email"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('marketplace::app.shop.sellers.account.reset-password.email')"
                            placeholder="email@example.com"
                            aria-label="@lang('marketplace::app.shop.sellers.account.reset-password.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.reset-password.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            name="password"
                            rules="required|min:6"
                            value=""
                            :label="trans('marketplace::app.shop.sellers.account.reset-password.password')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.reset-password.password')"
                            ref="password"
                            aria-label="@lang('marketplace::app.shop.sellers.account.reset-password.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.reset-password.confirm-password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('marketplace::app.shop.sellers.account.reset-password.confirm-password')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.reset-password.confirm-password')"
                            aria-label="@lang('marketplace::app.shop.sellers.account.reset-password.confirm-password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <div class="flex gap-9 flex-wrap mt-8 items-center">
                        <button
                            class="primary-button block w-full max-w-[374px] py-4 px-11 m-0 ltr:ml-0 rtl:mr-0 mx-auto rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('marketplace::app.shop.sellers.account.reset-password.submit-btn-title')
                        </button>
                    </div>

                </x-shop::form>
            </div>

        </div>

        <p class="mt-8 mb-4 text-center text-[#6E6E6E] text-xs">
            @lang('marketplace::app.shop.sellers.account.reset_password.footer', ['current_year'=> date('Y') ])
        </p>
    </div>
</x-marketplace::shop.layouts.full>
