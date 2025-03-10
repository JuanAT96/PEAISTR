<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInputPeriod.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            subject_name: "",
            subject_code: "",
            curricular_org_unit: "",
            academic_period_id: "",
            credits: "",
            contact_teacher: "",
            practical_teaching: "",
            practical_self_employment: "",
            self_employment: "",
            description: "",
            objectives: "",
            gral_learning_outcome: "",
            description_integrative_project: "",
        }),
    },
    academicperiods: {
        type: Object,
        required: true,
    },
});

defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Asignatura" : "Crear Nueva Asignatura" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Asignatura Seleccionada"
                    : "Crear Una Nueva Asignatura Desde Cero"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <h3 class="text-lg font-semibold mb-2">Información General</h3>
                <div>
                    <InputLabel
                        for="subject_name"
                        value="Nombre de la Asignatura"
                    />
                    <TextInput
                        id="subject_name"
                        v-model="form.subject_name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError
                        :message="$page.props.errors.subject_name"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        for="subject_code"
                        value="Código de la Asignatura"
                    />
                    <TextInput
                        id="subject_code"
                        v-model="form.subject_code"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError
                        :message="$page.props.errors.subject_code"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        for="curricular_org_unit"
                        value="Unidad Organizativa Curricular"
                    />
                    <TextInput
                        id="curricular_org_unit"
                        v-model="form.curricular_org_unit"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.curricular_org_unit"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-6 sm:col-span-6 mt-4">
                    <InputLabel
                        for="academic_period_id"
                        value="Período Académico"
                    />
                    <SelectInput
                        id="academic_period_id"
                        v-model="form.academic_period_id"
                        :options="academicperiods"
                        class="mt-1 block w-full"
                        required
                    >
                        <option
                            v-for="academicperiod in academicperiods"
                            :key="academicperiod.id"
                            :value="academicperiod.id"
                        >
                            {{ academicperiod.name }}
                        </option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.academic_period_id"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel for="credits" value="Créditos" />
                    <NumberInput
                        id="credits"
                        v-model="form.credits"
                        type="number"
                        min="1"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.credits"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <h3 class="text-lg font-semibold mb-2">Carga Horaria</h3>
                <div>
                    <InputLabel
                        for="contact_teacher"
                        value="Contacto Con El Docente"
                    />
                    <NumberInput
                        id="contact_teacher"
                        v-model="form.contact_teacher"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.contact_teacher"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        for="practical_teaching"
                        value="Trabajo Práctico/ Experimental Con El Docente"
                    />
                    <NumberInput
                        id="practical_teaching"
                        v-model="form.practical_teaching"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.practical_teaching"
                        class="mt-2"
                    />
                </div>
                <div>
                    <InputLabel
                        for="self_employment"
                        value="Trabajo Autónomo"
                    />
                    <NumberInput
                        id="self_employment"
                        v-model="form.self_employment"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.self_employment"
                        class="mt-2"
                    />
                </div>
                <div>
                    <InputLabel
                        for="practical_self_employment"
                        value="Trabajo Práctico/ Experimental Autónomo"
                    />
                    <NumberInput
                        id="practical_self_employment"
                        v-model="form.practical_self_employment"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.practical_self_employment"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <h3 class="text-lg font-semibold mb-2">
                    Información de la Asignatura
                </h3>
                <div>
                    <InputLabel
                        for="description"
                        value="Función específica de la asignatura en la formación profesional"
                    />
                    <TextareaInput
                        id="description"
                        v-model="form.description"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.description"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        for="objectives"
                        value="Objetivo de la Asignatura"
                    />
                    <TextareaInput
                        id="objectives"
                        v-model="form.objectives"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.objectives"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        for="gral_learning_outcome"
                        value="Resultado de aprendizaje general de la asignatura"
                    />
                    <TextareaInput
                        id="gral_learning_outcome"
                        v-model="form.gral_learning_outcome"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.gral_learning_outcome"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <h3 class="text-lg font-semibold mb-2">
                    Descripción del Proyecto Integrador de Saberes
                </h3>
                <div>
                    <InputLabel
                        for="description_integrative_project"
                        value="Descripción del Proyecto Integrador"
                    />
                    <TextareaInput
                        id="description_integrative_project"
                        v-model="form.description_integrative_project"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="
                            $page.props.errors.description_integrative_project
                        "
                        class="mt-2"
                    />
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
