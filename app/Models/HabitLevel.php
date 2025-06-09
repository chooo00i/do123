<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Helper;

class HabitLevel extends Model
{
    use SoftDeletes;
    use Helper;

    protected $fillable = ['habit_id', 'level', 'seq', 'content'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function levelLogs()
    {
        return $this->hasMany(LevelLog::class);
    }

    public function selectHabitLevelsGroupByLevel(int $habitId)
    {
        $levels = HabitLevel::where('habit_id', $habitId)
            ->orderBy('level')
            ->orderBy('seq')
            ->get();

        $group = $this->groupBy($levels, 'level');

        return $group;
    }
}
