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


    public function mount()
    {
        // Initialize component properties
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

    protected function rules()
    {
        return [
            'selectedUserId' => 'required|exists:users,id',
            'selectedPcId' => 'required|exists:pcs,id',
            'dayOfWeek' => 'required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon'
        ];
    }

    public function loadAvailability()
    {
        // Load availability of PCs by day
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
        // Update selected day and reload availability
        $this->selectedDay = $value;
        $this->loadAvailability();
    }

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
    
            // Clear selected assignment ID (if previously set)
            $this->selectedAssignmentId = null;
    
            $this->showForm = true; // Show the form to create a new assignment
    
            // You can also perform any additional initialization needed for creating a new assignment
        }
    }

    public function updateAssignment()
    {
         // Validate form data
        $validatedData = $this->validate();

        // Check if the User is already assigned on the selected day
        $alreadyAssigned = Assignment::where('user_id', $validatedData['selectedUserId'])
            ->where('day_of_week', $validatedData['dayOfWeek'])
            ->exists();
        
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

    public function cancelEdit()
    {
        // Reset form fields and hide the form
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.home');
    }
}
