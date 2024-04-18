<div class="max-w-4xl mx-auto flex flex-wrap">
    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
        <!-- Assignment Creation Form -->
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

            <!-- Day and Time Selection -->
            <div class="mb-4">
                <label for="day_and_time" class="text-sm font-semibold text-gray-700">Day and Time:</label>
                <select wire:model="dayOfWeek" id="day_and_time" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Select a day and time</option>
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
    </div>
</div>