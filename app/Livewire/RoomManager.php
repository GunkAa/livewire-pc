<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Room;
use App\Models\PC;

class RoomManager extends Component
{   
    // Properties related Room 
    public $name;
    public $comments;
    public $rooms;
    public $room_id;
    public $roomIdToDelete;
    public $selectedRoomId;
    
    // UI
    public $editingRoom = false; //Controll showing update form
    public $showDeleteModal= false; //Control modal visibility

    // Lifecycle method to initialize the component
    public function mount()
    {
        $this->rooms = Room::all(); // Load all rooms
    }

    // Define validation rules
    public function rules()
    {
        $uniqueRule = 'unique:rooms,name';

        // If we are editing an existing user, ignore their ID in the unique rule
        if ($this->selectedRoomId) {
            $uniqueRule .= ",$this->selectedRoomId";
        }

        return [
            'name' => [
                'min:3',
                'max:50',
                'required', 
                $uniqueRule,
            ],
            'comments' => 'max:250',
        ];
    }

    // Create room
    public function create()
    {
        $this->validate();
    
        Room::create([
            'name' => $this->name,
            'comments' => $this->comments,
            // Add other fields if needed
        ]);

        // Refresh Room List 
        $this->rooms = Room::all();
        // Reset input fields after creating room
        $this->reset(['name','comments']);
    }
    
    // Select room for editing
    public function edit($roomId)
    {
        $this->editingRoom = Room::find($roomId);
        if ($this->editingRoom) {
            $this->selectedRoomId = $this->editingRoom->id;
            $this->name = $this->editingRoom->name;
            $this->comments = $this->editingRoom->comments;
            // Add other fields if needed
        }
    }
    
    // Update an existing Room
    public function update()
    {
        $this->validate();

        $room = Room::find($this->selectedRoomId);
        if ($room) {
            $room->update([
                'name' => $this->name,
                'comments' => $this->comments,
                // Add other fields if needed
            ]);
    
            // Refresh room list
            $this->rooms = Room::all();
    
            // Reset input fields after updating room
            $this->cancelEdit();
        }
    }

    // Cancel editing and reset input fields
    public function cancelEdit()
    {
        $this->reset(['name', 'comments', 'editingRoom', 'selectedRoomId']);
    }
    
    // Function to trigger the delete confirmation modal
    public function delete($roomId)
    {
        $this->roomIdToDelete = $roomId;
        $this->showDeleteModal = true; // Show the modal
    }

    // Confirm the deletion
    public function confirmDelete()
    {
        // Find the room by ID
        $room = Room::find($this->roomIdToDelete);
    
        // Check if the room exists
        if ($room) {
        // Unassign PCs from this room
        PC::where('room_id', $room->id)->update(['room_id' => null]);
        // Delete the room
        $room->delete();
        }
        // Reset selected room ID if needed
        if ($this->selectedRoomId === $this->roomIdToDelete) {
            $this->selectedRoomId = null;
        }
        // Reset input fields
        $this->cancelEdit();
        // Reset modal state
        $this->resetDeleteState();
        //  Update room list 
        $this->rooms = Room::all();
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
        $this->roomIdToDelete = null;
    }

    // Render the component view
    public function render()
    {
        return view('livewire.room-manager');
    }
}
