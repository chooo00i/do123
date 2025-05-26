<template>
    <div class="mb-6">
        <h3 class="title-xl">나의 습관</h3>
        <p class="text-gray-500 dark:text-gray-400">진행한 습관을 확인해보세요.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <Link v-for="habit in userHabits" :key="habit.id" :href="route('statistics.show', habit.id)"
            class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
            <div class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ habit.emoji }} {{ habit.title }}</div>
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    userHabits: Object,
})
</script>