<div class="grid gap-4 border border-[#E9E9E9] rounded-xl p-7">
    <div class="shimmer w-44 h-8"></div>

    @foreach (range(1, 5) as $i)
        <div class="grid gap-2 py-6">
            <div class="shimmer w-60 h-6"></div>

            <div class="flex justify-between gap-5">                                
                <div class="shimmer w-4/5 h-3"></div>

                <div class="shimmer w-10 h-3"></div>
            </div>
        </div>
    @endforeach
</div>