<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SubjectForm from "@/Components/Subjects/Form.vue";

const props = defineProps({
    academicperiods: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_name: "",
    subject_code: "",
    curricular_org_unit: "",
    academic_period_id: "",
    credits: "",
    contact_teacher: "",
    practical_teaching: "",
    practical_self_employment: "",
    self_employment: "",
    description: "",
    objectives: "",
    gral_learning_outcome: "",
    description_integrative_project: "",
});
</script>

<template>
    <AppLayout title="Crear Asignatura">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Asignatura
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <SubjectForm
                            :academicperiods="props.academicperiods"
                            :form="form"
                            @submit="form.post(route('subjects.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
