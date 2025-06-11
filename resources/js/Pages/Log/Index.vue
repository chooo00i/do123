<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- 탭 버튼 영역 -->
        <ul
            class="flex flex-wrap text-lg font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <li class="me-2" v-for="(log, index) in logs" :key="log.id">
                <button @click="selectLog(log)" :class="[
                    'inline-block p-4 hover:bg-gray-200 dark:hover:bg-gray-700',
                    selectedLog?.id === log.id || (!selectedLog && index === 0)
                        ? 'bg-gray-200 dark:bg-gray-700'
                        : '',
                    index == 0 ? 'rounded-ss-lg' : '',
                ]">
                    {{ log.emoji }}
                </button>
            </li>
            <li>
                <button @click.prevent="newHabit()" class='inline-block p-4 hover:bg-gray-200 dark:hover:bg-gray-700'>
                +
                </button>
            </li>
            <li class="ml-auto" v-if="selectedLog">
                <Link :href="route('habit.edit', {
                    habit: selectedLog?.habit_id,
                    log_id: selectedLog?.id
                },)" class='inline-block p-4 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-se-lg'>
                ⚙️
                </Link>
            </li>
        </ul>
        <!-- 콘텐츠 영역 -->
        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <div v-if="logs[0]">
                <!-- 행동 목록 + 체크 -->
                <div class="flex flex-col lg:flex-row gap-6 justify-between">
                    <!-- 행동 목록 -->
                    <div>
                        <h2 class="text-3xl font-extrabold dark:text-white mb-5">
                            {{ selectedLog ? selectedLog.title + ' · ' + selectedLog.round + '회차' : logs[0].title + ' · ' + logs[0].round + '회차' }}
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
                                        <p class="content-sm">
                                            <span v-for="(data, index) in habitLevel[level]" :key="data">
                                                {{ data.content }}<span v-if="index < habitLevel[level].length - 1"> ·
                                                </span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 20일 체크 -->
                    <aside class="w-full lg:w-80">
                        <div class="grid grid-cols-5 gap-1">
                            <template v-for="(list, date) in levelLogData" :key="date">
                                <div v-if="list.status == 'unchecked'"
                                    class="flex items-center justify-center rounded-full text-sm text-gray-800 dark:text-white h-14 w-14 text-center font-semibold border border-gray-200 dark:border-gray-700">
                                    {{ dayjs(date).format('MM/DD') }}</div>
                                <button v-else @click="openModal(date)"
                                    :class="{
                                        'bg-sky-200': list.max_level === 1,
                                        'bg-sky-300': list.max_level === 2,
                                        'bg-sky-400': list.max_level === 3,
                                        'bg-sky-100': list.max_level === null,
                                    }"
                                    class="flex flex-col items-center justify-center rounded-full text-sm text-sky-800 hover:bg-sky-500 h-14 w-14 text-center font-semibold">
                                    <span class="text-[0.5rem] leading-[0.8]">{{ dayjs(date).format('MMDD') }}</span>
                                    <span>{{ list.max_level ? 'Lv' + list.max_level : 'skip' }}</span>
                                </button>
                            </template>
                        </div>
                    </aside>
                    <Modal :show="showModal" :title="modalTitle" @close="showModal = false">
                        <Edit :levelLogs="levelLogs" :showModal="showModal" />
                    </Modal>
                </div>
                <!-- 통계 -->
                <div class="mt-7">
                    <Level />
                    <HabitLevel v-if="habitLevelRankData[0]" class="mt-7" :habitLevelRankData="habitLevelRankData" />
                </div>
            </div>
            <div v-else>
                <h3 class="title-xl">👆 습관 만들기를 시작해보세요!</h3>
                <p class="mb-3 text-gray-500 dark:text-gray-400">아직 시작한 습관이 없어요. + 를 눌러 시작해보세요. 추천 습관을 선택하거나 나만의 습관 루틴을
                    만들어 시작할 수 있어요.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import axios from 'axios'
import Modal from '@/Components/UI/Modal.vue'
import Edit from './Edit.vue'
import Level from '@/Pages/Statistics/Level.vue'
import HabitLevel from '@/Pages/Statistics/HabitLevel.vue'

const { logs, habitLevel, levelLogData, selectedLog, habitLevelRankData } = defineProps({
    logs: Object,
    habitLevel: Object,
    levelLogData: Object,
    selectedLog: Object,
    habitLevelRankData: Array,
})

const showModal = ref(false)
const modalTitle = ref()
const logId = ref(0)
const levelLogs = ref(null)

// 모달을 열면서 해당 날짜 정보 전달
const openModal = async (date) => {
    const tabLogId = logId.value > 0 ? logId.value : selectedLog.id

    try {
        const response = await axios.get(route('level_log.by_date', {
            log_id: tabLogId,
            date: date
        }))

        levelLogs.value = response.data
        modalTitle.value = dayjs(date).format('M월 D일')
        showModal.value = true
    } catch (error) {
        console.error('데이터 불러오기 실패:', error)
    }
}

// 탭을 눌렀을 때 작동
const selectLog = (log) => {
    logId.value = log.id
    router.visit(route('home', log.id), {
        preserveScroll: true,
        preserveState: true, // 상태 유지 (모달 등)
        only: ['habitLevel', 'levelLogData', 'selectedLog', 'habitLevelRankData'],
    })
}

// 새 습관 이동
const newHabit = () => {
    if (logs.length >= 3) {
        alert('3개 이상 습관을 진행할 수 없습니다.')
        return
    } 
    router.visit(route('habit.index'))
}
</script>