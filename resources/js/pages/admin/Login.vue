<template>
    <Head title="Login" />
    <Card class="mx-auto my-20 flex w-[30vw] flex-col items-center justify-start p-8">
        <h1 class="mb-4 text-xl font-bold">Valomizer - Administration</h1>
        <div class="w-full">
            <form class="flex flex-col items-center justify-center" @submit.prevent="submit">
                <input
                    class="focus:ring-valorant mb-4 w-full rounded border p-2 focus:ring-2 focus:outline-none bg-accent dark:bg-card"
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    required
                />
                <input
                    class="focus:ring-valorant mb-4 w-full rounded border p-2 focus:ring-2 focus:outline-none bg-accent dark:bg-card"
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    required
                />
                <button class="bg-valorant hover:bg-valorant-accent text-foreground w-full rounded p-2 transition-colors" type="submit">Login</button>
            </form>
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue';
import AdministrationLayout from '@/layouts/AdministrationLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AdministrationLayout,
});

const form = useForm({
    email: '',
    password: '',
});

function submit() {
    form.post(route('admin.authenticate'));
}
</script>
