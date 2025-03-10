<script setup>
import { ref, watch, onMounted } from "vue";
import axios from "axios";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInputCareer from "@/Components/SelectInputCareer.vue";
import SelectInputSubject from "@/Components/SelectInputSubjectCode.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            career_id: "",
            subject_id: "",
            level: "",
        }),
    },
    careers: {
        type: Object,
        required: true,
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["submit"]);

const availableSubjects = ref([]);

const fetchAvailableSubjects = () => {
    if (props.form.career_id) {
        axios
            .get(
                `/api/careersubjects/available-subjects?career_id=${props.form.career_id}`
            )
            .then((response) => {
                availableSubjects.value = response.data;
            });
    } else {
        availableSubjects.value = props.subjects;
    }
};

watch(
    () => props.form.career_id,
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
            {{
                updating
                    ? "Actualizar Asignación de Asignatura a Carrera"
                    : "Crear Nueva Asignación de Asignatura a Carrera"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Asignación Seleccionada"
                    : "Crear Una Nueva Asignación Desde Cero"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="career_id" value="Carrera" />
                <SelectInputCareer
                    id="career_id"
                    v-model="form.career_id"
                    :options="careers"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.career_id"
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

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="level"
                    value="Nivel/Semestre (primero , segundo, ...)"
                />
                <TextInput
                    id="level"
                    v-model="form.level"
                    class="mt-1 block w-full"
                    required
                />

                <InputError :message="$page.props.errors.level" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
