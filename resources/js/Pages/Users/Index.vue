<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, computed } from "vue";

const props = defineProps({
    users: {
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

const deleteUser = (id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("users.destroy", id));
    }
};

const changePage = (page) => {
    Inertia.get(route("users.index", { page }));
};

const setSort = (column) => {
    if (sort.value.column === column) {
        sort.value.order = sort.value.order === "asc" ? "desc" : "asc";
    } else {
        sort.value.column = column;
        sort.value.order = "asc";
    }
};

const sortedUsers = computed(() => {
    let filtered = [...props.users];

    if (search.value) {
        filtered = filtered.filter((user) =>
            Object.values(user).some((value) => {
                if (
                    value &&
                    typeof value === "object" &&
                    Array.isArray(value)
                ) {
                    return value.some((innerValue) =>
                        Object.values(innerValue).some((grandChild) =>
                            String(grandChild)
                                .toLowerCase()
                                .includes(search.value.toLowerCase())
                        )
                    );
                }
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
            let valA = String(a[sort.value.column]).toLowerCase();
            let valB = String(b[sort.value.column]).toLowerCase();

            if (
                sort.value.column === "roles" &&
                a.roles.length > 0 &&
                b.roles.length > 0
            ) {
                valA = String(a.roles[0].name).toLowerCase();
                valB = String(b.roles[0].name).toLowerCase();
            }

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
    <AppLayout title="Usuarios">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes(
                                'crear usuarios'
                            )
                        "
                    >
                        <Link
                            :href="route('users.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR USUARIO
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
                                        <button @click="setSort('name')">
                                            Nombre
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
                                        <button @click="setSort('email')">
                                            Correo
                                            {{
                                                sort.column === "email"
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
                                        <button @click="setSort('roles')">
                                            Rol
                                            {{
                                                sort.column === "roles"
                                                    ? sort.order === "asc"
                                                        ? "▲"
                                                        : "▼"
                                                    : ""
                                            }}
                                        </button>
                                    </th>
                                    <th
                                        class="py-2 px-6 text-left text-sm font-semibold text-gray-700 text-right"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in sortedUsers"
                                    :key="user.id"
                                    class="border-b border-gray-200"
                                >
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ user.name || "Sin datos" }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ user.email || "Sin datos" }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{
                                            user.roles.length > 0
                                                ? user.roles[0].name
                                                : "Sin datos"
                                        }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right">
                                        <div
                                            class="flex flex-col items-end space-y-1"
                                        >
                                            <div class="flex space-x-2">
                                                <Link
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    :href="
                                                        route(
                                                            'users.edit',
                                                            user.id
                                                        )
                                                    "
                                                    v-if="
                                                        $page.props.user.permissions.includes(
                                                            'actualizar usuarios'
                                                        )
                                                    "
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    class="text-red-600 hover:text-red-900"
                                                    @click="deleteUser(user.id)"
                                                    v-if="
                                                        $page.props.user.permissions.includes(
                                                            'eliminar usuarios'
                                                        )
                                                    "
                                                >
                                                    Eliminar
                                                </button>
                                            </div>

                                            <Link
                                                class="text-center text-sm font-bold bg-gradient-to-r from-[#bf1627] to-[#013a6a] text-transparent bg-clip-text hover:text-blue-600 mt-1"
                                                :href="
                                                    route('users.show', user.id)
                                                "
                                                v-if="
                                                    $page.props.user.permissions.includes(
                                                        'crear roles'
                                                    )
                                                "
                                            >
                                                Asignar Roles
                                            </Link>
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
