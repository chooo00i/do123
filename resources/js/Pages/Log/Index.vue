<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- 탭 버튼 영역 -->
        <ul
            class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <li class="me-2">
                <!-- <button @click="activeTab = 'tab3'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab3' ? 'bg-gray-100 dark:hover:bg-gray-700' : ''
                ]">
                    +
                </button> -->
                <Link :href="route('habit.index')"
                    class='inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700'>
                +
                </Link>
            </li>
        </ul>

        <!-- 콘텐츠 영역 -->
        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <div>
                <h3 class="title-xl">👆 습관 만들기를 시작해보세요!</h3>
                <p class="mb-3 text-gray-500 dark:text-gray-400">아직 시작한 습관이 없어요. + 를 눌러 시작해보세요. 추천 습관을 선택하거나 나만의 습관 루틴을 만들어 시작할 수 있습니다.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const activeTab = ref('tab1')
</script>