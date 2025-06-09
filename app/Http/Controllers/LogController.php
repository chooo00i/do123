<?php

namespace App\Http\Controllers;

use App\Models\HabitLevel;
use App\Models\LevelLog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Helper;

class LogController extends Controller
{
    use AuthorizesRequests;
    use Helper;

    /**
     * 메인 log list + 통계
     */
    public function index(?Log $log)
    {
        // 해당 log 작성자만 접속 가능
        if ($log->id)
            $this->authorize('view', $log);
        $user = auth()->user();

        // 1. user의 진행중인 습관 log 정보
        $logs = new Log();
        $currentLogs = $logs->selectCurrentLogsForUser($user->id);

        $selectedLog = $log->id
            ? $currentLogs->firstWhere('id', $log->id)
            : $currentLogs->first();

        $habitLevel = null;
        $levelLogData = null;
        $habitLevelCounts = null;

        if ($selectedLog) {
            // 1-1. habit_level 정보
            $habitLevel = (new HabitLevel())->selectHabitLevelsGroupByLevel($selectedLog->habit_id);

            // 1-2. 20일 정보(가장 높은 레벨, 일자 등)
            $levelLogData = (new LevelLog())->getLevelLogData($selectedLog->id);

            // 2. 통계 관련 정보
            // 2-1. level, skip 백분율 / 20일 중 skip 하지 않은 비율

            // 2-2. 체크한 habit_level 회수 및 내림 차순 정렬
            $habitLevelCounts = (new LevelLog())->getHabitLevelRankData($selectedLog->id);
        }


        return inertia('Log/Index', [
            'logs' => $currentLogs,
            'habitLevel' => $habitLevel,
            'levelLogData' => $levelLogData,
            'selectedLog' => $selectedLog,
            'habitLevelCounts' => $habitLevelCounts,
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
     * 로그 중지
     */
    public function destroy(Log $log)
    {
        // Log, LevelLog 정보 삭제
        Log::where('id', $log->id)->delete();
        LevelLog::where('log_id', $log->id)->delete();

        // 로그 회차 업데이트
        $logs = Log::where('habit_id', $log->habit_id)
            ->whereNot('id', $log->id)
            ->orderBy('round')->get();
        if ($logs->isNotEmpty()) {
            $round = 1;
            foreach ($logs as $logItem) {
                $logItem->round = $round++;
                $logItem->save();
            }
        }

        return redirect()->route('home');
    }

    public function history()
    {
        $user = auth()->user();
        // 이전에 진행한 로그 목록
        // $userHabits = (new Habit())->selectHabitsForUser($user->id);

        // return inertia('Statistics/Index', [
        //     'userHabits' => $userHabits,
        // ]);
    }
}
