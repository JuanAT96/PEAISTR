<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, computed } from "vue";

const props = defineProps({
    subjects: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const search = ref("");

const deleteSubject = (subject_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("subjects.destroy", subject_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("subjects.index", { page }));
};

const filteredSubjects = computed(() => {
    return props.subjects.filter((subject) => {
        if (!search.value) {
            return true;
        }
        const searchTerm = search.value.toLowerCase();
        return Object.values(subject).some((value) => {
            if (value !== null && value !== undefined) {
                return String(value).toLowerCase().includes(searchTerm);
            }
            return false;
        });
    });
});
</script>

<template>
    <AppLayout title="Asignaturas">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignaturas ->

                <span class="text-sm text-gray-600">
                    Mostrando {{ pagination.from }} a {{ pagination.to }} de
                    {{ pagination.total }} resultados
                </span>
                <select
                    class="text-xs px-1 py-0.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    v-model="selectedPage"
                    @change="changePage(selectedPage)"
                >
                    <option
                        v-for="page in pagination.last_page"
                        :key="page"
                        :value="page"
                        :selected="page === pagination.current_page"
                    >
                        Pagina {{ page }}
                    </option>
                </select>
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear asignaturas'
                            )
                        "
                    >
                        <Link
                            :href="route('subjects.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR ASIGNATURA
                        </Link>
                    </div>

                    <div class="mt-4">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar en todas las columnas..."
                            class="mb-4 w-full border rounded px-3 py-2"
                        />
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

                        <div
                            v-for="subject in filteredSubjects"
                            :key="subject.id"
                            class="border-b border-gray-200 p-4"
                        >
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full border-collapse border border-gray-300"
                                >
                                    <tbody>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Asignatura
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.subject_name ===
                                                            null ||
                                                        subject.subject_name ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.subject_name ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Código
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.subject_code ===
                                                            null ||
                                                        subject.subject_code ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.subject_code ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Unidad Organizativa Curricular
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.curricular_org_unit ===
                                                            null ||
                                                        subject.curricular_org_unit ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.curricular_org_unit ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Período Académico
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.academic_period_id ===
                                                        null
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.academic_period_id
                                                            ? subject
                                                                  .academicperiods
                                                                  .name
                                                            : "Sin Datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Créditos
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.credits ===
                                                            null ||
                                                        subject.credits === ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.credits ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Contacto Con El Docente
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.contact_teacher ===
                                                            null ||
                                                        subject.contact_teacher ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.contact_teacher ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Trabajo Práctico con el Docente
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.practical_teaching ===
                                                            null ||
                                                        subject.practical_teaching ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.practical_teaching ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Trabajo Autónomo
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.self_employment ===
                                                            null ||
                                                        subject.self_employment ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.self_employment ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Trabajo Práctico Autónomo
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.practical_self_employment ===
                                                            null ||
                                                        subject.practical_self_employment ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.practical_self_employment ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Descripción
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.description ===
                                                            null ||
                                                        subject.description ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.description ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Objetivo
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.objectives ===
                                                            null ||
                                                        subject.objectives ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.objectives ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Resultado de Aprendizaje General
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.gral_learning_outcome ===
                                                            null ||
                                                        subject.gral_learning_outcome ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.gral_learning_outcome ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="px-4 py-2 font-semibold bg-gray-100"
                                            >
                                                Descripción del Proyecto
                                                Integrador
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    :class="
                                                        subject.description_integrative_project ===
                                                            null ||
                                                        subject.description_integrative_project ===
                                                            ''
                                                            ? 'text-red-500'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        subject.description_integrative_project ||
                                                        "Sin datos"
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end space-x-2 mt-4">
                                <Link
                                    class="text-indigo-600 hover:text-indigo-900"
                                    :href="route('subjects.edit', subject.id)"
                                    v-if="
                                        $page.props.user.permissions.includes(
                                            'actualizar asignaturas'
                                        )
                                    "
                                >
                                    Editar
                                </Link>
                                <button
                                    class="text-red-600 hover:text-red-900"
                                    @click="deleteSubject(subject.id)"
                                    v-if="
                                        $page.props.user.permissions.includes(
                                            'eliminar asignaturas'
                                        )
                                    "
                                >
                                    Borrar
                                </button>
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
                    <div class="mt-4 text-sm text-gray-600">
                        Mostrando {{ pagination.from }} a {{ pagination.to }} de
                        {{ pagination.total }} resultados
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
