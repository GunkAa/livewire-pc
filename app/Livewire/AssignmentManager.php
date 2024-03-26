<?php

namespace App\Livewire;

use App\Models\Pc;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\PcAssignment;

class AssignmentManager extends Component
{
    public $days;
    public $selectedDay;
    public $availabilityByDay;
    public $pcAssignments;
    public $users;
    public $pcs;
    public $currentPage = 'home'; // Default to home page

    public function mount()
    {
        $this->days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $this->selectedDay = request()->input('day', Carbon::now()->format('l'));
        $this->fetchData();
        $this->fetchAvailabilityByDay();
    }

    public function render()
    {
        return view('livewire.assignment-manager');
    }

    public function fetchData()
    {
        $this->pcAssignments = PcAssignment::with('user', 'pc')->get();
        $this->users = User::all();
        $this->pcs = Pc::all();
    }

    public function fetchAvailabilityByDay()
    {
        $pcs = Pc::with('room')->get();
        $this->availabilityByDay = $this->getAvailabilityByDay($pcs, $this->selectedDay);
    }

    private function getAvailabilityByDay($pcs, $selectedDay)
    {
        $availabilityByDay = [];
    
        foreach ($pcs as $pc) {
            // Check if the PC is available (based on the is_available field)
            $isAvailable = $pc->is_available;    
            $availabilityByDay[] = [
                'pc' => $pc,
                'isAvailable' => $isAvailable,
            ];
        }
    
        return $availabilityByDay;
    }

    // Add methods for handling user interactions (e.g., create, edit, delete) if needed
}