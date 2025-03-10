<script>
export default {
    name: "AcademicPeriodForm",
};
</script>

<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";

defineProps({
    /* academicPeriod: {
        type: Object,
        required: true,
    },*/
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: { name: "", estado: "" },
});

defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Periodo" : "Crear Nuevo Periodo" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar El Periodo Seleccionado"
                    : "Crear Un Nuevo Periodo Académico"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel
                    for="name"
                    value="Nombre del Periodo (ejemplo: 2024 - IIP)"
                />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="estado" value="estado" />
                <SelectInput
                    id="estado"
                    v-model="form.estado"
                    :options="['activo', 'inactivo']"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.estado" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
