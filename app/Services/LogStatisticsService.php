<?php

namespace App\Services;

use App\Models\Log;
use App\Models\HabitLevel;
use App\Models\LevelLog;
use Illuminate\Support\Collection;

class LogStatisticsService
{
    /**
     * 선택된 Log에 대한 통계 데이터를 생성
     *
     * @param Log $selectedLog
     * @return object
     */
    public function generateForLog(Log $selectedLog): object
    {
        $habitLevel = (new HabitLevel())->selectHabitLevelsGroupByLevel($selectedLog->habit_id);
        $levelLogData = (new LevelLog())->getLevelLogData($selectedLog->id);
        $habitLevelRankData = (new LevelLog())->getHabitLevelRankData($selectedLog->id);
        $levelRatioData = $this->calculateLevelRatio($levelLogData);

        return (object) [
            'habitLevel' => $habitLevel,
            'levelLogData' => $levelLogData,
            'habitLevelRankData' => $habitLevelRankData,
            'levelRatioData' => $levelRatioData,
        ];
    }

    /**
     * 레벨 로그 데이터를 기반으로 레벨별 비율을 계산
     *
     * @param Collection $levelLogData
     * @return array
     */
    private function calculateLevelRatio(Collection $levelLogData): array
    {
        // maxLevel이 존재하는 로그만 필터링
        $validLogs = $levelLogData->filter(fn($data) => array_key_exists('maxLevel', $data));

        // 레벨별 카운트를 집계 (0, 1, 2, 3)
        $levelCounts = collect([0, 1, 2, 3])
            ->mapWithKeys(fn($level) => [$level => 0])
            ->replace($validLogs->countBy('maxLevel'));

        $checkedTotalCount = $validLogs->where('maxLevel', '>', 0)->count();

        $dataList = $levelCounts->map(function ($count, $level) {
            $percentage = bcmul(bcdiv($count, '20', 2), '100');
            return ['count' => $count, 'level' => $level, 'percentage' => $percentage];
        })->values()->all();

        return [
            'checkedTotalCount' => $checkedTotalCount,
            'dataList' => $dataList,
        ];
    }
}