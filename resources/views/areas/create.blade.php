<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Area') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Left Column (3 columns wide) -->
        <div class="md:col-span-3">
            <x-map-polygon />
        </div>
        <!-- Right Column (1 column wide) -->
        <livewire:areas-create />
    </div>

</x-app-layout>
