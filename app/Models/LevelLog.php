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
        $levels = DB::table('level_logs')
            ->where('log_id', $logId)
            ->orderBy('log_date')
            ->orderBy('level')
            ->orderBy('seq')
            ->get()->toArray();

        $group = $this->groupBy($levels, 'log_date');
        return $group;
    }

    public function getLevelLogData($logId)
    {
        $levelLogGroup = $this->selectLevelLogsGroupByDate($logId);
        $levelLogData = [];
        $today = now()->toDateString();
        foreach ($levelLogGroup as $date => $logs) {
            // 지난 날짜면 상태 skip으로 기록
            if ($date < $today) {
                $levelLogData[$date]['status'] = 'skip';
                continue;
            } elseif ($date > $today) {
                $levelLogData[$date]['status'] = 'unchecked';
                continue;
            }

            $levels = [];

            foreach ($logs as $log) {
                if (!empty($log->is_checked)) {
                    $levels[] = $log->level;
                }
            }

            $levelLogData[$date]['max_level'] = $levels ? max($levels) : null;
        }
        return $levelLogData;
    }
}
