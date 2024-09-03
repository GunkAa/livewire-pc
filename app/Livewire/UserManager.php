<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;  

class UserManager extends Component
{
    // Load pagination
    use WithPagination, WithoutUrlPagination;

    // Listeners for page refreshing on deleting
    protected $listeners = ['user-deleted' => '$refresh'];
    
    #[Rule('min:3|max:50|required')]
    public $name;
    #[Rule('max:250')]
    public $comments;
    // public $users;
    public $selectedUserId;
    public $editingUser = false; //Controll showing form 
    public $search = ''; //Default Search field
    public $sortField = 'name'; // Default sort field
    public $sortDirection = 'asc'; // Default sort direction
    public $perPage = 10; // Number of items per page
    protected $paginationTheme = 'tailwind';

    // Create user
    public function create()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'comments' => $this->comments,
            // Add other fields if needed
        ]);

        // Refresh user list
        // $this->users = User::all();

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
            $this->reset(['name','comments','editingUser','selectedUserId']);
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
        
        $this->reset('name', 'comments','editingUser','selectedUserId');
    }

    public function cancelEdit()
    {
        $this->reset('name', 'comments', 'selectedUserId','editingUser');
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

    public function render()
    {
        $users = User::query()
        ->where('name', 'like', '%' . $this->search . '%')
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

    return view('livewire.user-manager', ['users' => $users]);
    }

}
