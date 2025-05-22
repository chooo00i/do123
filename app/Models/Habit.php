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

    public function addLevels(array $levels): array
    {
        $models = [];
        foreach ($levels as $level => $lists) {
            $i = 1;
            foreach ($lists as $list) {
                $models[] = new HabitLevel([
                    'level' => $level,
                    'seq' => $i++,
                    'content' => $list['content'],
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

    public function updateWithLevelsAndLogs(array $data, Habit $habit): mixed
    {
        return DB::transaction(function () use ($data, $habit) {
            // 1. habit 업데이트
            Habit::where('id', $habit->id)->update(['title' => $data['title'], 'emoji' => $data['emoji']]);

            // 2. 하위 log 업데이트
            DB::table('logs')->where('habit_id', $habit->id)->update(['title' => $data['title'], 'emoji' => $data['emoji']]);

            // 3. habitLevels 및 levelLogs 업데이트 혹은 삭제
            $selectedRoundLog = DB::table('logs')->where('id', $data['logId'])->firstOrFail();
            $startDate = $selectedRoundLog->start_date;
            foreach ($data['levels'] as $levelList) {
                foreach ($levelList as $list) {
                    if (is_null($list['id'])) {
                        // 생성
                        $habitLevelId = DB::table('habit_levels')->insertGetId([
                            'habit_id' => $habit->id,
                            'content' => $list['content'],
                            'level' => $list['level'],
                            'seq' => $list['seq'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        for ($i = 0; $i < 20; $i++) {
                            LevelLog::insert([
                                'log_id' => $data['logId'],
                                'habit_level_id' => $habitLevelId,
                                'content' => $list['content'],
                                'level' => $list['level'],
                                'seq' => $list['seq'],
                                'log_date' => Carbon::parse($startDate)->addDays($i),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    } else if (!in_array($list['id'], $data['removedLevelIds'])) {
                        // 수정
                        HabitLevel::where('id', $list['id'])->update(['content' => $list['content']]);
                        LevelLog::where(['habit_level_id' => $list['id']], ['log_id' => $data['logId']])
                            ->update(['content' => $list['content']]);
                    }
                }
            }

            // 삭제
            if (!empty($data['removedLevelIds'])) {
                HabitLevel::whereIn('id', $data['removedLevelIds'])->delete();
                LevelLog::where('log_id', $data['logId'])->whereIn('habit_level_id', $data['removedLevelIds'])->delete();
            }
        });
    }
}
