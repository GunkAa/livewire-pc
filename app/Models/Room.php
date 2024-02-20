<?php

namespace App\Models;

use App\Models\Pc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    public function pcs()
    {
        return $this->hasMany(Pc::class);
    }
}

