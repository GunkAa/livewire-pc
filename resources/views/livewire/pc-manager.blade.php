<!-- PC Creation Form -->
<div class="max-w-4xl mx-auto flex flex-wrap">
    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
        @if (!$editingPC)
            <form wire:submit.prevent="create" class="bg-gray-200 rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Create PC</h2>
                <div class="mb-4">
                    <label for="name" class="text-sm font-semibold text-gray-700">Name:</label>
                    <input wire:model.defer="name" type="text" id="name" class="mt-1 px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="comments" class="text-sm font-semibold text-gray-700">Comments:</label>
                    <textarea wire:model.defer="comments" id="comments" rows="3" class="mt-1 px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full"></textarea>
                    @error('comments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="room" class="text-sm font-semibold text-gray-700">Room:</label>
                    <select wire:model="selectedRoomId" id="room" class="mt-1 block w-full px-4 py-2 bg-white rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a room</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoomId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create PC</button>
            </form>
        @else
            <!-- Update PC Form -->
            <form wire:submit.prevent="update" class="bg-gray-200 rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Update PC</h2>
                <!-- Update form fields -->
                <input type="hidden" wire:model="selectedPCId" name="selectedPCId"> <!-- Hidden input field -->
                <div class="mb-4">
                    <label for="edit-name" class="text-sm font-semibold text-gray-700">Name:</label>
                    <input wire:model.defer="name" type="text" id="edit-name" class="mt-1 px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="edit-comments" class="text-sm font-semibold text-gray-700">Comments:</label>
                    <textarea wire:model.defer="comments" id="edit-comments" rows="3" class="mt-1 px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full"></textarea>
                    @error('comments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="room" class="text-sm font-semibold text-gray-700">Room:</label>
                    <select wire:model="selectedRoomId" id="room" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a room</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoomId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="inline-block px-2 py-1 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update PC</button>
                    <button type="button" wire:click="delete({{ $selectedPCId }})" class="inline-block px-2 py-1 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button>
                    <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-1 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                </div>
            </form>
        @endif
    </div>

    {{-- Display Room Selection and PC List --}}
    <div class="ml-24 bg-gray-200 rounded-lg shadow-md p-6" style="width: 395.438px;">
        <div class="mb-4">
            <h3 class="text-lg font-semibold mb-4">PC List</h3>
            <label for="room" class="font-semibold">Select a Room:</label>
            <select wire:model="room_id" wire:change="filterByRoom" id="room" name="room" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>            
        </div>

                <!-- Search Bar -->
                <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Search PCs..." class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>

        <!-- Sorting Buttons -->
        <div class="mb-4 flex space-x-4">
            <!-- Sort by Name -->
            <button wire:click="sortBy('name')" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Name
                @if ($sortField === 'name')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>

            <!-- Sort by Date Created -->
            <button wire:click="sortBy('created_at')" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Date Created
                @if ($sortField === 'created_at')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>

    <!-- PC List -->
    <div class="w-full md:w-1/2 lg:w-full px-4 mb-8"> <!-- Adjusted width -->
        <ul class="space-y-4">
            @foreach ($pcs as $pc)
                @if (!$selectedFilterRoomId || $pc->room_id == $selectedFilterRoomId)
                    <li class="bg-white rounded-lg shadow-md p-4">
                        <!-- PC details -->
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-lg font-semibold">{{ $pc->name }}</span>
                                <p class="text-gray-600">{{ $pc->comments }}</p>
                                <p class="text-gray-600">{{ $pc->room->name }}</p>
                            </div>
                            <!-- Edit and delete buttons -->
                            <div class="flex items-center space-x-2">
                                <button wire:click="edit({{ $pc->id }})" class="text-sm text-teal-500 font-semibold rounded hover:text-teal-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $pc->id }})" class="text-sm text-red-500 font-semibold rounded hover:text-teal-800 mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $pcs->links() }}
        </div>
    </div>
</div>

