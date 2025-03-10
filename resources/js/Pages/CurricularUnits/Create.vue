<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CurricularUnitForm from "@/Components/CurricularUnits/Form.vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: "",
    unit: "",
    name: "",
    learning_outcomes: "",
});
</script>

<template>
    <AppLayout title="Crear Unidad Curricular">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Unidad Curricular
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CurricularUnitForm
                            :subjects="props.subjects"
                            :form="form"
                            @submit="form.post(route('curricularunits.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
