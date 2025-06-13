<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitLevel;
use App\Models\Log;
use App\Models\LevelLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HabitService
{
    /**
     * 습관, 관련 레벨, 초기 로그를 트랜잭션 안에서 함께 생성
     * @param $data 유효성 검사를 통과한 데이터
     * @return array 생성된 habit, logId를 포함하는 배열
     */
    public function createHabitWithDetails($data): array
    {
        return DB::transaction(function () use ($data) {
            // 1. Habit 생성
            $habit = Habit::create([
                'title' => $data['title'],
                'emoji' => $data['emoji'],
                'creator_id' => $data['creator_id'],
                'is_template' => $data['is_template'] ?? false,
            ]);

            // 2. HabitLevel 생성
            $levels = $this->syncHabitLevels($habit->id, $data['levels']);

            // 3. 로그 생성 (템플릿이 아닌 경우에만)
            $logId = null;
            if (empty($data['is_template'])) {
                $habitDataForLog = ['habit' => $habit, 'levels' => $levels];
                $logId = (new Log)->addLogWithLevelLogs($habitDataForLog);
            }

            return [
                'habit' => $habit,
                'logId' => $logId,
            ];
        });
    }

    /**
     * 습관, 관련 레벨, 특정 로그 업데이트
     * @param $validatedData 유효성 검사를 통과한 요청 데이터
     * @param Habit $habit Habit 모델
     */
    public function updateHabitWithDetails($validatedData, Habit $habit)
    {
        DB::transaction(function () use ($validatedData, $habit) {
            // 1. Habit 정보 업데이트
            $habit->update([
                'title' => $validatedData->title,
                'emoji' => $validatedData->emoji
            ]);


            // 2. 관련된 모든 Log의 title, emoji 업데이트
            Log::where('habit_id', $habit->id)
                ->update([
                    'title' => $validatedData->title,
                    'emoji' => $validatedData->emoji
                ]);

            // 3. HabitLevel 동기화 (추가, 수정, 삭제)
            // 4. LevelLog 추가, 수정
            $this->syncHabitLevels($habit->id, $validatedData['levels'], $validatedData['removedHabitLevelIds'] ?? [], $validatedData['logId']);

            // 5. LevelLog 삭제
            if (!empty($validatedData['removedHabitLevelIds'])) {
                LevelLog::where('log_id', $validatedData['logId'])
                    ->whereIn('habit_level_id', $validatedData['removedHabitLevelIds'])
                    ->delete();
            }
        });
    }

    /**
     * 새로운 회차 시작
     * @param $validatedData 유효성 검사를 통과한 데이터
     * @param Habit $habit Habit 모델
     * @return array habit, logId 배열
     */
    public function startNewRound($validatedData, Habit $habit): array
    {
        return DB::transaction(function () use ($validatedData, $habit) {
            // 1. Habit 업데이트
            $habit->update([
                'title' => $validatedData->title,
                'emoji' => $validatedData->emoji
            ]);

            // 2. HabitLevel 동기화 (추가, 수정, 삭제)
            $levels = $this->syncHabitLevels($habit->id, $validatedData['levels'], $validatedData['removedHabitLevelIds'] ?? []);

            // 3. Log 및 LevelLog 생성
            $habitDataForLog = ['habit' => $habit, 'levels' => $levels];
            $logId = (new Log)->addLogWithLevelLogs($habitDataForLog);

            return [
                'habit' => $habit,
                'logId' => $logId,
            ];
        });
    }

    /**
     * HabitLevel을 동기화 (생성, 수정, 삭제)
     */
    private function syncHabitLevels(int $habitId, array $levelGroups, array $removedHabitLevelIds = [], int $logId = 0)
    {
        // HabitLevel 삭제 처리
        if (!empty($removedHabitLevelIds)) {
            HabitLevel::whereIn('id', $removedHabitLevelIds)->delete();
        }

        $syncedHabitLevels = [];
        foreach ($levelGroups as $levelGroup) {
            foreach ($levelGroup as $levelData) {
                // 삭제 리스트에 포함된 데이터 제외
                if (isset($levelData['id']) && in_array($levelData['id'], $removedHabitLevelIds)) {
                    continue;
                }

                $HabitLevelModel = HabitLevel::updateOrCreate(
                    ['id' => $levelData['id'] ?? null, 'habit_id' => $habitId],
                    [
                        'content' => $levelData['content'],
                        'level' => $levelData['level'],
                        'seq' => $levelData['seq'],
                    ]
                );

                if ($logId > 0) {
                    $this->syncLevelLogs($HabitLevelModel->id, $logId, $levelData);
                }

                $syncedHabitLevels[] = $HabitLevelModel;
            }
        }

        return $syncedHabitLevels;
    }

    /**
     * 특정 Log와 HabitLog에 연결된 LevelLog 동기화 (생성, 수정)
     */
    private function syncLevelLogs(int $habitLevelId, int $logId, array $levelData): void
    {
        $selectedLog = Log::findOrFail($logId);
        $startDate = Carbon::parse($selectedLog->start_date);

        if (is_null($levelData['id'])) {
            $habitGoalDays = 20;
            $levelLogsData = [];
            for ($i = 0; $i < $habitGoalDays; $i++) {
                $levelLogsData[] = [
                    'log_id' => $logId,
                    'habit_level_id' => $habitLevelId,
                    'content' => $levelData['content'],
                    'level' => $levelData['level'],
                    'seq' => $levelData['seq'],
                    'log_date' => $startDate->copy()->addDays($i)->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            LevelLog::insert($levelLogsData); // 대량 삽입
        } else {
            LevelLog::where('habit_level_id', $habitLevelId)
                ->where('log_id', $logId)
                ->update(['content' => $levelData['content']]);
        }
    }
}