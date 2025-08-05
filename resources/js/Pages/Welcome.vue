<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-950 text-white flex flex-col items-center justify-center relative overflow-hidden">
        <header class="absolute top-0 left-0 right-0 p-8 flex justify-between items-center z-10">
            <div class="text-2xl font-bold">NebulosaCRM</div>
            <Link :href="route('login')" class="text-lg hover:underline hover:text-gray-300 transition duration-300">Iniciar Sesión -</Link>
        </header>

        <div class="text-center px-4 py-20">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 max-w-4xl mx-auto">
                Gestión de Clientes Inteligente y Eficaz
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl text-gray-300 mb-10 max-w-3xl mx-auto">
                Optimice sus relaciones con los clientes con una plataforma que integra datos, automatización y una experiencia de usuario excepcional.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <Link href="#" class="px-8 py-4 bg-yellow-500 text-gray-900 font-bold rounded-lg shadow-lg hover:bg-yellow-400 transition duration-300 text-lg whitespace-nowrap">
                    Solicitar Demo
                </Link>
                <Link href="#" class="px-8 py-4 border border-white text-white font-bold rounded-lg hover:bg-white hover:text-gray-900 transition duration-300 text-lg whitespace-nowrap">
                    Descubrir Funcionalidades -
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
/*... (tus estilos existentes)... */
</style>