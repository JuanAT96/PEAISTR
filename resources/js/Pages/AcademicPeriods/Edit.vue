<script>
export default {
    name: "AcademicPeriodEdit",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import AcademicPeriodForm from "@/Components/AcademicPeriods/Form.vue";
const props = defineProps({
    academicPeriod: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    name: props.academicPeriod.name,
    estado: props.academicPeriod.estado,
});
</script>
<template>
    <AppLayout title="Editar periodo académico">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Periodo Académico
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <AcademicPeriodForm
                            :updating="true"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'academicperiods.update',
                                        academicPeriod.id
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
