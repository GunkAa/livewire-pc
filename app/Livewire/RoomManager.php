<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class RoomManager extends Component
{
        // Create room
        public function create()
        {
            $this->validate();
    
            Room::create([
                'name' => $this->name,
                'comments' => $this->comments,
                // Add other fields if needed
            ]);
    

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
            // Delete the room
            $room->delete();
            }
    
            // Dispatch event
            $this->dispatch('user-deleted');
            // Reset selected room ID if needed
            if ($this->selectedUserId === $roomId) {
                $this->selectedUserId = null;
            }
            
            $this->reset('name', 'comments','editingRoom','selectedRoomId');
        }
    public function render()
    {
        return view('livewire.room-manager');
    }
}
