<div class="flex max-sm:flex-wrap mt-8">
    <div class="py-2 px-4 border-b-2">
        <div class="shimmer w-[100px] h-7"></div>
    </div>

    <div class="py-2 px-4">
        <div class="shimmer w-[132px] h-7"></div>
    </div>

    <div class="py-2 px-4">
        <div class="shimmer w-[130px] h-7"></div>
    </div>

    <div class="py-2 px-4">
        <div class="shimmer w-[122px] h-7"></div>
    </div>
</div>

<div class="w-full overflow-x-auto border rounded-xl">
    <div class="table-responsive grid w-full box-shadow rounded bg-white overflow-hidden">
        <div class="row grid grid-cols-5 px-4 py-2.5 border-b">
            @foreach (range(1, 5) as $i)
                <div class="py-2">
                    <div class="shimmer w-[136px] h-5"></div>
                </div>
            @endforeach
        </div>
        
        @foreach (range(1, 5) as $i)
            <div class="row grid grid-cols-5 px-4 py-2.5 border-b last:border-none">
                @foreach (range(1, 5) as $i)
                    <div class="grid gap-y-1.5">
                        <div class="shimmer w-20 h-5"></div>

                        <div class="shimmer w-28 h-5"></div>

                        <div class="shimmer w-20 h-5"></div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="flex justify-between items-center p-4">
            <div class="shimmer w-40 h-4"></div>
            
            <div class="shimmer w-20 h-9 rounded-[10px]"></div>
        </div>
    </div>
</div>
