<script>
export default {
    name: "PeaCreate",
};
</script>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import PeaForm from "@/Components/Peas/Form.vue";
const props = defineProps({
    careers: {
        type: Array,
        required: true,
    },

    subjectst: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    subject_id: null,
    details: "",
});
</script>
<template>
    <AppLayout title="Crear PEA">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear PEA
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <PeaForm
                                :careers="props.careers"
                                :subjectst="props.subjectst"
                                :form="form"
                                @submit="
                                    form.post(route('peas.store'), {
                                        onSuccess: () => {
                                            // Éxito
                                        },
                                        onError: (errors) => {
                                            // Manejar errores
                                            console.error('yo aqui: ', errors);
                                            alert('error');
                                        },
                                    })
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
