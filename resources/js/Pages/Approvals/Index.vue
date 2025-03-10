<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    approvals: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteApproval = (approval_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("approvals.destroy", approval_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("approvals.index", { page }));
};

const groupedApprovals = computed(() => {
    let filteredApprovals = [...props.approvals];

    if (search.value) {
        const searchValue = search.value.toLowerCase();
        filteredApprovals = filteredApprovals.filter((approval) => {
            const teacherName = approval.teacher?.name
                ? approval.teacher.name.toLowerCase()
                : "";
            const coordinatorName = approval.coordinator?.name
                ? approval.coordinator.name.toLowerCase()
                : "";
            const viceRectorName = approval.vice_rector?.name
                ? approval.vice_rector.name.toLowerCase()
                : "";

            return (
                teacherName.includes(searchValue) ||
                coordinatorName.includes(searchValue) ||
                viceRectorName.includes(searchValue)
            );
        });
    }

    const groups = {};
    filteredApprovals.forEach((approval) => {
        const subjectName = approval.subject?.subject_name || "Sin asignatura";
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(approval);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Aprobaciones">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Aprobaciones
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear aprobaciones'
                            )
                        "
                    >
                        <Link
                            :href="route('approvals.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR APROBACIÓN
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
                            v-for="(approvals, subjectName) in groupedApprovals"
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
                                                Profesor
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Coordinador
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Vicerrector
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
                                            v-for="approval in approvals"
                                            :key="approval.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    approval.teacher?.name ||
                                                    "Sin profesor"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    approval.coordinator
                                                        ?.name ||
                                                    "Sin coordinador"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    approval.vice_rector
                                                        ?.name ||
                                                    "Sin vicerrector"
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
                                                                'approvals.edit',
                                                                approval.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar aprobaciones'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteApproval(
                                                                approval.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar aprobaciones'
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
