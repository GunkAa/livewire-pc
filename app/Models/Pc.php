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
        return $this->hasMany(Assignment::class);
    }

    public function availabilityByDay()
    {
        return $this->assignments()
            ->where('day_of_week', now()->format('l'))
            ->count() === 0;
    }

    /**
     * Check if the PC is available on the given day.
     *
     * @param  string  $selectedDay
     * @return bool
     */

     public function isAvailable($selectedDay)
     {
         return $this->assignments()->where('day_of_week', '=', $selectedDay)->count() === 0;
     }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUserName($selectedDay)
    {
        $assignment = $this->assignments()->where('day_of_week', '=', $selectedDay)->first();

        if ($assignment && $assignment->user) {
            return $assignment->user->name;
        }

        return 'Not Assigned';
    }

    public function isAssigned($selectedDay)
    {
        return $this->assignments()->where('day_of_week', '=', $selectedDay)->exists();
    }

}
