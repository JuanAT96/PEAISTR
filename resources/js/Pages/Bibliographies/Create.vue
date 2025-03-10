<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import BibliographyForm from "@/Components/Bibliographies/Form.vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    type: "",
    author: "",
    year: "",
    title: "",
    editorial: "",
    format: "",
    code: "",
    link: "",
    subject_id: "",
    uplift: "",
});
</script>

<template>
    <AppLayout title="Crear Bibliografía">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Bibliografía
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BibliographyForm
                            :subjects="props.subjects"
                            :form="form"
                            @submit="form.post(route('bibliographies.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
