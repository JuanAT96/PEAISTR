<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    evaluations: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteEvaluation = (evaluation_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("evaluations.destroy", evaluation_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("evaluations.index", { page }));
};

const groupedEvaluations = computed(() => {
    let filteredEvaluations = [...props.evaluations];

    if (search.value) {
        const searchValue = search.value.toLowerCase();
        filteredEvaluations = filteredEvaluations.filter((evaluation) => {
            const unit = evaluation.unit ? evaluation.unit.toLowerCase() : "";
            const learningOutcomes = evaluation.Learning_outcomes
                ? evaluation.Learning_outcomes.toLowerCase()
                : "";
            const type = evaluation.type ? evaluation.type.toLowerCase() : "";
            const criteria = evaluation.criteria
                ? evaluation.criteria.toLowerCase()
                : "";
            const activity = evaluation.activity
                ? evaluation.activity.toLowerCase()
                : "";
            const firstPartial = evaluation.first_partial
                ? evaluation.first_partial.toString()
                : "";
            const secondPartial = evaluation.second_partial
                ? evaluation.second_partial.toString()
                : "";

            return (
                unit.includes(searchValue) ||
                learningOutcomes.includes(searchValue) ||
                type.includes(searchValue) ||
                criteria.includes(searchValue) ||
                activity.includes(searchValue) ||
                firstPartial.includes(searchValue) ||
                secondPartial.includes(searchValue)
            );
        });
    }

    const groups = {};
    filteredEvaluations.forEach((evaluation) => {
        const subjectName =
            evaluation.subject?.subject_name || "Sin asignatura";
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(evaluation);
    });
    return groups;
});

const totalFirstPartial = (evaluations) => {
    return evaluations.reduce((total, evaluation) => {
        return total + (parseFloat(evaluation.first_partial) || 0);
    }, 0);
};

const totalSecondPartial = (evaluations) => {
    return evaluations.reduce((total, evaluation) => {
        return total + (parseFloat(evaluation.second_partial) || 0);
    }, 0);
};
</script>

<template>
    <AppLayout title="Evaluaciones">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Evaluaciones
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear evaluaciones'
                            )
                        "
                    >
                        <Link
                            :href="route('evaluations.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR EVALUACIÓN
                        </Link>
                    </div>

                    <div class="mt-4">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar en todas las columnas..."
                            class="mb-4 w-full border rounded px-3 py-2"
                        />

                        <div
                            v-for="(
                                evaluations, subjectName
                            ) in groupedEvaluations"
                            :key="subjectName"
                            class="mt-4"
                        >
                            <h2 class="text-lg font-semibold mb-2">
                                {{ subjectName }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full table-auto border-collapse"
                                >
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Unidad
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Resultados de Aprendizaje
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Tipo
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Criterios
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Actividad
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Primer Parcial
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Segundo Parcial
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700 text-right"
                                            >
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="evaluation in evaluations"
                                            :key="evaluation.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.unit
                                                        ? evaluation.cunit.unit
                                                        : "N/A"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.Learning_outcomes ||
                                                    "Sin resultados"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.type ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.criteria ||
                                                    "Sin criterios"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.activity ||
                                                    "Sin actividad"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.first_partial ||
                                                    "N/A"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    evaluation.second_partial ||
                                                    "N/A"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-right"
                                            >
                                                <div
                                                    class="flex justify-end space-x-2"
                                                >
                                                    <Link
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                        :href="
                                                            route(
                                                                'evaluations.edit',
                                                                evaluation.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar evaluaciones'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteEvaluation(
                                                                evaluation.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar evaluaciones'
                                                            )
                                                        "
                                                    >
                                                        Borrar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                                colspan="5"
                                            >
                                                Total
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalFirstPartial(
                                                        evaluations
                                                    )
                                                }}
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalSecondPartial(
                                                        evaluations
                                                    )
                                                }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <td
                                                colspan="8"
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Para la aprobación de una
                                                asignatura se requiere mínimo 7
                                                puntos y una asistencia del 70%,
                                                la nota final se obtiene
                                                promediando la nota de los dos
                                                parciales.
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <Link
                                v-if="pagination.current_page > 1"
                                @click="changePage(pagination.current_page - 1)"
                                class="py-2 px-4 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
                            >
                                Anterior
                            </Link>
                            <div v-else></div>
                            <Link
                                v-if="
                                    pagination.current_page <
                                    pagination.last_page
                                "
                                @click="changePage(pagination.current_page + 1)"
                                class="py-2 px-4 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
                            >
                                Siguiente
                            </Link>
                            <div v-else></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
