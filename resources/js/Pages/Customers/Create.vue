<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    customer_id: '',
    full_name: '',
    email: '',
    mobile_phone: '',
    birthday: '', // Nuevo campo
    telemarketing_observations: '', // Nuevo campo
    advisor_observations: '', // Nuevo campo
    // Añade aquí todos los campos que quieras que se puedan crear
    // Asegúrate de que coincidan con los `fillable` de tu modelo Customer
    // y las reglas de validación en CustomerController@store
});

const submit = () => {
    form.post(route('customers.store'), {
        onSuccess: () => {
            form.reset(); // Limpia el formulario después de un envío exitoso
        },
        onError: (errors) => {
            console.error('Error al crear cliente:', errors);
        },
    });
};
</script>

<template>
    <Head title="Crear Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Cliente</h2>
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
                                    autofocus
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
                                Crear Cliente
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
