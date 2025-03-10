<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import UserForm from "@/Components/Users/Form.vue";
const props = defineProps({
    roles: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
});
</script>

<template>
    <AppLayout title="Crear usuario">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Usuario
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <UserForm
                            :form="form"
                            :roles="props.roles"
                            @submit="form.post(route('users.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
