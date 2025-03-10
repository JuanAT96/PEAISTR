<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
//import DateInput from "@/Components/DateInput.vue";

defineProps({
    form: {
        type: Object,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
});

defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            <span v-if="type === 'subjects'"
                >Reporte de Asignaturas por Carrera</span
            >
            <span v-else-if="type === 'evaluations'"
                >Reporte de Evaluaciones por Asignatura</span
            >
            <span v-else-if="type === 'approvals'"
                >Reporte de Aprobaciones</span
            >
        </template>

        <template #description>
            Complete los filtros para generar el reporte
        </template>

        <template #form>
            <!-- Filtros para Reporte de Asignaturas por Carrera -->
            <template v-if="type === 'subjects'">
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="career" value="Carrera" />
                    <SelectInput
                        id="career"
                        v-model="form.career_id"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione una carrera</option>
                        <option
                            v-for="career in $page.props.careers"
                            :key="career.id"
                            :value="career.id"
                        >
                            {{ career.name }}
                        </option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.career_id"
                        class="mt-2"
                    />
                </div>
            </template>

            <!-- Filtros para Reporte de Evaluaciones por Asignatura -->
            <template v-if="type === 'evaluations'">
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="subject" value="Asignatura" />
                    <SelectInput
                        id="subject"
                        v-model="form.subject_id"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione una asignatura</option>
                        <option
                            v-for="subject in $page.props.subjects"
                            :key="subject.id"
                            :value="subject.id"
                        >
                            {{ subject.name }}
                        </option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.subject_id"
                        class="mt-2"
                    />
                </div>

                <div class="col-span-6 sm:col-span-3 mt-4">
                    <InputLabel for="date_from" value="Fecha Desde" />
                    <DateInput
                        id="date_from"
                        v-model="form.date_from"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.date_from"
                        class="mt-2"
                    />
                </div>

                <div class="col-span-6 sm:col-span-3 mt-4">
                    <InputLabel for="date_to" value="Fecha Hasta" />
                    <DateInput
                        id="date_to"
                        v-model="form.date_to"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="$page.props.errors.date_to"
                        class="mt-2"
                    />
                </div>
            </template>

            <!-- Filtros para Reporte de Aprobaciones -->
            <template v-if="type === 'approvals'">
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="filter_type" value="Filtrar por" />
                    <SelectInput
                        id="filter_type"
                        v-model="form.filter_type"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione tipo de filtro</option>
                        <option value="subject">Asignatura</option>
                        <option value="teacher">Docente</option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.filter_type"
                        class="mt-2"
                    />
                </div>

                <div
                    v-if="form.filter_type === 'subject'"
                    class="col-span-6 sm:col-span-6 mt-4"
                >
                    <InputLabel for="subject" value="Asignatura" />
                    <SelectInput
                        id="subject"
                        v-model="form.subject_id"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione una asignatura</option>
                        <option
                            v-for="subject in $page.props.subjects"
                            :key="subject.id"
                            :value="subject.id"
                        >
                            {{ subject.name }}
                        </option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.subject_id"
                        class="mt-2"
                    />
                </div>

                <div
                    v-if="form.filter_type === 'teacher'"
                    class="col-span-6 sm:col-span-6 mt-4"
                >
                    <InputLabel for="teacher" value="Docente" />
                    <SelectInput
                        id="teacher"
                        v-model="form.teacher_id"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione un docente</option>
                        <option
                            v-for="teacher in $page.props.teachers"
                            :key="teacher.id"
                            :value="teacher.id"
                        >
                            {{ teacher.name }}
                        </option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.teacher_id"
                        class="mt-2"
                    />
                </div>

                <div class="col-span-6 sm:col-span-3 mt-4">
                    <InputLabel for="period" value="Período" />
                    <SelectInput
                        id="period"
                        v-model="form.period"
                        class="mt-1 block w-full"
                        required
                    >
                        <option value="">Seleccione período</option>
                        <option value="current">Período Actual</option>
                        <option value="last">Período Anterior</option>
                        <option value="custom">Personalizado</option>
                    </SelectInput>
                    <InputError
                        :message="$page.props.errors.period"
                        class="mt-2"
                    />
                </div>
            </template>
        </template>

        <template #actions>
            <PrimaryButton> Generar Reporte </PrimaryButton>
        </template>
    </FormSection>
</template>
