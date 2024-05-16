<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use Livewire\Component;
use App\Models\Assignment;

class Home extends Component
{
    public $days;
    public $selectedDay;
    public $rooms;
    public $users;
    public $availabilityByDay;
    public $selectedUserId;
    public $selectedPcId;
    public $editingAssignment;

    public function mount()
    {
        $this->days = [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
        $this->selectedDay = Carbon::now()->format('l');
        $this->rooms = Room::with('pcs')->get();
        $this->users = User::all();
        $this->loadAvailability();
    }

    public function loadAvailability()
    {
        $this->availabilityByDay = [];
        foreach ($this->rooms as $room) {
            $pcs = $room->pcs;
            $this->availabilityByDay[$room->id] = $this->getAvailabilityByDay($pcs, $this->selectedDay);
        }
    }

    private function getAvailabilityByDay($pcs, $selectedDay)
    {
        $availabilityByDay = [];

        foreach ($pcs as $pc) {
            $isAvailable = $pc->isAvailable($selectedDay);
            $availabilityByDay[] = [
                'pc' => $pc,
                'isAvailable' => $isAvailable,
            ];
        }

        return $availabilityByDay;
    }

    public function editAssignment($assignmentId)
    {
        $this->editingAssignment = Assignment::findOrFail($assignmentId);
        $this->selectedUserId = $this->editingAssignment->user_id;
        $this->selectedPcId = $this->editingAssignment->pc_id;
    }

    public function updateAssignment()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
        ]);

        $this->editingAssignment->update([
            'user_id' => $this->selectedUserId,
            'pc_id' => $this->selectedPcId,
        ]);

        session()->flash('success', 'Assignment updated successfully.');
        $this->reset(['selectedUserId', 'selectedPcId', 'editingAssignment']);
    }

    public function render()
    {
        return view('livewire.home');
    }
}