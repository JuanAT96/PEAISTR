<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CurricularUnitForm from "@/Components/CurricularUnits/Form.vue";

const props = defineProps({
    curricular_units: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    subject_id: props.curricular_units.subject_id,
    unit: props.curricular_units.unit,
    name: props.curricular_units.name,
    learning_outcomes: props.curricular_units.learning_outcomes,
});
</script>

<template>
    <AppLayout title="Editar Unidad Curricular">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Unidad Curricular
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CurricularUnitForm
                            :updating="true"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'curricularunits.update',
                                        curricular_units.id
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
