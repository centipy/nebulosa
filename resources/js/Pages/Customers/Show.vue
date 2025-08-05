<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    customer: Object, // El objeto cliente que se pasa desde el controlador
});

// Función para formatear las etiquetas (keys) para que sean más legibles
const formatLabel = (key) => {
    // Reemplaza guiones bajos con espacios y capitaliza cada palabra
    return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

// Función para formatear los valores (maneja fechas, nulos, etc.)
const formatValue = (key, value) => {
    if (value === null || value === '') {
        return 'N/A';
    }
    // Si el campo es una fecha conocida, la formatea
    if (['birthday', 'date_increased', 'closing_date', 'original_order_date', 'last_purchase_date', 'last_payment_date', 'created_at', 'updated_at'].includes(key)) {
        const date = new Date(value);
        if (isNaN(date.getTime())) return value; // Si no es una fecha válida, devuelve el original
        return date.toLocaleDateString('es', { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'UTC' });
    }
    return value;
};

// Propiedad computada para filtrar y ordenar los campos que queremos mostrar
const displayableCustomer = computed(() => {
    if (!props.customer) return {};
    
    // Excluimos campos que no son útiles para el usuario final
    const excludedKeys = ['id'];
    
    return Object.entries(props.customer)
        .filter(([key]) => !excludedKeys.includes(key))
        .reduce((obj, [key, value]) => {
            obj[key] = value;
            return obj;
        }, {});
});
</script>

<template>
    <Head :title="`Detalles de Cliente: ${customer.full_name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalles de Cliente: {{ customer.full_name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="border-t border-gray-200">
                        <dl class="divide-y divide-gray-200">
                            <!-- Bucle dinámico para mostrar todos los campos del cliente -->
                            <div v-for="(value, key) in displayableCustomer" :key="key" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">{{ formatLabel(key) }}</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formatValue(key, value) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <Link :href="route('customers.edit', customer.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Cliente
                        </Link>
                        <Link :href="route('customers.index')" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Volver a Clientes
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
