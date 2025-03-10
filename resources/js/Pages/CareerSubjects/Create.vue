<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CareerSubjectForm from "@/Components/CareerSubjects/Form.vue";

const props = defineProps({
    careers: {
        type: Array,
        required: true,
    },
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    career_id: "",
    subject_id: "",
    level: "",
});
</script>

<template>
    <AppLayout title="Asignar Asignatura a Carrera">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignar Asignatura a Carrera
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CareerSubjectForm
                            :careers="props.careers"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="form.post(route('careersubjects.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
