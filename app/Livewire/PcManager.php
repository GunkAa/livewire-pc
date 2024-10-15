<?php

namespace App\Livewire;

use Exception;
use App\Models\PC;
use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;  


class PcManager extends Component
{
    use WithPagination, WithoutUrlPagination;
    // Define component properties and validation rules using attributes
    public $name;
    public $comments;
    public $rooms;
    public $room_id;
    public $selectedPCId;
    public $selectedRoomId;
    public $selectedFilterRoomId;
    public $defect = false; // Show defect PC
    public $editingPC = false; // Flag to indicate if a PC is being edited
    public $showDeleteModal = false; // Control modal visibility
    public $pcIdToDelete; // Store the ID of the PC to be deleted
    public $search = ''; //Default Search field
    public $sortField = 'name'; // Default sort field
    public $sortDirection = 'asc'; // Default sort direction
    public $perPage = 10; // Number of items per page

    // Lifecycle hook that is called once, immediately after the component is instantiated
    public function mount()
    {
        // Initialize component properties
        $this->rooms = Room::all(); // Load all rooms
    }

    // Define validation rules
    public function rules()
    {
        $uniqueRule = 'unique:pcs,name';

        // If we are editing an existing user, ignore their ID in the unique rule
        if ($this->selectedPCId) {
            $uniqueRule .= ",$this->selectedPCId";
        }

        return [
            'name' => [
                'min:3',
                'max:50',
                'required', 
                $uniqueRule,
            ],
            'comments' => 'max:250',
            'selectedRoomId' => 'nullable',
            'defect' => 'nullable|boolean',
        ];
    }

    // Create PC
    public function create()
    {
        // Validate Data
        $this->validate();

        // Create a new PC record
        PC::create([
            'name' => $this->name,
            'comments' => $this->comments,
            'room_id' => $this->selectedRoomId,
            'is_available' => true, // Default to available
            'defect' => false,
            // Add other fields if needed
        ]);

        // Refresh PC list
        $this->pcs = PC::all();

        // Reset input fields after creating PC
        $this->reset(['name', 'comments', 'selectedRoomId']);
    }

    // Select PC for editing
    public function edit($pcId)
    {
        $this->editingPC = PC::find($pcId); // Find the PC by ID
        if ($this->editingPC) {
            $this->selectedPCId = $this->editingPC->id;
            $this->name = $this->editingPC->name;
            $this->comments = $this->editingPC->comments;
            $this->selectedRoomId = $this->editingPC->room_id;
            $this->defect = (bool) $this->editingPC->defect;     
        }
    }

    // Update an existing PC
    public function update()
    {
        // Validate data
        $this->validate();

        // Find the PC by ID
        $pc = PC::find($this->selectedPCId);
        if ($pc) {
            // Update PC record
            $pc->update([
                'name' => $this->name,
                'comments' => $this->comments,
                'room_id' => $this->selectedRoomId,  
                'defect' => $this->defect,             
                // Add other fields if needed
            ]);

            // Refresh PC list
            $this->pcs = PC::all();

            // Reset input fields after updating PC
            $this->cancelEdit();
        }
    }

    // Cancel editing and reset input fields
    public function cancelEdit()
    {
        $this->reset(['name', 'comments', 'selectedPCId', 'selectedRoomId', 'editingPC', 'defect']);
    }

    // Function to trigger the delete confirmation modal
    public function delete($pcId)
    {
        $this->pcIdToDelete = $pcId;
        $this->showDeleteModal = true; // Show the modal
    }

    // Confirm the deletion
    public function confirmDelete()
    {
        // Find the PC by its ID
        $pc = PC::find($this->pcIdToDelete);

        // Check if the pc exists
        if ($pc) {
            // delete pc
            $pc->delete();
        }

        // Reset input fields
        $this->cancelEdit();

        // Reset modal state
        $this->resetDeleteState();
    } 

    // Cancel the deletion action and close the modal
    public function cancelDelete()
    {
        $this->resetDeleteState();
    }

    // Helper function to reset the modal and deletion state
    private function resetDeleteState()
    {
        $this->showDeleteModal = false;
        $this->pcIdToDelete = null;
    }

    // Filter Rooms and handles Pagination 
    public function filterByRoom()
    {
        if ($this->room_id === 'unassigned') {
            return PC::whereNull('room_id')
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        } elseif ($this->room_id) {
            // If a specific room is selected
            return PC::where('room_id', $this->room_id)
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        } else {
            $pcs = PC::latest()
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        }

        return $pcs;
    }

    public function updatingRoomId($value)
    {
        // Reset the pagination when filtering by room
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Render the component view
    public function render()
    {
        $pcs = $this->filterByRoom();
        return view('livewire.pc-manager',[
            'pcs' => $pcs,
            'rooms' => $this->rooms,
        ]);
    }
}