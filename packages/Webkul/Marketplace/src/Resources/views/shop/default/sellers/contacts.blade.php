<!-- Seller Contacts Vue Component -->
<v-seller-contacts>
    <div class="flex flex-wrap gap-3 md:gap-10">
        <div class="flex gap-2.5 items-center">
            <span class="mp-share-icon text-2xl"></span>
    
            <span class="text-lg font-normal">
                @lang('marketplace::app.shop.sellers.profile.share')
            </span>
        </div>
        
        <div class="flex gap-2.5 items-center">
            <span class="mp-phone-icon text-2xl"></span>
    
            <span class="text-lg font-normal">
                @lang('marketplace::app.shop.sellers.profile.contact')
            </span>
        </div>
    
        <span class="flex gap-2.5 items-center">
            <span class="mp-issue-icon text-2xl"></span>
    
            <span class="text-lg font-normal">
                @lang('marketplace::app.shop.sellers.profile.report-issue')
            </span>
        </span>
    </div>
</v-seller-contacts>

@pushOnce('scripts')
    <script type="text/x-template" id="v-seller-contacts-template">
        <div>
            <div class="flex flex-wrap gap-3 md:gap-10">
                <div
                    class="flex gap-2.5 items-center cursor-pointer"
                    @click="$refs.socialShareModal.open()"
                >
                    <span class="mp-share-icon text-2xl"></span>
    
                    <span class="text-lg font-normal">
                        @lang('marketplace::app.shop.sellers.profile.share')
                    </span>
                </div>
                
                <div
                    class="flex gap-2.5 items-center cursor-pointer"
                    @click="$refs.contactModal.open()"
                >
                    <span class="mp-phone-icon text-2xl"></span>
    
                    <span class="text-lg font-normal">
                        @lang('marketplace::app.shop.sellers.profile.contact')
                    </span>
                </div>
    
                <span
                    class="flex gap-2.5 items-center cursor-pointer"
                    @click="$refs.reportModal.open()"
                >
                    <span class="mp-issue-icon text-2xl"></span>
    
                    <span class="text-lg font-normal">
                        @lang('marketplace::app.shop.sellers.profile.report-issue')
                    </span>
                </span>
            </div>
    
            <!-- Social Share Modal -->
            <x-marketplace::shop.modal ref="socialShareModal">
                <x-slot:header>
                    <!-- Modal Header -->
                    <p class="text-2xl font-medium text-[#151515] leading-10">
                        @lang('marketplace::app.shop.sellers.profile.share-on')
                    </p>
                </x-slot:header>
    
                <!-- Modal Content -->
                <x-slot:content>
                    <div class="flex gap-5">
                        <div class="grid gap-2 justify-items-center">
                            <a
                                href="{{ $seller->facebook ?: 'javascript::void(0);' }}"
                                class="flex w-16 min-w-16 h-16 min-h-16 p-2.5 bg-[#1877F2] rounded-full"
                            >
                                <img
                                    src="{{ bagisto_asset('images/social-icons/facebook.svg', 'marketplace') }}"
                                    alt="facebook icon"
                                    width="80"
                                    height="80"
                                >
                            </a>
    
                            <span class="text-base leading-6 font-medium">
                                @lang('marketplace::app.shop.sellers.profile.facebook')
                            </span>
                        </div>
    
                        <div class="grid gap-2 justify-items-center">
                            <a
                                href="{{ $seller->twitter ?: 'javascript::void(0);' }}"
                                class="flex w-16 min-w-16 h-16 min-h-16 p-2.5 bg-[#1A1A1A] rounded-full"
                            >
                                <img
                                    src="{{ bagisto_asset('images/social-icons/twitter.svg', 'marketplace') }}"
                                    alt="twitter icon"
                                    width="80"
                                    height="80"
                                >
                            </a>
    
                            <span class="text-base leading-6 font-medium">
                                @lang('marketplace::app.shop.sellers.profile.twitter')
                            </span>
                        </div>
    
                        <div class="grid gap-2 justify-items-center">
                            <a
                                href="{{ $seller->pinterest ?: 'javascript::void(0);' }}"
                                class="flex w-16 min-w-16 h-16 min-h-16 bg-[#FFFFFF] rounded-full"
                            >
                                <img
                                    src="{{ bagisto_asset('images/social-icons/pinterest.svg', 'marketplace') }}"
                                    alt="pinterest icon"
                                    width="80"
                                    height="80"
                                >
                            </a>
    
                            <span class="text-base leading-6 font-medium">
                                @lang('marketplace::app.shop.sellers.profile.pinterest')
                            </span>
                        </div>
    
                        <div class="grid gap-2 justify-items-center">
                            <a
                                href="{{ $seller->linkedin ?: 'javascript::void(0);' }}"
                                class="flex w-16 min-w-16 h-16 min-h-16 p-2.5 bg-[#1D8DEE] rounded-full"
                            >
                                <img
                                    src="{{ bagisto_asset('images/social-icons/linkedin.svg', 'marketplace') }}"
                                    alt="linkedin icon"
                                    width="80"
                                    height="80"
                                >
                            </a>
    
                            <span class="text-base leading-6 font-medium">
                                @lang('marketplace::app.shop.sellers.profile.linkedin')
                            </span>
                        </div>
                    </div>
                </x-slot:content>
            </x-marketplace::shop.modal>
    
            <!-- Contact Seller Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, contactSeller)"
                    ref="contactForm"
                >
                    <!-- Contact Seller Modal -->
                    <x-marketplace::shop.modal ref="contactModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-2xl font-medium text-[#151515] leading-10">
                                @lang('marketplace::app.shop.sellers.profile.contact')
                            </p>
                        </x-slot:header>
    
                        <!-- Modal Content -->
                        <x-slot:content class="!pb-2">
                            <input
                                type="hidden"
                                name="seller_url"
                                value="{{$seller->url}}"
                            />
    
                            <div class="flex gap-4">
                                <x-shop::form.control-group class="w-full">
                                    <x-shop::form.control-group.label class="flex required">
                                        @lang('marketplace::app.shop.sellers.profile.name')
                                    </x-shop::form.control-group.label>
    
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="name"
                                        class="! shadow-none"
                                        rules="required"
                                        :label="trans('marketplace::app.shop.sellers.profile.name')"
                                        :placeholder="trans('marketplace::app.shop.sellers.profile.name')"
                                    />
                                    
                                    <x-shop::form.control-group.error
                                        control-name="name"
                                        class="flex"
                                    />
                                </x-shop::form.control-group>
    
                                <x-shop::form.control-group class="w-full">
                                    <x-shop::form.control-group.label class="flex required">
                                        @lang('marketplace::app.shop.sellers.profile.email')
                                    </x-shop::form.control-group.label>
    
                                    <x-shop::form.control-group.control
                                        type="email"
                                        name="email"
                                        class="! shadow-none"
                                        rules="required"
                                        :label="trans('marketplace::app.shop.sellers.profile.email')"
                                        :placeholder="trans('marketplace::app.shop.sellers.profile.email')"
                                    />
    
                                    <x-shop::form.control-group.error
                                        control-name="email"
                                        class="flex"
                                    />
                                </x-shop::form.control-group>
                            </div>
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="flex required">
                                    @lang('marketplace::app.shop.sellers.profile.subject')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="subject"
                                    class="! shadow-none"
                                    rules="required"
                                    :label="trans('marketplace::app.shop.sellers.profile.subject')"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.subject')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="subject"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="flex required">
                                    @lang('marketplace::app.shop.sellers.profile.query')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="textarea"
                                    name="query"
                                    class="! shadow-none"
                                    rules="required"
                                    :label="trans('marketplace::app.shop.sellers.profile.query')"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.query')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="query"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
                        </x-slot:content>
    
                        <x-slot:footer>
                            <div class="flex justify-end pb-4">
                                <button
                                    type="submit"
                                    class="w-1/2 py-4 px-7 bg-navyBlue text-white rounded-2xl text-base text-center"
                                >
                                    @lang('marketplace::app.shop.sellers.profile.submit')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-marketplace::shop.modal>
                </form>
            </x-shop::form>
    
            <!-- Report Seller Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, reportSeller)"
                    ref="reportForm"
                >
                    <!-- Report Seller Modal -->
                    <x-marketplace::shop.modal ref="reportModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-2xl text-[#151515] font-medium leading-9">
                                @lang('marketplace::app.shop.sellers.profile.report-issue')
                            </p>
                        </x-slot:header>
            
                        <!-- Modal Content -->
                        <x-slot:content class="!pb-2">
                            <input
                                type="hidden"
                                name="seller_url"
                                value="{{$seller->url}}"
                            />
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="flex required">
                                    @lang('marketplace::app.shop.sellers.profile.name')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="name"
                                    class="! shadow-none"
                                    rules="required"
                                    :label="trans('marketplace::app.shop.sellers.profile.name')"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.name')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="name"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="flex required">
                                    @lang('marketplace::app.shop.sellers.profile.email')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="email"
                                    name="email"
                                    class="! shadow-none"
                                    rules="required"
                                    :label="trans('marketplace::app.shop.sellers.profile.email')"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.email')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="email"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
    
                            <!-- Condition -->
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="flex required">
                                    @lang('marketplace::app.shop.sellers.profile.reason')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="reason"
                                    class="! shadow-none"
                                    rules="required"
                                >
                                    @foreach ($flagReasons as $reason)
                                        <option value="{{ $reason->reason }}">
                                            {{$reason->reason}}
                                        </option>
                                    @endforeach
                                </x-shop::form.control-group.control>
    
                                <x-shop::form.control-group.error
                                    control-name="reason"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
                        </x-slot::content>
    
                        <x-slot:footer>
                            <div class="flex justify-end pb-4">
                                <button
                                    type="submit"
                                    class="w-1/2 py-4 px-7 bg-navyBlue text-white rounded-2xl text-base text-center"
                                >
                                    @lang('marketplace::app.shop.sellers.profile.submit')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-marketplace::shop.modal>
                </form>
            </x-shop::form>
        </div>
    </script>

    <script type="module">
        app.component('v-seller-contacts', {
            template: '#v-seller-contacts-template',

            methods: {
                contactSeller(params, { resetForm, setErrors }) {
                    let formData = new FormData(this.$refs.contactForm);                     

                    this.$axios.post("{{route('marketplace.seller.contact')}}", formData)
                        .then((response) => {
                            this.$refs.contactForm.reset();
                            
                            this.$refs.contactModal.close();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                reportSeller(params, { resetForm, setErrors }) {
                    let formData = new FormData(this.$refs.reportForm);                     

                    this.$axios.post("{{route('marketplace.seller.flag.store')}}", formData)
                        .then((response) => {
                            this.$refs.reportForm.reset();
                            
                            this.$refs.reportModal.close();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            }
        });
    </script>
@endPushOnce