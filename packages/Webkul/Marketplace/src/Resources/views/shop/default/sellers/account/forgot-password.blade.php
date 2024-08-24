<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('marketplace::app.shop.sellers.account.forgot-password.title')"/>

    <meta name="keywords" content="@lang('marketplace::app.shop.sellers.account.forgot-password.title')"/>
@endPush

<x-marketplace::shop.layouts.full
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.forgot-password.title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5">

        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('marketplace::app.shop.sellers.account.forgot-password.bagisto')"
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
                @lang('marketplace::app.shop.sellers.account.forgot-password.title')
            </h1>

            <p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('marketplace::app.shop.sellers.account.forgot-password.forgot-password-text')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('marketplace.seller.forgot_password.store')">

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

                    <div>
                        {!! Captcha::render() !!}
                    </div>

                    <div class="flex gap-9 flex-wrap mt-8 items-center">
                        <button
                            class="primary-button block w-full max-w-[374px] m-0 ltr:ml-0 rtl:mr-0 mx-auto px-11 py-4 rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('marketplace::app.shop.sellers.account.forgot-password.submit')
                        </button>
                    </div>

                    <p class="mt-5 text-[#6E6E6E] font-medium">
                        @lang('marketplace::app.shop.sellers.account.forgot-password.back')

                        <a class="text-navyBlue"
                            href="{{ route('marketplace.seller.session.index') }}"
                        >
                            @lang('marketplace::app.shop.sellers.account.forgot-password.sign-in-button')
                        </a>
                    </p>

                </x-shop::form>
            </div>

        </div>

        <p class="mt-8 mb-4 text-[#6E6E6E] text-xs text-center">
            @lang('marketplace::app.shop.sellers.account.forgot-password.footer', ['current_year'=> date('Y') ])
        </p>
    </div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-marketplace::shop.layouts.full>
