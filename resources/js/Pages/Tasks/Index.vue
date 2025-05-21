<script setup>
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  tasks: Array,
  auth: Object,
  errors: Object
});

// Formulario para crear tarea nueva
const form = useForm({
  title: '',
  description: ''
});

// Para edición, guardamos el ID de la tarea que se está editando
const editingTaskId = ref(null);

// Formulario para editar tarea
const editForm = reactive({
  title: '',
  description: ''
});

// Enviar formulario de creación
const submit = () => {
  form.post('/tasks', {
    onSuccess: () => {
      form.reset();
    }
  });
};

// Empezar edición de una tarea: carga los datos en el formulario de edición
const startEdit = (task) => {
  editingTaskId.value = task.id;
  editForm.title = task.title;
  editForm.description = task.description || '';
};

// Cancelar edición
const cancelEdit = () => {
  editingTaskId.value = null;
};

// Guardar tarea editada
const updateTask = () => {
  router.put(`/tasks/${editingTaskId.value}`, {
    title: editForm.title,
    description: editForm.description,
  }, {
    onSuccess: () => {
      editingTaskId.value = null;
    }
  });
};

// Eliminar tarea
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

      <!-- Formulario de nueva tarea -->
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
