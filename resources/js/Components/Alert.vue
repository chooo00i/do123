<template>
    <div v-if="visible" :class="[
        baseClass,
        typeClasses[type] || typeClasses.success
    ]" role="alert">
        <slot name="icon">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
        </slot>

        <div class="ms-3 text-sm font-medium">
            {{ message }}
        </div>

        <button type="button" @click="visible = false" :class="closeButtonClasses[type] || closeButtonClasses.success"
            aria-label="Close"
            class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    type: {
        type: String,
        default: 'success', // success, error, warning 등
    },
    message: {
        type: String,
        required: true,
    },
    show: {
        type: Boolean,
        default: true,
    }
})

const emit = defineEmits(['update:show'])

const visible = ref(props.show)

watch(() => props.show, (val) => {
    visible.value = val
})

watch(visible, (val) => {
    emit('update:show', val)
})

const baseClass = "flex items-center p-4 mb-4 rounded-lg"

const typeClasses = {
    success: "text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400",
    error: "text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400",
    warning: "text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300",
}

const closeButtonClasses = {
    success: "bg-green-50 text-green-500 hover:bg-green-200 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700 focus:ring-green-400",
    error: "bg-red-50 text-red-500 hover:bg-red-200 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700 focus:ring-red-400",
    warning: "bg-yellow-50 text-yellow-500 hover:bg-yellow-200 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700 focus:ring-yellow-400",
}
</script>