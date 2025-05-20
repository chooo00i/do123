<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Seoul')->format('Y-m-d H:i:s');

        DB::table('habits')->insert([
            [
                'id' => 1,
                'emoji' => '📖',
                'title' => '책 읽기',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'emoji' => '🏃',
                'title' => '매일 운동',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'emoji' => '🧠',
                'title' => '영어 공부',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'emoji' => '✍️',
                'title' => '글쓰기',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'emoji' => '☀️',
                'title' => '건강한 아침',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'emoji' => '📈',
                'title' => '자기계발',
                'is_public' => 0,
                'is_template' => 1,
                'creator_id' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);
    }
}