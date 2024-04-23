<?php

namespace App\Livewire;

use App\Models\Pc;
use App\Models\User;
use Livewire\Component;
use App\Models\Assignment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AssignmentManager extends Component
{
    public $selectedUserId;
    public $selectedPcId;
    public $dayOfWeek;
    public $editingAssignment = null;

    // Get available days
    public function getDays()
    {
        return [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
    }

    // Initialize component
    public function mount()
    {
        // Set default values or fetch initial data
        $this->selectedUserId = null;
        $this->selectedPcId = null;
        $this->dayOfWeek = null;
        $this->editingAssignment = null;
    }
    public function rules()
    {
        return [
            'selectedPcId' => [
                'required',
                'integer',
                Rule::unique('assignments')->where(function ($query) {
                    return $query->where('day_of_week', $this->dayOfWeek);
                }),
                function ($attribute, $value, $fail) {
                    $exists = DB::table('assignments')
                                ->where('pc_id', $this->selectedPcId)
                                ->where('day_of_week', $this->dayOfWeek)
                                ->exists();
    
                    if ($exists) {
                        $fail('This PC is already assigned on ' . $this->dayOfWeek);
                    }
                },
            ],
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon',
        ];
    }
    
    //Create Assignment
    public function createAssignment()
    {
        $this->validate([
            'selectedUserId' => 'required',
            'selectedPcId' => 'required',
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon',
        ]);
    
        Assignment::create([
            'user_id' => $this->selectedUserId,
            'pc_id' => $this->selectedPcId,
            'day_of_week' => $this->dayOfWeek,
        ]);
    
        $this->resetForm();
    }

    // Edit Assignment
    public function editAssignment($assignmentId)
    {
        $this->editingAssignment = $assignmentId;
        $assignment = Assignment::find($assignmentId);

        $this->selectedUserId = $assignment->user_id;
        $this->selectedPcId = $assignment->pc_id;
        $this->dayOfWeek = $assignment->day_of_week . ' ' . $assignment->time_of_day;
    }
    
    // Update Assignment
    public function updateAssignment()
    {
        $this->validate([
            'selectedUserId' => 'required',
            'selectedPcId' => 'required',
            'dayOfWeek' => 'required',
        ]);

        $assignment = Assignment::find($this->editingAssignment);

        if ($assignment) {
            $assignment->update([
                'user_id' => $this->selectedUserId,
                'pc_id' => $this->selectedPcId,
                'day_of_week' => $this->dayOfWeek,
            ]);
        }

        // Reset the form and editing state
        $this->resetForm();
    }
    
    // Reset form
    public function resetForm()
    {
        $this->selectedUserId = null;
        $this->selectedPcId = null;
        $this->dayOfWeek = null;
        $this->editingAssignment = null;
    }

    // Delete Assignment
    public function deleteAssignment($assignmentId)
    {
        $assignment = Assignment::find($assignmentId);

        if ($assignment) {
            $assignment->delete();
        }

        // You can also add a confirmation message or any other logic here

        // Reset the form and reload the data
        $this->resetForm();
    }
    
    //Cancel Edit
    public function cancelEdit()
    {
        $this->resetForm();
    }

    // Render page
    public function render()
    {
        $users = User::all();
        $pcs = Pc::all();
        $assignments = Assignment::all();

        return view('livewire.assignment-manager', [
            'users' => $users,
            'pcs' => $pcs,
            'days' => $this->getDays(),
            'assignments' => $assignments,
        ]);
    }
}