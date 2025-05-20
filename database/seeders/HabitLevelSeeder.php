<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HabitLevelSeeder extends Seeder
{
    public function run(): void
    {
        $createdAt = Carbon::parse('2025-05-20 17:19:13');

        DB::table('habit_levels')->insert([
            // 책 읽기
            ['id' => 1, 'habit_id' => 1, 'level' => 1, 'content' => '3장 읽기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 2, 'habit_id' => 1, 'level' => 2, 'content' => '10분 읽기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 3, 'habit_id' => 1, 'level' => 3, 'content' => '30분 읽기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 4, 'habit_id' => 1, 'level' => 3, 'content' => '독서 노트 작성하기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],

            // 매일 운동
            ['id' => 5, 'habit_id' => 2, 'level' => 1, 'content' => '스트레칭 1분', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 6, 'habit_id' => 2, 'level' => 2, 'content' => '스쿼트 10개', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 7, 'habit_id' => 2, 'level' => 2, 'content' => '걷기 10분', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 8, 'habit_id' => 2, 'level' => 3, 'content' => '운동 30분', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 9, 'habit_id' => 2, 'level' => 3, 'content' => '운동 기록 작성', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],

            // 영어 공부
            ['id' => 10, 'habit_id' => 3, 'level' => 1, 'content' => '단어 3개 외우기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 11, 'habit_id' => 3, 'level' => 1, 'content' => '문장 1개 따라쓰기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 12, 'habit_id' => 3, 'level' => 2, 'content' => '영어 듣기 5분', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 13, 'habit_id' => 3, 'level' => 3, 'content' => '영어 뉴스/영상 10분 보기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 14, 'habit_id' => 3, 'level' => 3, 'content' => '영어 일기 쓰기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],

            // 글쓰기
            ['id' => 15, 'habit_id' => 4, 'level' => 1, 'content' => '문장 하나 쓰기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 16, 'habit_id' => 4, 'level' => 2, 'content' => '단락 하나 쓰기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 17, 'habit_id' => 4, 'level' => 2, 'content' => '5분 자유 글쓰기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 18, 'habit_id' => 4, 'level' => 3, 'content' => '하루 글 완성', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 19, 'habit_id' => 4, 'level' => 3, 'content' => '지정된 주제로 글쓰기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],

            // 건강한 아침
            ['id' => 20, 'habit_id' => 5, 'level' => 1, 'content' => '눈뜨자마자 기지개', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 21, 'habit_id' => 5, 'level' => 1, 'content' => '물 한 잔 마시기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 22, 'habit_id' => 5, 'level' => 2, 'content' => '5분 명상', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 23, 'habit_id' => 5, 'level' => 3, 'content' => '오늘 할 일 쓰기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],

            // 자기계발
            ['id' => 24, 'habit_id' => 6, 'level' => 1, 'content' => '오늘 목표 세우기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 25, 'habit_id' => 6, 'level' => 1, 'content' => '인상 깊은 문장 기록', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 26, 'habit_id' => 6, 'level' => 2, 'content' => '짧은 강연 시청', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 27, 'habit_id' => 6, 'level' => 2, 'content' => '책/기사 한 문단 읽기', 'seq' => 2, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
            ['id' => 28, 'habit_id' => 6, 'level' => 3, 'content' => '자기 전 오늘 회고 글쓰기', 'seq' => 1, 'created_at' => $createdAt, 'updated_at' => $createdAt, 'deleted_at' => null],
        ]);
    }
}