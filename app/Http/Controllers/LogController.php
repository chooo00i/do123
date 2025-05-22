<?php

namespace App\Http\Controllers;

use App\Models\HabitLevel;
use App\Models\LevelLog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LogController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(?Log $log)
    {
        if ($log->id) $this->authorize('view', $log);
        $user = auth()->user();

        // user의 진행중인 습관 log 정보
        $logs = new Log();
        $currentLogs = $logs->selectCurrentLogsForUser($user->id);

        $selectedLog = $log->id
            ? $currentLogs->firstWhere('id', $log->id)
            : $currentLogs->first();

        $habitLevel = null;
        $levelLogData = null;

        if ($selectedLog) {
            //  첫번째 로그 habit_level 정보
            $habitLevel = (new HabitLevel())->selectHabitLevelsGroupByLevel($selectedLog->habit_id);

            // 첫번째 로그 20일 정보
            $levelLogData = (new LevelLog())->getLevelLogData($selectedLog->id);
        }

        return inertia('Log/Index', [
            'logs' => $currentLogs,
            'habitLevel' => $habitLevel,
            'levelLogData' => $levelLogData,
            'selectedLog' => $selectedLog,
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

    /**
     * 통계 페이지
     */
    // public function showStatistics(Request $request) {
    //     $logId = $request->logId;
    //     $log = Log::findOrFail($logId);

    //     if (!$log) {
    //         return redirect()->back();
    //     }

    //     $this->authorize('view', $log);
    //     $user = auth()->user();
    // }
}
