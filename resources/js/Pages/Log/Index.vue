<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- 탭 버튼 영역 -->
        <ul
            class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <li class="me-2">
                <button @click="activeTab = 'tab1'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab1' ? 'text-blue-600 dark:text-blue-500' : ''
                ]">
                    🙇‍♀️
                </button>
            </li>
            <li class="me-2">
                <button @click="activeTab = 'tab2'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab2' ? 'text-blue-600 dark:text-blue-500' : ''
                ]">
                    🏠
                </button>
            </li>
        </ul>

        <!-- 콘텐츠 영역 -->
        <div v-if="activeTab === 'tab1'" class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <h2 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                🙇‍♀️ Powering innovation & trust
            </h2>
            <p class="mb-3 text-gray-500 dark:text-gray-400">
                Empower Developers and business teams to collaborate at high velocity.
            </p>
        </div>

        <div v-else-if="activeTab === 'tab2'" class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <h2 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                🏠 Powering innovation & trust
            </h2>
            <p class="mb-3 text-gray-500 dark:text-gray-400">
                Empower Developers and business teams to collaborate at high velocity.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('tab1')
</script>