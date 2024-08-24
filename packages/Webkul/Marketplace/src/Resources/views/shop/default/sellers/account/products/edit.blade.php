<x-marketplace::shop.layouts>
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.products.edit.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_product_edit" />
    @endSection

    @php
        $currentChannel = core()->getRequestedChannel();

        $currentLocale = core()->getRequestedLocale();
    @endphp

    <x-marketplace::shop.form
        action="{{route('marketplace.account.products.update', $product->id)}}"
        enctype="multipart/form-data"
    >
        @method('PUT')

        <input
            type="hidden"
            name="channel"
            value="{{ $currentChannel->code }}"
        />

        <!-- Page Header -->
        <div class="grid gap-2.5">
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <div class="grid gap-1.5">
                    <p class="text-2xl font-medium leading-6">
                        @lang('marketplace::app.shop.sellers.account.products.edit.title')
                    </p>
                </div>

                <div class="flex gap-x-2.5 items-center">
                    <button class="primary-button px-5 py-2.5">
                        @lang('marketplace::app.shop.sellers.account.products.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <div class="flex gap-8 mt-3.5 max-xl:flex-wrap">
            @foreach ($product->attribute_family->attribute_groups->groupBy('column') as $column => $groups)
                <div
                    @if ($column == 1) class="flex flex-col gap-8 flex-1 max-xl:flex-auto" @endif
                    @if ($column == 2) class="flex flex-col gap-8 w-[360px] max-w-full max-sm:w-full" @endif
                >
                    @foreach ($groups as $group)
                        @php
                            $customAttributes = $product->getEditableAttributes($group);
                        @endphp

                        @if (count($customAttributes))
                            <div class="relative p-5 bg-white border rounded-xl box-shadow">
                                <p class="text-base text-gray-800 font-semibold mb-4">
                                    {{ $group->name }}
                                </p>

                                @foreach ($customAttributes as $attribute)
                                    @php
                                        if (
                                            ! $sellerProduct->is_approved
                                            && $attribute->code == 'status'
                                        ) {
                                            continue;
                                        }
                                    @endphp
                                    
                                    <x-marketplace::shop.form.control-group>
                                        <x-marketplace::shop.form.control-group.label class="!mt-5">
                                            {{ $attribute->admin_name . ($attribute->is_required ? '*' : '') }}
                                        </x-marketplace::shop.form.control-group.label>

                                        @include ('marketplace::shop.sellers.account.products.edit.controls', [
                                            'attribute' => $attribute,
                                            'product'   => $product,
                                        ])
            
                                        <x-marketplace::shop.form.control-group.error :control-name="$attribute->code" />
                                    </x-marketplace::shop.form.control-group>
                                @endforeach

                                @includeWhen($group->name == 'Price', 'marketplace::shop.sellers.account.products.edit.price.group')

                                @includeWhen(
                                    $group->name == 'Inventories' && ! $product->getTypeInstance()->isComposite(),
                                    'marketplace::shop.sellers.account.products.edit.inventories'
                                )
                            </div>
                        @endif
                    @endforeach

                    @if ($column == 1)
                        <!-- Images View Blade File -->
                        @include('marketplace::shop.sellers.account.products.edit.images')

                        <!-- Videos View Blade File -->
                        @include('marketplace::shop.sellers.account.products.edit.videos')

                        <!-- Product Type View Blade File -->
                        @includeIf('marketplace::shop.sellers.account.products.edit.types.' . $product->type)

                        <!-- Include Product Type Additional Blade Files If Any -->
                        @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                            @includeIf($view)
                        @endforeach
                    @else
                        <!-- Categories View Blade File -->
                        @include('marketplace::shop.sellers.account.products.edit.categories')
                    @endif
                </div>
            @endforeach
        </div>
    </x-marketplace::shop.form>    
</x-marketplace::shop.layouts>