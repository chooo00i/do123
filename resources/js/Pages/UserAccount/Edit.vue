<template>
    <form @submit.prevent="update">
        <div class="md:w-1/2 mx-auto">
            <div>
                <label for="name" class="label">이름</label>
                <input id="name" type="text" class="input" v-model="form.name" />
                <div v-if="form.errors.name" class="input-error">{{ form.errors.name }}</div>
            </div>
            <div class="mt-4">
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
                <label for="password_confirmation" class="label">비밀번호 확인</label>
                <input id="password_confirmation" type="password" class="input" v-model="form.password_confirmation" />
                <div v-if="form.errors.password_confirmation" class="input-error">{{ form.errors.password_confirmation }}</div>
            </div>
            <div class="mt-4">
                <button class="btn-primary w-full" type="submit">수정하기</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm, Link } from '@inertiajs/vue3'

    const props = defineProps({
        user: Object,
    })
    
    const form = useForm({
        name: props.user.name,
        email: props.user.email,
        password: null,
        password_confirmation: null,
        id: props.user.id,
    })
    const update = () => form.put(route('user-account.update'))
</script>