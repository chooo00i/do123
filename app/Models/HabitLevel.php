<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLevel extends Model
{
    protected $fillable = ['habit_id', 'level', 'seq', 'content'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function levelLogs()
    {
        return $this->hasMany(LevelLog::class);
    }
}
