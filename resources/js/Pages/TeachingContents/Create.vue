<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import TeachingContentForm from "@/Components/TeachingContents/Form.vue";

const props = defineProps({
    curricular_units: {
        type: Array,
        required: true,
    },
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    curricular_units_id: "",
    schedule: "",
    content: "",
    contact_teacher: "",
    contact_teacher_duration: 0,
    practical_teaching: "",
    practical_teaching_duration: 0,
    practical_self_employment: "",
    practical_self_employment_duration: 0,
    self_employment: "",
    self_employment_duration: 0,
});
</script>

<template>
    <AppLayout title="Crear Contenido de Enseñanza">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Contenido de Enseñanza
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <TeachingContentForm
                            :curricularunits="props.curricular_units"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="form.post(route('teachingcontents.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
