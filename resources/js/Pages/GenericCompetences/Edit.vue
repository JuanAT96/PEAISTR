<script>
export default {
    name: "GenericCompetenceEdit",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import GenericCompetenceForm from "@/Components/GenericCompetences/Form.vue";
const props = defineProps({
    genericCompetence: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    subject_id: props.genericCompetence.subject_id,
    soft_skills: props.genericCompetence.soft_skills,
});
</script>
<template>
    <AppLayout title="Editar competencia genérica">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Competencia Genérica
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <GenericCompetenceForm
                            :updating="true"
                            :form="form"
                            :subjects="props.subjects"
                            @submit="
                                form.put(
                                    route(
                                        'genericcompetences.update',
                                        genericCompetence.id
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
