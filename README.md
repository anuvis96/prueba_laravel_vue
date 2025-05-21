# Aplicación de Gestión de Tareas con Laravel 10, Vue 3 e Inertia.js

Este proyecto es una aplicación web que permite a los usuarios autenticados crear, visualizar, editar y eliminar tareas. Está desarrollada utilizando Laravel 10, Vue 3 (Composition API), Inertia.js y MySQL, con autenticación proporcionada por Laravel Breeze.

🛠️ Tecnologías utilizadas: Laravel 10 (Backend), Vue 3 + Inertia.js (Frontend), MySQL (Base de datos), Laravel Breeze (Autenticación), Git (Control de versiones)

🚀 Instalación y configuración:

1. Clonar el repositorio  
git clone https://github.com/tu-usuario/nombre-del-repo.git  
cd nombre-del-repo

2. Instalar dependencias de PHP  
composer install

3. Instalar dependencias de Node.js  
npm install

4. Copiar archivo de entorno y generar clave de la app  
cp .env.example .env  
php artisan key:generate

5. Configurar base de datos en el archivo .env  
DB_DATABASE=nombre_de_tu_bd  
DB_USERNAME=tu_usuario  
DB_PASSWORD=tu_password

6. Ejecutar migraciones  
php artisan migrate

7. Compilar los assets  
npm run dev

8. Iniciar el servidor  
php artisan serve

✨ Funcionalidades:  
- Registro e inicio de sesión  
- Listado de tareas del usuario autenticado  
- Crear nuevas tareas  
- Editar tareas existentes  
- Eliminar tareas con confirmación

📂 Estructura de carpetas destacadas:  
- app/Http/Controllers/TaskController.php: lógica del CRUD  
- resources/js/Pages/Tasks/Index.vue: página del frontend con Vue 3  
- routes/web.php: rutas protegidas para tareas  
- database/migrations/: migraciones incluyendo la tabla tasks

✅ Autenticación:  
Este proyecto usa Laravel Breeze con Vue 3 + Inertia.js. Si necesitas reinstalar:

composer require laravel/breeze --dev  
php artisan breeze:install vue  
npm install && npm run dev  
php artisan migrate

🤝 Contribuciones:  
Este proyecto fue desarrollado como parte de una prueba técnica. No está abierto a contribuciones externas por el momento.

📄 Licencia:  
Este proyecto está bajo la licencia MIT.

🧠 Notas finales:  
- El CRUD está protegido por autenticación  
- Se usa authorize() en lugar de Policies para comprobar que el usuario es dueño de la tarea  
- El frontend es simple, funcional y no usa frameworks de estilos como Tailwind o Bootstrap
