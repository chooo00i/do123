<template>
    <form @submit.prevent="login">
        <div class="md:w-1/2 mx-auto">
            <div>
                <label for="email" class="label">E-mail</label>
                <input id="email" type="text" class="input" v-model="form.email" />
                <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
            </div>
            <div class="mt-4">
                <label for="password" class="label">비밀번호</label>
                <input id="password" type="password" class="input" v-model="form.password" />
                <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
            </div>
            <div class="mt-4">
                <button class="btn-primary w-full" type="submit">로그인</button>
                <div class="mt-2 text-center">
                    <Link :href="route('user-account.create')" class="text-sm text-gray-500">계정이 없으신가요? 클릭하세요</Link>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    email: null,
    password: null,
})
const login = () => form.post(route('login.store'))
</script>