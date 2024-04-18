<?php

namespace App\Livewire;

use Log;
use App\Models\Pc;

use App\Models\User;
use Livewire\Component;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;



class AssignmentManager extends Component
{
    public $selectedUserId;
    public $selectedPcId;
    public $dayOfWeek;
    public $timeOfDay;
    public $users;
    public $pcs;
    public $days;

    // Fetch all necessary data on component mount
    public function mount()
    {
        $this->users = User::all();
        $this->pcs = Pc::all();
        $this->days = [
            'Monday Morning', 'Monday Afternoon',
            'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon',
            'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
    }

    public function createAssignment()
    {
        $validatedData = $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
            'dayOfWeek' => 'required|string',
            'timeOfDay' => 'required|string',
        ]);

        Assignment::create([
            'user_id' => $validatedData['selectedUserId'],
            'pc_id' => $validatedData['selectedPcId'],
            'day_of_week' => $validatedData['dayOfWeek'] . ' ' . $validatedData['timeOfDay'],
        ]);

        session()->flash('message', 'Assignment created successfully!');

        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'timeOfDay']);
    }

    public function render()
    {
        return view('livewire.assignment-manager');
    }
}