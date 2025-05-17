<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLevel extends Model
{
    protected $guarded = [];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function levelLogs()
    {
        return $this->hasMany(LevelLog::class);
    }
}
