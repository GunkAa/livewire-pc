<div class="container mx-auto">
    <h1 class="text-3xl font-semibold mb-5">PC Availability</h1>

    <form>
        <div class="mb-4">
            <label for="day" class="font-semibold">Select a Day:</label>
            <select id="day" name="day" onchange="this.form.submit()" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @foreach ($days as $day)
                    <option value="{{ $day }}" {{ $selectedDay === $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="flex flex-wrap">
        <div class="w-1/2 p-3">
            <h2 class="text-xl font-semibold mb-2">{{ $selectedDay }}</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($availabilityByDay as $pcDay)
                    <a href="{{ route('pcs.show', $pcDay['pc']->id) }}" class="bg-{{ $pcDay['isAvailable'] ? 'green' : 'red' }}-500 text-white font-bold text-center py-4 rounded-lg">
                        <p class="text-lg">{{ $pcDay['pc']->name }}</p>
                        <p class="text-sm">{{ $pcDay['pc']->room->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
