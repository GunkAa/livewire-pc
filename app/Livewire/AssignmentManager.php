<?php

namespace App\Livewire;

use App\Models\Pc;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Assignment;

class AssignmentManager extends Component
{
    // Define component properties
    public $users;
    public $pcs;
    public $selectedUserId;
    public $selectedPcId;
    public $selectedAssignmentId;
    public $dayOfWeek;
    public $days;
    public $assignments;
    public $editingAssignment;
    

    public function mount()
    {
        // Initialize component properties
        $this->users = User::all();
        $this->pcs = Pc::all();
        $this->days = [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
        $this->dayOfWeek = Carbon::now()->format('l'); // Set default day to current day
        $this->assignments = Assignment::all(); // Fetch assignments
        $this->editingAssignment = null;
    }

    protected function rules()
    {
        // Validation rules component properties
        return [
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon'
        ];
    }

    // Create Assignment
    public function createAssignment()
    {
        // Validation
        $validatedData = $this->validate();
        
        // Check if the User is already assigned on the selected day
        $alreadyAssigned = Assignment::where('user_id', $validatedData['selectedUserId'])
            ->where('day_of_week', $validatedData['dayOfWeek'])
            ->exists();
        
        if ($alreadyAssigned) {
            $this->addError('selectedUserId', 'This User is already assigned on ' . $validatedData['dayOfWeek']);
            return;
        }
        
        // Check if the PC is already assigned on the selected day
        $alreadyAssigned = Assignment::where('pc_id', $validatedData['selectedPcId'])
            ->where('day_of_week', $validatedData['dayOfWeek'])
            ->exists();
        
        if ($alreadyAssigned) {
            $this->addError('selectedPcId', 'This PC is already assigned on ' . $validatedData['dayOfWeek']);
            return;
        }
        
        Assignment::create([
            'user_id' => $validatedData['selectedUserId'],
            'pc_id' => $validatedData['selectedPcId'],
            'day_of_week' => $validatedData['dayOfWeek'],
        ]);

        session()->flash('success', 'PC assignment created successfully.');
        $this->assignments = Assignment::all();

        // Reset form fields
        $this->reset(['selectedUserId','selectedPcId','dayOfWeek']);
    }

    // Edit Assignment
    public function editAssignment($assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        
        // Set the properties for editing
        $this->selectedUserId = $assignment->user_id;
        $this->selectedPcId = $assignment->pc_id;
        $this->dayOfWeek = $assignment->day_of_week;
        $this->editingAssignment = $assignmentId; // Set the editingAssignment to the ID of the assignment being edited
    }

    // Update Assignment
    public function updateAssignment()
    {
        $validatedData = $this->validate();
        
        // Find the assignment being edited
        $assignment = Assignment::findOrFail($this->editingAssignment);
        
        // Check if the User is already assigned on the selected day
        $alreadyAssigned = Assignment::where('user_id', $validatedData['selectedUserId'])
            ->where('day_of_week', $validatedData['dayOfWeek'])
            ->exists();
        
        if ($alreadyAssigned) {
            $this->addError('selectedUserId', 'This User is already assigned on ' . $validatedData['dayOfWeek']);
            return;
        }

        // Check if the PC is already assigned on the selected day
        $alreadyAssigned = Assignment::where('pc_id', $validatedData['selectedPcId'])
        ->where('day_of_week', $validatedData['dayOfWeek'])
        ->exists();
            
        if ($alreadyAssigned) {
            $this->addError('selectedPcId', 'This PC is already assigned on ' . $validatedData['dayOfWeek']);
            return;
        }
        
        // Update the assignment
        $assignment->update([
            'user_id' => $validatedData['selectedUserId'],
            'pc_id' => $validatedData['selectedPcId'],
            'day_of_week' => $validatedData['dayOfWeek'],
        ]);

        session()->flash('success', 'Assignment updated successfully.');
        $this->assignments = Assignment::all();

        // Reset form fields and editingAssignment
        $this->reset(['selectedUserId','selectedPcId','dayOfWeek', 'editingAssignment']);
    }

    // Cancel Edit Mode
    public function cancelEdit()
    {
        // Reset form fields and editingAssignment
        $this->reset(['selectedUserId','selectedPcId','dayOfWeek', 'editingAssignment']);
    }

        // Delete Assignment
    public function deleteAssignment($assignmentId)
    {
        $assignment = Assignment::find($assignmentId);
    
        if ($assignment) {
            $assignment->delete();
        }
    
        // You can also add a confirmation message or any other logic here
            // Dispatch event
            $this->dispatch('assignment-deleted');
            // Reset selected user ID if needed
            if ($this->selectedUserId === $assignmentId) {
                $this->selectedUserId = null;
            }
        // Reset the form and reload the data
        $this->reset(['selectedUserId','selectedPcId','dayOfWeek','editingAssignment']);
        $this->assignments = Assignment::all();
    }

    // Render page
    public function render()
    {

        return view('livewire.assignment-manager');
    }
}