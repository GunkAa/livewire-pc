<div class="container mx-auto">
    <h1 class="text-3xl font-semibold mb-5">Assignment Manager</h1>

    <!-- Day Selection Dropdown -->
    <div class="mb-4">
        <label for="day" class="font-semibold">Select a Day:</label>
        <select wire:model="selectedDay" id="day" name="day" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @foreach ($days as $day)
                <option value="{{ $day }}">{{ $day }}</option>
            @endforeach
        </select>
    </div>

    <!-- PC Availability -->
    <div class="grid grid-cols-4 gap-4">
        @foreach ($availabilityByDay as $pcDay)
            <div class="bg-{{ $pcDay['isAvailable'] ? 'green' : 'red' }}-500 text-white font-bold text-center py-4 rounded-lg">
                <p class="text-lg">{{ $pcDay['pc']->name }}</p>
                <p class="text-sm">{{ $pcDay['pc']->room->name }}</p>
            </div>
        @endforeach
    </div>

    <!-- PC Assignments Table -->
    <h2 class="text-2xl font-semibold mt-8 mb-4">PC Assignments</h2>
    <div class="overflow-x-auto">
        <table class="table-auto border w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">PC</th>
                    <th class="px-4 py-2">Day of Week</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pcAssignments as $pcAssignment)
                    <tr>
                        <td class="border px-4 py-2">{{ $pcAssignment->user->name }}</td>
                        <td class="border px-4 py-2">{{ $pcAssignment->pc->name }}</td>
                        <td class="border px-4 py-2">{{ $pcAssignment->day_of_week }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div