<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CareerSubjectForm from "@/Components/CareerSubjects/Form.vue";

const props = defineProps({
    career_subjects: {
        type: Object,
        required: true,
    },
    careers: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    career_id: props.career_subjects.career_id,
    subject_id: props.career_subjects.subject_id,
    level: props.career_subjects.level,
});
</script>

<template>
    <AppLayout title="Editar Asignación de Asignatura a Carrera">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Asignación de Asignatura a Carrera
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CareerSubjectForm
                            :updating="true"
                            :careers="props.careers"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'careersubjects.update',
                                        props.career_subjects.id
                                    )
                                )
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
