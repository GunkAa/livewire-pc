<div>
    <div class="container mx-auto">
        <h1 class="text-3xl font-semibold mb-5">PC Availability</h1>

        @if ($showForm)
            <form wire:submit.prevent="updateAssignment" class="bg-gray-200 rounded-lg shadow-md p-6 mb-8"> <!-- Added mb-8 for more space -->
                <h2 class="text-lg font-semibold mb-4">Update Assignment: {{ $selectedPcName }}</h2>

                <!-- Hidden Assignment ID -->
                <input type="hidden" wire:model="selectedAssignmentId">

                <!-- User Selection -->
                <div class="mb-4">
                    <label for="edit-user" class="text-sm font-semibold text-gray-700">User:</label>
                    <select wire:model="selectedUserId" id="edit-user" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-2">
                    <button type="submit" class="inline-block px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update</button>
                    <button type="button" wire:click="deleteAssignment({{ $selectedAssignmentId }})" class="inline-block px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button>
                    <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-1 bg-gray-600 text-white text-xs font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                </div>
            </form>
        @endif

        
        @if ($createForm)
            <form wire:submit.prevent="updateAssignment" class="bg-gray-200 rounded-lg shadow-md p-6 mb-8"> <!-- Added mb-8 for more space -->
                <h2 class="text-lg font-semibold mb-4">Create Assignment: {{ $selectedPcName }}</h2>

                <!-- Hidden Assignment ID -->
                <input type="hidden" wire:model="selectedAssignmentId">

                <!-- User Selection -->
                <div class="mb-4">
                    <label for="edit-user" class="text-sm font-semibold text-gray-700">User:</label>
                    <select wire:model="selectedUserId" id="edit-user" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-2">
                    <button type="submit" class="inline-block px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
                    <button type="button" wire:click="cancelEdit" class="inline-block px-2 py-1 bg-gray-600 text-white text-xs font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                </div>
            </form>
        @endif

        <!-- Select Day -->
        <form wire:submit.prevent="loadAvailability" class="mb-8 p-3 bg-gray-200 rounded-lg"> <!-- Added mb-8 for more space -->
            <div class="mb-4">
                <label for="day" class="font-semibold">Select a Daypart:</label>
                <select wire:model="selectedDay" wire:change="dayChanged($event.target.value)" id="day" name="day" class="block w-full py-2 px-3 mt-4 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($days as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <!-- Render Rooms and PC's -->
        <div class="grid grid-cols-2 gap-4 mt-4">
            @foreach ($rooms as $room)
                @if (isset($availabilityByDay[$room->id]))
                    <div class="p-3 bg-gray-200 rounded-lg">
                        <h2 class="text-xl font-semibold mb-2">{{ $room->name }}</h2>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($availabilityByDay[$room->id] as $pcDay)
                                <button wire:click.prevent="editAssignment({{ $pcDay['pc']->id }})" class="bg-{{ $pcDay['isAvailable'] ? 'green' : 'red' }}-500 text-white font-bold text-center py-4 rounded-lg">
                                    <p class="text-lg">{{ $pcDay['pc']->name }}</p>
                                    <p class="text-sm">{{ $pcDay['pc']->room->name }}</p>
                                    <p class="text-sm text-gray-700 bg-slate-300">{{ $pcDay['pc']->assignedUserName($selectedDay) }}</p>
                                    <p>Available: {{ $pcDay['isAvailable'] ? 'Yes' : 'No' }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
