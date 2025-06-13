<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habit extends Model
{
    use SoftDeletes;
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

    /**
     * 유저의 습관 목록
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, Habit>
     */
    public function selectHabitsForUser(int $userId): object
    {
        $habits = Habit::where('creator_id', $userId)
            ->get();

        return $habits;
    }

    /**
     * 진행중인 log가 없는 habit 목록
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, Habit>
     */
    public function selectNotProgressingHabits(int $userId)
    {
        $notProgressingHabits = Habit::where('creator_id', $userId)
            ->whereDoesntHave('logs', function ($query) {
                $query->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
            })
            ->get();

        return $notProgressingHabits;
    }
}
