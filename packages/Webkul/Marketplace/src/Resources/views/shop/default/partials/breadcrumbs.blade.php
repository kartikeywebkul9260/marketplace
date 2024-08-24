@unless ($breadcrumbs->isEmpty())
    <nav aria-label="">
        <ol class="flex">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url 
                    && ! $loop->last
                )
                    <li class="flex gap-x-2.5 items-center text-base font-medium">
                        <a href="{{ $breadcrumb->url }}">
                            {{ $breadcrumb->title }}
                        </a>

                        <span class="after:content-['/'] text-[#727272] text-base mr-2.5"></span>
                    </li>
                @else
                    <li 
                        class="flex gap-x-2.5 items-center mr-2.5 text-[#727272] text-base font-medium after:content-['/'] after:last:hidden" 
                        aria-current="page"
                    >
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
