<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Define el formulario con un campo 'file' inicializado a null
const form = useForm({
    file: null,
});

// Función para manejar el envío del formulario
const submit = () => {
    // Envía el formulario a la ruta 'customers.import.store'
    // Inertia.js maneja automáticamente la subida de archivos si el campo es de tipo 'file'
    form.post(route('customers.import.store'), {
        onSuccess: () => {
            // Si la importación es exitosa, resetea el campo 'file' del formulario
            form.reset('file');
            // La redirección con mensaje de éxito ya se maneja en el controlador
        },
        onError: (errors) => {
            // Si hay errores, los imprime en la consola
            console.error('Error de importación:', errors);
            // Los errores de validación de Laravel Excel (si los hay)
            // se pasarán a través de `form.errors.import_errors` y se mostrarán en la vista
        },
        // Esto es importante para que Inertia sepa que estás subiendo un archivo
        forceFormData: true,
    });
};

// Función para actualizar el campo 'file' del formulario cuando el usuario selecciona un archivo
const handleFileChange = (e) => {
    form.file = e.target.files[0]; // Asigna el primer archivo seleccionado al campo 'file' del formulario
};
</script>

<template>
    <Head title="Importar Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Importar Clientes desde Excel</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Seleccionar archivo Excel/CSV:</label>
                            <input
                                type="file"
                                id="file"
                                @change="handleFileChange"
                                class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100"
                            />
                            <!-- Muestra errores de validación generales del campo 'file' -->
                            <InputError :message="form.errors.file" class="mt-2" />
                            <!-- Muestra errores específicos de la importación (ej. validaciones de fila de Laravel Excel) -->
                            <div v-if="form.errors.import_errors" class="mt-2 text-red-600 text-sm">
                                <p v-for="(error, index) in form.errors.import_errors" :key="index">{{ error }}</p>
                            </div>
                        </div>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ form.processing ? 'Importando...' : 'Subir y Procesar' }}
                        </PrimaryButton>
                    </form>

                    <!-- Mensajes flash de éxito o error desde el controlador -->
                    <div v-if="$page.props.flash.success" class="mt-4 p-3 bg-green-100 text-green-700 rounded-md">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash.error" class="mt-4 p-3 bg-red-100 text-red-700 rounded-md">
                        {{ $page.props.flash.error }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>