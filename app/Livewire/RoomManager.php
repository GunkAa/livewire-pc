<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Room;
use App\Models\PC;

class RoomManager extends Component
{
    protected $listeners = ['room-deleted' => '$refresh'];

    #[Rule('min:3|max:50|required')]
    public $name;
    #[Rule('max:250')]
    public $comments;
    public $rooms;
    public $room_id;
    public $selectedRoomId;
    public $editingRoom = false; // Flag to indicate if a PC is being edited

        public function mount()
        {
            $this->rooms = Room::all();
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
    
        public function update()
        {
            $this->validate([
                'name' => 'required|min:3|max:50',
                'comments' => 'max:250',
            ]);
    
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
                $this->reset(['name','comments','editingRoom','selectedRoomId']);
            }
        }
    
            // delete room 
        public function delete($roomId)
        {
            // Find the room by ID
            $room = Room::find($roomId);
    
            // Check if the room exists
            if ($room) {
            // Unassign PCs from this room
            PC::where('room_id', $roomId)->update(['room_id' => null]);
            // Delete the room
            $room->delete();
            }
    
            // Dispatch event
            $this->dispatch('room-deleted');
            // Reset selected room ID if needed
            if ($this->selectedRoomId === $roomId) {
                $this->selectedRoomId = null;
            }
            
            $this->reset('name', 'comments','editingRoom','selectedRoomId');
        }
    public function render()
    {
        return view('livewire.room-manager');
    }
}
