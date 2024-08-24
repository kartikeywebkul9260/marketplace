@bagistoVite(['src/Resources/assets/css/shop.css'], 'marketplace')

@if (core()->getConfigData('marketplace.settings.general.status'))
    <a
        href="{{ route('marketplace.seller_central.index') }}"
        aria-label="Sell"
    >
        <span class="mp-store-icon inline-block text-[24px] cursor-pointer"></span>
    </a>
@endif
