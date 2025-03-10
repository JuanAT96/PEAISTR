<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import BibliographyForm from "@/Components/Bibliographies/Form.vue";

const props = defineProps({
    bibliography: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    type: props.bibliography.type,
    author: props.bibliography.author,
    year: props.bibliography.year,
    title: props.bibliography.title,
    editorial: props.bibliography.editorial,
    format: props.bibliography.format,
    code: props.bibliography.code,
    link: props.bibliography.link,
    subject_id: props.bibliography.subject_id,
    uplift: props.bibliography.uplift,
});
</script>

<template>
    <AppLayout title="Editar Bibliografía">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Bibliografía
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BibliographyForm
                            :updating="true"
                            :form="form"
                            :subjects="props.subjects"
                            @submit="
                                form.put(
                                    route(
                                        'bibliographies.update',
                                        bibliography.id
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
