<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLevel;
use Illuminate\Http\Request;
use App\Http\Requests\HabitRequest;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templateHabits = $templateHabits = Habit::where('is_template', 1)->get();
        return inertia('Habit/Index', [
            'templateHabits' => $templateHabits,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia(
            'Habit/Create',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HabitRequest $request)
    {
        try {
            // Habit/Habit_level 저장
            $user = auth()->user();
            $isAdmin = $user->is_admin;

            // todo 인당 진행중인 습관 최대 5개 제한 추가

            $habit = new Habit();
            // habits, habit_levels, logs, level_logs 한번에 초기 생성
            $habit->createWithLevelsAndLogs([
                'title' => $request['title'],
                'emoji' => $request['emoji'],
                'creator_id' => $user['id'],
                'is_template' => $isAdmin,
                'is_public' => $request['isPublic'] ?? false,
                'levels' => $request['levels'],
            ]);
            // 성공 응답
            return redirect()->route('home')->with('success', '습관 만들기 완료!');
        } catch (\Exception $e) {
            // 실패 응답
            return redirect()->back()->with('error', '저장 실패');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habit $habit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        //
    }

    /**
     * 습관 복사(템플릿)
     */
    public function copy(Habit $habit)
    {
        // 템플릿이 아닌 습관 id는 접근 불가
        if (!$habit->is_template) {
            return redirect()->back();
        }
        // habitLevels
        $habitLevel = new HabitLevel();
        $habitLevels = $habitLevel->selectHabitLevelsGroupByHabitId($habit->id);

        return inertia(
            'Habit/Create',
            [
                'habit' => $habit,
                'habitLevels' => $habitLevels,
            ]
        );
    }
}
