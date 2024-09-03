<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('All Areas') }}
    </h2>
</x-slot>

<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden">
            <div class="p-6 text-gray-900">
                <div class="space-y-6">

                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-8">
                        <!-- Left side: Search and Category -->
                        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                            <input wire:model.live="searchQuery" type="search" id="search" placeholder="Search..."
                                   class="w-full md:w-auto px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300">

                            <select wire:model.live="searchCategory" name="category" class="w-full md:w-auto px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300">
                                <option value="0">-- CHOOSE CATEGORY --</option>
                                @foreach($categories as $id => $category)
                                    <option value="{{ $id }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Right side: Add new areas button -->
                        <div class="w-full md:w-auto">
                            <a href="{{ route('areas.create') }}" class="inline-flex items-center w-full md:w-auto justify-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                Add new area
                            </a>
                        </div>
                    </div>

                    <div class="text-red-600" wire:loading.delay>Loading...</div>

                    <div wire:loading wire:target="deleteArea">
                        Removing area...
                    </div>

                    <div class="overflow-x-auto" wire:loading.delay.class="opacity-50">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                            <tr>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Category</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Description</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Valid From</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Valid To</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">In Breaches</span>
                                </th>
                                <th class="px-2 md:px-6 py-3 bg-gray-50 text-left"></th>
                            </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            @forelse($areas as $area)
                                <tr class="bg-white">
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $area->name }}
                                    </td>
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $area->category->name }}
                                    </td>
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $area->description }}
                                    </td>
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $area->valid_from->format('d/m/Y') }}
                                    </td>
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        @if($area->valid_to)
                                            {{ $area->valid_to->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="px-2 md:px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        @if($area->in_breaches) Yes @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('areas.edit', $area) }}"
                                           class="inline-flex items-center px-4 py-1 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                            Edit </a>
                                        <a href="#"
                                           wire:click="deleteArea({{ $area->id }})"
                                           onclick="return confirm('Are you sure?') || event.stopImmediatePropagation()"
                                           class="inline-flex items-center px-4 py-1 bg-red-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-sm" colspan="7">
                                        No Areas were found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $areas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
