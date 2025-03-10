<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ApprovalForm from "@/Components/Approvals/Form.vue";
const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
    teachers: {
        type: Array,
        required: true,
    },
    coordinators: {
        type: Array,
        required: true,
    },
    vice_rectors: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: "",
    teacher_id: "",
    coordinator_id: "",
    vice_rector_id: "",
});
</script>

<template>
    <AppLayout title="Crear Aprobación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Aprobación
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ApprovalForm
                            :subjects="props.subjects"
                            :teachers="props.teachers"
                            :coordinators="props.coordinators"
                            :vice_rectors="props.vice_rectors"
                            :form="form"
                            @submit="form.post(route('approvals.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
