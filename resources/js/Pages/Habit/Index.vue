<template>
    <div class="mb-6">
        <h3 class="title-xl">추천 습관</h3>
        <p class="text-gray-500 dark:text-gray-400">원하는 습관을 선택해보세요!</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div v-for="habit in templateHabits" :key="habit.id">
            <Link :href="route('habit.copy', habit.id)" class="btn-primary">{{ habit.emoji }} {{ habit.title }}</Link>
        </div>
    </div>
    <Link :href="route('habit.create')" class="btn-green-to-blue">+ 나만의 습관 만들기</Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    templateHabits: Array,
})
</script>