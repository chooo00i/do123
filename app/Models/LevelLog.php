<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Helper;

class LevelLog extends Model
{
    use Helper;

    protected $fillable = ['log_id', 'habit_level_id', 'level', 'seq', 'log_date'];

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
}
