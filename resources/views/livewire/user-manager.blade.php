
<!-- livewire/user-manager.blade.php -->

<div class="max-w-2xl mx-auto">
    <!-- User Creation Form -->
    <form wire:submit.prevent="createUser" class="mb-8">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input wire:model.defer="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="comments" class="block text-sm font-medium text-gray-700">Comments:</label>
            <textarea wire:model.defer="comments" id="comments" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('comments') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create User</button>
    </form>

    <!-- User List -->
    <ul>
        @foreach ($users as $user)
            <li class="mb-4">
                <span class="font-medium">{{ $user->name }}</span>
                <span class="text-gray-500"> - {{ $user->comments }}</span>
                <button wire:click="selectUser({{ $user->id }})" class="ml-4 text-sm text-blue-500">Edit</button>
            </li>
        @endforeach
    </ul>

    <!-- Update User Form -->
    @if ($selectedUserId)
    <form wire:submit.prevent="updateUser" class="mt-8">
        <input type="hidden" wire:model="selectedUserId">
        <div class="mb-4">
            <label for="edit-name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input wire:model.defer="name" type="text" id="edit-name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="edit-comments" class="block text-sm font-medium text-gray-700">Comments:</label>
            <textarea wire:model.defer="comments" id="edit-comments" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('comments') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update User</button>
    </form>
    @endif
    
</div>