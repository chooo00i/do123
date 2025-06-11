<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Helper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

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
    public function selectLevelLogsGroupByDate(int $logId): Collection
    {
        $levels = LevelLog::where('log_id', $logId)
            ->orderBy('log_date')
            ->orderBy('level')
            ->orderBy('seq')
            ->get();

        return $levels->groupBy('log_date');
    }

    /**
     * 로그의 20일간의 습관 체크 상태 데이터
     * @param int $logId
     * @return Collection
     */
    public function getLevelLogData(int $logId): Collection
    {
        $levelLogGroup = collect($this->selectLevelLogsGroupByDate($logId));

        $today = Date::today()->toDateString();

        return $levelLogGroup->mapWithKeys(function (Collection $logs, string $date) use ($today) {
            // 아직 오지 않은 날짜는 unchecked로 처리
            if ($date > $today) {
                return [$date => ['status' => 'unchecked']];
            }

            // maxLevel 계산
            $maxLevel = $logs->where('is_checked', true)
                ->pluck('level')
                ->max() ?? 0;

            return [$date => ['maxLevel' => $maxLevel]];
        });
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
