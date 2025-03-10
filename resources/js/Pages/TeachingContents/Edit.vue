<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import TeachingContentForm from "@/Components/TeachingContents/Form.vue";

const props = defineProps({
    teaching_contents: {
        type: Object,
        required: true,
    },
    curricular_units: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    curricular_units_id: props.teaching_contents.curricular_units_id,
    schedule: props.teaching_contents.schedule,
    content: props.teaching_contents.content,
    contact_teacher: props.teaching_contents.contact_teacher,
    contact_teacher_duration: props.teaching_contents.contact_teacher_duration,
    practical_teaching: props.teaching_contents.practical_teaching,
    practical_teaching_duration:
        props.teaching_contents.practical_teaching_duration,
    practical_self_employment:
        props.teaching_contents.practical_self_employment,
    practical_self_employment_duration:
        props.teaching_contents.practical_self_employment_duration,
    self_employment: props.teaching_contents.self_employment,
    self_employment_duration: props.teaching_contents.self_employment_duration,
});
</script>

<template>
    <AppLayout title="Editar Contenido de Enseñanza">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Contenido de Enseñanza
            </h1>
            <!-- <pre>{{ JSON.stringify(subjects, null, 2) }}</pre>-->
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <TeachingContentForm
                            :updating="true"
                            :curricularunits="props.curricular_units"
                            :subjects="props.subjects"
                            :form="form"
                            @submit="
                                form.put(
                                    route(
                                        'teachingcontents.update',
                                        teaching_contents.id
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
