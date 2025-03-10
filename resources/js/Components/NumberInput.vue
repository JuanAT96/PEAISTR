<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number], // Acepta cadenas o números
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const handleInput = (event) => {
    // Intenta convertir el valor a un número; si falla, usa el valor original
    const numericValue =
        event.target.value === "" ? "" : Number(event.target.value);
    emit("update:modelValue", numericValue);
};
</script>

<template>
    <input
        ref="input"
        type="number"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="handleInput"
    />
</template>
