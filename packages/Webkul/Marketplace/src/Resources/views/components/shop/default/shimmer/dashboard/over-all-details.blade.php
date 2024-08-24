<div class="grid gap-2.5 p-5 border border-[#E9E9E9] rounded-xl mt-8">
    <div class="shimmer w-40 h-7"></div>

    <div class="grid gap-2.5 grid-cols-2 md:grid-cols-3">
        @foreach (range(1, 6) as $i)
            <div class="grid gap-1 py-2.5">
                <div class="flex gap-1 items-end">
                    <div class="shimmer w-24 h-7"></div>

                    <div class="shimmer w-8 h-4"></div>
                </div>

                <div class="shimmer w-40 h-5"></div>
            </div>
        @endforeach
    </div>
</div>