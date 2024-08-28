<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Area') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Left Column (3 columns wide) -->
                            <div class="md:col-span-3">
                                <x-map-polygon />
                            </div>
                            <!-- Right Column (1 column wide) -->
                            <livewire:areas-edit :area="$area" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
