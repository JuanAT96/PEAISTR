<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import TeacherSubjectForm from "@/Components/SubjectTeachers/Form.vue";

const props = defineProps({
    teachers: {
        type: Array,
        required: true,
    },
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: "",
    user_id: "",
});
</script>

<template>
    <AppLayout title="Asignar Docente">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignar Docente a Asignatura
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <TeacherSubjectForm
                            :teachers="props.teachers"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="form.post(route('subjectteachers.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
