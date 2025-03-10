<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Bienvenid@" />
    <div
        class="flex items-center justify-center min-h-screen bg-gradient-to-r from-[#013a6a] to-[#bf1627] text-gray-900"
    >
        <div
            class="bg-white text-gray-900 p-4 sm:p-8 md:p-12 rounded-2xl shadow-lg max-w-md sm:max-w-lg md:max-w-3xl text-center"
        >
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
                ¡Bienvenido al Sistema de Automatización PEA!
            </h1>
            <p class="text-base sm:text-lg text-gray-700 mb-6">
                Mejora la planificación y gestión del Programa de Estudio de la
                Asignatura de manera eficiente y moderna.
            </p>
            <header class="py-6 sm:py-8 md:py-10 bg-gray-200">
                <nav v-if="canLogin" class="flex justify-center">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            Acceder
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#bf1627] hover:bg-[#a91221] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013a6a]"
                        >
                            Registrarse
                        </Link>
                    </template>
                </nav>
            </header>
        </div>
    </div>
</template>
