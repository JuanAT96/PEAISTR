<script>
export default {
    name: "CareersForm",
};
</script>

<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { reactive, ref } from "vue";

const props = defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        name: "",
        research_lines: "",
        coordinator: "",
        modality: "",
        duration: "",
        logo_path: "",
    },
});

const emit = defineEmits(["submit", "update:logo_path"]);

const logoFile = ref(null);
const imageUrl = ref(null); // Variable para la URL de la imagen

const handleLogoChange = (event) => {
    logoFile.value = event.target.files[0];
    emit("update:logo_path", logoFile.value);

    // Actualizar la URL de la imagen
    if (logoFile.value) {
        imageUrl.value = URL.createObjectURL(logoFile.value);
    } else {
        imageUrl.value = null;
    }
};

const handleSubmit = () => {
    emit("submit", { ...props.form, logo_path: logoFile.value });
};
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? "Actualizar Carrera" : "Crear Nueva Carrera" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Carrera Seleccionada"
                    : "Crear Una Nueva Carrera Desde Cero"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel
                    for="name"
                    name="name"
                    value="Nombre de la Carrera"
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
                <InputLabel
                    for="research_lines"
                    value="Líneas de Investigación de la Carrera (sin son varias escriba separadas por comas)"
                />
                <TextareaInput
                    id="research_lines"
                    v-model="form.research_lines"
                    rows="4"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.research_lines"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel
                    for="coordinator"
                    name="coordinator"
                    value="Nombre del Coordinador de la Carrera"
                />
                <TextInput
                    id="coordinator"
                    v-model="form.coordinator"
                    type="text"
                    autocomplete="name"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.coordinator"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="modality" value="Modalidad de la Carrera" />
                <SelectInput
                    id="modality"
                    v-model="form.modality"
                    :options="[
                        'PRESENCIAL',
                        'SEMI-PRESENCIAL',
                        'NIVELACIÓN',
                        'EN LÍNEA',
                        'DUAL',
                        'OTRO',
                    ]"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.modality"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="duration" value="Duración (semestres)" />
                <NumberInput
                    id="duration"
                    v-model="form.duration"
                    type="number"
                    min="1"
                    max="5"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.duration"
                    class="mt-2"
                />
            </div>
            <div v-show="false">
                <InputLabel
                    for="logo_path"
                    name="logo_path"
                    value="Dirección del Logo"
                />
                <TextInput
                    id="logo_path"
                    v-model="form.logo_path"
                    type="text"
                    autocomplete="name"
                    class="mt-1 block w-full"
                    required
                    disabled
                />
                <InputError
                    :message="$page.props.errors.logo_path"
                    class="mt-2"
                />
            </div>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="logo" value="Logo de la Carrera" />
                <input
                    type="file"
                    id="logo"
                    @change="handleLogoChange"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.logo_path"
                    class="mt-2"
                />
            </div>
            <div v-if="imageUrl" class="col-span-6 sm:col-span-6 mt-4">
                <img
                    :src="imageUrl"
                    alt="Logo de la Carrera"
                    class="max-w-full h-auto"
                />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
