<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    customer: Object, // El objeto cliente que se pasa desde el controlador
});

const form = useForm({
    customer_id: props.customer.customer_id,
    full_name: props.customer.full_name,
    email: props.customer.email,
    mobile_phone: props.customer.mobile_phone,
    birthday: props.customer.birthday, // Nuevo campo
    telemarketing_observations: props.customer.telemarketing_observations, // Nuevo campo
    advisor_observations: props.customer.advisor_observations, // Nuevo campo
    // Asegúrate de inicializar todos los campos editables aquí
});

const submit = () => {
    form.put(route('customers.update', props.customer.id), {
        onSuccess: () => {
            // Puedes redirigir o mostrar un mensaje de éxito
        },
        onError: (errors) => {
            console.error('Error al actualizar cliente:', errors);
        },
    });
};
</script>

<template>
    <Head :title="`Editar Cliente: ${form.full_name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Cliente: {{ form.full_name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="customer_id" value="ID Cliente" />
                                <TextInput
                                    id="customer_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.customer_id"
                                    required
                                />
                                <InputError :message="form.errors.customer_id" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="full_name" value="Nombre Completo" />
                                <TextInput
                                    id="full_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.full_name"
                                    required
                                />
                                <InputError :message="form.errors.full_name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Correo Electrónico" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="mobile_phone" value="Teléfono Móvil" />
                                <TextInput
                                    id="mobile_phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.mobile_phone"
                                />
                                <InputError :message="form.errors.mobile_phone" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="birthday" value="Fecha de Cumpleaños" />
                                <TextInput
                                    id="birthday"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.birthday"
                                />
                                <InputError :message="form.errors.birthday" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="telemarketing_observations" value="Observaciones Telemarketing" />
                                <textarea
                                    id="telemarketing_observations"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.telemarketing_observations"
                                ></textarea>
                                <InputError :message="form.errors.telemarketing_observations" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="advisor_observations" value="Observaciones Asesor" />
                                <textarea
                                    id="advisor_observations"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.advisor_observations"
                                ></textarea>
                                <InputError :message="form.errors.advisor_observations" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Cliente
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
