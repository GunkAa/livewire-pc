<div>
    <div class="container mx-auto">
        <h1 class="text-3xl font-semibold mb-5">PC Availability</h1>

        <form wire:submit.prevent="loadAvailability">
            <div class="mb-4">
                <label for="day" class="font-semibold">Select a Daypart:</label>
                <select wire:model="selectedDay" wire:change="dayChanged($event.target.value)" id="day" name="day" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($days as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="grid grid-cols-2 gap-4">
            @foreach ($rooms as $room)
                @if (isset($availabilityByDay[$room->id]))
                    <div class="p-3 bg-white rounded-lg">
                        <h2 class="text-xl font-semibold mb-2">{{ $room->name }}</h2>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($availabilityByDay[$room->id] as $pcDay)
                            @php
                                $assignment = \App\Models\Assignment::where('pc_id', $pcDay['pc']->id)
                                    ->where('day_of_week', $selectedDay)
                                    ->first();
                            @endphp
                            <a href="#" wire:click.prevent="editAssignment({{ $assignment ? $assignment->id : 0 }})" class="bg-{{ $pcDay['isAvailable'] ? 'green' : 'red' }}-500 text-white font-bold text-center py-4 rounded-lg">
                                <p class="text-lg">{{ $pcDay['pc']->name }}</p>
                                <p class="text-sm">{{ $pcDay['pc']->room->name }}</p>
                                <p class="text-sm text-gray-700 bg-slate-300">{{ $pcDay['pc']->assignedUserName($selectedDay) }}</p>
                                <p>Available: {{ $pcDay['isAvailable'] ? 'Yes' : 'No' }}</p>
                            </a>
                        @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Assignment Edit Form -->
        @if ($editingAssignment)
            <form wire:submit.prevent="updateAssignment">
                <div class="mt-5">
                    <label for="selectedUserId" class="font-semibold">Select User:</label>
                    <select wire:model="selectedUserId" id="selectedUserId" name="selectedUserId" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-5">
                    <label for="selectedPcId" class="font-semibold">Select PC:</label>
                    <select wire:model="selectedPcId" id="selectedPcId" name="selectedPcId" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($rooms as $room)
                            @foreach ($room->pcs as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('selectedPcId') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="mt-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Assignment</button>
            </form>
        @endif
    </div>
</div>
