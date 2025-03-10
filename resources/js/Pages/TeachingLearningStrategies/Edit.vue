<script>
export default {
    name: "TeachingLearningStrategyEdit",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import TeachingLearningStrategyForm from "@/Components/TeachingLearningStrategies/Form.vue";
const props = defineProps({
    teachingLearningStrategy: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    subject_id: props.teachingLearningStrategy.subject_id,
    description: props.teachingLearningStrategy.description,
});
</script>
<template>
    <AppLayout title="Editar estrategia de enseñanza-aprendizaje">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estrategia de Enseñanza-Aprendizaje
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <TeachingLearningStrategyForm
                            :updating="true"
                            :form="form"
                            :subjects="props.subjects"
                            @submit="
                                form.put(
                                    route(
                                        'teachinglearningstrategies.update',
                                        teachingLearningStrategy.id
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
