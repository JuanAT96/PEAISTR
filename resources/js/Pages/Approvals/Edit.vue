<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ApprovalForm from "@/Components/Approvals/Form.vue";

const props = defineProps({
    approval: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
    teachers: {
        type: Object,
        required: true,
    },
    coordinators: {
        type: Object,
        required: true,
    },
    vice_rectors: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    subject_id: props.approval.subject_id,
    teacher_id: props.approval.teacher_id,
    coordinator_id: props.approval.coordinator_id,
    vice_rector_id: props.approval.vice_rector_id,
});
</script>

<template>
    <AppLayout title="Editar Aprobación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Aprobación
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ApprovalForm
                            :updating="true"
                            :form="form"
                            :subjects="props.subjects"
                            :teachers="props.teachers"
                            :coordinators="props.coordinators"
                            :vice_rectors="props.vice_rectors"
                            @submit="
                                form.put(route('approvals.update', approval.id))
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
