<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number], // Acepta cadenas o números
        default: "",
    },
    options: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);
const selectInput = ref(null);

onMounted(() => {
    if (selectInput.value.hasAttribute("autofocus")) {
        selectInput.value.focus();
    }
});

const updateValue = (event) => {
    emit("update:modelValue", event.target.value);
};

defineExpose({ focus: () => selectInput.value.focus() });
</script>

<template>
    <select
        ref="selectInput"
        :value="modelValue"
        @change="updateValue"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option value="" disabled>Seleccione una opción</option>
        <option
            v-for="option in props.options"
            :key="option.id"
            :value="option.id"
        >
            {{ option.name }}
        </option>
    </select>
</template>
