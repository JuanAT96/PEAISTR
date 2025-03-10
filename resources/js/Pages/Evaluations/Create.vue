<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import EvaluationForm from "@/Components/Evaluations/Form.vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
    curricularunits: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    type: "",
    criteria: "",
    activity: "",
    first_partial: null,
    second_partial: null,
    subject_id: "",
    unit: "",
    Learning_outcomes: "",
});
</script>

<template>
    <AppLayout title="Crear Evaluación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Evaluación
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <EvaluationForm
                            :subjects="props.subjects"
                            :curricularunits="props.curricularunits"
                            :form="form"
                            @submit="form.post(route('evaluations.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
