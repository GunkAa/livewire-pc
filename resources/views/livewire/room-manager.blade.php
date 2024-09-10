<!-- PC Creation Form -->
<div class="max-w-4xl mx-auto flex flex-wrap">
    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
        @if (!$editingRoom)
            <form wire:submit.prevent="create" class="bg-gray-200 rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Create Room</h2>
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
                <button type="submit" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create PC</button>
            </form>
        @else
            <!-- Update PC Form -->
            <form wire:submit.prevent="update" class="bg-gray-200 rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Update Room</h2>
                <!-- Update form fields -->
                <input type="hidden" wire:model="selectedUserId" name="selectedUserId"> <!-- Hidden input field -->
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
                <div class="flex space-x-4">
                    <button type="submit" class="inline-block px-2 py-1 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update PC</button>
                    <button type="button" wire:click="delete({{ $selectedRoomId }})" class="inline-block px-2 py-1 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button>
                    <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-1 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                </div>
            </form>
        @endif
    </div>
