<?php

namespace App\Livewire;

use Exception;
use App\Models\PC;
use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Rule;

class PCManager extends Component
{
    protected $listeners = ['pc-deleted' => '$refresh'];

    #[Rule('min:3|max:50|required')]
    public $name;

    #[Rule('max:250')]
    public $comments;

    public $pcs;
    public $rooms;
    public $selectedPCId;
    #[Rule('nullable')]
    public $selectedRoomId;

    // mount PCs
    public function mount()
    {
        $this->pcs = PC::all();
        $this->rooms = Room::all();
    
    }
    // create PC
    public function create()
    {
        $this->validate();

        PC::create([
            'name' => $this->name,
            'comments' => $this->comments,
            'room_id' => $this->selectedRoomId,
            // Add other fields if needed
        ]);

        // Refresh PC list
        $this->pcs = PC::all();

        // Reset input fields after creating PC
        $this->reset(['name','comments','rooms']);
    }

    // Select PC for editing
    public function edit($pcId)
    {
        $pc = PC::find($pcId);
        if ($pc) {
            $this->selectedPCId = $pc->id;
            $this->name = $pc->name;
            $this->comments = $pc->comments;
            $this->rooms = $pc->selectedRoomId;
            // Add other fields if needed
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|max:50',
            'comments' => 'max:250',
        ]);

        $pc = PC::find($this->selectedPCId);
        if ($pc) {
            $pc->update([
                'name' => $this->name,
                'comments' => $this->comments,
                'rooms' => $this->rooms,                // Add other fields if needed
            ]);

            // Refresh PC list
            $this->pcs = PC::all();

            // Reset input fields after updating PC
            $this->reset(['name','comments','rooms','selectedPCId']);
        }
    }

    // delete PC 
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
        
        $this->reset('name', 'comments', 'selectedPCId');
    }

    public function cancelEdit()
    {
        $this->reset('name', 'comments', 'selectedPCId');
    }

    public function render()
    {
        return view('livewire.pc-manager');
    }
}