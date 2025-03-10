<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, computed } from "vue";

const props = defineProps({
    subjectteachers: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");
const sort = ref({
    column: null,
    order: "asc",
});

const deleteTeacherSubject = (id) => {
    if (confirm("¿Estás seguro de eliminar esta asignación?")) {
        Inertia.delete(route("subjectteachers.destroy", id));
    }
};

const changePage = (page) => {
    Inertia.get(route("subjectteachers.index", { page }));
};

const setSort = (column) => {
    if (sort.value.column === column) {
        sort.value.order = sort.value.order === "asc" ? "desc" : "asc";
    } else {
        sort.value.column = column;
        sort.value.order = "asc";
    }
};

const sortedSubjectTeachers = computed(() => {
    let filtered = [...props.subjectteachers];

    if (search.value) {
        filtered = filtered.filter((assignment) =>
            Object.values(assignment).some((value) => {
                if (value && typeof value === "object") {
                    return Object.values(value).some((innerValue) =>
                        String(innerValue)
                            .toLowerCase()
                            .includes(search.value.toLowerCase())
                    );
                }
                return String(value)
                    .toLowerCase()
                    .includes(search.value.toLowerCase());
            })
        );
    }

    if (sort.value.column) {
        filtered.sort((a, b) => {
            const valA = String(a[sort.value.column]).toLowerCase();
            const valB = String(b[sort.value.column]).toLowerCase();

            if (valA < valB) {
                return sort.value.order === "asc" ? -1 : 1;
            }
            if (valA > valB) {
                return sort.value.order === "asc" ? 1 : -1;
            }
            return 0;
        });
    }

    const groups = {};
    filtered.forEach((assignment) => {
        const teacherName = assignment.user?.name || "Sin docente";
        if (!groups[teacherName]) {
            groups[teacherName] = [];
        }
        groups[teacherName].push(assignment);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Asignaciones de Docentes">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignaciones de Docentes a Asignaturas
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear asignaciones'
                            )
                        "
                    >
                        <Link
                            :href="route('subjectteachers.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            NUEVA ASIGNACIÓN
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
                                assignments, teacherName
                            ) in sortedSubjectTeachers"
                            :key="teacherName"
                            class="mt-4"
                        >
                            <h2 class="text-lg font-semibold mb-2">
                                {{ teacherName }}
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
                                                <button
                                                    @click="
                                                        setSort(
                                                            'subject.subject_name'
                                                        )
                                                    "
                                                >
                                                    Asignatura
                                                    {{
                                                        sort.column ===
                                                        "subject.subject_name"
                                                            ? sort.order ===
                                                              "asc"
                                                                ? "▲"
                                                                : "▼"
                                                            : ""
                                                    }}
                                                </button>
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
                                            v-for="assignment in assignments"
                                            :key="assignment.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    assignment.subject
                                                        ?.subject_name ||
                                                    "Sin asignatura"
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
                                                                'subjectteachers.edit',
                                                                assignment.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar asignaciones'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteTeacherSubject(
                                                                assignment.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar asignaciones'
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
