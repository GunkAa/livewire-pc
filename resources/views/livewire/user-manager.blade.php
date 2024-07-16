
<!-- livewire/user-manager.blade.php -->

<div class="max-w-4xl mx-auto flex flex-wrap">
    <!-- User Creation Form -->
    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
        @if (!$editingUser)
        <form wire:submit.prevent="create" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Create User</h2>
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
            <button type="submit" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create User</button>
        </form>
        @else
        <!-- Update User Form -->
        <form wire:submit.prevent="update" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Update User</h2>
            <!-- Update form fields -->
            <input type="hidden" wire:model="selectedUserId">
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
                <button type="submit" class="inline-block px-2 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update User</button>
                <button type="button" wire:click="delete({{ $selectedUserId  }})" class="inline-block px-2 py-2 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button>
                <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
            </div>
        </form>
        @endif
    </div>

    <!-- User List -->
    <div class="w-full md:w-1/2 lg:w-2/3 px-4 mb-8">
        <ul class="space-y-4">
            @foreach ($users as $user)
                <li class="bg-white rounded-lg shadow-md p-4">
                    <!-- User details -->
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-lg font-semibold">{{ $user->name }}</span>
                            <p class="text-gray-600">{{ $user->comments }}</p>
                        </div>
                        <!-- Edit and delete buttons -->
                        <div class="flex items-center space-x-2">
                            <button wire:click="edit({{ $user->id }})" class="text-sm text-teal-500 font-semibold rounded hover:text-teal-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $user->id }})" class="text-sm text-red-500 font-semibold rounded hover:text-teal-800 mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

