<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ReportForm from "@/Components/Reports/Form.vue";

const props = defineProps({
    report: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    type: props.report.type,
    career_id: props.report.career_id,
    subject_id: props.report.subject_id,
    teacher_id: props.report.teacher_id,
    filter_type: props.report.filter_type,
    period: props.report.period,
    date_from: props.report.date_from,
    date_to: props.report.date_to,
});
</script>

<template>
    <AppLayout title="Editar Reporte">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Reporte
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ReportForm
                            :updating="true"
                            :form="form"
                            :type="report.type"
                            @submit="
                                form.put(route('reports.update', report.id))
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
