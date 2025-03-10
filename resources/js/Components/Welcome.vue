<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const quizQuestions = ref([
    {
        question: "¿Cuál es el objetivo principal de un PEA?",
        options: [
            "Organizar actividades",
            "Evaluar estudiantes",
            "Definir competencias",
        ],
        answer: 0,
    },
    {
        question: "¿Cuántos créditos suele tener una asignatura semestral?",
        options: ["3-4 créditos", "6-7 créditos", "1-2 créditos"],
        answer: 0,
    },
    {
        question:
            "¿Cuál de las siguientes es una técnica de evaluación formativa?",
        options: ["Examen final", "Foro de discusión", "Prueba escrita"],
        answer: 1,
    },
]);

const currentQuestion = ref(0);
const score = ref(0);
const showResult = ref(false);

function selectAnswer(index) {
    if (index === quizQuestions.value[currentQuestion.value].answer) {
        score.value++;
    }
    if (currentQuestion.value < quizQuestions.value.length - 1) {
        currentQuestion.value++;
    } else {
        showResult.value = true;
    }
}

function resetGame() {
    currentQuestion.value = 0;
    score.value = 0;
    showResult.value = false;
}
</script>

<template>
    <Head title="Welcome" />
    <div
        class="flex items-center justify-center min-h-screen bg-gray-200 text-gray-900"
    >
        <div
            class="bg-white text-gray-900 p-12 rounded-2xl shadow-lg max-w-3xl text-center"
        >
            <h1 class="text-4xl font-bold mb-4">
                ¡Bienvenido al Sistema de Automatización PEA!
            </h1>
            <p class="text-lg text-gray-700 mb-6">
                Mejora la planificación y gestión del Programa de Estudio de la
                Asignatura de manera eficiente y moderna.
            </p>

            <div class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">
                    Juego de Preguntas: Evaluación Docente
                </h2>
                <div v-if="!showResult" class="text-left">
                    <p class="text-lg font-medium">
                        {{ quizQuestions[currentQuestion].question }}
                    </p>
                    <div class="mt-4 space-y-2">
                        <button
                            v-for="(option, index) in quizQuestions[
                                currentQuestion
                            ].options"
                            :key="index"
                            @click="selectAnswer(index)"
                            class="w-full text-white py-2 px-4 rounded-lg"
                            :class="
                                index === 0
                                    ? 'bg-gradient-to-r from-[#013a6a] to-[#bf1627] hover:from-[#012a5a] hover:to-[#a91221] focus:ring-2 focus:ring-[#bf1627]'
                                    : 'bg-gradient-to-r from-[#bf1627] to-[#013a6a] hover:from-[#a91221] hover:to-[#012a5a] focus:ring-2 focus:ring-[#013a6a]'
                            "
                        >
                            {{ option }}
                        </button>
                    </div>
                </div>
                <div v-else>
                    <p class="text-xl font-semibold">
                        Tu puntuación es: {{ score }} /
                        {{ quizQuestions.length }}
                    </p>
                    <button
                        @click="resetGame"
                        class="mt-4 text-white py-2 px-4 rounded-lg bg-[#013a6a] hover:bg-[#012a5a] focus:ring-2 focus:ring-[#bf1627]"
                    >
                        Jugar de Nuevo
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
