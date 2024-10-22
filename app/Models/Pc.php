<?php

namespace App\Models;


use App\Models\Room;
use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pc extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'comments',
        'is_available',
        'room_id',
        'defect'
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class); // Relationship with Assignment model
    }

    public function availabilityByDay()
    {
        return $this->assignments()
            ->where('day_of_week', now()->format('l'))
            ->count() === 0; // Check availability for today
    }

    /**
     * Check if the PC is available on the given day.
     *
     * @param  string  $selectedDay
     * @return bool
     */
     public function isAvailable($selectedDay)
     {
         return $this->assignments()->where('day_of_week', '=', $selectedDay)->count() === 0; // Check availability for a specific day
     }

    public function room()
    {
        return $this->belongsTo(Room::class); // Relationship with Room model
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Relationship with User model
    }

    public function assignedUserName($selectedDay)
    {
        $assignment = $this->assignments()->where('day_of_week', '=', $selectedDay)->first();

        if ($assignment && $assignment->user) {
            return $assignment->user->name; // Return assigned user's name
        }

        return 'Not Assigned'; // Default return value
    }

    public function isAssigned($selectedDay)
    {
        return $this->assignments()->where('day_of_week', '=', $selectedDay)->exists(); // Check if assigned for a specific day
    }

}
