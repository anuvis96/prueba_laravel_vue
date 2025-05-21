<script setup>
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

// Recibe props con tareas, usuario autenticado y errores desde Laravel/Inertia
const props = defineProps({
  tasks: Array,
  auth: Object,
  errors: Object
});

// Formulario reactivo para crear nueva tarea
const form = useForm({
  title: '',
  description: ''
});

// Variable para almacenar la tarea que se está editando (su ID)
const editingTaskId = ref(null);

// Formulario reactivo para editar tarea existente
const editForm = reactive({
  title: '',
  description: ''
});

// Envía formulario para crear nueva tarea vía POST a /tasks
const submit = () => {
  form.post('/tasks', {
    onSuccess: () => {
      form.reset(); // Limpia formulario después de crear tarea
    }
  });
};

// Carga los datos de la tarea para editarla
const startEdit = (task) => {
  editingTaskId.value = task.id;
  editForm.title = task.title;
  editForm.description = task.description || '';
};

// Cancela la edición actual
const cancelEdit = () => {
  editingTaskId.value = null;
};

// Envía los datos editados para actualizar la tarea vía PUT
const updateTask = () => {
  router.put(`/tasks/${editingTaskId.value}`, {
    title: editForm.title,
    description: editForm.description,
  }, {
    onSuccess: () => {
      editingTaskId.value = null; // Sale del modo edición
    }
  });
};

// Elimina una tarea con confirmación previa
const deleteTask = (taskId) => {
  if (confirm('¿Seguro que quieres eliminar esta tarea?')) {
    router.delete(`/tasks/${taskId}`);
  }
};
</script>

<template>
  <AuthenticatedLayout :user="auth.user">
    <Head title="Mis Tareas" />

    <div class="py-12 max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Mis Tareas</h1>

      <!-- Formulario para crear nueva tarea -->
      <form @submit.prevent="submit" class="mb-8 bg-white shadow p-6 rounded">
        <div class="mb-4">
          <label class="block font-semibold">Título</label>
          <input
            v-model="form.title"
            type="text"
            class="w-full border p-2 rounded mt-1"
            :class="{ 'border-red-500': errors.title }"
          />
          <div v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</div>
        </div>

        <div class="mb-4">
          <label class="block font-semibold">Descripción</label>
          <textarea
            v-model="form.description"
            class="w-full border p-2 rounded mt-1"
          ></textarea>
        </div>

        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded"
          :disabled="form.processing"
        >
          Crear Tarea
        </button>
      </form>

      <!-- Lista de tareas -->
      <div v-if="tasks.length > 0" class="space-y-4">
        <div
          v-for="task in tasks"
          :key="task.id"
          class="bg-white p-4 rounded shadow flex flex-col"
        >
          <!-- Modo edición -->
          <template v-if="editingTaskId === task.id">
            <input
              v-model="editForm.title"
              type="text"
              class="border p-2 rounded mb-2"
            />
            <textarea
              v-model="editForm.description"
              class="border p-2 rounded mb-2"
            ></textarea>
            <div>
              <button
                @click="updateTask"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded mr-2"
              >
                Guardar
              </button>
              <button
                @click="cancelEdit"
                class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded"
              >
                Cancelar
              </button>
            </div>
          </template>

          <!-- Vista normal -->
          <template v-else>
            <h2 class="font-bold text-lg">{{ task.title }}</h2>
            <p class="text-gray-600">{{ task.description }}</p>
            <div class="mt-2 flex space-x-2">
              <button
                @click="startEdit(task)"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
              >
                Editar
              </button>
              <button
                @click="deleteTask(task.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Eliminar
              </button>
            </div>
          </template>
        </div>
      </div>
      <div v-else class="text-gray-500">No tienes tareas aún.</div>
    </div>
  </AuthenticatedLayout>
</template>
