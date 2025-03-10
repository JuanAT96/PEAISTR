<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    teachingLearningStrategies: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteTeachingLearningStrategy = (teachingLearningStrategyId) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(
            route(
                "teachinglearningstrategies.destroy",
                teachingLearningStrategyId
            )
        );
    }
};

const changePage = (page) => {
    Inertia.get(route("teachinglearningstrategies.index", { page }));
};

const groupedStrategies = computed(() => {
    let filteredStrategies = [...props.teachingLearningStrategies];

    if (search.value) {
        filteredStrategies = filteredStrategies.filter((strategy) => {
            const description = strategy.description || "";
            const subjectName = strategy.subject?.subject_name || "";

            return (
                description
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                subjectName.toLowerCase().includes(search.value.toLowerCase())
            );
        });
    }

    const groups = {};
    filteredStrategies.forEach((strategy) => {
        const subjectName = strategy.subject?.subject_name || "Sin asignatura";
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(strategy);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Estrategias de Enseñanza-Aprendizaje">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Estrategias de Enseñanza-Aprendizaje
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear estrategias de enseñanza-aprendizaje'
                            )
                        "
                    >
                        <Link
                            :href="route('teachinglearningstrategies.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR ESTRATEGIA
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
                                strategies, subjectName
                            ) in groupedStrategies"
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
                                                Descripción
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
                                            v-for="strategy in strategies"
                                            :key="strategy.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    strategy.description ||
                                                    "Sin datos"
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
                                                                'teachinglearningstrategies.edit',
                                                                strategy.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar estrategias de enseñanza-aprendizaje'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteTeachingLearningStrategy(
                                                                strategy.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar estrategias de enseñanza-aprendizaje'
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
