<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ReportForm from "@/Components/Reports/Form.vue";

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
});

const form = useForm({
    type: props.type,
    career_id: "",
    subject_id: "",
    teacher_id: "",
    filter_type: "",
    period: "",
    date_from: "",
    date_to: "",
});
</script>

<template>
    <AppLayout :title="'Generar Reporte'">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Generar Reporte
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ReportForm
                            :form="form"
                            :type="type"
                            @submit="form.post(route('reports.generate'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
