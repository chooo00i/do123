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
     * 로그 체크 업데이트
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function check(Request $request)
    {
        $data = $request->all();
        $toCheck = [];
        $toUncheck = [];

        foreach ($data as $id => $isChecked) {
            if ($isChecked) {
                $toCheck[] = $id;
            } else {
                $toUncheck[] = $id;
            }
        }

        if (!empty($toCheck)) {
            LevelLog::whereIn('id', $toCheck)->update(['is_checked' => true]);
        }

        if (!empty($toUncheck)) {
            LevelLog::whereIn('id', $toUncheck)->update(['is_checked' => false]);
        }

        return redirect()->back();
    }
}
