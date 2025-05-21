<script setup>
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage, router } from '@inertiajs/vue3';

// Recibe props desde Laravel con tareas y datos de usuario
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

// Variable para guardar el ID de la tarea que se está editando
const editingTaskId = ref(null);

// Datos para edición de tarea, reactivos para que Vue los actualice en UI
const editForm = reactive({
  title: '',
  description: ''
});

// Enviar formulario para crear nueva tarea
const submit = () => {
  form.post('/tasks', {
    onSuccess: () => {
      form.reset(); // Limpia formulario al enviar con éxito
    }
  });
};

// Iniciar edición de una tarea: carga datos en formulario de edición
const startEdit = (task) => {
  editingTaskId.value = task.id;
  editForm.title = task.title;
  editForm.description = task.description || '';
};

// Cancelar edición actual
const cancelEdit = () => {
  editingTaskId.value = null;
};

// Guardar cambios en tarea editada (actualizar)
const updateTask = () => {
  router.put(`/tasks/${editingTaskId.value}`, {
    title: editForm.title,
    description: editForm.description,
  }, {
    onSuccess: () => {
      editingTaskId.value = null; // Termina edición y actualiza vista
    }
  });
};

// Eliminar tarea con confirmación de usuario
const deleteTask = (taskId) => {
  if (confirm('¿Seguro que quieres eliminar esta tarea?')) {
    router.delete(`/tasks/${taskId}`);
  }
};
</script>
