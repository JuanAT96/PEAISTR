<script setup>
import { ref, onMounted, computed } from "vue";

const props = defineProps({
    modelValue: [String, Number],
    options: {
        type: Array,
        required: true,
    },
    subjects: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);
const selectInput = ref(null);

onMounted(() => {
    if (selectInput.value && selectInput.value.hasAttribute("autofocus")) {
        selectInput.value.focus();
    }
});

const updateValue = (event) => {
    emit("update:modelValue", event.target.value);
};

// Propiedad computada para obtener las opciones con el nombre de la asignatura
const formattedOptions = computed(() => {
    return props.options.map((option) => {
        const subject = props.subjects.find(
            (subject) => subject.id === option.subject_id
        );
        return {
            ...option,
            subject_name: subject ? subject.subject_name : "Sin Asignatura",
        };
    });
});

defineExpose({ focus: () => selectInput.value.focus() });
</script>

<template>
    <select
        ref="selectInput"
        :value="modelValue"
        @change="updateValue"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option value="" disabled selected>Seleccione una opción</option>
        <option
            v-for="option in formattedOptions"
            :key="option.unit"
            :value="option.id"
        >
            {{ option.subject_name }} - {{ option.unit }} -
            {{ option.learning_outcomes }}
        </option>
    </select>
</template>
