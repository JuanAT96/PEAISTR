<script>
export default {
    name: "AcademicPeriodCreate",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import AcademicPeriodForm from "@/Components/AcademicPeriods/Form.vue";
const form = useForm({
    name: "",
    estado: "",
});
</script>
<template>
    <AppLayout title="Crear periodo académico">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Periodo Académico
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <AcademicPeriodForm
                                :form="form"
                                @submit="
                                    form.post(route('academicperiods.store'))
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
