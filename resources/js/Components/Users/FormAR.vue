<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInputRoles.vue";

defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        name: "",
        role: "",
    },
    roles: {
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
                updating ? "Actualizar Rol a Usuario" : "Asignar Rol a Usuario"
            }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar Rol al Usuario Seleccionado"
                    : "Asignar Rol al Usuario Seleccionado"
            }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Nombre" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    class="mt-1 block w-full text-xl font-bold bg-gradient-to-b from-[#bf1627] to-[#013a6a] text-transparent bg-clip-text cursor-not-allowed"
                    required
                    readonly
                    disabled
                />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="role" value="Rol" />
                <SelectInput
                    id="role"
                    v-model="form.role"
                    :options="roles"
                    class="mt-1 block w-full"
                    required
                >
                    <option value="">Seleccione un docente</option>
                    <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"
                    >
                        {{ role.name }}
                    </option>
                </SelectInput>
                <InputError :message="$page.props.errors.role" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Asignar" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
