<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CareerForm from "@/Components/Careers/Form.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    career: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.career.name,
    research_lines: props.career.research_lines,
    coordinator: props.career.coordinator,
    modality: props.career.modality,
    duration: props.career.duration,
    logo_path: props.career.logo_path,
});

// Edit.vue

const handleUpdateLogoPath = (newValue) => {
    form.logo_path = newValue;
};
</script>

<template>
    <AppLayout title="Editar carrera">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Carrera
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CareerForm
                            :updating="true"
                            :form="form"
                            @update:logo_path="handleUpdateLogoPath"
                            @submit="
                                form.post(
                                    route('careers.update', props.career.id)
                                )
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
