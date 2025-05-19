<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- 탭 버튼 영역 -->
        <ul
            class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <!-- <li class="me-2">
                <button @click="activeTab = 'tab1'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab1' ? 'bg-gray-100 dark:hover:bg-gray-700' : ''
                ]">
                    🙇‍♀️
                </button>
            </li>
            <li class="me-2">
                <button @click="activeTab = 'tab2'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab2' ? 'bg-gray-100 dark:hover:bg-gray-700' : ''
                ]">
                    🏠
                </button>
            </li> -->
            <li class="me-2">
                <button @click="activeTab = 'tab3'" :class="[
                    'inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:hover:bg-gray-700',
                    activeTab === 'tab3' ? 'bg-gray-100 dark:hover:bg-gray-700' : ''
                ]">
                    +
                </button>
            </li>
        </ul>

        <!-- 콘텐츠 영역 -->
        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
            <button @click="showModal = true" class="btn-primary">New Habit</button>
            <Modal :show="showModal" title="Add Habit" @close="showModal = false">
                <!-- content -->
                <template #default>
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
                                                <button
                                                    v-if="form.levels[level].length > 1 && i === form.levels[level].length - 1"
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
                <!-- footer 영역 -->
                <template #footer>
                    <div class="flex justify-between">
                        <ToggleSwitch v-if="user?.is_admin == false" v-model="isPublic" label="Allow search" />
                        <button @click.prevent="save" class="btn-primary">Save</button>
                    </div>
                </template>
            </Modal>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Modal from '@/Components/UI/Modal.vue'
import ToggleSwitch from '@/Components/UI/ToggleSwitch.vue'

const activeTab = ref('tab1')
const showModal = ref(false)

// 모달 안 작동
const isPublic = ref(false)
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

// admin 토글 숨기기
const page = usePage()
const user = computed(() => page.props.user)

// 이모티콘 선택창 불러오기
const showEmojiPicker = ref(false)
function selectEmoji(event) {
    form.emoji = event.detail.unicode
    showEmojiPicker.value = false
}

// content level 저장
const save = () => {
    form.post(route('habit.store'), {
        onSuccess: (res) => {

        },
        onFinish: () => {
            showModal.value = false
            form.reset()
        }
    })
}

</script>