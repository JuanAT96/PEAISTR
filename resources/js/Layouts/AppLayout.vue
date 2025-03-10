<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showingDropdowns = ref({
    gestionAcademica: false,
    disenoCurricular: false,
    evaluacionRecursos: false,
    administracionSeguridad: false,
});

const toggleDropdown = (categoryName) => {
    // Cierra todos los demás dropdowns antes de abrir uno nuevo
    Object.keys(showingDropdowns.value).forEach((key) => {
        if (key !== categoryName) {
            showingDropdowns.value[key] = false;
        }
    });
    showingDropdowns.value[categoryName] =
        !showingDropdowns.value[categoryName];
};

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gradient-to-r from-[#013a6a] to-[#bf1627]">
            <nav class="bg-[#013a6a] border-b border-gray-100 shadow-md">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationMark class="block h-9 w-auto" />
                            </Link>

                            <!-- Menús desplegables por categoría -->
                            <div class="hidden sm:flex sm:space-x-4 sm:ms-4">
                                <template
                                    v-for="(
                                        category, categoryName
                                    ) in navigationLinks"
                                    :key="categoryName"
                                >
                                    <div class="relative">
                                        <button
                                            @click="
                                                toggleDropdown(categoryName)
                                            "
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:bg-[#012a4a] focus:outline-none focus:bg-[#012a4a] transition ease-in-out duration-150"
                                        >
                                            {{ categoryName }}
                                            <svg
                                                class="ms-2 -me-0.5 size-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                />
                                            </svg>
                                        </button>

                                        <!-- Dropdown para la categoría -->
                                        <div
                                            v-if="
                                                showingDropdowns[categoryName]
                                            "
                                            class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                        >
                                            <div
                                                class="py-1"
                                                role="menu"
                                                aria-orientation="vertical"
                                                aria-labelledby="options-menu"
                                            >
                                                <template
                                                    v-for="(
                                                        link, index
                                                    ) in category"
                                                    :key="index"
                                                >
                                                    <Link
                                                        v-if="
                                                            hasPermission(
                                                                link.permission
                                                            )
                                                        "
                                                        :href="
                                                            route(link.route)
                                                        "
                                                        class="block px-4 py-2 text-sm leading-5 text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:bg-indigo-100 transition duration-150 ease-in-out"
                                                        :class="{
                                                            'font-semibold':
                                                                route().current(
                                                                    link.activeRoute
                                                                ),
                                                        }"
                                                    >
                                                        {{ link.label }}
                                                    </Link>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                        >
                                            <img
                                                class="size-8 rounded-full object-cover"
                                                :src="
                                                    $page.props.auth.user
                                                        .profile_photo_url
                                                "
                                                :alt="
                                                    $page.props.auth.user.name
                                                "
                                            />
                                        </button>
                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-500 bg-white hover:text-red-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div
                                            class="block px-4 py-2 text-xs text-red-400"
                                        >
                                            Administrar cuenta
                                        </div>
                                        <DropdownLink
                                            :href="route('profile.show')"
                                            >Perfil</DropdownLink
                                        >
                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .hasApiFeatures
                                            "
                                            :href="route('api-tokens.index')"
                                            >API Tokens</DropdownLink
                                        >
                                        <div class="border-t border-gray-200" />
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button"
                                                >Cerrar Sesión</DropdownLink
                                            >
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menú responsive -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <template
                        v-for="(category, categoryName) in navigationLinks"
                        :key="categoryName"
                    >
                        <div class="pt-2 pb-3 space-y-1">
                            <div
                                class="px-4 py-2 text-sm font-semibold text-white"
                            >
                                {{ categoryName }}
                            </div>
                            <template
                                v-for="(link, index) in category"
                                :key="index"
                            >
                                <div
                                    v-if="hasPermission(link.permission)"
                                    class="pt-2 pb-3 space-y-1"
                                >
                                    <ResponsiveNavLink
                                        :href="route(link.route)"
                                        :active="
                                            route().current(link.activeRoute)
                                        "
                                    >
                                        {{ link.label }}
                                    </ResponsiveNavLink>
                                </div>
                            </template>
                        </div>
                    </template>

                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="shrink-0 me-3"
                            >
                                <img
                                    class="size-10 rounded-full object-cover"
                                    :src="
                                        $page.props.auth.user.profile_photo_url
                                    "
                                    :alt="$page.props.auth.user.name"
                                />
                            </div>
                            <div>
                                <div
                                    class="font-medium text-base text-gray-800"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                Perfil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')"
                            >
                                API Tokens
                            </ResponsiveNavLink>
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button"
                                    >Cerrar sesión</ResponsiveNavLink
                                >
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="bg-gradient-to-r from-[#013a6a] to-[#bf1627]">
                <div
                    v-if="$page.props.flash.message"
                    class="container my-2 px-4 py-3 bg-red-300 text-red-800 rounded shadow-sm"
                >
                    {{ $page.props.flash.message }}
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            navigationLinks: {
                "Gestión Académica": [
                    {
                        route: "academicperiods.index",
                        activeRoute: "academicperiods.*",
                        label: "Periodos Académicos",
                        permission: "leer periodos académicos",
                    },
                    {
                        route: "careers.index",
                        activeRoute: "careers.*",
                        label: "Carreras",
                        permission: "leer carreras",
                    },
                    {
                        route: "subjects.index",
                        activeRoute: "subjects.*",
                        label: "Asignaturas",
                        permission: "leer asignaturas",
                    },
                    {
                        route: "careersubjects.index",
                        activeRoute: "careersubjects.*",
                        label: "Carrera/Asignatura",
                        permission: "leer asignaciones",
                    },
                    {
                        route: "subjectrequisites.index",
                        activeRoute: "subjectrequisites.*",
                        label: "Requisitos de Asignaturas",
                        permission: "leer requisitos de asignaturas",
                    },
                    {
                        route: "subjectteachers.index",
                        activeRoute: "subjectteachers.*",
                        label: "Asignatura/Docente",
                        permission: "leer asignaciones",
                    },
                ],
                "Diseño Curricular": [
                    {
                        route: "specificcompetences.index",
                        activeRoute: "specificcompetences.*",
                        label: "Competencias Específicas",
                        permission: "leer competencias específicas",
                    },
                    {
                        route: "genericcompetences.index",
                        activeRoute: "genericcompetences.*",
                        label: "Competencias Genéricas",
                        permission: "leer competencias genéricas",
                    },
                    {
                        route: "teachinglearningstrategies.index",
                        activeRoute: "teachinglearningstrategies.*",
                        label: "Estrategias de Enseñanza-Aprendizaje",
                        permission: "leer estrategias de enseñanza-aprendizaje",
                    },
                    {
                        route: "curricularunits.index",
                        activeRoute: "curricularunits.*",
                        label: "Unidades Curriculares",
                        permission: "leer unidades curriculares",
                    },
                    {
                        route: "teachingcontents.index",
                        activeRoute: "teachingcontents.*",
                        label: "Contenidos de Enseñanza",
                        permission: "leer contenidos de enseñanza",
                    },
                ],
                "Evaluación y Recursos": [
                    {
                        route: "evaluations.index",
                        activeRoute: "evaluations.*",
                        label: "Evaluaciones",
                        permission: "leer evaluaciones",
                    },
                    {
                        route: "bibliographies.index",
                        activeRoute: "bibliographies.*",
                        label: "Bibliografías",
                        permission: "leer bibliografías",
                    },
                    {
                        route: "approvals.index",
                        activeRoute: "approvals.*",
                        label: "Aprobaciones",
                        permission: "leer aprobaciones",
                    },
                ],

                "Administración y Seguridad": [
                    {
                        route: "peas.index",
                        activeRoute: "peas.*",
                        label: "PEAs (Programas de Estudio)",
                        permission: "leer pea",
                    },
                    {
                        route: "users.index",
                        activeRoute: "users.*",
                        label: "Usuarios",
                        permission: "leer usuarios",
                    },
                    {
                        route: "roles.index",
                        activeRoute: "roles.*",
                        label: "Roles",
                        permission: "leer roles",
                    },
                ],
            },
        };
    },
    methods: {
        // Verifica si el usuario tiene un permiso específico
        hasPermission(permission) {
            return (
                !permission ||
                (this.$page.props.user.permissions &&
                    this.$page.props.user.permissions.includes(permission))
            );
        },

        // Verifica si el usuario tiene permiso para ver al menos un elemento de la categoría
        hasCategoryPermission(category) {
            return this.navigationLinks[category].some((item) =>
                this.hasPermission(item.permission)
            );
        },
    },
};
</script>
