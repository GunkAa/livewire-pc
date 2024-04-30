<div>
    <div class="max-w-4xl mx-auto flex flex-wrap">
        <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
        <!-- Create Assignment Form -->
        @if (!$editingAssignment)
            <form wire:submit.prevent="createAssignment" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Create Assignment</h2>

                <!-- User Selection -->
                <div class="mb-4">
                    <label for="user" class="text-sm font-semibold text-gray-700">User:</label>
                    <select wire:model="selectedUserId" id="user" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- PC Selection -->
                <div class="mb-4">
                    <label for="pc" class="text-sm font-semibold text-gray-700">PC:</label>
                    <select wire:model="selectedPcId" id="pc" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a PC</option>
                        @foreach ($pcs as $pc)
                            <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedPcId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Day Selection -->
                <div class="mb-4">
                    <label for="day" class="text-sm font-semibold text-gray-700">Day:</label>
                    <select wire:model="dayOfWeek" id="day" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a day</option>
                        @foreach ($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('dayOfWeek') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Assignment</button>
                </div>
            </form>

        <!-- Update Assignment Form -->
        @else
            <form wire:submit.prevent="updateAssignment" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Update Assignment</h2>

                <!-- Hidden Assignment ID -->
                <input type="hidden" wire:model="selectedAssignmentId">

                <!-- User Selection -->
                <div class="mb-4">
                    <label for="edit-user" class="text-sm font-semibold text-gray-700">User:</label>
                    <select wire:model="selectedUserId" id="edit-user" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- PC Selection -->
                <div class="mb-4">
                    <label for="edit-pc" class="text-sm font-semibold text-gray-700">PC:</label>
                    <select wire:model="selectedPcId" id="edit-pc" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a PC</option>
                        @foreach ($pcs as $pc)
                            <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedPcId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Day Selection -->
                <div class="mb-4">
                    <label for="edit-day" class="text-sm font-semibold text-gray-700">Day:</label>
                    <select wire:model="dayOfWeek" id="edit-day" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a day</option>
                        @foreach ($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('dayOfWeek') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" class="inline-block px-2 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update Assignment</button>
                    {{-- <button type="button" wire:click="deleteAssignment({{ $assignment->id }})" class="inline-block px-2 py-2 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button> --}}
                    <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                </div>
            </form>
        @endif
    
        <!-- Assignment List -->
        <div class="w-full mt-8">
            <h2 class="text-lg font-semibold mb-4">Assignments</h2>
            <ul class="space-y-4">
                @foreach ($assignments as $assignment)
                    <li class="bg-white rounded-lg shadow-md p-4">
                        <!-- Editable Assignment Details -->
                        @if ($editingAssignment === $assignment->id)
                            <!-- ... (same as previous) ... -->
                        @else
                            <!-- Display Mode -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-lg font-semibold">{{ $assignment->user->name }}</span>
                                    <p class="text-gray-600">{{ $assignment->pc->name }}</p>
                                    <p class="text-gray-600">{{ $assignment->day_of_week }}</p>
                                </div>
                            <!-- Edit and delete buttons -->
                            <div class="flex items-center space-x-2">
                                <button wire:click="editAssignment({{ $assignment->id }})" class="text-sm text-teal-500 font-semibold rounded hover:text-teal-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <button wire:click="deleteAssignment({{ $assignment->id }})" class="text-sm text-red-500 font-semibold rounded hover:text-teal-800 mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>