<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\Pc;
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
    public $dayOfWeek;
    public $showForm = false;  // To control form visibility
    public $selectedAssignmentId;
    public $pcs;

    public function mount()
    {
        $this->days = [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
        $this->selectedDay = $this->days[0];
        $this->rooms = Room::with('pcs')->get();
        $this->pcs = Pc::all(); // Ensure this line is present
        $this->users = User::all();
        $this->loadAvailability();
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
    }

    public function dayChanged($value)
    {
        $this->selectedDay = $value;
        $this->loadAvailability();
    }

    public function editAssignment($assignmentId)
    {
        $assignment = Assignment::find($assignmentId);
        if ($assignment) {
            $this->selectedUserId = $assignment->user_id;
            $this->selectedPcId = $assignment->pc_id;
            $this->dayOfWeek = $assignment->day_of_week;
            $this->selectedAssignmentId = $assignment->id;
            $this->showForm = true;  // Show the form
        }
    }

    public function cancelEdit()
    {
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
        $this->showForm = false;
    }

    public function updateAssignment()
    {
        $validatedData = $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon'
        ]);

        $assignment = Assignment::findOrFail($this->selectedAssignmentId);

        $assignment->update([
            'user_id' => $validatedData['selectedUserId'],
            'pc_id' => $validatedData['selectedPcId'],
            'day_of_week' => $validatedData['dayOfWeek'],
        ]);

        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    public function deleteAssignment($assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $assignment->delete();

        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    public function render()
    {
        return view('livewire.home');
    }
}
