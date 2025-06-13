<template>
    <div class="p-4">
        <div v-for="level in [1, 2, 3]" :key="level" class="flex flex-row gap-4 mb-4 items-start">
            <h3 class="title-normal w-[70px]">Level {{ level }}</h3>
            <div class="flex-1">
                <ul class="grid w-full gap-3">
                    <li v-for="(list) in levelLogs[level]" :key="list.id">
                        <input type="checkbox" :id="list.id" v-model="list.is_checked" :true-value="1" :false-value="0" class="hidden peer" />
                        <label :for="list.id"
                            class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-sky-300 dark:peer-checked:border-sky-400 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-800">
                            <div class="block">
                                <div class="w-full text-sm">{{ list.content }}</div>
                            </div>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex justify-end">
            <button @click.prevent="save" class="btn-primary">저장</button>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    levelLogs: Object,
})
const levelLogs = props.levelLogs
const allLists = Object.values(levelLogs).flat()

const save = () => {
    router.visit(route('level_log.batch-check'), {
        method: 'patch',
        data: allLists,
        preserveScroll: true,
    })
}
</script>