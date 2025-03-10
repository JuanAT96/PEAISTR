<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    bibliographies: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteBibliography = (bibliography_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("bibliographies.destroy", bibliography_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("bibliographies.index", { page }));
};

const groupedBibliographies = computed(() => {
    let filteredBibliographies = [...props.bibliographies];

    if (search.value) {
        const searchValue = search.value.toLowerCase();
        filteredBibliographies = filteredBibliographies.filter(
            (bibliography) => {
                const type = bibliography.type
                    ? bibliography.type.toLowerCase()
                    : "";
                const author = bibliography.author
                    ? bibliography.author.toLowerCase()
                    : "";
                const year = bibliography.year
                    ? bibliography.year.toString()
                    : "";
                const title = bibliography.title
                    ? bibliography.title.toLowerCase()
                    : "";
                const editorial = bibliography.editorial
                    ? bibliography.editorial.toLowerCase()
                    : "";
                const format = bibliography.format
                    ? bibliography.format.toLowerCase()
                    : "";
                const code = bibliography.code
                    ? bibliography.code.toString()
                    : "";
                const link = bibliography.link
                    ? bibliography.link.toLowerCase()
                    : "";
                const uplift = bibliography.uplift
                    ? bibliography.uplift.toLowerCase()
                    : "";

                return (
                    type.includes(searchValue) ||
                    author.includes(searchValue) ||
                    year.includes(searchValue) ||
                    title.includes(searchValue) ||
                    editorial.includes(searchValue) ||
                    format.includes(searchValue) ||
                    code.includes(searchValue) ||
                    link.includes(searchValue) ||
                    uplift.includes(searchValue)
                );
            }
        );
    }

    const groups = {};
    filteredBibliographies.forEach((bibliography) => {
        const subjectName =
            bibliography.subject?.subject_name || "Sin asignatura";
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(bibliography);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Bibliografías">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Bibliografías
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear bibliografías'
                            )
                        "
                    >
                        <Link
                            :href="route('bibliographies.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR BIBLIOGRAFÍA
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
                                bibliographies, subjectName
                            ) in groupedBibliographies"
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
                                                Tipo
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Autor
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Año
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Título
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Editorial
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Formato
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Código del libro
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Link del recurso
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                Sustentación
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
                                            v-for="bibliography in bibliographies"
                                            :key="bibliography.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.type ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.author ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.year ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.title ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.editorial ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.format ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.code ||
                                                    "Sin codigo"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.link ||
                                                    "Sin enlace"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    bibliography.uplift ||
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
                                                                'bibliographies.edit',
                                                                bibliography.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar bibliografías'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteBibliography(
                                                                bibliography.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar bibliografías'
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
