<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    roles: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const deleteRole = (role_id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("roles.destroy", role_id));
    }
};

const changePage = (page) => {
    Inertia.get(route("roles.index", { page }));
};
</script>

<template>
    <AppLayout title="Roles">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles y Permisos
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="p-6 bg-white border border-gray-200 rounded-lg shadow-md"
                >
                    <div
                        class="flex justify-between mb-4"
                        v-if="
                            $page.props.user.permissions.includes('crear roles')
                        "
                    >
                        <Link
                            :href="route('roles.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            AGREGAR ROL
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                    >
                                        Permisos
                                    </th>
                                    <th
                                        class="py-3 px-4 text-right text-sm font-semibold text-gray-700"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="role in roles"
                                    :key="role.id"
                                    class="border-b border-gray-200"
                                >
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        {{ role.name }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <span
                                            v-for="permission in role.permissions"
                                            :key="permission.id"
                                            class="inline-block bg-gradient-to-b from-red-200 to-blue-200 rounded px-2 py-1 text-xs mr-1 mb-1"
                                        >
                                            {{ permission.name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right text-sm">
                                        <div class="flex justify-end space-x-2">
                                            <Link
                                                v-if="
                                                    $page.props.user.permissions.includes(
                                                        'actualizar roles'
                                                    )
                                                "
                                                :href="
                                                    route('roles.edit', role.id)
                                                "
                                                class="text-blue-600 hover:text-blue-800 transition duration-200 ease-in-out"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                v-if="
                                                    $page.props.user.permissions.includes(
                                                        'eliminar roles'
                                                    )
                                                "
                                                @click="deleteRole(role.id)"
                                                class="text-red-600 hover:text-red-800 transition duration-200 ease-in-out"
                                            >
                                                Borrar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between mt-6">
                        <Link
                            v-if="pagination.current_page > 1"
                            @click="changePage(pagination.current_page - 1)"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Anterior
                        </Link>
                        <div v-else></div>
                        <Link
                            v-if="
                                pagination.current_page < pagination.last_page
                            "
                            @click="changePage(pagination.current_page + 1)"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500"
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
