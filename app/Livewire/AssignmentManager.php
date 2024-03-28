<?php

namespace App\Livewire;

use App\Models\Pc;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;


class AssignmentManager extends Component
{
    public $days;
    public $selectedDay;
    public $availabilityByRoom;
    public $assigningPc;
    public $showAssignUserModal = false;
    public $availableUsers;

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
        $this->availabilityByRoom = Pc::with('room')->get()
            ->groupBy('room.name')
            ->map(function ($pcs) {
                return $pcs->map(function ($pc) {
                    $isAvailable = $pc->is_available; // Check the is_available field
                    return [
                        'pc' => $pc,
                        'isAvailable' => $isAvailable,
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
        if (!$this->isPcAvailable($pc)) {
            session()->flash('error', 'PC is not available for assignment on ' . $this->selectedDay);
            return;
        }
    
        // Fetch available users for assignment
        $availableUsers = User::whereDoesntHave('assignments', function ($query) {
            $query->where('day_of_week', $this->selectedDay);
        })->get();
    
        // Set properties to control modal and available users list
        $this->assigningPc = $pc;
        $this->showAssignUserModal = true;
        $this->availableUsers = $availableUsers;
        
    }

    public function closeModal()
    {
        $this->showAssignUserModal = false;
    }
    
    public function isPcAvailable($pc)
    {
        return $pc->is_available; // Check the is_available field
    }
    


    public function userAssigned()
    {
        $this->fetchAvailabilityByRoom();
    }
}