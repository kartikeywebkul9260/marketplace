<div class="grid gap-4 p-7 border border-[#E9E9E9] rounded-xl">
    <div class="max-h-11 flex gap-2 justify-between">
        <div class="shimmer w-28 h-7"></div>

        <div class="shimmer w-[104px] h-11 rounded-xl"></div>
    </div>

    <!-- Graph Chart -->
    <div class="flex gap-1.5">
        <div class="grid">
            @foreach (range(1, 10) as $i)
                <div class="shimmer w-[34px] h-2.5">
                </div>
            @endforeach
        </div>

        <div class="w-full grid gap-1.5">
            <div class="flex items-end w-[285px] md:w-full md:h-[240px] pl-2.5 border-l border-b">
                <div class="w-full flex gap-2.5 justify-between items-end aspect-[2]">
                    @foreach (range(1, 14) as $i)
                        <div class="flex shimmer w-full" style="height: {{ rand(10, 100) }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-5 justify-between pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
                @foreach (range(1, 10) as $i)
                    <div class="shimmer rotate-45 flex w-3 mt-1 h-10"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>