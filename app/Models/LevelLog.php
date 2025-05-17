<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelLog extends Model
{
    protected $guarded = [];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

    public function habitLevel()
    {
        return $this->belongsTo(HabitLevel::class);
    }
}
