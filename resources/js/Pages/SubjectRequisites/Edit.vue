<script>
export default {
    name: "SubjectRequisiteEdit",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SubjectRequisiteForm from "@/Components/SubjectRequisites/FormEdit.vue";

const props = defineProps({
    subjectRequisite: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    subject_id: props.subjectRequisite.subject_id,
    prerequisites: props.subjectRequisite.prerequisites,
    corequisites: props.subjectRequisite.corequisites,
});
</script>

<template>
    <AppLayout title="Editar requisito de asignatura">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Requisito de Asignatura
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <SubjectRequisiteForm
                            :updating="true"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'subjectrequisites.update',
                                        props.subjectRequisite.id
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
