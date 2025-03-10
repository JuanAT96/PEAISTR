<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputSubjectCode.vue";
import SelectInputUnit from "@/Components/SelectInput.vue";

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
            unit: "",
            name: "",
            learning_outcomes: "",
        }),
    },
    subjects: {
        type: Object,
        required: true,
    },
});
//const emit = defineEmits(["submit"]);
defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{
                updating
                    ? "Actualizar Unidad Curricular"
                    : "Crear Nueva Unidad Curricular"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Unidad Curricular Seleccionada"
                    : "Crear Una Nueva Unidad Curricular Desde Cero"
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
                <InputLabel for="unit" value="Unidad" />
                <SelectInputUnit
                    id="unit"
                    v-model="form.unit"
                    :options="[
                        'UNIDAD 1',
                        'UNIDAD 2',
                        'UNIDAD 3',
                        'UNIDAD 4',
                        'UNIDAD 5',
                        'UNIDAD 6',
                        'UNIDAD 7',
                        'UNIDAD 8',
                        'UNIDAD 9',
                        'UNIDAD 10',
                    ]"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.unit" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Nombre de la Unidad" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="learning_outcomes"
                    value="Resultado de Aprendizaje"
                />
                <TextareaInput
                    id="learning_outcomes"
                    v-model="form.learning_outcomes"
                    rows="4"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.learning_outcomes"
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
