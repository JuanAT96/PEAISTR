<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import UserForm from "@/Components/Users/FormAR.vue";

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: props.users.name,
    role: "",
});
</script>

<template>
    <AppLayout title="Editar usuario">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignar Rol al Usuario
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <UserForm
                            :updating="true"
                            :form="form"
                            :roles="props.roles"
                            @submit="
                                form.put(route('users.updateRole', users.id))
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
