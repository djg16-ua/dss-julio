# Requisitos del Sistema
PHP: 8.1 o superior
Composer: 2.0 o superior
Node.js: 16.0 o superior
NPM: 8.0 o superior
Base de datos: MySQL 8.0+ / PostgreSQL 12+ / SQLite 3.8+

# Instalación
1. Instalar dependencias de Composer
composer install

2. Copiar archivo de configuración
cp .env.example .env

3. Generar clave de aplicación
php artisan key:generate

4. Configurar Base de Datos
Editar el archivo .env con la configuración de tu base de datos:
envDB_CONNECTION = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_DATABASE = dss_julio
DB_USERNAME = tu_usuario
DB_PASSWORD = tu_contraseña

5. Ejecutar Migraciones
php artisan migrate

6. Ejecutar seeders
php artisan db:seed

7. Ejecutar proyecto
php artisan serve


# Estructura del Proyecto
dss-julio/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md


# Documentación

Laravel: https://laravel.com/docs
API: Documentación en /docs (si implementado)
Postman Collection: docs/api-collection.json

# Licencia
Este proyecto está bajo la licencia MIT. Ver archivo LICENSE para detalles.
