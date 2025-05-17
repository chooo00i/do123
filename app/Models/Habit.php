<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function levels()
    {
        return $this->hasMany(HabitLevel::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
