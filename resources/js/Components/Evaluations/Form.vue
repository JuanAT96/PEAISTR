<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputSubjectCode.vue";
import SelectInputn from "@/Components/SelectInput.vue";
import SelectInputUn from "@/Components/SelectInputUnit.vue";

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
            Learning_outcomes: "",
            type: "",
            criteria: "",
            activity: "",
            first_partial: null,
            second_partial: null,
        }),
    },
    subjects: {
        type: Object,
        required: true,
    },
    curricularunits: {
        type: Object,
        required: true,
    },
});

defineEmits(["submit"]);

// Propiedad computada para obtener el nombre de la asignatura
const getSubjectName = (curricularUnitId) => {
    const curricularUnit = props.curricularunits.find(
        (unit) => unit.id === curricularUnitId
    );
    if (curricularUnit) {
        const subject = props.subjects.find(
            (subject) => subject.id === curricularUnit.subject_id
        );
        return subject ? subject.subject_name : "Sin Asignatura";
    }
    return "Sin Asignatura";
};
</script>

<template>
    <!--<pre>{{ JSON.stringify(subjects, null, 2) }}</pre>-->
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Evaluación" : "Crear Nueva Evaluación" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Evaluación Seleccionada"
                    : "Crear Una Nueva Evaluación Desde Cero"
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

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="unit" value="Unidad" />
                <SelectInputUn
                    id="unit"
                    v-model="form.unit"
                    :options="curricularunits"
                    :subjects="subjects"
                    class="mt-1 block w-full"
                    required
                >
                    <option
                        v-for="curricularunit in curricularunits"
                        :key="curricularunit.id"
                        :value="curricularunit.id"
                    >
                        {{ getSubjectName(curricularunit.id) }} -
                        {{ curricularunit.unit }} -
                        {{ curricularunit.learning_outcomes }}
                    </option>
                </SelectInputUn>
                <InputError
                    :message="$page.props.errors.curricular_units_id"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="Learning_outcomes"
                    value="Resultados de Aprendizaje"
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
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="type" value="Tipo de Evaluación" />
                <SelectInputn
                    id="type"
                    v-model="form.type"
                    :options="['Formativa', 'Sumativa']"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.type" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="criteria" value="Criterios de Evaluación" />
                <TextareaInput
                    id="criteria"
                    v-model="form.criteria"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.criteria"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="activity" value="Actividad de Evaluación" />
                <TextareaInput
                    id="activity"
                    v-model="form.activity"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.activity"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel value="Notas a Ingresar" />
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.include_first_partial"
                            class="mr-2"
                        />
                        Primer Parcial
                    </label>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.include_second_partial"
                            class="mr-2"
                        />
                        Segundo Parcial
                    </label>
                </div>
            </div>

            <div
                class="col-span-6 sm:col-span-6 mt-4"
                v-if="form.include_first_partial"
            >
                <InputLabel for="first_partial" value="Primer Parcial" />
                <TextInput
                    id="first_partial"
                    v-model="form.first_partial"
                    type="number"
                    step="0.01"
                    min="0"
                    max="10"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.first_partial"
                    class="mt-2"
                />
            </div>

            <div
                class="col-span-6 sm:col-span-6 mt-4"
                v-if="form.include_second_partial"
            >
                <InputLabel for="second_partial" value="Segundo Parcial" />
                <TextInput
                    id="second_partial"
                    v-model="form.second_partial"
                    type="number"
                    step="0.01"
                    min="0"
                    max="10"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.second_partial"
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
