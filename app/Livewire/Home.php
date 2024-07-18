<?php

namespace App\Livewire;

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
    public $selectedPcName;
    public $dayOfWeek;
    public $days;
    public $selectedDay;
    public $rooms;
    public $users;
    public $availabilityByDay;
    public $showForm = false;  // To control form visibility
    public $selectedAssignmentId;

// Lifecycle hook that is called once, immediately after the component is instantiated
    public function mount()
    {
        // Initialize component properties
        $this->days = [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
        $this->selectedDay = $this->days[0]; // Set the default selected day
        $this->rooms = Room::with('pcs')->get(); // Load rooms with their PCs
        $this->users = User::all(); //Load all users
        $this->loadAvailability(); // Load the availability data
    }

    // Define validation rules for component properties
    protected function rules()
    {
        return [
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon'
        ];
    }

    // Load availability of PCs by day
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

    // Update selected day and reload availability
    public function dayChanged($value)
    {
        $this->selectedDay = $value; // Update the selected day
        $this->loadAvailability(); // Reload availability data
    }

    // Edit assignment for the selected PC
    public function editAssignment($pcId)
    {
        // Fetch assignment for the selected PC and day
        $assignment = Assignment::where('pc_id', $pcId)
            ->where('day_of_week', $this->selectedDay)
            ->first();

            $pc = Pc::findOrFail($pcId);
            $this->selectedPcName = $pc->name; // Update the selected PC name
    
        if ($assignment) {
            //If Assignment found: Populate form fields for editing
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
            $this->selectedAssignmentId = null; // Clear selected assignment ID (if previously set)
            $this->showForm = true; // Show the form to create a new assignment
        }
    }

    // Update or create an assignment
    public function updateAssignment()
    {
         // Validate form data
        $validatedData = $this->validate();

        // Check if the User is already assigned on the selected day
        $alreadyAssigned = Assignment::where('user_id', $validatedData['selectedUserId'])
            ->where('day_of_week', $validatedData['dayOfWeek'])
            ->exists();
        
        // Error when User is already assigned on the selected day
        if ($alreadyAssigned) {
            $this->addError('selectedUserId', 'This User is already assigned on ' . $validatedData['dayOfWeek']);
            return;
        }
    
        if ($this->selectedAssignmentId) {
            // Update existing assignment
            $assignment = Assignment::findOrFail($this->selectedAssignmentId);
            $assignment->update([
                'user_id' => $validatedData['selectedUserId'],
            ]);
        } else {
            // Create new assignment
            $assignment = Assignment::create([
                'user_id' => $validatedData['selectedUserId'],
                'pc_id' => $validatedData['selectedPcId'],
                'day_of_week' => $validatedData['dayOfWeek'],
            ]);
        }
    
        // Reload availability and reset form
        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    // Delete an assignment
    public function deleteAssignment($assignmentId)
    {
        // Find and delete the assignment
        $assignment = Assignment::findOrFail($assignmentId);
        $assignment->delete();
    
        // Reload availability and reset form
        $this->loadAvailability();
        $this->showForm = false;
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
    }

    // Cancel editing and reset form fields
    public function cancelEdit()
    {
        // Reset form fields and hide the form
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
        $this->showForm = false;
    }

    // Render the component view
    public function render()
    {
        return view('livewire.home');
    }
}
