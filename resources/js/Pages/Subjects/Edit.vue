<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SubjectForm from "@/Components/Subjects/Form.vue";

const props = defineProps({
    subject: {
        type: Object,
        required: true,
    },
    academicperiods: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    subject_name: props.subject.subject_name,
    subject_code: props.subject.subject_code,
    curricular_org_unit: props.subject.curricular_org_unit,
    academic_period_id: props.subject.academic_period_id,
    credits: props.subject.credits,
    contact_teacher: props.subject.contact_teacher,
    practical_teaching: props.subject.practical_teaching,
    practical_self_employment: props.subject.practical_self_employment,
    self_employment: props.subject.self_employment,
    description: props.subject.description,
    objectives: props.subject.objectives,
    gral_learning_outcome: props.subject.gral_learning_outcome,
    description_integrative_project:
        props.subject.description_integrative_project,
});
</script>

<template>
    <AppLayout title="Editar Asignatura">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Asignatura
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <SubjectForm
                            :updating="true"
                            :academicperiods="props.academicperiods"
                            :form="form"
                            @submit="
                                form.put(route('subjects.update', subject.id))
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
