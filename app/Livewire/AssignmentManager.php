<?php

namespace App\Livewire;

use App\Models\Pc;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\PcAssignment;

class AssignmentManager extends Component
{
    public $days;
    public $selectedDay;
    public $availabilityByDay;

    public function mount()
    {
        $this->days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $this->selectedDay = Carbon::now()->format('l');
    }

    public function render()
    {
        $pcs = Pc::with('room')->get();
        $this->availabilityByDay = $this->getAvailabilityByDay($pcs, $this->selectedDay);

        return view('livewire.assignment-manager', [
            'pcs' => $pcs,
        ]);
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
}
