@php
    $currentLocale = core()->getRequestedLocale();
@endphp

<v-downloadable-links :errors="errors"></v-downloadable-links>

<v-downloadable-samples :errors="errors"></v-downloadable-samples>

@pushOnce('scripts')
    <script type="text/x-template" id="v-downloadable-links-template">
        <div class="relative bg-white dark:bg-gray-900 rounded mt-2.5 box-shadow">
            <!-- Panel Header -->
            <div class="flex gap-5 justify-between mb-2.5 p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.title')
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-1 items-center">
                    <div
                        class="secondary-button"
                        @click="resetForm(); $refs.updateCreateLinkDrawer.open()"
                    >
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.add-btn')
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div
                class="grid"
                v-if="links.length"
            >
                <!-- Draggable Products -->
                <draggable
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="links"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex gap-2.5 justify-between p-4 border-b border-slate-300 dark:border-gray-800 cursor-pointer">
                            <!-- Hidden Input -->
                            <input type="hidden" :name="'downloadable_links[' + element.id + '][{{$currentLocale->code}}][title]'" :value="element.title"/>

                            <input type="hidden" :name="'downloadable_links[' + element.id + '][price]'" :value="element.price"/>

                            <input type="hidden" :name="'downloadable_links[' + element.id + '][downloads]'" :value="element.downloads"/>

                            <input type="hidden" :name="'downloadable_links[' + element.id + '][sort_order]'" :value="index"/>


                            <!-- File Hidden Fields -->
                            <input type="hidden" :name="'downloadable_links[' + element.id + '][type]'" :value="element.type"/>

                            <template v-if="element.type == 'file'">
                                <input type="hidden" :name="'downloadable_links[' + element.id + '][file]'" :value="element.file"/>

                                <input type="hidden" :name="['downloadable_links[' + element.id + '][file_name]']" v-model="element.file_name"/>
                            </template>

                            <template v-else>
                                <input type="hidden" :name="['downloadable_links[' + element.id + '][url]']" v-model="element.url"/>
                            </template>


                            <!-- Sample Hidden Fields -->
                            <input type="hidden" :name="'downloadable_links[' + element.id + '][sample_type]'" :value="element.sample_type"/>

                            <template v-if="element.sample_type == 'file'">
                                <input type="hidden" :name="'downloadable_links[' + element.id + '][sample_file]'" :value="element.sample_file"/>

                                <input type="hidden" :name="['downloadable_links[' + element.id + '][sample_file_name]']" v-model="element.sample_file_name"/>
                            </template>

                            <template v-else>
                                <input type="hidden" :name="['downloadable_links[' + element.id + '][sample_url]']" v-model="element.sample_url"/>
                            </template>

                            <!-- Information -->
                            <div class="flex gap-2.5">
                                <!-- Drag Icon -->
                                <i class="icon-drag text-xl text-gray-600 dark:text-gray-300 transition-all pointer-events-none"></i>

                                <div class="grid gap-1.5">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        @{{ element.title }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        <template v-if="element.type == 'file'">
                                            <div>
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.file')

                                                <a
                                                    :href="element.file_url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.file_name }}
                                                </a>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <div>
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.url')

                                                <a
                                                    :href="element.url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.url }}
                                                </a>
                                            </div>
                                        </template>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        <template v-if="element.sample_type == 'file'">
                                            <div v-if="element.sample_file_url">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.sample-file')

                                                <a
                                                    :href="element.sample_file_url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.sample_file_name }}
                                                </a>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <div v-if="element.sample_url">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.sample-url')

                                                <a
                                                    :href="element.sample_url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.sample_url }}
                                                </a>
                                            </div>
                                        </template>
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-1 text-right">
                                <p class="text-gray-800 font-semibold dark:text-white">
                                    @{{ $admin.formatPrice(element.price) }}    
                                </p>

                                <div class="flex gap-x-5 items-center">
                                    <p
                                        class="text-red-600 cursor-pointer transition-all hover:underline"
                                        @click="remove(element)"
                                    >
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.delete-btn')
                                    </p>

                                    <p
                                        class="text-blue-600 cursor-pointer transition-all hover:underline"
                                        @click="selectedLink = element; $refs.updateCreateLinkDrawer.open()"
                                    >
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.edit-btn')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- For Empty Links -->
            <div
                class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                    class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-base text-gray-400 font-semibold">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.empty-info')
                    </p>
                </div>
                
                <!-- Add Row Button -->
                <div
                    class="secondary-button text-sm"
                    @click="resetForm(); $refs.updateCreateLinkDrawer.open()"
                >
                    @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.add-btn')
                </div>
            </div>


            <!-- Add Option Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <!-- Search Drawer -->
                    <x-admin::drawer ref="updateCreateLinkDrawer">
                        <!-- Drawer Header -->
                        <x-slot:header>
                            <div class="grid gap-3">
                                <div class="flex justify-between items-center">
                                    <p class="text-xl font-medium dark:text-white">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.title')
                                    </p>

                                    <button class="mr-11 primary-button">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.save-btn')
                                    </button>
                                </div>
                            </div>
                        </x-slot:header>

                        <!-- Drawer Content -->
                        <x-slot:content class="!p-0">
                            <!-- Modal Content -->
                            <div class="px-4 py-2.5">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="title"
                                        v-model="selectedLink.title"
                                        rules="required"
                                        :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.name')"
                                    />
            
                                    <x-admin::form.control-group.error control-name="title" />
                                </x-admin::form.control-group>

                                <div class="flex gap-4">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.price')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="price"
                                            v-model="selectedLink.price"
                                            rules="required|decimal|min_value:0"
                                            :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.price')"
                                        />
                
                                        <x-admin::form.control-group.error control-name="price" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.downloads')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="downloads"
                                            v-model="selectedLink.downloads"
                                            rules="required|numeric|min_value:1"
                                            :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.downloads')"
                                        />
                
                                        <x-admin::form.control-group.error control-name="downloads" />
                                    </x-admin::form.control-group>
                                </div>

                                <div class="flex gap-4">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            v-model="selectedLink.type"
                                            rules="required"
                                            :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file-type')"
                                        >
                                            <option value="file">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file')
                                            </option>

                                            <option value="url">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="type" />
                                    </x-admin::form.control-group>

                                    <!-- If Type is File -->
                                    <template v-if="selectedLink.type == 'file'">
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file')
                                            </x-admin::form.control-group.label>
                                            
                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file"
                                                rules="required"
                                                v-model="selectedLink.file"
                                                :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file')"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file_name"
                                                v-model="selectedLink.file_name"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file_url"
                                                v-model="selectedLink.file_url"
                                            />

                                            <input
                                                type="file"
                                                name="file"
                                                class="flex w-full min-h-10 py-1 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                                :class="[errors['file'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                ref="file"
                                                @change="uploadFile('file')"
                                            />

                                            <a
                                                :href="selectedLink.sample_file_url"
                                                target="_blank"
                                                class="text-blue-600 break-all transition-all hover:underline"
                                                v-if="selectedLink.file_url"
                                            >
                                                @{{ selectedLink.file_name }}
                                            </a>
                    
                                            <x-admin::form.control-group.error control-name="file" />
                                        </x-admin::form.control-group>
                                    </template>

                                    <!-- Else URL -->
                                    <template v-else>
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="url"
                                                v-model="selectedLink.url"
                                                rules="required"
                                                :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')"
                                            />
                    
                                            <x-admin::form.control-group.error control-name="url" />
                                        </x-admin::form.control-group>
                                    </template>
                                </div>

                                <div class="flex gap-4">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label>
                                            @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.sample-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="sample_type"
                                            v-model="selectedLink.sample_type"
                                        >
                                            <option value="file">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.file')
                                            </option>

                                            <option value="url">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')
                                            </option>
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>

                                    <!-- If Type is File -->
                                    <template v-if="selectedLink.sample_type == 'file'">
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label>
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.sample')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="sample_file"
                                                v-model="selectedLink.sample_file"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="sample_file_name"
                                                v-model="selectedLink.sample_file_name"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="sample_file_url"
                                                v-model="selectedLink.sample_file_url"
                                            />

                                            <input
                                                type="file"
                                                name="sample_file"
                                                class="flex w-full min-h-10 py-1 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                                ref="sample_file"
                                                @change="uploadFile('sample_file')"
                                            />

                                            <a
                                                :href="selectedLink.sample_file_url"
                                                target="_blank"
                                                class="text-blue-600 break-all transition-all hover:underline"
                                                v-if="selectedLink.sample_file_url"
                                            >
                                                @{{ selectedLink.sample_file_name }}
                                            </a>
                                        </x-admin::form.control-group>
                                    </template>

                                    <!-- Else URL -->
                                    <template v-else>
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sample_url"
                                                v-model="selectedLink.sample_url"
                                                rules="required"
                                                :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.links.update-create.url')"
                                            />
                    
                                            <x-admin::form.control-group.error control-name="sample_url" />
                                        </x-admin::form.control-group>
                                    </template>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-admin::drawer>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="text/x-template" id="v-downloadable-samples-template">
        <div class="relative bg-white dark:bg-gray-900 rounded box-shadow">
            <!-- Panel Header -->
            <div class="flex gap-5 justify-between mb-2.5 p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.title')
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-1 items-center">
                    <div
                        class="secondary-button"
                        @click="resetForm(); $refs.updateCreateSampleDrawer.open()"
                    >
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.add-btn')
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div
                class="grid"
                v-if="samples.length"
            >
                <!-- Draggable Products -->
                <draggable
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="samples"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex gap-2.5 justify-between p-4 border-b border-slate-300 dark:border-gray-800 cursor-pointer">
                            <!-- Hidden Input -->
                            <input type="hidden" :name="'downloadable_samples[' + element.id + '][title]'" :value="element.title"/>

                            <input type="hidden" :name="'downloadable_samples[' + element.id + '][sort_order]'" :value="index"/>

                            <!-- File Hidden Fields -->
                            <input type="hidden" :name="'downloadable_samples[' + element.id + '][type]'" :value="element.type"/>

                            <template v-if="element.type == 'file'">
                                <input type="hidden" :name="'downloadable_samples[' + element.id + '][file]'" :value="element.file"/>

                                <input type="hidden" :name="['downloadable_samples[' + element.id + '][file_name]']" v-model="element.file_name"/>
                            </template>

                            <template v-else>
                                <input type="hidden" :name="['downloadable_samples[' + element.id + '][url]']" v-model="element.url"/>
                            </template>

                            <!-- Information -->
                            <div class="flex gap-2.5">
                                <!-- Drag Icon -->
                                <i class="icon-drag text-xl text-gray-600 dark:text-gray-300 transition-all pointer-events-none"></i>

                                <div class="grid gap-1.5">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        @{{ element.title }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        <template v-if="element.type == 'file'">
                                            <div>
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.file')

                                                <a
                                                    :href="element.file_url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.file_name }}
                                                </a>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <div>
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.url')

                                                <a
                                                    :href="element.url"
                                                    target="_blank"
                                                    class="text-blue-600 break-all transition-all hover:underline"
                                                >
                                                    @{{ element.url }}
                                                </a>
                                            </div>
                                        </template>
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-1 text-right">
                                <div class="flex gap-x-5 items-center">
                                    <p
                                        class="text-red-600 cursor-pointer transition-all hover:underline"
                                        @click="remove(element)"
                                    >
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.delete-btn')
                                    </p>

                                    <p
                                        class="text-blue-600 cursor-pointer transition-all hover:underline"
                                        @click="selectedSample = element; $refs.updateCreateSampleDrawer.open()"
                                    >
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.edit-btn')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- For Empty Links -->
            <div
                class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                    class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-base text-gray-400 font-semibold">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.empty-info')
                    </p>
                </div>
                
                <!-- Add Row Button -->
                <div
                    class="secondary-button text-sm"
                    @click="resetForm(); $refs.updateCreateSampleDrawer.open()"
                >
                    @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.add-btn')
                </div>
            </div>


            <!-- Add Option Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <!-- Search Drawer -->
                    <x-admin::drawer ref="updateCreateSampleDrawer">
                        <!-- Drawer Header -->
                        <x-slot:header>
                            <div class="grid gap-3">
                                <div class="flex justify-between items-center">
                                    <p class="text-xl font-medium dark:text-white">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.title')
                                    </p>

                                    <button class="mr-11 primary-button">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.save-btn')
                                    </button>
                                </div>
                            </div>
                        </x-slot:header>

                        <!-- Drawer Content -->
                        <x-slot:content class="!p-0">
                            <!-- Modal Content -->
                            <div class="px-4 py-2.5">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="title"
                                        v-model="selectedSample.title"
                                        rules="required"
                                        :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.name')"
                                    />
            
                                    <x-admin::form.control-group.error control-name="title" />
                                </x-admin::form.control-group>

                                <div class="flex gap-4">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.file-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            v-model="selectedSample.type"
                                            rules="required"
                                            :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.file-type')"
                                        >
                                            <option value="file">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.file')
                                            </option>

                                            <option value="url">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.url')
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="type" />
                                    </x-admin::form.control-group>

                                    <!-- If Type is File -->
                                    <template v-if="selectedSample.type == 'file'">
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.file')
                                            </x-admin::form.control-group.label>
                                            
                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file"
                                                rules="required"
                                                v-model="selectedSample.file"
                                                :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.file')"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file_name"
                                                v-model="selectedSample.file_name"
                                            />

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="file_url"
                                                v-model="selectedSample.file_url"
                                            />

                                            <input
                                                type="file"
                                                name="file"
                                                class="flex w-full min-h-10 py-1 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                                :class="[errors['file'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                ref="file"
                                                @change="uploadFile('file')"
                                            />

                                            <a
                                                :href="selectedSample.sample_file_url"
                                                target="_blank"
                                                class="text-blue-600 break-all transition-all hover:underline"
                                                v-if="selectedSample.file_url"
                                            >
                                                @{{ selectedSample.file_name }}
                                            </a>
                    
                                            <x-admin::form.control-group.error control-name="file" />
                                        </x-admin::form.control-group>
                                    </template>

                                    <!-- Else URL -->
                                    <template v-else>
                                        <x-admin::form.control-group class="flex-1">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.url')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="url"
                                                v-model="selectedSample.url"
                                                rules="required"
                                                :label="trans('marketplace::app.admin.sellers.assign-product.downloadable.samples.update-create.url')"
                                            />
                    
                                            <x-admin::form.control-group.error control-name="url" />
                                        </x-admin::form.control-group>
                                    </template>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-admin::drawer>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-downloadable-links', {
            template: '#v-downloadable-links-template',

            props: ['errors'],

            data() {
                return {
                    links: [],
                    selectedLink: {},
                }
            },

            methods: {
                updateOrCreate(params) {
                    if (this.selectedLink.id == undefined) {
                        params.id = 'link_' + this.links.length;

                        this.links.push(params);
                    } else {
                        const indexToUpdate = this.links.findIndex(link => link.id === params.id);

                        this.links[indexToUpdate] = params;
                    }

                    this.resetForm();

                    this.$refs.updateCreateLinkDrawer.close();
                },

                uploadFile(type) {
                    let self = this;

                    let formData = new FormData();

                    formData.append(type, this.$refs[type].files[0]);

                    this.$axios.post("{{ route('admin.catalog.products.upload_link', $product->id) }}", formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function(response) {
                            Object.assign(self.selectedLink, response.data);
                        })
                        .catch(function() {});
                },

                remove(link) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.links.indexOf(link);

                            this.links.splice(index, 1);
                        }
                    });
                },

                resetForm() {
                    this.selectedLink = {
                        title: '',
                        type: 'file',
                        file: '',
                        file_name: '',
                        file_url: '',
                        url: '',
                        sample_type: 'file',
                        sample_file: '',
                        sample_file_name: '',
                        sample_file_url: '',
                        sample_url: '',
                        downloads: 1,
                        sort_order: 0
                    };
                },
            }
        });

        app.component('v-downloadable-samples', {
            template: '#v-downloadable-samples-template',

            props: ['errors'],

            data() {
                return {
                    samples: [],
                    selectedSample: {}
                }
            },

            methods: {
                updateOrCreate(params) {
                    if (this.selectedSample.id == undefined) {
                        params.id = 'sample_' + this.samples.length;

                        this.samples.push(params);
                    } else {
                        const indexToUpdate = this.samples.findIndex(link => link.id === params.id);

                        this.samples[indexToUpdate] = params;
                    }

                    this.resetForm();

                    this.$refs.updateCreateSampleDrawer.close();
                },

                uploadFile(type) {
                    let self = this;

                    let formData = new FormData();

                    formData.append(type, this.$refs[type].files[0]);

                    this.$axios.post("{{ route('admin.catalog.products.upload_sample', $product->id) }}", formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function(response) {
                            Object.assign(self.selectedSample, response.data);
                        })
                        .catch(function() {});
                },

                remove(sample) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.samples.indexOf(sample)

                            this.samples.splice(index, 1)
                        }
                    });
                },

                resetForm() {
                    this.selectedSample = {
                        title: '',
                        type: 'file',
                        file: '',
                        file_name: '',
                        file_url: '',
                        url: '',
                        sort_order: 0
                    };
                },
            }
        });
    </script>
@endpushOnce