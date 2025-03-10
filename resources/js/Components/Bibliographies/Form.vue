<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SelectInputSubject from "@/Components/SelectInputSubjectCode.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            type: "",
            author: "",
            year: "",
            title: "",
            editorial: "",
            format: "",
            code: "",
            link: "",
            subject_id: "",
            uplift: "",
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
                updating
                    ? "Actualizar Bibliografía"
                    : "Crear Nueva Bibliografía"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Bibliografía Seleccionada"
                    : "Crear Una Nueva Bibliografía Desde Cero"
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
                <InputLabel for="type" value="Tipo de Bibliografía" />
                <SelectInput
                    id="type"
                    v-model="form.type"
                    :options="[
                        'Libro',
                        'Capítulo de libro',
                        'Artículo de revista',
                        'Artículo de periódico',
                        'Sitio web',
                        'Informe',
                        'Tesis o disertación',
                        'Video',
                        'Podcast',
                        'Publicación en redes sociales',
                        'Otro',
                    ]"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.type" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="author" value="Autor" />
                <TextInput
                    id="author"
                    v-model="form.author"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.author" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="year" value="Año" />
                <TextInput
                    id="year"
                    v-model="form.year"
                    type="number"
                    min="1900"
                    max="2100"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.year" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="title" value="Título" />
                <TextInput
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.title" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="editorial" value="Editorial" />
                <TextInput
                    id="editorial"
                    v-model="form.editorial"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.editorial"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="format" value="Formato" />
                <SelectInput
                    id="format"
                    v-model="form.format"
                    :options="[
                        'Impreso',
                        'Digital',
                        'Audiovisual',
                        'En línea',
                        'Otro',
                    ]"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.format" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="code" value="Código del libro (opcional)" />
                <TextInput
                    id="code"
                    v-model="form.code"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="$page.props.errors.code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="link" value="Enlace del recurso (opcional)" />
                <TextInput
                    id="link"
                    v-model="form.link"
                    type="url"
                    class="mt-1 block w-full"
                />
                <InputError :message="$page.props.errors.link" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="uplift"
                    value="Sustentación científica técnica y didáctico adecuado de la bibliografía básica (Justificación)"
                />
                <TextareaInput
                    id="uplift"
                    v-model="form.uplift"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.uplift" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
