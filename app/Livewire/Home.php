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
        $this->selectedDay = $this->days[0];
        $this->rooms = Room::with('pcs')->get();
        $this->users = User::all();
        $this->loadAvailability();

        // Debugging initial load

        // dd('Initial Load', [
        //     'selectedDay' => $this->selectedDay,
        //     'rooms' => $this->rooms,
        //     'users' => $this->users,
        // ]);
    }

    public function loadAvailability()
    {
        $this->availabilityByDay = [];

        foreach ($this->rooms as $room) {
            $pcs = $room->pcs;
            foreach ($pcs as $pc) {
                $isAvailable = $pc->isAvailable($this->selectedDay);
                $this->availabilityByDay[$room->id][] = [
                    'pc' => $pc,
                    'isAvailable' => $isAvailable,
                ];
            }
        }

        // Debugging availability load

        // dd('Availability Loaded', [
        //     'selectedDay' => $this->selectedDay,
        //     'availabilityByDay' => $this->availabilityByDay,
        // ]);
    }

    public function dayChanged($value)
    {
        $this->selectedDay = $value;
        $this->loadAvailability();

        // Debugging day update

        // dd('Day Updated', [
        //     'selectedDay' => $this->selectedDay,
        //     'availabilityByDay' => $this->availabilityByDay,
        // ]);
    }

    public function editAssignment($assignmentId)
    {
        if (!$assignmentId) {
            // Handle the case when there is no assignment
            $this->editingAssignment = null;
            $this->selectedUserId = null;
            $this->selectedPcId = null;
            return;
        }
    
        $assignment = Assignment::find($assignmentId);
    
        if ($assignment) {
            $this->selectedUserId = $assignment->user_id;
            $this->selectedPcId = $assignment->pc_id;
            $this->editingAssignment = $assignment;
        } else {
            $this->editingAssignment = null;
        }
    }
    public function updateAssignment()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
        ]);

        $assignment = Assignment::findOrFail($this->editingAssignment);

        $assignment->update([
            'user_id' => $this->selectedUserId,
            'pc_id' => $this->selectedPcId,
        ]);

        session()->flash('success', 'Assignment updated successfully.');
        $this->reset(['selectedUserId', 'selectedPcId', 'editingAssignment']);

        return redirect()->to('/home');
    }

    public function render()
    {
        $this->loadAvailability();

        // Debugging render

        // dd('Render', [
        //     'selectedDay' => $this->selectedDay,
        //     'availabilityByDay' => $this->availabilityByDay,
        // ]);

        return view('livewire.home');
    }
}
