<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from "vue";

const props = defineProps({
    teaching_contents: {
        type: Array,
        required: true,
    },
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

const deleteTeachingContent = (teaching_content_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("teachingcontents.destroy", teaching_content_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("teachingcontents.index", { page }));
};

const totalHours = computed(() => {
    return (
        totalContactTeacherDuration.value +
        totalPracticalTeachingDuration.value +
        totalSelfEmploymentDuration.value +
        totalPracticalSelfEmploymentDuration.value
    );
});

const totalContactTeacherDuration = computed(() => {
    return props.teaching_contents.reduce((total, content) => {
        const duration = parseFloat(content.contact_teacher_duration);
        return total + (isNaN(duration) ? 0 : duration);
    }, 0);
});

const totalPracticalTeachingDuration = computed(() => {
    return props.teaching_contents.reduce((total, content) => {
        const duration = parseFloat(content.practical_teaching_duration);
        return total + (isNaN(duration) ? 0 : duration);
    }, 0);
});

const totalSelfEmploymentDuration = computed(() => {
    return props.teaching_contents.reduce((total, content) => {
        const duration = parseFloat(content.self_employment_duration);
        return total + (isNaN(duration) ? 0 : duration);
    }, 0);
});

const totalPracticalSelfEmploymentDuration = computed(() => {
    return props.teaching_contents.reduce((total, content) => {
        const duration = parseFloat(content.practical_self_employment_duration);
        return total + (isNaN(duration) ? 0 : duration);
    }, 0);
});

const groupedTeachingContents = computed(() => {
    let filteredContents = [...props.teaching_contents];

    if (search.value) {
        filteredContents = filteredContents.filter((content) => {
            const unit = content.curricularunit?.unit || "";
            const schedule = content.schedule || "";
            const contentText = content.content || "";
            const contactTeacher = content.contact_teacher || "";
            const contactTeacherDuration =
                content.contact_teacher_duration || "";
            const practicalTeaching = content.practical_teaching || "";
            const practicalTeachingDuration =
                content.practical_teaching_duration || "";
            const selfEmployment = content.self_employment || "";
            const selfEmploymentDuration =
                content.self_employment_duration || "";
            const practicalSelfEmployment =
                content.practical_self_employment || "";
            const practicalSelfEmploymentDuration =
                content.practical_self_employment_duration || "";

            const searchValue = search.value.toLowerCase();

            return (
                unit.toLowerCase().includes(searchValue) ||
                schedule.toLowerCase().includes(searchValue) ||
                contentText.toLowerCase().includes(searchValue) ||
                contactTeacher.toLowerCase().includes(searchValue) ||
                contactTeacherDuration.toString().includes(searchValue) ||
                practicalTeaching.toLowerCase().includes(searchValue) ||
                practicalTeachingDuration.toString().includes(searchValue) ||
                selfEmployment.toLowerCase().includes(searchValue) ||
                selfEmploymentDuration.toString().includes(searchValue) ||
                practicalSelfEmployment.toLowerCase().includes(searchValue) ||
                practicalSelfEmploymentDuration.toString().includes(searchValue)
            );
        });
    }

    const groups = {};
    filteredContents.forEach((content) => {
        let subjectName = "Sin asignatura";
        if (content.curricularunit && content.curricularunit.subject_id) {
            const subjectId = content.curricularunit.subject_id;
            const foundSubject = props.subjects.find(
                (subject) => subject.id === subjectId
            );
            if (foundSubject) {
                subjectName = foundSubject.subject_name;
            }
        }
        if (!groups[subjectName]) {
            groups[subjectName] = [];
        }
        groups[subjectName].push(content);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Contenidos de Enseñanza">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Contenidos de Enseñanza
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear contenidos de enseñanza'
                            )
                        "
                    >
                        <Link
                            :href="route('teachingcontents.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR CONTENIDO
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
                                contents, subjectName
                            ) in groupedTeachingContents"
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
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Unidad Curricular
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Semana /Fechas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Contenido
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Contacto Docente
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                horas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Enseñanza Práctica
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                horas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Autónomo
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                horas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                Autónomo Práctico
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-center"
                                            >
                                                horas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-right"
                                            >
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="content in contents"
                                            :key="content.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.curricularunit
                                                        ?.unit ||
                                                    "Sin unidad curricular"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.schedule ||
                                                    "Sin horario"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.content ||
                                                    "Sin contenido"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.contact_teacher ||
                                                    "Sin contacto"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.contact_teacher_duration ||
                                                    "0"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.practical_teaching ||
                                                    "Sin práctica"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.practical_teaching_duration ||
                                                    "0"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.self_employment ||
                                                    "Sin autoempleo"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.self_employment_duration ||
                                                    "0"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.practical_self_employment ||
                                                    "Sin autoempleo"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-gray-800"
                                            >
                                                {{
                                                    content.practical_self_employment_duration ||
                                                    "0"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-1 text-sm text-right"
                                            >
                                                <div
                                                    class="flex justify-end space-x-2"
                                                >
                                                    <Link
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                        :href="
                                                            route(
                                                                'teachingcontents.edit',
                                                                content.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'actualizar contenidos de enseñanza'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deleteTeachingContent(
                                                                content.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar contenidos de enseñanza'
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
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                                colspan="4"
                                            >
                                                Total
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalContactTeacherDuration
                                                }}
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            ></th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalPracticalTeachingDuration
                                                }}
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            ></th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalSelfEmploymentDuration
                                                }}
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            ></th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                            >
                                                {{
                                                    totalPracticalSelfEmploymentDuration
                                                }}
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700 text-right"
                                            ></th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                                colspan="2"
                                            >
                                                Total de Horas
                                            </th>
                                            <th
                                                class="py-2 px-1 text-left text-sm font-semibold text-gray-700"
                                                colspan="1"
                                            >
                                                {{ totalHours }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <Link
                                v-if="pagination.current_page > 1"
                                @click="changePage(pagination.current_page - 1)"
                                class="py-2 px-1 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
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
                                class="py-2 px-1 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
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
