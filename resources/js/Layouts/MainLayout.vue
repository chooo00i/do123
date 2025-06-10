<template>
    <header>
        <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <Link :href="route('home')"
                        class="self-center text-2xl font-extrabold whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">
                    Do123</Link>
                </a>
                <button @click="isNavOpen = !isNavOpen" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-default" :aria-expanded="isNavOpen">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                <div :class="{ 'hidden': !isNavOpen }" class="w-full md:block md:w-auto" id="navbar-default">
                    <ul
                        class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li v-for="(menu, index) in menus" :key="index">
                            <Link :href="menu.href" :method="menu.method" :as="menu.as"
                                class="block py-2 px-3 text-extrabold text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-sky-700 md:p-0 dark:text-white md:dark:hover:text-sky-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            {{ menu.label }}
                            </Link>
                        </li>
                    </ul>
                </div>
                <!-- <button data-collapse-toggle="navbar-default" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul
                        class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li v-for="(menu, index) in menus" :key="index">
                            <Link :href="menu.href" :method="menu.method" :as="menu.as"
                                class="block py-2 px-3 text-extrabold text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-sky-700 md:p-0 dark:text-white md:dark:hover:text-sky-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            {{ menu.label }}
                            </Link>
                        </li>
                    </ul>
                </div> -->
            </div>
        </nav>
    </header>
    <main class="container mx-auto mt-2 sm:mt-6 p-4 sm:p-8 w-full">
        <Alert v-if="flashSuccess" type="success" :message="flashSuccess" :key="flashSuccess + String(Date.now())" />
        <Alert v-if="flashError" type="error" :message="flashError" :key="flashSuccess + String(Date.now())" />
        <slot>Default</slot>
    </main>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Alert from '@/Components/UI/Alert.vue'

const isNavOpen = ref(false)
const page = usePage()
const flashSuccess = computed(() =>
    page.props.flash.success
)
const flashError = computed(() =>
    page.props.flash.error
)
const user = computed(() => page.props.user)

const menus = computed(() => {
    if (!user.value) {
        return [
            // { label: 'Overview' },
            // { label: 'My Page', href: route('user-account.edit') },
            { label: 'Login', href: route('login') },
        ]
    }

    return [
        // { label: 'Overview', href: route('statistics.index')},
        { label: 'My Page', href: route('user-account.edit', user.value.id) },
        { label: 'Logout', href: route('logout'), method: 'delete', as: 'button' },
    ]
})
</script>