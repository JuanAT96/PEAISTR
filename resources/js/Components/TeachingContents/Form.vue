<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputUnit.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextInput from "@/Components/TextInput.vue"; // Importar TextInput
import { computed } from "vue"; // Importa computed

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            curricular_units_id: "",
            schedule: "",
            content: "",
            contact_teacher: "",
            contact_teacher_duration: 0,
            practical_teaching: "",
            practical_teaching_duration: 0,
            practical_self_employment: "",
            practical_self_employment_duration: 0,
            self_employment: "",
            self_employment_duration: 0,
        }),
    },
    curricularunits: {
        type: Object,
        required: true,
    },
    subjects: {
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
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{
                updating
                    ? "Actualizar Contenido de Enseñanza"
                    : "Crear Nuevo Contenido de Enseñanza"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar El Contenido de Enseñanza Seleccionado"
                    : "Crear Un Nuevo Contenido de Enseñanza Desde Cero"
            }}
        </template>

        <template #form>
            <!--<pre>{{ JSON.stringify(curricularunits, null, 2) }}</pre>-->
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="curricular_units_id"
                    value="Unidad Curricular"
                />
                <SelectInput
                    id="curricular_units_id"
                    v-model="form.curricular_units_id"
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
                </SelectInput>
                <InputError
                    :message="$page.props.errors.curricular_units_id"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="schedule" value="Semana/Fechas" />
                <TextInput
                    id="schedule"
                    v-model="form.schedule"
                    class="mt-1 block w-full"
                />
                <InputError
                    :message="$page.props.errors.schedule"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="content" value="Contenido (Conceptos)" />
                <TextareaInput
                    id="content"
                    v-model="form.content"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.content"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="contact_teacher"
                    value="Aprendizaje En Contacto Con El Docente"
                />
                <TextareaInput
                    id="contact_teacher"
                    v-model="form.contact_teacher"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.contact_teacher"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="contact_teacher_duration"
                    value="Duración Contacto Docente (horas)"
                />
                <NumberInput
                    id="contact_teacher_duration"
                    v-model="form.contact_teacher_duration"
                    type="number"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.contact_teacher_duration"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="practical_teaching"
                    value="Aprendizaje Práctico/ Experimental Con El Docente"
                />
                <TextareaInput
                    id="practical_teaching"
                    v-model="form.practical_teaching"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.practical_teaching"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="practical_teaching_duration"
                    value="Duración Enseñanza Práctica (horas)"
                />
                <NumberInput
                    id="practical_teaching_duration"
                    v-model="form.practical_teaching_duration"
                    type="number"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.practical_teaching_duration"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="self_employment"
                    value="Aprendizaje Autónomo"
                />
                <TextareaInput
                    id="self_employment"
                    v-model="form.self_employment"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.self_employment"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="self_employment_duration"
                    value="Duración Autónomo (horas)"
                />
                <NumberInput
                    id="self_employment_duration"
                    v-model="form.self_employment_duration"
                    type="number"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.self_employment_duration"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="practical_self_employment"
                    value="Aprendizaje Práctico/ Experimental Autónomo"
                />
                <TextareaInput
                    id="practical_self_employment"
                    v-model="form.practical_self_employment"
                    rows="4"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.practical_self_employment"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="practical_self_employment_duration"
                    value="Duración Autónomo Práctico (horas)"
                />
                <NumberInput
                    id="practical_self_employment_duration"
                    v-model="form.practical_self_employment_duration"
                    type="number"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="
                        $page.props.errors.practical_self_employment_duration
                    "
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
