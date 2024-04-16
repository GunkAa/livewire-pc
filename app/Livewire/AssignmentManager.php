<?php

namespace App\Livewire;

use Log;
use App\Models\Pc;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use Livewire\Component;
use App\Models\PcAssignment;


class AssignmentManager extends Component
{
    public $days;
    public $selectedDay;
    public $availabilityByRoom;
    public $assigningPc;
    public $showAssignUserModal = false;
    public $availableUsers;
    public $selectedPcId;
    public $selectedUserId; 

    public function mount()
    {
        $this->days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $this->selectedDay = Carbon::now()->format('l');
        $this->fetchAvailabilityByRoom();
    }

    public function render()
    {
        return view('livewire.assignment-manager');
    }

public function fetchAvailabilityByRoom()
{
    $this->availabilityByRoom = Pc::with(['room', 'user']) // Eager load room and user
        ->get()
        ->groupBy('room.name')
        ->map(function ($pcs) {
            return $pcs->map(function ($pc) {
                $isAvailable = $pc->is_available; // Check the is_available field
                $assignedUser = $pc->assignments ? $pc->assignments->first()->user : null;

                return [
                    'pc' => $pc,
                    'isAvailable' => $isAvailable,
                    'assignedUser' => $assignedUser,
                ];
            });
        });
}
    public function assignUser($pcId)
    {
        $pc = Pc::find($pcId);
        
        // Check if the PC exists
        if (!$pc) {
            session()->flash('error', 'PC not found.');
            return;
        }
        
        // Check if any users are already assigned to this PC on the selected day
        $existingAssignment = PcAssignment::where('pc_id', $pcId)
            ->where('day_of_week', $this->selectedDay)
            ->first();
    
        if ($existingAssignment) {
            session()->flash('error', 'PC is already assigned to ' . $existingAssignment->user->name . ' on ' . $this->selectedDay);
            return;
        }
    
        // Fetch available users for assignment
        $availableUsers = User::whereDoesntHave('assignments', function ($query) {
            $query->where('day_of_week', $this->selectedDay);
        })->get();
    
        // Set properties to control modal and available users list
        $this->assigningPc = $pc;
        $this->selectedPcId = $pcId;
        $this->availableUsers = $availableUsers;
        $this->showAssignUserModal = true;
    }
    public function assign()
    {
    // Validate selected user
    $this->validate([
        'selectedUserId' => 'required|exists:users,id',
    ]);

    // Create or update the assignment
    PcAssignment::updateOrCreate(
        [
            'pc_id' => $this->selectedPcId,
            'day_of_week' => $this->selectedDay,
        ],
        [
            'user_id' => $this->selectedUserId,
        ]
    );

    // Close the modal and reset properties
    $this->showAssignUserModal = false;
    $this->selectedUserId = null;

    session()->flash('success', 'User assigned successfully.');
    }

    public function closeModal()
    {
        $this->showAssignUserModal = false;
    }
    

}