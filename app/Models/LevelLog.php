<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelLog extends Model
{
    protected $fillable = ['log_id', 'habit_level_id', 'level', 'seq', 'log_date'];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

    public function habitLevel()
    {
        return $this->belongsTo(HabitLevel::class);
    }
}
