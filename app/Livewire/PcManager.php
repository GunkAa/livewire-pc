<?php

namespace App\Livewire;

use Exception;
use App\Models\PC;
use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;

class PCManager extends Component
{
    // Define component properties and listeners
    protected $listeners = ['pc-deleted' => '$refresh'];

    // Define validation rules using attributes
    #[Rule('min:3|max:50|required')]
    public $name;
    #[Rule('max:250')]
    public $comments;
    public $pcs;
    public $rooms;
    public $room_id;
    public $selectedPCId;
    #[Rule('required')]
    public $selectedRoomId;
    public $selectedFilterRoomId;
    public $editingPC = false; // Flag to indicate if a PC is being edited

    // Lifecycle hook that is called once, immediately after the component is instantiated
    public function mount()
    {
        // Initialize component properties
        $this->pcs = PC::all(); // Load all PCs
        $this->rooms = Room::all(); // Load all rooms
    }

    // Filter PCs based on the selected room
    public function filterByRoom()
    {
        // Log the current room_id
        info('filterByRoom called with room_id:', ['room_id' => $this->room_id]);

        if ($this->room_id) {
            // Check if the room exists
            $room = Room::find($this->room_id);
            if ($room) {
                // Filter PCs by room_id
                $this->pcs = PC::where('room_id', $this->room_id)->latest()->get();
            } else {
                // Reset PCs if room does not exist
                $this->pcs = PC::latest()->get();
            }
        } else {
            // Get all PCs if no room is selected
            $this->pcs = PC::latest()->get();
        }
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
            // Add other fields if needed
        ]);

        // Refresh PC list
        $this->pcs = PC::all();

        // Filter PCs based on the selected room
        $this->filterByRoom();

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
        }
    }

    // Update an existing PC
    public function update()
    {
        // Validate data
        $this->validate([
            'name' => 'required|min:3|max:50',
            'comments' => 'max:250',
            'selectedRoomId' => 'required',
        ]);

        // Find the PC by ID
        $pc = PC::find($this->selectedPCId);
        if ($pc) {
            // Update PC record
            $pc->update([
                'name' => $this->name,
                'comments' => $this->comments,
                'room_id' => $this->selectedRoomId,                
                // Add other fields if needed
            ]);

            // Refresh PC list
            $this->pcs = PC::all();

            // Filter PCs based on the selected room
            $this->filterByRoom();

            // Reset input fields after updating PC
            $this->reset(['name', 'comments', 'selectedPCId', 'selectedRoomId', 'editingPC']);
        }
    }

    // Delete PC 
    public function delete($pcId)
    {
        // Find the PC by ID
        $pc = PC::find($pcId);

        // Check if the PC exists
        if ($pc) {
            // Delete the PC
            $pc->delete();
        }

        // Dispatch event
        $this->dispatch('pc-deleted');

        // Reset selected PC ID if needed
        if ($this->selectedPCId === $pcId) {
            $this->selectedPCId = null;
        }
        
        // Reset input fields
        $this->reset(['name', 'comments', 'selectedPCId', 'selectedRoomId', 'editingPC']);
    }

    // Cancel editing and reset input fields
    public function cancelEdit()
    {
        $this->reset(['name', 'comments', 'selectedPCId', 'selectedRoomId', 'editingPC']);
    }

    // Render the component view
    public function render()
    {
        return view('livewire.pc-manager');
    }
}