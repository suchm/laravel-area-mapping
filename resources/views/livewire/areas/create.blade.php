<div class="md:col-span-1 bg-white shadow-md rounded-lg p-4 pt-0">

        <form wire:submit.prevent="submit" id="file-upload-form" action="#" enctype="multipart/form-data">
            @csrf
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500" id="formTab" data-tabs-target="#formFields" type="button" role="tab" aria-controls="formFields" aria-selected="false">Form</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="geojsonTab" data-tabs-target="#geojsonFields" type="button" role="tab" aria-controls="geojsonFields" aria-selected="false">Geojson</button>
                </li>
            </ul>
        </div>
            <div class="mb-2">@error('form.geojson') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror</div>

        <div id="default-tab-content">
            <div class="rounded-lg" id="formFields" role="tabpanel" aria-labelledby="formTab">

                <div class="mb-2">
                    <label class="block text-gray-700">Upload File</label>
                    <div></div>
                    <div id="file-dropzone" class="dropzone min-h-28 border-dashed border-2 p-4 flex flex-col justify-center items-center border-gray-500 bg-gray-100" wire:ignore>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <div class="text-center" wire:loading.remove wire.target="file">Drop Your GeoJson File Here</div>
                        <div id="dropzone-preview"></div>
                    </div>
                    @error('file') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Name Input -->
                <div class="mb-2">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" wire:model.defer="form.name" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('form.name') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror
                </div>

                <div class="mb-2">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" wire:model.defer="form.description" rows="4" class="block p-2.5 w-full text-gray-900 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                    @error('form.description') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Category Dropdown -->
                <div class="mb-2">
                    <label for="category" class="block text-gray-700">Category</label>
                    <select wire:model="form.category" name="category" id="category"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $id => $category)
                            <option value="{{ $id }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('form.category')
                    <span class="mt-2 text-sm text-red-600 error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date Valid From Input -->
                <div class="mb-2">
                    <label for="valid_from" class="block text-gray-700">Valid From</label>
                    <input type="date" id="valid_from" wire:model.defer="form.valid_from" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('form.valid_from') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Date Valid To Input -->
                <div class="mb-2">
                    <label for="valid_to" class="block text-gray-700">Valid To</label>
                    <input type="date" id="valid_to" wire:model.defer="form.valid_to" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('form.valid_to') <span class="text-red-500 text-sm error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Display in Breaches List Checkbox -->
                <div class="mb-2">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model.defer="form.in_breaches" class="form-checkbox border-gray-300 rounded-md shadow-sm">
                        <span class="ml-2 text-gray-700">Display in Breaches List</span>
                    </label>
                </div>
            </div>
            <div class="hidden rounded-lg mb-4" id="geojsonFields" role="tabpanel" aria-labelledby="geojsonTab">
                <textarea wire:model.defer="form.geojson" id="geojson" name="geojson" rows="4" class="min-h-96 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Geojson data..."></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600">
                Submit
            </button>
        </div>
    </form>
</div>
