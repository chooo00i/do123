<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Helper;

class LevelLog extends Model
{
    use Helper;

    protected $fillable = ['log_id', 'habit_level_id', 'level', 'seq', 'log_date', 'content'];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

    public function habitLevel()
    {
        return $this->belongsTo(HabitLevel::class);
    }

    /**
     * 20일 기록
     * @param int $logId
     * @return array<array>
     */
    public function selectLevelLogsGroupByDate(int $logId): array
    {
        $levels = LevelLog::where('log_id', $logId)
            ->orderBy('log_date')
            ->orderBy('level')
            ->orderBy('seq')
            ->get();

        $group = $this->groupBy($levels, 'log_date');
        return $group;
    }

    public function getLevelLogData(int $logId)
    {
        $levelLogGroup = $this->selectLevelLogsGroupByDate($logId);
        $levelLogData = [];
        $today = now()->toDateString();
        foreach ($levelLogGroup as $date => $logs) {
            // 오늘 기준 이후 날짜는 다른 상태로 기록 -> 체크 불가
            if ($date > $today) {
                $levelLogData[$date]['status'] = 'unchecked';
                continue;
            }

            $levels = [];

            foreach ($logs as $log) {
                if ($log['is_checked']) {
                    $levels[] = $log['level'];
                }
            }

            $levelLogData[$date]['max_level'] = $levels ? max($levels) : null;
        }
        return $levelLogData;
    }

    /**
     * 체크된 levelLogs 정보
     * @param int $logId
     * @return \Illuminate\Database\Eloquent\Collection<int, LevelLog>
     */
    public function selectCheckedLevelLogs(int $logId)
    {
        $levelLogs = LevelLog::where('log_id', $logId)
            ->where('is_checked', true)
            ->get();

        return $levelLogs;
    }

    /**
     * 행동 순위 관련 데이터
     * @param int $logId
     * @return array{content: mixed, count: int, level: mixed, percentage: float, rank: int[]}
     */
    public function getHabitLevelRankData(int $logId)
    {
        $checkedHabitLevels = $this->selectCheckedLevelLogs($logId);
        $groupedByHabitLevelId = $this->groupBy($checkedHabitLevels, 'habit_level_id');
        uasort($groupedByHabitLevelId, function ($a, $b) {
            return count($b) <=> count($a);
        });

        $totalCount = array_sum(array_map('count', $groupedByHabitLevelId));
        $habitLevelRankData = [];
        $rank = 0;
        $lastCount = -1;
        $index = 0;

        foreach ($groupedByHabitLevelId as $habitLevels) {
            $currentCount = count($habitLevels);
            // 이전 항목과 count가 다를 경우에만 rank를 현재 index+1로 갱신
            // count가 같으면 이전 rank를 그대로 사용하게 되어 동점 처리
            if ($currentCount !== $lastCount) {
                $rank = $index + 1;
            }

            // 퍼센테이지: 0으로 나누는 오류를 방지
            $percentage = ($totalCount > 0) ? ($currentCount / $totalCount) * 100 : 0;

            $habitLevelRankData[] = [
                'count' => $currentCount,
                'content' => $habitLevels[0]->content,
                'level' => $habitLevels[0]->level,
                'rank' => $rank,
                'percentage' => round($percentage),
            ];

            $lastCount = $currentCount;
            $index++;
        }

        return $habitLevelRankData;
    }
}
