@props([
    'name'  => '',
    'entity' => null,
])

<div class="flex justify-start mt-5 mb-2.5 max-lg:hidden">
    <div class="flex gap-x-3.5 items-center">        
        {{ Breadcrumbs::view('marketplace::shop.partials.breadcrumbs', $name, $entity) }}
    </div>
</div>
