<script>
export default {
    name: "SpecificCompetenceEdit",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SpecificCompetenceForm from "@/Components/SpecificCompetences/Form.vue";
const props = defineProps({
    specificcompetences: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    subject_id: props.specificcompetences.subject_id,
    graduate_profile: props.specificcompetences.graduate_profile,
    contribution_level: props.specificcompetences.contribution_level,
    Learning_outcomes: props.specificcompetences.Learning_outcomes,
});
</script>
<template>
    <AppLayout title="Editar competencia específica">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Competencia Específica
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <SpecificCompetenceForm
                            :updating="true"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'specificcompetences.update',
                                        specificcompetences.id
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
