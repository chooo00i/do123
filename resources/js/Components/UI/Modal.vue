<template>
    <div v-if="show" tabindex="-1" role="dialog" aria-modal="true"
        class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between pl-5 pr-5 pt-3 pb-3 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ title }}
                    </h3>
                    <button type="button" @click="emitClose"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-1 overflow-y-auto max-h-[70vh]">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Modal'
    }
})

const emit = defineEmits(['close'])
const emitClose = () => {
    emit('close')
};
</script>