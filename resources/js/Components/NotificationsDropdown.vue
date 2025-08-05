<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Dropdown from '@/Components/Dropdown.vue';

const notifications = ref([]);
const today = new Date().toISOString().slice(0, 10);

const fetchNotifications = async () => {
    try {
        const response = await axios.get(route('notifications.index'));
        notifications.value = response.data.unread;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

onMounted(() => {
    fetchNotifications();
});

const newNotifications = computed(() => {
    return notifications.value.filter(n => n.data.birthday_date >= today);
});

const overdueNotifications = computed(() => {
    return notifications.value.filter(n => n.data.birthday_date < today);
});

const markAsRead = async (notification) => {
    try {
        await axios.patch(route('notifications.read', { notification: notification.id }));
        notifications.value = notifications.value.filter(n => n.id !== notification.id);
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
};
</script>

<template>
    <div class="relative">
        <Dropdown align="right" width="96">
            <template #trigger>
                <span class="inline-flex rounded-md">
                    <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none relative">
                        <!-- Icono de Campana -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <!-- Badge de Notificaciones -->
                        <span v-if="notifications.length > 0" class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                            {{ notifications.length }}
                        </span>
                    </button>
                </span>
            </template>

            <template #content>
                <div class="p-2">
                    <h3 class="text-sm font-semibold text-gray-700 px-2">Notificaciones</h3>
                    
                    <!-- Notificaciones Nuevas -->
                    <div v-if="newNotifications.length > 0" class="mt-2">
                        <h4 class="text-xs font-bold text-gray-500 uppercase px-2">Nuevas</h4>
                        <div v-for="notification in newNotifications" :key="notification.id" class="mt-1 p-2 text-sm text-gray-800 rounded-md hover:bg-gray-100">
                            <p>{{ notification.data.message }}</p>
                            <button @click.stop="markAsRead(notification)" class="text-xs text-blue-500 hover:underline mt-1">Marcar como visto</button>
                        </div>
                    </div>

                    <!-- Notificaciones Vencidas -->
                    <div v-if="overdueNotifications.length > 0" class="mt-4">
                        <h4 class="text-xs font-bold text-red-500 uppercase px-2">Vencidas</h4>
                         <div v-for="notification in overdueNotifications" :key="notification.id" class="mt-1 p-2 text-sm text-gray-800 bg-red-50 rounded-md hover:bg-red-100">
                            <p>{{ notification.data.message }}</p>
                            <button @click.stop="markAsRead(notification)" class="text-xs text-blue-500 hover:underline mt-1">Marcar como visto</button>
                        </div>
                    </div>

                    <div v-if="notifications.length === 0" class="p-4 text-center text-sm text-gray-500">
                        No tienes notificaciones nuevas.
                    </div>
                </div>
            </template>
        </Dropdown>
    </div>
</template>
