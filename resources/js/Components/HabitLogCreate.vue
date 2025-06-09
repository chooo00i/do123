<template>
    <form class="p-4">
        <!-- 습관 이름 -->
        <div class="mb-4">
            <h3 class="title-xl">Habit Name</h3>
            <div class="flex flex-row gap-2">
                <div class="w-14 shrink-0 relative">
                    <input id="emoji" type="text" class="input text-center cursor-pointer" maxlength="2"
                        v-model="form.emoji" readonly @click="showEmojiPicker = !showEmojiPicker" />
                    <div v-if="showEmojiPicker"
                        class="fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <emoji-picker @emoji-click="selectEmoji" />
                    </div>
                </div>
                <div class="flex-1">
                    <input id="title" type="text" class="input" v-model="form.title" />
                </div>
            </div>
        </div>

        <!-- 습관 내용 -->
        <div class="mb-4">
            <h3 class="title-xl">Habit Contents</h3>
            <div v-for="level in [1, 2, 3]" :key="level" class="flex flex-row gap-4 mb-3 items-start">
                <h3 class="title-normal w-[70px]">Level {{ level }}</h3>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 flex flex-col gap-2">
                            <div v-for="(input, i) in form.levels[level]" :key="i" class="relative">
                                <input :data-level="level" type="text" class="input pr-10"
                                    v-model="form.levels[level][i]" />
                                <button v-if="form.levels[level].length > 1 && i === form.levels[level].length - 1"
                                    type="button" tabindex="-1"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 btn-circle"
                                    @click="removeInput(level)">
                                    −
                                </button>
                            </div>
                        </div>
                        <button v-if="form.levels[level].length < 3" type="button" tabindex="-1"
                            class="btn-circle flex-shrink-0" @click="addInput(level)">
                            +
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    levels: {
        1: [''],
        2: [''],
        3: ['']
    },
    title: null,
    emoji: '🎬',
    isPublic: false,
})

function addInput(level) {
    if (form.levels[level].length < 3) {
        form.levels[level].push('')
    }
}
function removeInput(level) {
    if (form.levels[level].length > 1) {
        form.levels[level].pop()
    }
}

// 이모티콘 선택창 불러오기
const showEmojiPicker = ref(false)
function selectEmoji(event) {
    form.emoji = event.detail.unicode
    showEmojiPicker.value = false
}

const emit = defineEmits(['close'])
const save = () => {
    form.post(route('habit.store'), {
        onSuccess: (res) => {
            // success logic
        },
        onFinish: () => {
            emit('close')
            form.reset()
        }
    })
}
defineExpose({
    save,
    form,
})
</script>