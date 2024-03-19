<?php

namespace App\Livewire;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class UserManager extends Component
{
    protected $listeners = ['user-deleted' => '$refresh'];
    #[Rule('min:3|max:50|required')]
    public $name;
    #[Rule('max:250')]
    public $comments;
    public $users;
    public $selectedUserId;
    public $editingUser = false;

    // mount users
    public function mount()
    {
        $this->users = User::all();
    
    }
    // create user
    public function create()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'comments' => $this->comments,
            // Add other fields if needed
        ]);

        // Refresh user list
        $this->users = User::all();

    // Reset input fields after creating user
        $this->reset(['name','comments']);
    }

    // Select user for editing
    public function edit($userId)
    {
        $this->editingUser = User::find($userId);
        if ($this->editingUser) {
            $this->selectedUserId = $this->editingUser->id;
            $this->name = $this->editingUser->name;
            $this->comments = $this->editingUser->comments;
            // Add other fields if needed
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|max:50',
            'comments' => 'max:250',
        ]);

        $user = User::find($this->selectedUserId);
        if ($user) {
            $user->update([
                'name' => $this->name,
                'comments' => $this->comments,
                // Add other fields if needed
            ]);

            // Refresh user list
            $this->users = User::all();

            // Reset input fields after updating user
            $this->reset(['name','comments', 'selectedUserId']);
        }
    }

        // delete user 
    public function delete($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        // Check if the user exists
        if ($user) {
        // Delete the user
        $user->delete();
        }

        // Dispatch event
        $this->dispatch('user-deleted');
        // Reset selected user ID if needed
        if ($this->selectedUserId === $userId) {
            $this->selectedUserId = null;
        }
        
        $this->reset('name', 'comments', 'selectedUserId');
    }

    public function cancelEdit()
    {
        $this->reset('name', 'comments', 'selectedUserId','editingUser');
    }

    public function render()
    {
        return view('livewire.user-manager');
    }
}
