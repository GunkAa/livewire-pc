<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pc_id',
        'day_of_week',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relationship with User model
    }

    public function pc()
    {
        return $this->belongsTo(Pc::class); // Relationship with Pc model
    }
}
