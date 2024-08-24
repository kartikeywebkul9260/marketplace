<div class="fixed h-full bg-white w-[260px] max-lg:hidden transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] border-r border-[#E9E9E9]">
    <div class="h-[calc(100vh-120px)] overflow-auto journal-scroll group-[.sidebar-collapsed]/container:overflow-y-auto overflow-x-hidden">
        <!-- Account Navigation Menus -->
        @foreach ($menu->items as $menuItem)
            <div class="max-md:border max-md:border-t-0 max-md:border-r max-md:border-l max-md:border-b max-md:border-[#E9E9E9] max-md:rounded-md">
                <!-- Account Navigation Content -->
                @foreach ($menuItem['children'] as $subMenuItem)
                    <a href="{{ $subMenuItem['url'] }}">
                        <div class="flex justify-between p-5 border-[#E9E9E9] hover:bg-[#f3f4f682] cursor-pointer">
                            <div class="flex gap-x-4 items-center">
                                <span class="{{ $subMenuItem['icon'] }} text-2xl"></span>

                                <span class="font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden">
                                    @lang($subMenuItem['name'])
                                </span>
                            </div>

                            @if ($menu->getActive($subMenuItem))
                                <span class="mp-arrow-right-icon text-2xl max-md:hidden"></span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Collapse menu -->
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-sidebar-collapse-template">
        <div
            class="bg-whitefixed w-full max-w-[260px] bottom-0 hover:bg-gray-100 border-t border-gray-200 transition-all duration-300 cursor-pointer"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex gap-x-4 p-5 items-center text-lg font-medium">
                <span
                    class="mp-collapse-icon transition-all text-2xl"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>

                <template v-if="! isCollapsed">
                    @lang('marketplace::app.shop.components.layouts.sidebar.collapse')
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: {{ request()->cookie('sidebar_collapsed') ?? 0 }},
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }
                    
                    return 0;
                },
            },
        });
    </script>
@endpushOnce