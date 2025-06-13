<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLevel;
use App\Models\Log;
use App\Services\HabitService;
use App\Http\Requests\HabitStoreRequest;
use App\Http\Requests\HabitUpdateRequest;
use App\Http\Requests\StartNewRoundRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HabitController extends Controller
{
    use AuthorizesRequests;

    protected $habitService;

    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // 템플릿 습관 목록
        $templateHabits = Habit::where('is_template', 1)->get();

        // 현재 진행중인 것 제외 나의 습관 목록
        $habit = new Habit();
        $notProgressingHabits = $habit->selectNotProgressingHabits($user->id);

        return inertia('Habit/Index', [
            'templateHabits' => $templateHabits,
            'notProgressingHabits' => $notProgressingHabits,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia(
            'Habit/Create',
            [
                'type' => 'create'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HabitStoreRequest $request)
    {
        try {
            // 새로운 습관 저장
            $user = auth()->user();
            $request->merge(['creator_id' => $user->id, 'is_template' => $user->is_admin]);
            $result = $this->habitService->createHabitWithDetails($request);
            // 성공 응답
            return redirect()
                ->route('home', $result['logId'])
                ->with('success', '습관 만들기 완료!');
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
    public function edit(Habit $habit, int $logId)
    {
        $this->authorize('update', $habit);

        // habitLevels
        $habitLevel = new HabitLevel();
        $habitLevels = $habitLevel->selectHabitLevelsGroupByLevel($habit->id);

        return inertia(
            'Habit/Create',
            [
                'habit' => $habit,
                'habitLevels' => $habitLevels,
                'type' => 'edit',
                'logId' => $logId,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HabitUpdateRequest $request, Habit $habit)
    {
        $this->habitService->updateHabitWithDetails($request, $habit);
        return redirect()->route('home', $request['logId'])->with('success', '습관 수정 완료!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        //
    }

    /**
     * 습관 복사(템플릿) 이동
     */
    public function copy(Habit $habit)
    {
        $user = auth()->user();
        // 템플릿이 아닌 습관 id 혹은 생성자가 아니면 접근 불가
        if (!$habit->is_template && $habit->creator_id !== $user->id) {
            return redirect()->back();
        }
        // habitLevels
        $habitLevel = new HabitLevel();
        $habitLevels = $habitLevel->selectHabitLevelsGroupByLevel($habit->id);

        return inertia(
            'Habit/Create',
            [
                'habit' => $habit,
                'habitLevels' => $habitLevels,
                'type' => $habit->is_template ? 'copy' : 'newRound', // 템플릿 복사 혹은 새로운 회차 시작
            ]
        );
    }

    /**
     * 새 회차 시작
     * @param \App\Http\Requests\StartNewRoundRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startNewRound(StartNewRoundRequest $request, Habit $habit)
    {
        try {
            $user = auth()->user();
            $request->merge(['creator_id' => $user->id]);
            $result = $this->habitService->startNewRound($request, $habit);
            // 성공 응답
            return redirect()
                ->route('home', $result['logId'])
                ->with('success', '새로운 회차 시작!');
        } catch (\Throwable $th) {
            // 실패 응답
            return redirect()->back()->with('error', '저장 실패');
        }
    }
}
