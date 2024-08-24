<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.transactions.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.transactions.index.title')
        </p>
    </div>

    <x-admin::datagrid src="{{ route('admin.marketplace.transactions.index') }}"></x-admin::datagrid>
</x-marketplace::admin.layouts>
