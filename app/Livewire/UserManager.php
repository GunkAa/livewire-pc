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
    
    // Properties related PC
    public $name;
    public $comments;
    public $selectedUserId;
    public $uniqueRule;
    public $userIdToDelete; // Store the ID of the user to be deleted

    // Filter
    public $search = ''; //Default search field
    public $sortField = 'name'; // Default sort field
    public $sortDirection = 'asc'; // Default sort direction
    public $perPage = 10; // Number of items per page

    // UI
    public $editingUser = false; // Show editingform 
    public $showDeleteModal= false; //Show delete modal
    protected $paginationTheme = 'tailwind'; //PaginationTheme

    // Define validation rules
    public function rules()
    {
        $uniqueRule = 'unique:users,name';

        // If we are editing an existing user, ignore their ID in the unique rule
        if ($this->selectedUserId) {
            $uniqueRule .= ",$this->selectedUserId";
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

    // Create user
    public function create()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'comments' => $this->comments,
            // Add other fields if needed
        ]);

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
        $this->validate();

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
            $this->cancelEdit();
        }
    }


    // Function to trigger the delete confirmation modal
    public function delete($userId)
    {
        $this->userIdToDelete = $userId;
        $this->showDeleteModal = true; // Show the modal
    }

    // Confirm the deletion
    public function confirmDelete()
    {
        // Find the user by ID
        $user = User::find($this->userIdToDelete);

        // Check if the user exists
        if ($user) {
        // Delete the user
        $user->delete();
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
        $this->userIdToDelete = null;
    }

    // Cancel editing and reset input fields
    public function cancelEdit()
    {
        $this->reset('name', 'comments', 'selectedUserId','editingUser',);
    }

    // Reset pagination when search term is updated
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Toggle sorting direction
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
        $users = User::query()
        ->where('name', 'like', '%' . $this->search . '%')
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

    return view('livewire.user-manager', ['users' => $users]);
    }

}
