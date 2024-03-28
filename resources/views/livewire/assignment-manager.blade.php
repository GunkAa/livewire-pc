<!-- assignment-manager.blade.php -->
<div class="container mx-auto">
    <h1 class="text-3xl font-semibold mb-5">PC Availability</h1>

    <!-- Day Selection Dropdown -->
    <div class="mb-4">
        <label for="day" class="font-semibold">Select a Daypart:</label>
        <select wire:model="selectedDay" id="day" name="day" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @foreach ($days as $day)
                <option value="{{ $day }}">{{ $day }}</option>
            @endforeach
        </select>
    </div>

    <!-- PC Availability -->
    <div class="grid grid-cols-2 gap-4">
        @foreach ($availabilityByRoom as $roomName => $availabilityByDay)
            <div class="p-3 bg-white rounded-lg">
                <h2 class="text-xl font-semibold mb-2">{{ $roomName }}</h2>
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($availabilityByDay as $pcDay)
                        <div wire:click="assignUser({{ $pcDay['pc']->id }})" class="bg-{{ $pcDay['isAvailable'] ? 'green' : 'red' }}-500 text-white font-bold text-center py-4 rounded-lg cursor-pointer">
                            <p class="text-lg">{{ $pcDay['pc']->name }}</p>
                            <p class="text-sm">{{ $pcDay['pc']->room->name }}</p>
                                   
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div