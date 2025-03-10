<script setup>
import { ref, onMounted, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    options: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);
const selectInput = ref(null);

const setActiveByDefault = () => {
    const activeOption = props.options.find(
        (option) => option.estado.toLowerCase() === "activo"
    );
    if (activeOption && !props.modelValue) {
        emit("update:modelValue", activeOption.id);
    }
};

onMounted(() => {
    setActiveByDefault();
    if (selectInput.value.hasAttribute("autofocus")) {
        selectInput.value.focus();
    }
});

watch(
    () => props.options,
    () => {
        setActiveByDefault();
    },
    { immediate: true }
);

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
        <option v-for="option in options" :key="option.id" :value="option.id">
            {{ option.name }}
        </option>
    </select>
</template>
