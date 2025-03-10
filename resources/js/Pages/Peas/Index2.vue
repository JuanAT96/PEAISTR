<template>
    <AppLayout title="PEA">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Plan de Estudios por Asignatura (PEA)
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Botón para generar PEA -->
                        <button
                            @click="generatePea"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Generar PEA
                        </button>

                        <!-- 1. DATOS GENERALES -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            1. DATOS GENERALES
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Carrera:</label
                                >
                                <input
                                    v-model="form.career_id"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Asignatura:</label
                                >
                                <input
                                    v-model="form.subject_id"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Código de la Asignatura:</label
                                >
                                <input
                                    v-model="form.subject_code"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nivel:</label
                                >
                                <input
                                    v-model="form.level"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Período Académico:</label
                                >
                                <input
                                    v-model="form.academic_period_id"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Modalidad:</label
                                >
                                <input
                                    v-model="form.modality"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Docente:</label
                                >
                                <input
                                    v-model="form.teacher_id"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Créditos:</label
                                >
                                <input
                                    v-model="form.credits"
                                    type="number"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                        </div>

                        <!-- 2. PRERREQUISITOS Y CORREQUISITOS -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            2. PRERREQUISITOS Y CORREQUISITOS
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Prerrequisitos:</label
                                >
                                <input
                                    v-model="form.prerequisites"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Correquisitos:</label
                                >
                                <input
                                    v-model="form.corequisites"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                        </div>

                        <!-- 3. INFORMACIÓN DE LA ASIGNATURA -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            3. INFORMACIÓN DE LA ASIGNATURA
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Descripción:</label
                            >
                            <textarea
                                v-model="form.description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Objetivos:</label
                            >
                            <textarea
                                v-model="form.objectives"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Resultados de Aprendizaje:</label
                            >
                            <textarea
                                v-model="form.learning_outcomes"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 4. MAPA DE COMPETENCIAS -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            4. MAPA DE COMPETENCIAS
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Competencias Específicas:</label
                            >
                            <textarea
                                v-model="form.specific_competences"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Competencias Genéricas:</label
                            >
                            <textarea
                                v-model="form.generic_competences"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 5. ESTRATEGIAS METODOLOGÍAS DE ENSEÑANZA – APRENDIZAJE -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            5. ESTRATEGIAS METODOLOGÍAS DE ENSEÑANZA –
                            APRENDIZAJE
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Estrategias:</label
                            >
                            <textarea
                                v-model="form.teaching_strategies"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 6. CONTENIDOS DE ENSEÑANZA -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            6. CONTENIDOS DE ENSEÑANZA
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Contenidos:</label
                            >
                            <textarea
                                v-model="form.teaching_contents"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 7. SISTEMA DE EVALUACIÓN -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            7. SISTEMA DE EVALUACIÓN
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Criterios de Evaluación:</label
                            >
                            <textarea
                                v-model="form.evaluation_criteria"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Actividades de Evaluación:</label
                            >
                            <textarea
                                v-model="form.evaluation_activities"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 8. BIBLIOGRAFÍA -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            8. BIBLIOGRAFÍA
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Bibliografía:</label
                            >
                            <textarea
                                v-model="form.bibliography"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            ></textarea>
                        </div>

                        <!-- 9. REVISIÓN Y APROBACIÓN -->
                        <h2 class="text-xl font-semibold mt-6 mb-4">
                            9. REVISIÓN Y APROBACIÓN
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Docente:</label
                                >
                                <input
                                    v-model="form.teacher_approval"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Coordinación de Carrera:</label
                                >
                                <input
                                    v-model="form.coordinator_approval"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Vicerrectorado Académico:</label
                                >
                                <input
                                    v-model="form.vice_rector_approval"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                        </div>

                        <!-- Botón para guardar -->
                        <div class="mt-6">
                            <button
                                @click="submitForm"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Guardar PEA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    career_id: "",
    subject_id: "",
    subject_code: "",
    level: "",
    academic_period_id: "",
    modality: "",
    teacher_id: "",
    credits: "",
    prerequisites: "",
    corequisites: "",
    description: "",
    objectives: "",
    learning_outcomes: "",
    specific_competences: "",
    generic_competences: "",
    teaching_strategies: "",
    teaching_contents: "",
    evaluation_criteria: "",
    evaluation_activities: "",
    bibliography: "",
    teacher_approval: "",
    coordinator_approval: "",
    vice_rector_approval: "",
});

const submitForm = () => {
    form.post(route("pea.store"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
