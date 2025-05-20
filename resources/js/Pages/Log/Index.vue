<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- 탭 버튼 영역 -->
        <ul
            class="flex flex-wrap text-lg font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <li class="me-2">
                <button v-for="log in logs" :key="log.id"
                    class="inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    {{ log.emoji }}
                </button>
                <Link :href="route('habit.index')"
                    class='inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700'>
                +
                </Link>
            </li>
        </ul>
        <!-- 콘텐츠 영역 -->
        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <div v-if="logs" class="flex flex-col lg:flex-row gap-6 justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold dark:text-white mb-5">
                        {{ logs[0].title }}
                    </h2>
                    <div class="flex-1">
                        <div class="flex flex-col gap-4">
                            <div v-for="level in [1, 2, 3]" :key="level" class="flex items-start gap-4">
                                <div class="w-5 h-5 rounded-full mt-1" :class="{
                                    'bg-sky-200': level === 1,
                                    'bg-sky-300': level === 2,
                                    'bg-sky-500': level === 3,
                                }"></div>
                                <div>
                                    <p class="font-bold text-sky-600 dark:text-sky-300 text-xs sm:text-base">
                                        Level {{ level }}
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">
                                        <span v-for="(data, index) in habitLevel[level]" :key="data">
                                            {{ data }}<span v-if="index < habitLevel[level].length - 1"> · </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <aside class="w-full lg:w-80">
                    <div class="grid grid-cols-5 gap-3">
                        <template v-for="(lists, date) in levelLogData" :key="date">
                            <div v-if="lists.status == 'unchecked'"
                                class="flex items-center justify-center rounded-full text-sm text-gray-800 dark:text-white h-12 w-12 text-center font-semibold border border-gray-200 dark:border-gray-700">
                                {{ dayjs(date).format('MM/DD') }}</div>
                            <button v-else
                                class="flex items-center justify-center rounded-full bg-sky-100 dark:bg-sky-600 text-sm text-sky-800 dark:text-white hover:bg-sky-200 dark:hover:bg-sky-700 h-12 w-12 text-center font-semibold">
                                {{ lists.max_level ? 'Lv' + lists.max_level : 'skip' }}
                            </button>
                        </template>
                        <!-- <button
                            class="flex items-center justify-center rounded-full bg-sky-200 dark:bg-sky-700 text-sm text-sky-900 dark:text-white hover:bg-sky-300 dark:hover:bg-sky-800 h-12 w-12 text-center font-semibold">lv1</button>
                        <button
                            class="flex items-center justify-center rounded-full bg-sky-300 dark:bg-sky-800 text-sm text-sky-900 dark:text-white hover:bg-sky-400 dark:hover:bg-sky-900 h-12 w-12 text-center font-semibold">lv2</button>
                        <button
                            class="flex items-center justify-center rounded-full bg-sky-300 dark:bg-sky-800 text-sm text-sky-900 dark:text-white hover:bg-sky-400 dark:hover:bg-sky-900 h-12 w-12 text-center font-semibold">lv3</button> -->
                    </div>
                </aside>
            </div>
            <div v-else>
                <h3 class="title-xl">👆 습관 만들기를 시작해보세요!</h3>
                <p class="mb-3 text-gray-500 dark:text-gray-400">아직 시작한 습관이 없어요. + 를 눌러 시작해보세요. 추천 습관을 선택하거나 나만의 습관 루틴을
                    만들어 시작할 수 있습니다.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
// import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'

defineProps({
    logs: Object,
    habitLevel: Object,
    levelLogData: Array,
})

// const firstLog = logs[0]
// const dates = ref([])
// const start = dayjs(firstLog.start_date)

// for (let i = 0; i < 20; i++) {
//     dates.value.push(dayjs(start).add(i, 'day').format('MM/DD'))
// }
</script>