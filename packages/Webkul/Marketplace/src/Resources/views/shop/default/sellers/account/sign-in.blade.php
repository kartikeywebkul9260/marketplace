<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('marketplace::app.shop.sellers.account.login.page-title')"/>

    <meta name="keywords" content="@lang('marketplace::app.shop.sellers.account.login.page-title')"/>
@endPush

<x-marketplace::shop.layouts.full
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.login.page-title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5">
        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('marketplace::app.shop.sellers.account.login.bagisto')"
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
                @lang('marketplace::app.shop.sellers.account.login.page-title')
            </h1>

            <p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('marketplace::app.shop.sellers.account.login.form-login-text')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('marketplace.seller.session.create')">

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.login.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            name="email"
                            rules="required|email"
                            value=""
                            :label="trans('marketplace::app.shop.sellers.account.login.email')"
                            placeholder="email@example.com"
                            aria-label="@lang('marketplace::app.shop.sellers.account.login.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- Password -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.login.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            id="password"
                            name="password"
                            rules="required|min:6"
                            value=""
                            :label="trans('marketplace::app.shop.sellers.account.login.password')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.login.password')"
                            aria-label="@lang('marketplace::app.shop.sellers.account.login.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <div class="flex justify-between">
                        <div class="select-none items-center flex gap-1.5">
                            <input
                                type="checkbox"
                                id="show-password"
                                class="hidden peer"
                                onchange="switchVisibility()"
                            />

                            <label
                                class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                for="show-password"
                            ></label>

                            <label
                                class="text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0 select-none cursor-pointer"
                                for="show-password"
                            >
                                @lang('marketplace::app.shop.sellers.account.login.show-password')
                            </label>
                        </div>

                        <div class="block">
                            <a
                                href="{{ route('marketplace.seller.forgot_password.create') }}"
                                class="text-base cursor-pointer text-black max-sm:text-xs"
                            >
                                <span>
                                    @lang('marketplace::app.shop.sellers.account.login.forgot-pass')
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="flex mt-5">
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex gap-9 flex-wrap mt-8 items-center">
                        <button
                            class="primary-button block w-full max-w-[374px] py-4 px-11 m-0 ltr:ml-0 rtl:mr-0 rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('marketplace::app.shop.sellers.account.login.button-title')
                        </button>
                    </div>
                </x-shop::form>
            </div>

            <p class="mt-5 text-[#6E6E6E] font-medium">
                @lang('marketplace::app.shop.sellers.account.login.new-seller')

                <a
                    class="text-navyBlue"
                    href="{{ route('marketplace.seller.register.create') }}"
                >
                    @lang('marketplace::app.shop.sellers.account.login.create-your-account')
                </a>
            </p>
        </div>

        <p class="mt-8 mb-4 text-center text-[#6E6E6E] text-xs">
            @lang('marketplace::app.shop.sellers.account.login.footer', ['current_year' => date('Y') ])
        </p>
    </div>

    @push('scripts')
        {!! Captcha::renderJS() !!}

        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");

                passwordField.type = passwordField.type === "password"
                    ? "text"
                    : "password";
            }
        </script>
    @endpush
</x-marketplace::shop.layouts.full>
