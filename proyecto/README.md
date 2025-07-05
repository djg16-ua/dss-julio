# 🚀 TaskFlow - Sistema de Gestión de Proyectos

<div align="center">

**Desarrollo de Software Seguro (DSS) - Universidad de Alicante**

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

*Sistema completo de gestión de proyectos con autenticación segura, recuperación de contraseñas por email y dashboard interactivo*

[🔗 Demo](#-demo-en-vivo) • [📖 Documentación](#-documentación-y-referencias) • [🐛 Reportar Bug](#-contacto-y-soporte) • [💡 Solicitar Feature](#-contribución)

</div>

---

## 📋 Tabla de Contenidos

- [✨ Características](#-características)
- [🎯 Demo en Vivo](#-demo-en-vivo)
- [⚡ Quick Start](#-quick-start-para-desarrollo)
- [📋 Requisitos del Sistema](#-requisitos-del-sistema)
- [🚀 Instalación Completa](#-instalación-completa)
- [📧 Configurar Emails](#-configurar-emails-para-recuperación-de-contraseñas)
- [🗄️ Administrar BD con Adminer](#️-administrar-base-de-datos-con-adminer)
- [👤 Usuarios de Prueba](#-usuarios-de-prueba)
- [📱 Funcionalidades](#-funcionalidades-implementadas)

---

## ✨ Características

<table>
<tr>
<td width="50%">

### 🔐 **Autenticación Completa**
- ✅ Registro y login seguro
- ✅ Roles (ADMIN/USER)
- ✅ Logout automático
- ✅ Contraseñas hasheadas

### 📧 **Recuperación de Contraseña** 
- ✅ Emails reales con Brevo API
- ✅ Tokens seguros con expiración
- ✅ Templates HTML personalizados
- ✅ Fallback a logs

</td>
<td width="50%">

### 🎨 **Interfaz Moderna**
- ✅ Bootstrap 5 responsive
- ✅ Dashboard interactivo
- ✅ Tema consistente
- ✅ Iconografía profesional

### 🗄️ **Gestión de Datos**
- ✅ MySQL con Eloquent ORM
- ✅ Migraciones y seeders
- ✅ Adminer integrado
- ✅ Relaciones bien definidas

</td>
</tr>
</table>

---

## 🎯 Demo en Vivo

### 🖥️ **Capturas de Pantalla**

<details>
<summary>📸 Ver capturas de pantalla</summary>

| Landing Page | Dashboard | Login |
|:---:|:---:|:---:|
| ![Landing](https://via.placeholder.com/300x200/4f46e5/ffffff?text=Landing+Page) | ![Dashboard](https://via.placeholder.com/300x200/06b6d4/ffffff?text=Dashboard) | ![Login](https://via.placeholder.com/300x200/10b981/ffffff?text=Login) |

</details>

### 🔑 **Credenciales de Prueba**
```bash
# Usuario Admin
Email: admin@taskflow.com
Password: password

# Usuario Normal  
Email: user@taskflow.com
Password: password
```

---

## ⚡ Quick Start para Desarrollo

```bash
# 🚀 Instalación rápida (5 minutos)
git clone https://github.com/djg16-ua/dss-julio.git
cd dss-julio
composer install && npm install
cp .env.example .env && php artisan key:generate

# ⚙️ Configurar BD en .env
# DB_DATABASE=taskflow_db
# DB_USERNAME=tu_usuario  
# DB_PASSWORD=tu_contraseña

# 🗄️ Preparar base de datos
php artisan migrate --seed

# 🌐 Iniciar servidor
php artisan serve

# ✅ Listo! Visitar: http://localhost:8000
```

<details>
<summary>🔧 Instalación opcional de Adminer</summary>

```bash
# Instalar Adminer para gestión de BD
mkdir -p public/adminer
curl -L https://www.adminer.org/latest.php -o public/adminer/index.php
# Acceder: http://localhost:8000/adminer
```

</details>

---

## 📋 Requisitos del Sistema

| Componente | Versión Mínima | Comando de Verificación |
|------------|----------------|-------------------------|
| **PHP** | 8.1+ | `php --version` |
| **Composer** | 2.0+ | `composer --version` |
| **Node.js** | 16.0+ | `node --version` |
| **NPM** | 8.0+ | `npm --version` |
| **MySQL** | 8.0+ | `mysql --version` |
| **Git** | 2.0+ | `git --version` |

---

## 🚀 Instalación Completa

<details>
<summary>📂 1. Clonar Repositorio</summary>

```bash
git clone https://github.com/djg16-ua/dss-julio.git
cd dss-julio
```

</details>

<details>
<summary>📦 2. Instalar Dependencias</summary>

```bash
# Backend (PHP)
composer install

# Frontend (Node.js)
npm install
```

</details>

<details>
<summary>⚙️ 3. Configurar Entorno</summary>

```bash
# Copiar configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

Editar `.env` con tu configuración:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

</details>

<details>
<summary>🗄️ 4. Preparar Base de Datos</summary>

```bash
# Ejecutar migraciones
php artisan migrate

# Cargar datos de prueba (opcional)
php artisan db:seed
```

</details>

<details>
<summary>🎨 5. Compilar Assets</summary>

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

</details>

<details>
<summary>🔐 6. Configurar Permisos (Linux/macOS)</summary>

```bash
# Permisos de escritura
chmod -R 775 storage bootstrap/cache

# Para servidor web
sudo chown -R www-data:www-data storage bootstrap/cache
```

</details>

<details>
<summary>🌐 7. Iniciar Servidor</summary>

```bash
php artisan serve
```

**Aplicación disponible en:** `http://localhost:8000`

</details>

---

## 📧 Configurar Emails para Recuperación de Contraseñas

### 📨 Emails Reales con Brevo**

<details>
<summary>⚙️ Configuración paso a paso</summary>

#### 1️⃣ **Crear cuenta gratuita**
- Visitar: [brevo.com](https://www.brevo.com)
- Registrarse (300 emails/día gratis)

#### 2️⃣ **Obtener API Key**
- Dashboard → Settings → API Keys
- Create new API key
- **⚠️ Importante**: Copiar key que empieza con `xkeysib-`

#### 3️⃣ **Configurar en .env**
```env
BREVO_API_KEY=xkeysib-tu-key-aqui
```

#### 4️⃣ **Aplicar cambios**
```bash
php artisan config:clear
```

</details>

---

## 🗄️ Administrar Base de Datos con Adminer

### 📥 **Instalación**

<details>
<summary>🔽 Método automático (recomendado)</summary>

```bash
# Crear directorio
mkdir -p public/adminer

# Descargar Adminer
curl -L https://www.adminer.org/latest.php -o public/adminer/index.php
```

</details>

### 🔌 **Conexión**

1. **Acceder**: `http://localhost:8000/adminer`
2. **Credenciales**:
   - **Sistema**: MySQL
   - **Servidor**: 127.0.0.1:3306
   - **Usuario**: tu_usuario (del .env)
   - **Contraseña**: tu_contraseña (del .env)
   - **Base de datos**: taskflow_db

### ⚠️ **Seguridad**
```bash
# ⚠️ ELIMINAR en producción
rm -rf public/adminer/
```

---

## 👤 Usuarios de Prueba

### 🔑 **Crear via Tinker**

<details>
<summary>👨‍💼 Usuario Administrador</summary>

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@taskflow.com',
    'password' => 'password123',
    'role' => 'ADMIN'
]);
```

</details>

<details>
<summary>👤 Usuario Normal</summary>

```php
\App\Models\User::create([
    'name' => 'Usuario Normal',
    'email' => 'user@taskflow.com', 
    'password' => 'password123',
    'role' => 'USER'
]);
```

</details>

---

## 📱 Funcionalidades Implementadas

### 🔐 **Sistema de Autenticación**
| Funcionalidad | Ruta | Descripción |
|---------------|------|-------------|
| **Registro** | `/register` | Crear cuenta nueva |
| **Login** | `/login` | Iniciar sesión |
| **Dashboard** | `/dashboard` | Panel principal |
| **Logout** | - | Dropdown en navbar |

### 🔄 **Recuperación de Contraseña**
| Funcionalidad | Ruta | Descripción |
|---------------|------|-------------|
| **Solicitar Reset** | `/forgot-password` | Enviar email de recuperación |
| **Reset Password** | `/reset-password/{token}` | Cambiar contraseña |

### 📄 **Páginas Públicas**
| Página | Ruta | Descripción |
|--------|------|-------------|
| **Inicio** | `/` | Landing page |
| **Sobre Nosotros** | `/about` | Historia del proyecto |
| **Contacto** | `/contact` | Formulario de contacto |


## 🛠️ Comandos Útiles para Desarrollo

<details>
<summary>⚡ Desarrollo Diario</summary>

```bash
# Servidor de desarrollo
php artisan serve

# Assets con watch
npm run dev

# Logs en tiempo real
tail -f storage/logs/laravel.log
```

</details>

<details>
<summary>🗄️ Base de Datos</summary>

```bash
# Migraciones
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh --seed
php artisan migrate:status
```

</details>

<details>
<summary>🧹 Cache y Optimización</summary>

```bash
# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
```

</details>

<details>
<summary>🔧 Artisan Generators</summary>

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

</details>

---

## 🔧 Solución de Problemas

<details>
<summary>🚨 Error 500 - Internal Server Error</summary>

```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Verificar permisos
chmod -R 775 storage bootstrap/cache
```

</details>

<details>
<summary>🗄️ Error de conexión a BD</summary>

```bash
# Verificar configuración
php artisan config:clear

# Probar conexión
php artisan tinker
>>> DB::connection()->getPdo();
```

</details>

<details>
<summary>📧 Emails no llegan</summary>

```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Verificar API Key
php artisan tinker
>>> env('BREVO_API_KEY')
```

</details>

<details>
<summary>🎨 Assets no cargan</summary>

```bash
# Recompilar assets
npm run build

# Limpiar cache del navegador
# Ctrl+F5 o Cmd+Shift+R
```

</details>

<details>
<summary>📦 Problemas con Composer</summary>

```bash
# Limpiar cache
composer clear-cache
composer install --no-cache
```

</details>

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=TestName

# Tests con coverage
php artisan test --coverage

# Tests con detalles
php artisan test --verbose
```

---

## 🔗 Tecnologías Utilizadas

<div align="center">

| Backend | Frontend | Database | Email | Tools |
|:---:|:---:|:---:|:---:|:---:|
| ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) | ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white) | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white) | ![Brevo](https://img.shields.io/badge/Brevo-0092FF?style=for-the-badge&logo=mail.ru&logoColor=white) | ![Adminer](https://img.shields.io/badge/Adminer-34495E?style=for-the-badge&logo=database&logoColor=white) |
| PHP 8.1+ | Blade + CSS | Eloquent ORM | API 300/día | Web Interface |

</div>

### 🔧 **Stack Técnico Detallado**

- **Backend**: Laravel 10+ con PHP 8.1+
- **Frontend**: Bootstrap 5 + Blade Templates + CSS personalizado
- **Base de datos**: MySQL con Eloquent ORM
- **Emails**: Brevo API (300 emails/día gratis)
- **Autenticación**: Laravel nativo con middleware
- **Gestión BD**: Adminer (interfaz web)
- **Build tools**: Vite + NPM
- **Estilos**: Bootstrap Icons + Inter Font

---

## 🤝 Contribución

<details>
<summary>📝 Proceso de contribución</summary>

1. **Fork** del repositorio
2. **Crear rama**: `git checkout -b feature/nueva-funcionalidad`
3. **Commit**: `git commit -am 'Añadir nueva funcionalidad'`
4. **Push**: `git push origin feature/nueva-funcionalidad`
5. **Pull Request**

### 📏 **Estándares de Código**
- ✅ Seguir PSR-12 para PHP
- ✅ Usar PHPDoc para documentar
- ✅ Escribir tests para nuevas funcionalidades
- ✅ Seguir convenciones de Laravel
- ✅ Commit messages descriptivos
- ✅ Code review antes de merge

</details>

---

## 📚 Documentación y Referencias

| Recurso | Descripción | Enlace |
|---------|-------------|--------|
| **Laravel** | Framework principal | [laravel.com/docs](https://laravel.com/docs) |
| **Eloquent** | ORM de Laravel | [laravel.com/docs/eloquent](https://laravel.com/docs/eloquent) |
| **Blade** | Motor de plantillas | [laravel.com/docs/blade](https://laravel.com/docs/blade) |
| **Bootstrap** | Framework CSS | [getbootstrap.com](https://getbootstrap.com/docs/5.3/) |
| **Brevo** | API de emails | [developers.brevo.com](https://developers.brevo.com/) |
| **Adminer** | Gestión de BD | [adminer.org](https://www.adminer.org/) |

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver archivo `LICENSE` para detalles.

```
MIT License

Copyright (c) 2025 TaskFlow - DSS Julio

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software...
```

---

## 📞 Contacto y Soporte

<div align="center">

**🎓 Proyecto Académico - DSS Julio**

📧 **Universidad de Alicante** • 🔗 **Desarrollo de Software Seguro**

[![GitHub Issues](https://img.shields.io/badge/Issues-GitHub-181717?style=for-the-badge&logo=github)](https://github.com/djg16-ua/dss-julio/issues)
[![GitHub Repo](https://img.shields.io/badge/Repositorio-GitHub-181717?style=for-the-badge&logo=github)](https://github.com/djg16-ua/dss-julio)

</div>

### 🆘 **Si tienes problemas:**

1. 📋 **Revisar logs**: `storage/logs/laravel.log`
2. 🧹 **Limpiar cache**: `php artisan config:clear`
3. ⚙️ **Verificar .env**: Todas las variables configuradas
4. 🗄️ **Consultar Adminer**: Para verificar base de datos
5. 🐛 **Abrir issue**: En GitHub con detalles del error

### 📈 **Roadmap del Proyecto**

- [x] ✅ Sistema de autenticación completo
- [x] ✅ Recuperación de contraseñas por email
- [x] ✅ Dashboard responsive
- [x] ✅ Gestión de usuarios y roles
- [ ] 🔄 Gestión completa de proyectos
- [ ] 🔄 Sistema de tareas y asignaciones
- [ ] 🔄 Comentarios y colaboración
- [ ] 🔄 Notificaciones en tiempo real
- [ ] 🔄 API REST completa
- [ ] 🔄 Tests unitarios y de integración

### 🎯 **Objetivos Académicos**

Este proyecto forma parte del curso **Desarrollo de Software Seguro (DSS)** y demuestra:

- ✅ **Autenticación segura** con hashing de contraseñas
- ✅ **Validación de datos** en frontend y backend
- ✅ **Protección CSRF** en formularios
- ✅ **Gestión de sesiones** segura
- ✅ **Tokens de recuperación** con expiración
- ✅ **Middlewares de seguridad**
- ✅ **Configuración de entorno** sin secretos expuestos

---

## 🏆 Reconocimientos

### 🎨 **Diseño e Inspiración**
- **Bootstrap Team** - Framework CSS
- **Laravel Team** - Framework PHP excepcional
- **Brevo** - Servicio de email confiable

### 🛠️ **Herramientas Utilizadas**
- **GitHub** - Control de versiones
- **VS Code** - Editor de código
- **Postman** - Testing de APIs
- **MySQL Workbench** - Diseño de BD

---

<div align="center">

## ⭐ **¡Dale una Estrella al Proyecto!**

**Si este proyecto te ha sido útil, considera darle una ⭐ en GitHub**

[![GitHub stars](https://img.shields.io/github/stars/djg16-ua/dss-julio?style=social)](https://github.com/djg16-ua/dss-julio/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/djg16-ua/dss-julio?style=social)](https://github.com/djg16-ua/dss-julio/network/members)
[![GitHub watchers](https://img.shields.io/github/watchers/djg16-ua/dss-julio?style=social)](https://github.com/djg16-ua/dss-julio/watchers)

---

### 📊 **Estadísticas del Proyecto**

![GitHub code size](https://img.shields.io/github/languages/code-size/djg16-ua/dss-julio)
![GitHub repo size](https://img.shields.io/github/repo-size/djg16-ua/dss-julio)
![GitHub last commit](https://img.shields.io/github/last-commit/djg16-ua/dss-julio)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/djg16-ua/dss-julio)

---

**🎓 Desarrollado con ❤️ para la Universidad de Alicante**

*Proyecto académico DSS Julio - Gestión de Proyectos con Laravel*

</div>