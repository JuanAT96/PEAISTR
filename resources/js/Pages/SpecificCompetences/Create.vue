<script>
export default {
    name: "SpecificCompetenceCreate",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import SpecificCompetenceForm from "@/Components/SpecificCompetences/Form.vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: "",
    graduate_profile: "",
    contribution_level: "",
    Learning_outcomes: "",
});
</script>
<template>
    <AppLayout title="Crear competencia específica">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Competencia Específica
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <SpecificCompetenceForm
                                :subjects="props.subjects"
                                :form="form"
                                @submit="
                                    form.post(
                                        route('specificcompetences.store')
                                    )
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
