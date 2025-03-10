<script>
export default {
    name: "SubjectRequisiteForm",
};
</script>

<script setup>
import { ref, watch, onMounted } from "vue";
import axios from "axios";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInputSubjectCode.vue";

const props = defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            subject_id: null,
            prerequisites: null,
            corequisites: null,
        }),
    },
    subjects: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["submit"]);
const availablePrerequisites = ref([]);
const availableCorequisites = ref([]);

const fetchAvailableSubjects = () => {
    axios
        .get(`/api/subjectrequisites/available-subjects`, {
            params: {
                subject_id: props.form.subject_id,
                //prerequisites: props.form.prerequisites,
                //corequisites: props.form.corequisites,
                // requisiteId: props.updating ? props.form.id : null,
            },
        })
        .then((response) => {
            availablePrerequisites.value = response.data;
            availableCorequisites.value = response.data;
        });
};

watch(
    () => props.form.subject_id,
    () => {
        fetchAvailableSubjects();
    }
);

watch(
    () => props.form.prerequisites,
    () => {
        fetchAvailableSubjects();
    }
);

watch(
    () => props.form.corequisites,
    () => {
        fetchAvailableSubjects();
    }
);

onMounted(() => {
    fetchAvailableSubjects();
});
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Requisito" : "Crear Nuevo Requisito" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar El Requisito Seleccionado"
                    : "Crear Un Nuevo Requisito de Asignatura"
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
                <InputLabel for="prerequisites" value="Prerrequisito" />
                <SelectInput
                    id="prerequisites"
                    v-model="form.prerequisites"
                    :options="availablePrerequisites"
                    class="mt-1 block w-full"
                >
                    <option
                        v-for="subject in availablePrerequisites"
                        :key="subject.id"
                        :value="subject.id"
                    >
                        {{ subject.subject_name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.prerequisites"
                    class="mt-2"
                />
            </div>
            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="corequisites" value="Correquisito" />
                <SelectInput
                    id="corequisites"
                    v-model="form.corequisites"
                    :options="availableCorequisites"
                    class="mt-1 block w-full"
                >
                    <option
                        v-for="subject in availableCorequisites"
                        :key="subject.id"
                        :value="subject.id"
                    >
                        {{ subject.subject_name }}
                    </option>
                </SelectInput>
                <InputError
                    :message="$page.props.errors.corequisites"
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
