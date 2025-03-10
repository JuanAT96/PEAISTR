<script>
export default {
    name: "SpecificCompetenceForm",
};
</script>

<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputSubjectCode.vue";
import SelectInputNivel from "@/Components/SelectInput.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            graduate_profile: "",
            contribution_level: "",
            Learning_outcomes: "",
            subject_id: "",
        }),
    },
    subjects: {
        type: Object,
        required: true,
    },
});

defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{
                updating ? "Actualizar Competencia" : "Crear Nueva Competencia"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Competencia Seleccionada"
                    : "Crear Una Nueva Competencia Específica"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="subject_id" value="Asignatura" />
                <SelectInput
                    id="subject_id"
                    v-model="form.subject_id"
                    :options="subjects"
                    class="mt-1 block w-full"
                    required
                >
                    <option
                        v-for="subject in subjects"
                        :key="subject.id"
                        :value="subject.id"
                    >
                        {{ subject.subject_name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.subject_id"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <h3 class="text-lg font-semibold mb-2">
                    Competencias Específicas del perfil de egreso que
                    contribuyen a formar (resultados de aprendizaje)
                </h3>
                <InputLabel for="graduate_profile" value="Perfil de Egreso" />
                <TextareaInput
                    id="graduate_profile"
                    v-model="form.graduate_profile"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.graduate_profile"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="contribution_level"
                    value="Nivel de Contribución"
                />
                <SelectInputNivel
                    id="contribution_level"
                    v-model="form.contribution_level"
                    :options="['ALTA', 'MEDIA', 'BAJA']"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.contribution_level"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel
                    for="Learning_outcomes"
                    value="Resultado de Aprendizaje"
                />
                <TextareaInput
                    id="Learning_outcomes"
                    v-model="form.Learning_outcomes"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.Learning_outcomes"
                    class="mt-2"
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
