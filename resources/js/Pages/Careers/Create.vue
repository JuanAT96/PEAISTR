<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CareerForm from "@/Components/Careers/Form.vue";

const form = useForm({
    name: "",
    research_lines: "",
    coordinator: "",
    modality: "",
    duration: "",
    logo_path: null,
});

const handleUpdateLogoPath = (newValue) => {
    form.logo_path = newValue; // Actualizar el valor reactivamente
};
</script>

<template>
    <AppLayout title="Crear carrera">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Carrera
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <CareerForm
                                :form="form"
                                @update:logo_path="handleUpdateLogoPath"
                                @submit="form.post(route('careers.store'))"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
