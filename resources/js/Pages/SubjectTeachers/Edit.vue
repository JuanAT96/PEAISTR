<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import TeacherSubjectForm from "@/Components/SubjectTeachers/Form.vue";

const props = defineProps({
    subjectteacher: {
        type: Object,
        required: true,
    },
    teachers: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    subject_id: props.subjectteacher.subject_id,
    user_id: props.subjectteacher.user_id,
});
</script>

<template>
    <AppLayout title="Editar Asignación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Asignación de Docente
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <TeacherSubjectForm
                            :updating="true"
                            :teachers="props.teachers"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'subjectteachers.update',
                                        subjectteacher.id
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
