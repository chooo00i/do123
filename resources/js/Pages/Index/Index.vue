<template>
    <div>
        <Link href="/hello">Show Page</Link>
    </div>
    <div>The message is {{ message }}</div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3'
    // import MainLayout from '../../Layouts/MainLayout.vue'

    defineProps({
        message: String
    })
</script>