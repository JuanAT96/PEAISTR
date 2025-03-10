<script>
export default {
    name: "SubjectRequisiteCreate",
};
</script>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SubjectRequisiteForm from "@/Components/SubjectRequisites/Form.vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: null,
    prerequisites: null,
    corequisites: null,
});
</script>

<template>
    <AppLayout title="Crear requisito de asignatura">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Requisito de Asignatura
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <SubjectRequisiteForm
                                :subjects="props.subjects"
                                :form="form"
                                @submit="
                                    form.post(route('subjectrequisites.store'))
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
