<script setup>
import { ref, watch, onMounted } from "vue";
import axios from "axios";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInputTeacher.vue";
import SelectInputSubject from "@/Components/SelectInputSubjectCode.vue";

const props = defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            subject_id: "",
            user_id: "",
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
});

const emit = defineEmits(["submit"]);

const availableSubjects = ref([]);

const fetchAvailableSubjects = () => {
    if (props.form.user_id) {
        axios
            .get(
                `/api/subjectteachers/available-subjects?user_id=${props.form.user_id}`
            )
            .then((response) => {
                availableSubjects.value = response.data;
            });
    } else {
        availableSubjects.value = props.subjects;
    }
};

watch(
    () => props.form.user_id,
    () => {
        fetchAvailableSubjects();
    }
);

onMounted(() => {
    availableSubjects.value = props.subjects;
});
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Asignación" : "Nueva Asignación" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Asignación de Docente"
                    : "Crear Una Nueva Asignación de Docente a Asignatura"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="user_id" value="Docente" />
                <SelectInput
                    id="user_id"
                    v-model="form.user_id"
                    :options="teachers"
                    class="mt-1 block w-full"
                    required
                >
                    <option value="">Seleccione un docente</option>
                    <option
                        v-for="teacher in teachers"
                        :key="teacher.id"
                        :value="teacher.id"
                    >
                        {{ teacher.name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.user_id"
                    class="mt-2"
                />
            </div>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="subject_id" value="Asignatura" />
                <SelectInputSubject
                    id="subject_id"
                    v-model="form.subject_id"
                    :options="availableSubjects"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.subject_id"
                    class="mt-2"
                />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Asignar" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
