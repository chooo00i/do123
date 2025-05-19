<template>
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" class="sr-only peer" :checked="modelValue"
            @change="$emit('update:modelValue', $event.target.checked)" />
        <div :class="[
            `relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4`,
            `peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-['']`, 
            `after:absolute after:top-0.5 after:start-[2px] after:bg-white`,
            `after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600`,
            colorClass
            ]"></div>
        <span v-if="label" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
            {{ label }}
        </span>
    </label>
</template>

<script setup>
const props = defineProps({
    modelValue: Boolean,
    label: {
        type: String,
        default: ''
    }, 
})
const emit = defineEmits(['update:modelValue'])
const colorClass = `peer-focus:ring-slate-300 dark:peer-focus:ring-slate-800 peer-checked:bg-slate-600 dark:peer-checked:bg-slate-600`
</script>