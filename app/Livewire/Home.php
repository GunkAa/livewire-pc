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

    public function render()
    {
        return view('livewire.home', [
            'users' => $this->users,
            'pcs' => $this->rooms->pluck('pcs')->flatten(),
        ]);
    }
}