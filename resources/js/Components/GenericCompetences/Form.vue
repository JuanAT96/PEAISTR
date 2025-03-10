<script>
export default {
    name: "GenericCompetenceForm",
};
</script>

<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputSubjectCode.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            subject_id: "",
            soft_skills: "",
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
                    : "Crear Una Nueva Competencia Genérica"
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
                <InputLabel for="soft_skills" value="Habilidades Blandas" />
                <TextareaInput
                    id="soft_skills"
                    v-model="form.soft_skills"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.soft_skills"
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
