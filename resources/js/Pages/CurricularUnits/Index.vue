<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    curricular_units: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteCurricularUnit = (curricular_unit_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("curricularunits.destroy", curricular_unit_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("curricularunits.index", { page }));
};

const groupedCurricularUnits = computed(() => {
    let filteredUnits = [...props.curricular_units];

    if (search.value) {
        filteredUnits = filteredUnits.filter((unit) => {
            const unitNumber = unit.unit || "";
            const unitName = unit.name || "";
            const learningOutcomes = unit.learning_outcomes || "";
            const subjectName = unit.subject?.subject_name || "";

            return (
                unitNumber.toLowerCase().includes(search.value.toLowerCase()) ||
                unitName.toLowerCase().includes(search.value.toLowerCase()) ||
                learningOutcomes
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                subjectName.toLowerCase().includes(search.value.toLowerCase())
            );
        });
    }

    const groups = {};
    filteredUnits.forEach((unit) => {
        const subjectName = unit.subject?.subject_name || "Sin asignatura";
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(unit);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Unidades Curriculares">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Unidades Curriculares
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear unidades curriculares'
                            )
                        "
                    >
                        <Link
                            :href="route('curricularunits.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR UNIDAD CURRICULAR
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
                                units, subjectName
                            ) in groupedCurricularUnits"
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
                                                Nombre de Unidad
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Resultado de Aprendizaje
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
                                            v-for="unit in units"
                                            :key="unit.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{ unit.unit || "Sin datos" }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{ unit.name || "Sin datos" }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    unit.learning_outcomes ||
                                                    "Sin descripción"
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
                                                                'curricularunits.edit',
                                                                unit.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar unidades curriculares'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteCurricularUnit(
                                                                unit.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar unidades curriculares'
                                                            )
                                                        "
                                                    >
                                                        Borrar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
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
