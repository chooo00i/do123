<?php

namespace App\Http\Controllers;

use App\Models\HabitLevel;
use App\Models\LevelLog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // user의 진행중인 습관 log 정보
        $logs = new Log();
        $currentLogs = $logs->selectCurrentLogsForUser($user->id);

        $firstLog = $currentLogs[0];
        // 첫번째 로그 habit_level 정보
        $habitLevel = (new HabitLevel())->selectHabitLevelsGroupByLevel($firstLog->habit_id);

        // 첫번째 로그 20일 정보
        $levelLogGroup = (new LevelLog())->selectLevelLogsGroupByDate($firstLog->id);
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

        return inertia('Log/Index', [
            'logs' => $currentLogs,
            'habitLevel' => $habitLevel,
            'levelLogData' => $levelLogData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        //
    }
}
