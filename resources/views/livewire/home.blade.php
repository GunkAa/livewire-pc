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

    <!-- Include the AssignmentManager component -->
    <livewire:assignment-manager :users="$users" :pcs="$pcs" :days="$days"/>
</div>
