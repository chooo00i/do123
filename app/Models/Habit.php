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

            return $habitData;
        });
    }

    public function updateWithLevelsAndLogs(array $data, Habit $habit): mixed
    {
        return DB::transaction(function () use ($data, $habit) {
            // 1. habit 업데이트
            Habit::where('id', $habit->id)->update(['title' => $data['title'], 'emoji' => $data['emoji']]);

            // 2. 하위 log 업데이트
            Log::where('habit_id', $habit->id)->update(['title' => $data['title'], 'emoji' => $data['emoji']]);

            // 3. HabitLevels 및 LevelLogs 처리
            $this->processHabitLevelsAndLogs($habit->id, $data);

            // 4. 삭제된 HabitLevels 및 관련 LevelLogs 처리
            $this->deleteRemovedLevels($data);
        });
    }

    /**
     * HabitLevels 및 관련 LevelLogs를 생성하거나 업데이트.
     */
    protected function processHabitLevelsAndLogs(int $habitId, array $data): void
    {
        // $data['logId']로 Log를 가져오거나, Log 모델 인스턴스를 직접 전달받는 것도 고려
        $selectedLog = Log::findOrFail($data['logId']); // Log 모델 사용 예시
        // $selectedRoundLog = DB::table('logs')->where('id', $data['logId'])->firstOrFail(); // 기존 코드
        $startDate = Carbon::parse($selectedLog->start_date);

        foreach ($data['levels'] as $levelGroup) { // $levelList -> $levelGroup으로 변수명 변경
            foreach ($levelGroup as $levelData) { // $list -> $levelData으로 변수명 변경
                // 삭제 대상이 아닌 경우에만 생성 또는 수정 로직 실행
                if (isset($levelData['id']) && in_array($levelData['id'], $data['removedLevelIds'] ?? [])) {
                    continue; // 삭제 대상이면 건너뛰기
                }

                if (is_null($levelData['id'])) {
                    // 생성 로직
                    $this->createHabitLevelAndLogs($habitId, $selectedLog->id, $levelData, $startDate);
                } else {
                    // 수정 로직 (삭제 대상이 아닌 경우)
                    $this->updateHabitLevelAndLogs($selectedLog->id, $levelData);
                }
            }
        }
    }

    /**
     * 새로운 HabitLevel과 관련 LevelLog 생성.
     */
    protected function createHabitLevelAndLogs(int $habitId, int $logId, array $levelData, Carbon $startDate): void
    {
        $habitLevel = HabitLevel::create([
            'habit_id' => $habitId,
            'content' => $levelData['content'],
            'level' => $levelData['level'],
            'seq' => $levelData['seq'],
        ]);

        $levelLogsData = [];
        for ($i = 0; $i < 20; $i++) {
            $levelLogsData[] = [
                'log_id' => $logId,
                'habit_level_id' => $habitLevel->id,
                'content' => $levelData['content'],
                'level' => $levelData['level'],
                'seq' => $levelData['seq'],
                'log_date' => $startDate->copy()->addDays($i)->toDateString(), // toDateString() 등으로 포맷팅
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        LevelLog::insert($levelLogsData); // 대량 삽입
    }

    /**
     * 기존 HabitLevel과 관련 LevelLog 업데이트.
     */
    protected function updateHabitLevelAndLogs(int $logId, array $levelData): void
    {
        $habitLevel = HabitLevel::find($levelData['id']);
        if ($habitLevel) {
            $habitLevel->update(['content' => $levelData['content']]);

            LevelLog::where('habit_level_id', $levelData['id'])
                ->where('log_id', $logId)
                ->update(['content' => $levelData['content']]);
        }
    }

    /**
     * 제거 대상으로 표시된 HabitLevels 및 관련 LevelLogs(완전 삭제) 삭제.
     */
    protected function deleteRemovedLevels(array $data): void
    {
        if (isset($data['removedLevelIds']) && !empty($data['removedLevelIds'])) {
            HabitLevel::whereIn('id', $data['removedLevelIds'])->delete();
            LevelLog::where('log_id', $data['logId'])
                ->whereIn('habit_level_id', $data['removedLevelIds'])
                ->delete();
        }
    }
}
