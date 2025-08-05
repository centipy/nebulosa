<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

const props = defineProps({
    customers: Object,
    filters: Object,
});
// Estado reactivo para el campo de búsqueda
const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(route('customers.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));
// Función para formatear fechas de manera robusta
const formatDate = (dateString) => {
    if (!dateString || typeof dateString !== 'string') {
        return 'N/A';
    }
    const parts = dateString.split('-');
    if (parts.length !== 3) {
        return 'Fecha no válida';
    }
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10);
    const day = parseInt(parts[2], 10);
    if (isNaN(year) || isNaN(month) || isNaN(day)) {
        return 'Fecha no válida';
    }
    const date = new Date(Date.UTC(year, month - 1, day));
    if (isNaN(date.getTime())) {
        return 'Fecha no válida';
    }
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC'
    };
    return date.toLocaleDateString('es', options);
};
// Navegación de paginación
const goToPage = (url) => {
    if (url) {
        router.get(url, { search: search.value }, { preserveState: true, replace: true });
    }
};
//Verifica si la visita está pendiente (hoy o pasada)
const isVisitPending = (customer) => {
    if (!customer.next_visit) return true; // Si nunca se ha agendado, se puede marcar
    const nextVisitDate = new Date(customer.next_visit);
    const today = new Date();
    // La visita está pendiente si la fecha de próxima visita es hoy o ya pasó
    return nextVisitDate <= today;
};
// Confirma la visita y actualiza las fechas
const confirmVisit = (customer) => {
    if (!customer.visit_confirmed) {
        router.patch(route('customers.confirmVisit', customer.id), {}, {
            preserveScroll: true,
        });
    }
};

const confirmDelete = (customer) => {
    if (confirm(`¿Estás seguro de que quieres eliminar a ${customer.full_name}?`)) {
        router.delete(route('customers.destroy', customer.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar clientes..."
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-1/3"
                        />
                        <div class="flex space-x-2">
                            <a :href="route('customers.export')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Descargar Excel
                            </a>
                            <Link :href="route('customers.import.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Subir Excel
                            </Link>
                            <Link :href="route('customers.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Añadir Cliente
                            </Link>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo Electrónico</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono Móvil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Nacimiento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obs. Telemarketing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obs. Asesor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Visita</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Próxima Visita</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Visita Realizada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="customers.data.length === 0">
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay clientes para mostrar.
                                    </td>
                                </tr>
                                <tr v-for="customer in customers.data" :key="customer.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.customer_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ customer.full_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ customer.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ customer.mobile_phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(customer.birthday) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate">{{ customer.telemarketing_observations }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate">{{ customer.advisor_observations }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(customer.last_visit) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(customer.next_visit) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input
                                            type="checkbox"
                                            :checked="customer.visit_confirmed"
                                            @change="confirmVisit(customer)"
                                            :disabled="customer.visit_confirmed"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 disabled:opacity-50"
                                        />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <Link :href="route('customers.show', customer.id)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Ver</Link>
                                            <Link :href="route('customers.edit', customer.id)" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">Editar</Link>
                                            <button @click="confirmDelete(customer)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <nav v-if="customers.links.length > 3" class="mt-4 flex justify-between items-center" aria-label="Pagination">
                        <div class="flex-1 flex justify-between sm:justify-end">
                            <button
                                @click="goToPage(customers.prev_page_url)"
                                :disabled="!customers.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Anterior
                            </button>
                            <button
                                @click="goToPage(customers.next_page_url)"
                                :disabled="!customers.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Siguiente
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
