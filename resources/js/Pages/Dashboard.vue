<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Dashboard" />

  <div class="py-12 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Bienvenido</h1>
    <p class="mb-6 text-gray-600">Desde aquí puedes gestionar tus tareas.</p>
    <Link
      href="/tasks"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
    >
      Ir a Mis Tareas
    </Link>
  </div>
</template>
