# DSS Julio - Proyecto Laravel

## Descripción

Proyecto desarrollado con Laravel como parte del curso de Desarrollo de Software Seguro (DSS). Esta aplicación web implementa un sistema de gestión de proyectos con equipos, módulos, tareas y comentarios.

## Requisitos del Sistema

- **PHP**: 8.1 o superior
- **Composer**: 2.0 o superior  
- **Node.js**: 16.0 o superior
- **NPM**: 8.0 o superior
- **Base de datos**: MySQL 8.0+ / PostgreSQL 12+ / SQLite 3.8+
- **Git**: Para clonar el repositorio

## Instalación

### 1. Verificar Requisitos

```bash
# Verificar versión de PHP
php --version

# Verificar Composer
composer --version

# Verificar Node.js y NPM
node --version
npm --version
```

### 2. Clonar el Repositorio

```bash
git clone https://github.com/djg16-ua/dss-julio.git
cd dss-julio
```

### 3. Instalar Dependencias

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 4. Configurar Variables de Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configurar Base de Datos

Editar el archivo `.env` con tu configuración de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dss_julio
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Preparar Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (opcional)
php artisan db:seed
```

### 7. Compilar Assets Frontend

```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

### 8. Configurar Permisos (Linux/macOS)

```bash
# Dar permisos de escritura
chmod -R 775 storage bootstrap/cache

# Si usas servidor web (Apache/Nginx)
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 9. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## Comandos Útiles

### Desarrollo Diario
```bash
# Iniciar servidor de desarrollo
php artisan serve

# Compilar assets en modo desarrollo (con watch)
npm run dev

# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Rollback de migraciones
php artisan migrate:rollback

# Refrescar BD (rollback + migrate + seed)
php artisan migrate:refresh --seed

# Ver estado de migraciones
php artisan migrate:status
```

### Cache y Optimización
```bash
# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Artisan Generators
```bash
# Crear controlador
php artisan make:controller NombreController

# Crear modelo con migración
php artisan make:model NombreModelo -m

# Crear seeder
php artisan make:seeder NombreSeeder

# Crear middleware
php artisan make:middleware NombreMiddleware
```

## Solución de Problemas

### Problemas Comunes

**Error 500 - Internal Server Error**
```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Verificar permisos
chmod -R 775 storage bootstrap/cache
```

**Error de conexión a BD**
```bash
# Verificar configuración en .env
php artisan config:clear

# Probar conexión
php artisan tinker
>>> DB::connection()->getPdo();
```

**Assets no cargan**
```bash
# Recompilar assets
npm run build

# Limpiar cache del navegador
php artisan route:clear
```

**Problemas con Composer**
```bash
# Limpiar cache
composer clear-cache
composer install --no-cache
```

## Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=TestName

# Tests con coverage
php artisan test --coverage
```

## Contribución

1. Fork del repositorio
2. Crear rama: `git checkout -b feature/nueva-funcionalidad`
3. Commit: `git commit -am 'Añadir nueva funcionalidad'`
4. Push: `git push origin feature/nueva-funcionalidad`
5. Crear Pull Request

### Estándares de Código
- Seguir PSR-12 para PHP
- Usar PHPDoc para documentar
- Escribir tests para nuevas funcionalidades
- Seguir convenciones de Laravel

## Documentación

- **Laravel**: https://laravel.com/docs
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **Blade Templates**: https://laravel.com/docs/blade

## Licencia

Este proyecto está bajo la licencia MIT. Ver archivo `LICENSE` para detalles.

## Contacto

- **Autor**: [Tu nombre]
- **Email**: [tu-email@ua.es]
- **Universidad**: Universidad de Alicante
- **Curso**: Desarrollo de Software Seguro (DSS)

---

**⚡ Quick Start:**
```bash
git clone https://github.com/djg16-ua/dss-julio.git
cd dss-julio
composer install && npm install
cp .env.example .env
php artisan key:generate
# Configurar BD en .env
php artisan migrate
php artisan serve
```
