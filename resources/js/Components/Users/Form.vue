<script>
export default {
    name: "UsersForm",
};
</script>
<script setup>
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInputRoles.vue";

const props = defineProps({
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
        default: () => ({
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            role: "",
        }),
    },

    roles: {
        type: Object,
        required: true,
    },
});
console.log("Props roles:", props.roles);
console.log("Props form:", props.form);

defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? "Actualizar Usuario" : "Crear Nuevo Usuario" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar El Usuario Seleccionado"
                    : "Crear Un Nuevo Usuario Desde Cero"
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
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="email" value="Correo Electrónico" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel for="password" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.password"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-6 mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirmar Contraseña"
                />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    required
                />
                <InputError
                    :message="$page.props.errors.password_confirmation"
                    class="mt-2"
                />
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
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
