<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import EvaluationForm from "@/Components/Evaluations/Form.vue";

const props = defineProps({
    evaluation: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
    curricularunits: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    type: props.evaluation.type,
    criteria: props.evaluation.criteria,
    activity: props.evaluation.activity,
    first_partial: props.evaluation.first_partial,
    second_partial: props.evaluation.second_partial,
    subject_id: props.evaluation.subject_id,
    unit: props.evaluation.unit,
    Learning_outcomes: props.evaluation.Learning_outcomes,
});
</script>

<template>
    <AppLayout title="Editar Evaluación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Evaluación
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <EvaluationForm
                            :updating="true"
                            :form="form"
                            :subjects="props.subjects"
                            :curricularunits="props.curricularunits"
                            @submit="
                                form.put(
                                    route('evaluations.update', evaluation.id)
                                )
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
