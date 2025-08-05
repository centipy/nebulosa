<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3'; // Importar router
import { Bar, Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement);

const props = defineProps({
    dashboardData: {
        type: Object,
        default: () => null,
    },
});

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};

const formatCurrency = (value) => {
    if (typeof value !== 'number') {
        value = parseFloat(value);
    }
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

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

const formatBirthday = (dateString) => {
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
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC'
    };
    return date.toLocaleDateString('es', options);
};

// --- FUNCIÓN FALTANTE AÑADIDA AQUÍ ---
const confirmDashboardVisit = (customer) => {
    router.patch(route('customers.confirmVisit', customer.id), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard de Cartera</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <div v-if="dashboardData && dashboardData.pendingVisits.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Visitas Pendientes (Próximos 3 días)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Visita</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="visit in dashboardData.pendingVisits" :key="visit.id">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ visit.full_name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ formatDate(visit.next_visit) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        <button @click="confirmDashboardVisit(visit)" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs font-semibold">
                                            Confirmar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Sección de Cumpleaños del Mes -->
                <div v-if="dashboardData && dashboardData.monthlyBirthdays.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">🎂 Cumpleaños de este Mes</h3>
                    <ul class="divide-y divide-gray-200">
                        <li v-for="client in dashboardData.monthlyBirthdays" :key="client.full_name" class="py-3 flex justify-between items-center text-sm">
                            <span class="text-gray-700">{{ client.full_name }}</span>
                            <span class="font-semibold text-gray-800">{{ formatBirthday(client.birthday) }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Contenedor para los KPIs -->
                <div v-if="dashboardData" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <h3 class="text-gray-500 text-sm font-medium">Total de Clientes</h3>
                        <p class="text-3xl font-bold mt-2">{{ dashboardData.totalClients }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <h3 class="text-gray-500 text-sm font-medium">Saldo Total de Cartera</h3>
                        <p class="text-3xl font-bold mt-2">${{ dashboardData.totalBalance }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <h3 class="text-gray-500 text-sm font-medium">Cartera Vencida (>30d)</h3>
                        <p class="text-3xl font-bold mt-2 text-red-500">${{ dashboardData.overdueBalance }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <h3 class="text-gray-500 text-sm font-medium">Índice de Morosidad</h3>
                        <p class="text-3xl font-bold mt-2 text-red-500">{{ dashboardData.delinquencyRate }}%</p>
                    </div>
                </div>

                <!-- Contenedor para el Gráfico de Aging -->
                <div v-if="dashboardData" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                     <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Distribución de Cartera (Aging)</h3>
                        <div class="h-96">
                            <Bar :data="dashboardData.agingChartData" :options="barChartOptions" />
                        </div>
                    </div>
                </div>

                <!-- Contenedor para las tablas y el nuevo gráfico -->
                <div v-if="dashboardData" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top 20 Clientes por Saldo</h3>
                        <ul class="divide-y divide-gray-200">
                            <li v-for="client in dashboardData.topClients" :key="client.full_name" class="py-3 flex justify-between items-center text-sm">
                                <span class="text-gray-700">{{ client.full_name }}</span>
                                <span class="font-semibold text-gray-800">{{ formatCurrency(client.current_balance) }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Clientes por Nivel</h3>
                            <div class="h-64">
                                <Doughnut :data="dashboardData.levelDistributionChartData" :options="doughnutChartOptions" />
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Top 5 Ciudades</h3>
                             <ul class="divide-y divide-gray-200">
                                <li v-for="city in dashboardData.topCities" :key="city.city" class="py-3 flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ city.city }}</span>
                                    <span class="font-semibold text-gray-800">{{ city.total }} clientes</span>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Top 5 Estados</h3>
                             <ul class="divide-y divide-gray-200">
                                <li v-for="state in dashboardData.topStates" :key="state.state" class="py-3 flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ state.state }}</span>
                                    <span class="font-semibold text-gray-800">{{ state.total }} clientes</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        No hay datos de clientes para mostrar en el dashboard.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
