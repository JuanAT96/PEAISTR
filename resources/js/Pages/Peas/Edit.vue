<script>
export default {
    name: "PeaEdit",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import PeaForm from "@/Components/Peas/Form.vue";
const props = defineProps({
    pea: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    details: props.pea.details,
});
</script>
<template>
    <AppLayout title="Editar PEA">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar PEA
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <PeaForm
                            :updating="true"
                            :form="form"
                            @submit="form.put(route('peas.update', pea.id))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
