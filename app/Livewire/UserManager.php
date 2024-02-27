<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class UserManager extends Component
{
    #[Rule('min:3|max:50|required')]
    public $name;

    #[Rule('max:250')]
    public $comments;

    public $users;

    public $selectedUserId;

    // mount users
    public function mount()
    {
        $this->users = User::all();
    }
    // create user
    public function createUser()
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

    // Searching user
    public function selectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->selectedUserId = $user->id;
            $this->name = $user->name;
            $this->comments = $user->comments;
            // Add other fields if needed
        }
    }

    public function updateUser()
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

    public function render()
    {
        return view('livewire.user-manager');
    }
}
