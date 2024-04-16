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
                            @if($pcDay['assignedUser'])
                                <p class="text-sm text-gray-700 bg-slate-300">{{ $pcDay['assignedUser']->name }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Markup -->
    @if($showAssignUserModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            <div class="relative bg-white p-6 rounded-lg shadow-lg w-1/2">
                <span class="absolute top-0 right-0 p-4 cursor-pointer" wire:click="closeModal">&times;</span>
                <h2 class="text-xl font-semibold mb-4">Select User</h2>
                <form wire:submit.prevent="assign">
                    <label for="user_id">Assign User:</label>
                    <select wire:model="selectedUserId" id="user_id" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a user</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign</button>
                </form>
            </div>
        </div>
    @endif
</div>