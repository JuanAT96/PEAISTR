<script setup>
import { computed, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    careers: {
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

const getAssetUrl = (path) => {
    return window.asset(path);
};

const deleteCareer = (id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("careers.destroy", id));
    }
};

const changePage = (page) => {
    Inertia.get(route("careers.index", { page }));
};

const setSort = (column) => {
    if (sort.value.column === column) {
        sort.value.order = sort.value.order === "asc" ? "desc" : "asc";
    } else {
        sort.value.column = column;
        sort.value.order = "asc";
    }
};

const sortedCareers = computed(() => {
    let filtered = [...props.careers];

    if (search.value) {
        filtered = filtered.filter((career) =>
            Object.values(career).some((value) =>
                String(value).toLowerCase().includes(search.value.toLowerCase())
            )
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

    return filtered;
});
</script>
<template>
    <AppLayout title="Carreras">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Carreras
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear carreras'
                            )
                        "
                    >
                        <Link
                            :href="route('careers.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR CARRERA
                        </Link>
                    </div>

                    <div class="mt-4">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar en todas las columnas..."
                            class="mb-4 w-full border rounded px-3 py-2"
                        />

                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        logo
                                    </th>
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        <button @click="setSort('name')">
                                            Carrera
                                            {{
                                                sort.column === "name"
                                                    ? sort.order === "asc"
                                                        ? "▲"
                                                        : "▼"
                                                    : ""
                                            }}
                                        </button>
                                    </th>
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        <button
                                            @click="setSort('research_lines')"
                                        >
                                            Líneas de Investigación
                                            {{
                                                sort.column === "research_lines"
                                                    ? sort.order === "asc"
                                                        ? "▲"
                                                        : "▼"
                                                    : ""
                                            }}
                                        </button>
                                    </th>
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        <button @click="setSort('coordinator')">
                                            Coordinador
                                            {{
                                                sort.column === "coordinator"
                                                    ? sort.order === "asc"
                                                        ? "▲"
                                                        : "▼"
                                                    : ""
                                            }}
                                        </button>
                                    </th>
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        <button @click="setSort('modality')">
                                            Modalidad
                                            {{
                                                sort.column === "modality"
                                                    ? sort.order === "asc"
                                                        ? "▲"
                                                        : "▼"
                                                    : ""
                                            }}
                                        </button>
                                    </th>
                                    <th
                                        class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        <button @click="setSort('duration')">
                                            Duración (Semestres)
                                            {{
                                                sort.column === "duration"
                                                    ? sort.order === "asc"
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
                                    v-for="career in sortedCareers"
                                    :key="career.id"
                                    class="border-b border-gray-200"
                                >
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <img
                                            v-if="career.logo_path"
                                            :src="
                                                getAssetUrl(
                                                    '' + career.logo_path
                                                )
                                            "
                                            alt="Logo de la Carrera"
                                            class="w-16 h-16 object-contain rounded"
                                        />
                                        <span v-else>Sin Logo</span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ career.name || "Sin datos" }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{
                                            career.research_lines || "Sin datos"
                                        }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{
                                            career.coordinator ||
                                            "Sin descripción"
                                        }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ career.modality || "Sin datos" }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ career.duration || "Sin datos" }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right">
                                        <div class="flex justify-end space-x-2">
                                            <Link
                                                class="text-indigo-600 hover:text-indigo-900"
                                                :href="
                                                    route(
                                                        'careers.edit',
                                                        career.id
                                                    )
                                                "
                                                v-if="
                                                    $page.props.user.permissions.includes(
                                                        'actualizar carreras'
                                                    )
                                                "
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                class="text-red-600 hover:text-red-900"
                                                @click="deleteCareer(career.id)"
                                                v-if="
                                                    $page.props.user.permissions.includes(
                                                        'eliminar carreras'
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

                    <div class="flex justify-between mt-4">
                        <Link
                            v-if="pagination.current_page > 1"
                            @click="changePage(pagination.current_page - 1)"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#bf1627] hover:bg-[#a91221] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013a6a]"
                        >
                            Anterior
                        </Link>
                        <div v-else></div>
                        <Link
                            v-if="
                                pagination.current_page < pagination.last_page
                            "
                            @click="changePage(pagination.current_page + 1)"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
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
