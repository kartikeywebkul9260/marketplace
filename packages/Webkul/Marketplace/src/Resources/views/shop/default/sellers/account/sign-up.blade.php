<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('marketplace::app.shop.sellers.account.signup.page-title')"/>

    <meta name="keywords" content="@lang('marketplace::app.shop.sellers.account.signup.page-title')"/>
@endPush

<x-marketplace::shop.layouts.full
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.signup.page-title')
    </x-slot>

	<div class="container mt-20 max-1180:px-5">

        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('marketplace::app.shop.sellers.account.signup.bagisto')"
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
                @lang('marketplace::app.shop.sellers.account.signup.page-title')
            </h1>

			<p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('marketplace::app.shop.sellers.account.signup.form-signup-text')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('marketplace.seller.register.store')">
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.signup.name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="!p-[20px_25px] rounded-lg"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('marketplace::app.shop.sellers.account.signup.name')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.signup.name')"
                            aria-label="@lang('marketplace::app.shop.sellers.account.signup.name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.signup.url')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="!p-[20px_25px] rounded-lg"
                            name="url"
                            rules="required"
                            :value="old('url')"
                            :label="trans('marketplace::app.shop.sellers.account.signup.url')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.signup.url')"
                            :aria-label="trans('marketplace::app.shop.sellers.account.signup.url')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="url" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.signup.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('marketplace::app.shop.sellers.account.signup.email')"
                            placeholder="email@example.com"
                            aria-label="@lang('marketplace::app.shop.sellers.account.signup.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.signup.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            name="password"
                            rules="required|min:6"
                            :value="old('password')"
                            :label="trans('marketplace::app.shop.sellers.account.signup.password')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.signup.password')"
                            ref="password"
                            aria-label="@lang('marketplace::app.shop.sellers.account.signup.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('marketplace::app.shop.sellers.account.signup.confirm-pass')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('marketplace::app.shop.sellers.account.signup.password')"
                            :placeholder="trans('marketplace::app.shop.sellers.account.signup.confirm-pass')"
                            aria-label="@lang('marketplace::app.shop.sellers.account.signup.confirm-pass')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </x-shop::form.control-group>

                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="flex mb-5">
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    <div class="flex mt-8">
                        <button
                            class="primary-button block w-full max-w-[374px] py-4 px-11 m-0 ltr:ml-0 rtl:mr-0 rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('marketplace::app.shop.sellers.account.signup.button-title')
                        </button>
                    </div>
                </x-shop::form>
            </div>

			<p class="mt-5 text-[#6E6E6E] font-medium">
                @lang('marketplace::app.shop.sellers.account.signup.account-exists')

                <a class="text-navyBlue"
                    href="{{ route('marketplace.seller.session.index') }}"
                >
                    @lang('marketplace::app.shop.sellers.account.signup.sign-in-button')
                </a>
            </p>
		</div>

        <p class="mt-8 mb-4 text-center text-[#6E6E6E] text-xs">
            @lang('marketplace::app.shop.sellers.account.signup.footer', ['current_year' => date('Y') ])
        </p>
	</div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-marketplace::shop.layouts.full>