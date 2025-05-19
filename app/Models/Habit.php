<?php

namespace App\Models;

use Brick\Math\BigInteger;
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

    public function addLevels(array $levels, int $habitId): void
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
    }

    public static function createWithLevels(array $data): self
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
            $habitId = $habit->id;

            // 2. HabitLevel 저장
            $habit->addLevels($data['levels'], $habitId);

            return $habit;
        });
    }
}
