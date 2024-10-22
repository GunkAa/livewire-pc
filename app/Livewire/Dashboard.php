<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;
use App\Models\Pc;
use App\Models\Assignment;
use Livewire\Attributes\Rule;

class Dashboard extends Component
{
    // Properties related to user and PC assignment
    #[Rule('required|exists:users,id')]
    public $selectedUserId;
    public $users;
    
    #[Rule('required|exists:pcs,id')]
    public $selectedPcId;
    public $selectedPcName;

    public $selectedAssignmentId;
    
    // Day selection for assignment
    #[Rule('required|in:Monday Morning,Monday Afternoon,Tuesday Morning,Tuesday Afternoon,Wednesday Morning,Wednesday Afternoon,Thursday Morning,Thursday Afternoon,Friday Morning,Friday Afternoon')]
    public $dayOfWeek;
    public $days;
    public $selectedDay;
   
    // Properties related to rooms
    public $rooms;

    // Filter
    public $pageTitle;
    public $availabilityByDay;

    // UI
    public $showDeleteModal;
    public $showForm = false;  //Controll showing update form
    public $createForm = false; //Controll showing create form



    // Lifecycle method to initialize the component
    public function mount($title = 'Default Title')
    {
        // Define the available time slots for each day of the week
        $this->days = [
            'Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon',
            'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon',
            'Friday Morning', 'Friday Afternoon'
        ];
        $this->pageTitle = $title;
        $this->selectedDay = $this->days[0]; // Set the default selected day
        $this->rooms = Room::with('pcs')->get(); // Load rooms with their PCs
        $this->users = User::all(); //Load all users
        $this->loadAvailability(); // Load the availability data
    }

    // Load availability of PCs by day
    public function loadAvailability()
    {
        $this->availabilityByDay = []; // Initialize the availability array

        // Iterate through each room
        foreach ($this->rooms as $room) {
            $pcs = $room->pcs; // Get the PCs associated with the room
            
            // Iterate through each PC in the room
            foreach ($pcs as $pc) {
                $isAvailable = $pc->isAvailable($this->selectedDay); // Check if the PC is available on the selected day
                
                // Store the PC information and its availability status
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
            $this->showForm = true;  // Show the form to update assignment
            $this->createForm = false;
        } else {
            // No assignment found: Prepare for creating a new assignment
            $this->selectedPcId = $pcId; // Set the PC ID
            $this->selectedUserId = null; // Clear user ID (if previously set)
            $this->dayOfWeek = $this->selectedDay; // Set the day of the week
            $this->selectedAssignmentId = null; // Clear selected assignment ID (if previously set)
            $this->createForm = true; // Show the form to create a new assignment
            $this->showForm = false;
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
        $this->resetForm();
    }

    // Delete an assignment
    public function deleteAssignment($assignmentId)
    {
        // Find and delete the assignment
        $assignment = Assignment::findOrFail($assignmentId);
        $assignment->delete();

        // Reload availability and reset form
        $this->loadAvailability();
        $this->resetForm();
    }

    // Cancel editing and reset form fields
    public function cancelEdit()
    {
        $this->resetForm();
    }
    
    // Resets form properties
    private function resetForm()
    {
        $this->reset(['selectedUserId', 'selectedPcId', 'dayOfWeek', 'selectedAssignmentId']);
        $this->resetErrorBag();
        $this->showForm = false; //Controll showing update form
        $this->createForm = false; //Controll showing create form
    }

    // Render the component view
    public function render()
    {
        return view('livewire.dashboard');
    }
}
