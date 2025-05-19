<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Log/Index');
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
        $res = $request;
        // 1. 유효성 검사
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'emoji' => 'required|string|max:10',
            'levels' => 'required|array',
            'levels.*' => 'array|max:3',
            'levels.*.*' => 'required|string|max:255',
        ]);

        try {
            $user = auth()->user();
            // 2. Habit/Habit_level 저장
            Habit::createWithLevels([
                'title' => $validated['title'],
                'emoji' => $validated['emoji'],
                'creator_id' => $user['id'],
                'is_template' => $user->is_admin == true ? true : false,
                'is_public' => $validated['isPublic'] ?? false,
                'levels' => $validated['levels'],
            ]);
            // 3. 성공 응답
            return redirect()->back()->with('success', 'Habit Saved!');
        } catch (\Exception $e) {
            // 4. 실패 응답
            return redirect()->back()->with('error', 'Save failed!');
        }
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
