<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserAccountController extends Controller
{
    public function create()
    {
        return inertia('UserAccount/Create');
    }

    public function store(Request $request)
    {
        $user = User::create($request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed' // password_confirmation까지 유효성 검사
        ]));

        // 계정 생성 후 로그인 페이지로 이동
        return redirect()->route('login')
            ->with('success', '회원가입 완료!');
    }

    public function edit(User $user)
    {
        $loginUser = auth()->user();
        if ($user->id !== $loginUser->id) return redirect()->back();
        return Inertia('UserAccount/Edit');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id, // 원래 이메일은 유니크에서 제외
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->update($validated);
            return redirect()->back()
                ->with('success', '수정 완료!');
        } else {
            return redirect()->back()
                ->with('error', '유저 정보 오류!');
        }
    }
}