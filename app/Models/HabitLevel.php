<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function selectHabitLevelsGroupByHabitId(int $habitId): array
    {
        $levels = DB::table('habit_levels')
            ->where('habit_id', $habitId)
            ->orderBy('level')
            ->orderBy('seq')
            ->get();

        $grouped = [1 => [], 2 => [], 3 => []];

        foreach ($levels as $level) {
            if (isset($grouped[$level->level])) {
                $grouped[$level->level][] = $level->content;
            }
        }

        return $grouped;
    }
}
