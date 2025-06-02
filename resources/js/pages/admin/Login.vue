<template>
    <Head title="Login" />
    <Card class="w-[30vw] mx-auto my-20 p-8 flex flex-col items-center justify-start">
        <h1 class="mb-4 text-xl font-bold">Valomizer - Administration</h1>
        <div class="w-full">
            <form class="flex flex-col items-center justify-center" @submit.prevent="submit">
                <input 
                    class="mb-4 w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-valorant"
                    v-model="form.email" 
                    type="email" 
                    placeholder="Email" 
                    required 
                />
                <input 
                    class="mb-4 w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-valorant"
                    v-model="form.password" 
                    type="password" 
                    placeholder="Password" 
                    required 
                    />
                <button 
                    class="w-full bg-valorant text-white p-2 rounded hover:bg-valorant-accent transition-colors"    
                    type="submit"
                >Login</button>
            </form>
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';


const form = useForm({
    email: '',
    password: '',
});

function submit() {
    form.post(route('admin.authenticate'));
}
</script>
