<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    protected $guarded = [];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function levelLogs()
    {
        return $this->hasMany(LevelLog::class);
    }

    public function addLevelLogs(array $levels, object $today): array
    {
        $models = [];
        for ($i = 0; $i < 20; $i++) {
            foreach ($levels as $habit_level) {
                $models[] = new LevelLog([
                    'habit_level_id' => $habit_level->id,
                    'level' => $habit_level->level,
                    'seq' => $habit_level->seq,
                    'log_date' => $today->copy()->addDays($i),
                ]);
            }
        }
        $this->levelLogs()->saveMany($models);
        return $models;
    }

    public function addLogWithLevelLogs(array $data): void
    {
        // 1. Habit 저장
        $habit = $data['habit'];
        $today = today();
        $log = new self;
        $log->title = $habit->title;
        $log->emoji = $habit->emoji;
        $log->creator_id = $habit->creator_id;
        $log->habit_id = $habit->id;
        $log->start_date = $today;
        $log->end_date = $today->copy()->addDays(20);
        $log->round = $this->checkRound($habit['id']) + 1;
        $log->save();

        // 2. HabitLevel 저장
        $log->addLevelLogs($data['levels'], $today);
    }

    /**
     * 습관 진행회차 확인
     */
    public function checkRound(int $habitId): int
    {
        $latestRound = DB::table('logs')
            ->where('habit_id', $habitId)
            ->orderByDesc('round')
            ->value('round');

        return $latestRound ?? 0;
    }
}
