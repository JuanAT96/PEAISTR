<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            name: "",
            permissions: [],
        }),
    },
});

defineEmits(["submit"]);
</script>

<template>
    <!--
    <pre>{{ JSON.stringify($page.props.permissions, null, 2) }}</pre>
    -->
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Rol" : "Crear Nuevo Rol" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar el Rol Seleccionado"
                    : "Crear un Nuevo Rol Desde Cero"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Nombre del Rol" />
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
                <InputLabel value="Permisos" />
                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        v-for="permission in $page.props.permissions"
                        :key="permission.id"
                    >
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                :value="permission.id"
                                v-model="form.permissions"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />
                            <span class="ml-2 text-sm text-gray-600">{{
                                permission.name
                            }}</span>
                        </label>
                    </div>
                </div>
                <InputError
                    :message="$page.props.errors.permissions"
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
