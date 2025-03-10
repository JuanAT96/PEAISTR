<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    reports: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const downloadReport = (reportId) => {
    window.location.href = route("reports.download", reportId);
};

const changePage = (page) => {
    Inertia.get(route("reports.index", { page }));
};
</script>

<template>
    <AppLayout title="Reportes">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes del Sistema
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <Link
                            :href="route('reports.subjects')"
                            class="block p-6 bg-indigo-50 hover:bg-indigo-100 rounded-lg"
                        >
                            <h3 class="text-lg font-semibold text-indigo-700">
                                Asignaturas por Carrera
                            </h3>
                            <p class="text-sm text-gray-600 mt-2">
                                Generar reporte de asignaturas filtrado por
                                carrera
                            </p>
                        </Link>

                        <Link
                            :href="route('reports.evaluations')"
                            class="block p-6 bg-indigo-50 hover:bg-indigo-100 rounded-lg"
                        >
                            <h3 class="text-lg font-semibold text-indigo-700">
                                Evaluaciones por Asignatura
                            </h3>
                            <p class="text-sm text-gray-600 mt-2">
                                Generar reporte de evaluaciones filtrado por
                                asignatura
                            </p>
                        </Link>

                        <Link
                            :href="route('reports.approvals')"
                            class="block p-6 bg-indigo-50 hover:bg-indigo-100 rounded-lg"
                        >
                            <h3 class="text-lg font-semibold text-indigo-700">
                                Reporte de Aprobaciones
                            </h3>
                            <p class="text-sm text-gray-600 mt-2">
                                Generar reporte de aprobaciones por asignatura o
                                docente
                            </p>
                        </Link>
                    </div>

                    <h2 class="text-lg font-semibold mb-4">
                        Reportes Generados
                    </h2>
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th
                                    class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                >
                                    Tipo de Reporte
                                </th>
                                <th
                                    class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                >
                                    Filtros Aplicados
                                </th>
                                <th
                                    class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                >
                                    Fecha de Generación
                                </th>
                                <th
                                    class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="report in reports"
                                :key="report.id"
                                class="border-b border-gray-200"
                            >
                                <td class="py-3 px-4 text-sm text-gray-800">
                                    {{ report.type }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-800">
                                    {{ report.filters }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-800">
                                    {{ report.created_at }}
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <button
                                        @click="downloadReport(report.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Descargar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

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
                                pagination.current_page < pagination.last_page
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
    </AppLayout>
</template>
