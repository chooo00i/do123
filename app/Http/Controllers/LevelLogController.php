<?php

namespace App\Http\Controllers;

use App\Models\LevelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Helper;

class LevelLogController extends Controller
{
    use Helper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(LevelLog $levelLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LevelLog $levelLog)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LevelLog $levelLog)
    {
        //
    }

    public function byDate(Request $request)
    {
        $levelLogs = LevelLog::where('log_id', $request->log_id)
            ->where('log_date', $request->date)
            ->get();

        $levelLogs = $this->groupBy($levelLogs, 'level');

        return response()->json($levelLogs);
    }

    /**
     * 로그 체크 업데이트 후 이전 페이지 리다이렉트
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function batchCheck(Request $request)
    {
        $validated = $request->validate([
            '*.id' => ['required', 'integer', 'exists:level_logs,id'],
            '*.is_checked' => ['required', 'boolean'],
        ]);

        $toCheck = [];
        $toUncheck = [];

        foreach ($validated as $item) {
            if ($item['is_checked']) {
                $toCheck[] = $item['id'];
            } else {
                $toUncheck[] = $item['id'];
            }
        }

        DB::transaction(function () use ($toCheck, $toUncheck) {
            if (!empty($toCheck)) {
                LevelLog::whereIn('id', $toCheck)->update(['is_checked' => true]);
            }

            if (!empty($toUncheck)) {
                LevelLog::whereIn('id', $toUncheck)->update(['is_checked' => false]);
            }
        });

        return redirect()->back();
    }
}
