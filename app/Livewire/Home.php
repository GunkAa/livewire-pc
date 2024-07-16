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
    // Define component properties
    public $selectedUserId;
    public $selectedPcId;
    public $dayOfWeek;
    public $days;
    public $selectedDay;
    public $rooms;
    public $users;
    public $availabilityByDay;
    public $editingAssignment;
    public $showForm = false;  // To control form visibility
    public $selectedAssignmentId;
    public $assignmentId;
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

    public function editAssignment($pcId)
    {
        $assignment = Assignment::where('pc_id', $pcId)
            ->where('day_of_week', $this->selectedDay)
            ->first();
    
        if ($assignment) {
            // Assignment found: Populate form fields for editing
            $this->selectedUserId = $assignment->user_id;
            $this->selectedPcId = $assignment->pc_id;
            $this->dayOfWeek = $assignment->day_of_week;
            $this->selectedAssignmentId = $assignment->id;
            $this->showForm = true;  // Show the form
        } else {
            // No assignment found: Prepare for creating a new assignment
            $this->selectedPcId = $pcId; // Set the PC ID
            $this->selectedUserId = null; // Clear user ID (if previously set)
            $this->dayOfWeek = $this->selectedDay; // Set the day of the week
    
            // Clear selected assignment ID (if previously set)
            $this->selectedAssignmentId = null;
    
            $this->showForm = true; // Show the form to create a new assignment
    
    
            // You can also perform any additional initialization needed for creating a new assignment
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

        ]);
    
        $assignment = Assignment::findOrFail($this->selectedAssignmentId);
    
        $assignment->update([
            'user_id' => $validatedData['selectedUserId'],
        ]);
    
        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    public function deleteAssignment($assignmentId)
    {
        // Logic to delete the assignment
        $assignment = Assignment::findOrFail($assignmentId);
        $assignment->delete();
    
        // Reload availability and reset form
        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    public function render()
    {
        return view('livewire.home');
    }
}
