<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInputSubject from "@/Components/SelectInputSubjectCode.vue";
import SelectInput from "@/Components/SelectInputTeacher.vue";

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
            teacher_id: "",
            coordinator_id: "",
            vice_rector_id: "",
        }),
    },
    subjects: {
        type: Object,
        required: true,
    },
    teachers: {
        type: Object,
        required: true,
    },
    coordinators: {
        type: Object,
        required: true,
    },
    vice_rectors: {
        type: Object,
        required: true,
    },
});

defineEmits(["submit"]);
</script>

<template>
    <!--<pre>{{ JSON.stringify(teachers, null, 2) }}</pre>-->
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Aprobación" : "Crear Nueva Aprobación" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Aprobación Seleccionada"
                    : "Crear Una Nueva Aprobación Desde Cero"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="subject_id" value="Asignatura" />
                <SelectInputSubject
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
                </SelectInputSubject>
                <InputError
                    :message="$page.props.errors.subject_id"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="teacher_id" value="Docente" />
                <SelectInput
                    id="teacher_id"
                    v-model="form.teacher_id"
                    :options="teachers"
                    class="mt-1 block w-full"
                    required
                >
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

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="coordinator_id" value="Coordinador" />
                <SelectInput
                    id="coordinator_id"
                    v-model="form.coordinator_id"
                    :options="coordinators"
                    class="mt-1 block w-full"
                    required
                >
                    <option
                        v-for="coordinator in $page.props.coordinators"
                        :key="coordinator.id"
                        :value="coordinator.id"
                    >
                        {{ coordinator.name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.coordinator_id"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="vice_rector_id" value="Vicerrector" />
                <SelectInput
                    id="vice_rector_id"
                    v-model="form.vice_rector_id"
                    :options="vice_rectors"
                    class="mt-1 block w-full"
                    required
                >
                    <option
                        v-for="viceRector in $page.props.vice_rectors"
                        :key="viceRector.id"
                        :value="viceRector.id"
                    >
                        {{ viceRector.name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.vice_rector_id"
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
