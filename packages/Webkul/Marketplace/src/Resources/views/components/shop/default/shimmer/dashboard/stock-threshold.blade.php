<div class="grid gap-4 border border-[#E9E9E9] rounded-xl p-7">
    <div class="flex justify-between items-center">
        <div class="shimmer w-40 h-8"></div>

        <div class="shimmer w-24 h-11 rounded-xl"></div>
    </div>

    @foreach (range(1, 5) as $i)
        <div class="flex justify-between items-center py-4 border-b last:border-b-0">
            <div class="grid gap-1">
                <div class="shimmer w-32 h-6"></div>

                <div class="shimmer w-32 h-6"></div>
            </div>

            <div class="flex gap-5">
                <div class="shimmer w-15 h-15 rounded-xl"></div>

                <div class="flex flex-col gap-1">
                    <div class="shimmer w-16 h-6"></div>

                    <div class="shimmer w-16 h-6"></div>
                </div>

                <div class="flex items-center">
                    <div class="shimmer w-6 h-6 rounded-md"></div>
                </div>
            </div>
        </div>
    @endforeach
</div>