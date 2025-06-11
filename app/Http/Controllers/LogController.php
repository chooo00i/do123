<?php

namespace App\Http\Controllers;

use App\Models\HabitLevel;
use App\Models\LevelLog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Helper;
use Illuminate\Support\Carbon;
use App\Services\LogStatisticsService;

class LogController extends Controller
{
    use AuthorizesRequests;
    use Helper;

    public function __construct(private LogStatisticsService $logStatisticsService)
    {
    }

    /**
     * 메인 log list + 통계
     */
    public function index(?Log $log)
    {
        // 해당 log 작성자만 접속 가능
        if ($log->id)
            $this->authorize('view', $log);

        $startDate = Carbon::parse($log->start_date)->startOfDay();
        $endDate = Carbon::parse($log->end_date)->startOfDay();
        if (!today()->between($startDate, $endDate)) {
            return redirect()->route('home');
        }

        $user = auth()->user();
        $logs = new Log();
        $currentLogs = $logs->selectCurrentLogsForUser($user->id);

        $selectedLog = $log->id
            ? $currentLogs->firstWhere('id', $log->id)
            : $currentLogs->first();

        if ($selectedLog) {
            $statistics = $this->logStatisticsService->generateForLog($selectedLog);
        }

        return inertia('Log/Index', [
            'logs' => $currentLogs,
            'selectedLog' => $selectedLog,
            // 서비스에서 받은 데이터를 분해하여 전달하거나, 객체 그대로 전달
            'habitLevel' => $statistics?->habitLevel,
            'levelLogData' => $statistics?->levelLogData,
            'habitLevelRankData' => $statistics?->habitLevelRankData,
            'levelRatioData' => $statistics?->levelRatioData,
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
