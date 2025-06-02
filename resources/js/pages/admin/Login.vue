<template>
    <form class="flex flex-col items-center justify-center" @submit.prevent="submit">
        <input v-model="form.email" type="email" placeholder="Email" required />
        <input v-model="form.password" type="password" placeholder="Password" required />
        <button type="submit">Login</button>
    </form>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

function submit() {
    form.post(route('admin.authenticate'));
}
</script>
