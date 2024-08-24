<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $title ?? '' }}
    </x-slot>

    @push('styles')
        @bagistoVite(['src/Resources/assets/css/admin.css'], 'marketplace')
    @endpush

    <!-- Page Content -->
    {{ $slot }}
</x-admin::layouts>
