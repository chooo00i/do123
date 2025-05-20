<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Habit extends Model
{
    protected $fillable = ['title', 'emoji', 'creator_id', 'is_public', 'is_template'];

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

    public function addLevels(array $levels): array
    {
        $models = [];
        foreach ($levels as $level => $contents) {
            $i = 1;
            foreach ($contents as $content) {
                $models[] = new HabitLevel([
                    'level' => $level,
                    'seq' => $i++,
                    'content' => $content,
                ]);
            }
        }
        $this->levels()->saveMany($models);
        return $models;
    }

    public function createWithLevels(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Habit 저장
            $habit = new self;
            $habit->title = $data['title'];
            $habit->emoji = $data['emoji'];
            $habit->creator_id = $data['creator_id'];
            $habit->is_public = $data['is_public'];
            $habit->is_template = $data['is_template'];
            $habit->save();

            // 2. HabitLevel 저장
            $levels = $habit->addLevels($data['levels']);

            return [
                'habit' => $habit,
                'levels' => $levels,
            ]; 
        });
    }

    public function createWithLevelsAndLogs(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Habits, Habit_levels 저장
            $habitData = $this->createWithLevels($data);
            
            // admin이 생성하는 template는 log 생성하지 않음
            if (!$data['is_template']) {
                // Logs, level_logs 20일치 데이터 저장
                (new Log)->addLogWithLevelLogs($habitData);
            }
        });
    }
}
