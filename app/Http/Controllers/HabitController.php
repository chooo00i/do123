<?php

namespace App\Http\Controllers;

use App\Models\Habit;
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
        return inertia('Habit/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HabitRequest $request)
    {
        try {
            // Habit/Habit_level 저장
            $user = auth()->user();
            $habit = new Habit();
            $habit->createWithLevelsAndLogs([
                'title' => $request['title'],
                'emoji' => $request['emoji'],
                'creator_id' => $user['id'],
                'is_template' => $user->is_admin == true ? true : false,
                'is_public' => $request['isPublic'] ?? false,
                'levels' => $request['levels'],
            ]);
            // 성공 응답
            return redirect()->route('home')->with('success', '습관 만들기 완료!');
        } catch (\Exception $e) {
            // 실패 응답
            return redirect()->back()->with('error', '저장 실패' . ' : ' . $e);
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
}
