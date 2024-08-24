@if ($paginator->hasPages())
    <div class="flex items-center justify-center gap-5 mt-5">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <a class="flex items-center p-1 border rounded-md">
                <span
                    class="icon-arrow-left text-2xl cursor-pointer opacity-60"
                    role="button"
                >
                </span>
            </a>
        @else
            <a
                data-page="{{ urldecode($paginator->previousPageUrl()) }}"
                href="{{ urldecode($paginator->previousPageUrl()) }}"
                id="previous"
                class="flex items-center p-1 transition-all hover:bg-[#F1EADF] border rounded-md"
            >
                <span
                    class="icon-arrow-left text-2xl cursor-pointer"
                    role="button"
                >
                </span>
            </a>
        @endif

        <!-- Pagination Elements -->
        @foreach ($elements as $element)
            <!-- "Three Dots" Separator -->
            @if (is_string($element))
                <a
                    class="flex items-center justify-center cursor-pointer border h-[34px] w-[34px] rounded-md disabled"
                    aria-disabled="true"
                >
                    {{ $element }}
                </a>
            @endif

            <!-- Array Of Links -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="flex items-center justify-center cursor-pointer border h-[34px] w-[34px] bg-[#F1EADF] rounded-md">
                            {{ $page }}
                        </a>
                    @else
                        <a
                            class="flex items-center justify-center cursor-pointer border h-[34px] w-[34px] transition-all hover:bg-[#F1EADF] rounded-md"
                            href="{{ urldecode($url) }}"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <a
                href="{{ urldecode($paginator->nextPageUrl()) }}"
                data-page="{{ urldecode($paginator->nextPageUrl()) }}"
                id="next"
                class="flex items-center p-1 transition-all hover:bg-[#F1EADF] border rounded-md"
            >
                <span
                    class="icon-arrow-right text-2xl cursor-pointer"
                    role="button"
                >
                </span>
            </a>
        @else
            <a class="flex items-center p-1 border rounded-md">
                <span
                    class="icon-arrow-right text-2xl cursor-pointer opacity-60"
                    role="button"
                >
                </span>
            </a>
        @endif
    </div>
@endif
