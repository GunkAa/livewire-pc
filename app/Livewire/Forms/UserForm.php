<?php
namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Livewire\Attributes\Rule;

class UserForm extends Form
{
    public ?User $user; // Declare the user property to hold user data

    // Define validation rules for the name field
    #[Rule('required|min:3|max:50')]
    public string $name = '';

    // Define validation rules for the comments field
    #[Rule('nullable|max:250')]
    public ?string $comments;

    // Initialize the form with optional user data
    public function mount(?User $user = null)
    {
        $this->user = $user; // Set the user property with optional user data

        if ($user) {
            $this->name = $user->name; // Populate the name field with user's name
            $this->comments = $user->comments; // Populate the comments field with user's comments
        }
    }

    // Method to save user data
    public function save()
    {
        $this->validate(); // Validate the form fields

        if ($this->user) {
            $this->user->update($this->all()); // Update existing user with form data
        } else {
            User::create($this->all()); // Create a new user with form data
        }

        $this->reset(['name', 'comments']); // Reset form fields after saving
    }
}