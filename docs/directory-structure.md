# Directory Structure (Estructura de Directorios)

> Comprende la organización de archivos y carpetas en NatanPHP

---

## Introducción

NatanPHP sigue una estructura de directorios clara y organizada que facilita el desarrollo y mantenimiento de aplicaciones. Esta guía te ayudará a entender dónde va cada cosa y por qué.

### Filosofía de Organización

- 📁 **Separación de responsabilidades** - Cada carpeta tiene un propósito claro
- 🎯 **Convención sobre configuración** - Nombres predecibles
- 🔒 **Seguridad por diseño** - Solo `public/` es accesible desde web
- 🧩 **Modularidad** - Código organizado por tipo y función
- 📚 **Fácil navegación** - Encuentra lo que buscas rápidamente

---

## Tabla de Contenidos

- [Estructura Completa](#estructura-completa)
- [Carpetas Principales](#carpetas-principales)
- [Directorio Core](#directorio-core)
- [Directorio App](#directorio-app)
- [Directorio Public](#directorio-public)
- [Directorio Routes](#directorio-routes)
- [Directorio Tests](#directorio-tests)
- [Convenciones de Nombres](#convenciones-de-nombres)

---

## Estructura Completa

```
NatanPHP-Framework/
│
├── docroot/                      # Raíz del framework
│   │
│   ├── .env                      # Configuración (NO en git)
│   ├── .env.example              # Plantilla de configuración
│   ├── .gitignore                # Archivos ignorados por Git
│   ├── composer.json             # Dependencias de Composer
│   │
│   ├── core/                     # 🔧 Núcleo del framework
│   │   ├── Router.php            # Sistema de routing
│   │   ├── Request.php           # Manejo de peticiones HTTP
│   │   ├── RouteRegistrar.php    # Registro de rutas
│   │   └── helpers.php           # Funciones helper globales
│   │
│   ├── app/                      # 📦 Tu aplicación
│   │   ├── Web/                  # Aplicación web (frontend)
│   │   │   └── Controllers/      # Controladores web
│   │   │       ├── HomeController.php
│   │   │       └── ...
│   │   │
│   │   └── Api/                  # API (backend)
│   │       └── Controllers/      # Controladores API
│   │           ├── ApiController.php
│   │           └── ...
│   │
│   ├── routes/                   # 🚦 Definición de rutas
│   │   ├── web.php               # Rutas web
│   │   └── api.php               # Rutas API
│   │
│   ├── public/                   # 🌐 Punto de entrada web
│   │   ├── index.php             # Front controller
│   │   │
│   │   └── assets/               # Archivos estáticos
│   │       ├── css/              # Hojas de estilo
│   │       ├── js/               # Scripts JavaScript
│   │       ├── images/           # Imágenes
│   │       └── fonts/            # Fuentes tipográficas
│   │
│   ├── tests/                    # 🧪 Tests automatizados
│   │   ├── RouterTest.php
│   │   ├── RequestTest.php
│   │   └── HelpersTest.php
│   │
│   ├── views/                    # 👁️ Plantillas HTML (futuro)
│   │   ├── layouts/              # Layouts base
│   │   ├── components/           # Componentes reutilizables
│   │   └── pages/                # Páginas específicas
│   │
│   ├── storage/                  # 💾 Almacenamiento (futuro)
│   │   ├── logs/                 # Archivos de log
│   │   ├── cache/                # Cache de aplicación
│   │   └── uploads/              # Archivos subidos
│   │
│   └── config/                   # ⚙️ Archivos de config (futuro)
│       ├── app.php
│       ├── database.php
│       └── cache.php
│
└── docs/                         # 📚 Documentación (esta guía)
    ├── index.html
    ├── _sidebar.md
    └── ...
```

---

## Carpetas Principales

### 📁 docroot/
**Raíz del framework** - Contiene toda la aplicación

```
docroot/
├── .env              # Configuración privada
├── .env.example      # Plantilla pública
├── composer.json     # Dependencias PHP
└── ...
```

**Propósito:**
- Raíz de todo el proyecto
- Configuración global
- Punto de partida de Git

**Archivos importantes:**
- `.env` - Variables de entorno (ignorado por Git)
- `.env.example` - Plantilla para nuevos desarrolladores
- `composer.json` - Gestión de paquetes PHP

---

## Directorio Core

### 🔧 core/
**Núcleo del framework** - Código fundamental que hace funcionar NatanPHP

```
core/
├── Router.php            # Sistema de routing
├── Request.php           # Manejo de peticiones
├── RouteRegistrar.php    # Registro de rutas
└── helpers.php           # Funciones globales
```

**¿Qué contiene?**

#### Router.php
Sistema de routing que mapea URLs a controladores

```php
namespace NatanPHP\Core;

class Router
{
    public static function get($uri, $action) { }
    public static function post($uri, $action) { }
    // ...
}
```

**Uso:**
```php
Router::get('/home', 'HomeController@index');
```

#### Request.php
Maneja datos de peticiones HTTP (GET, POST, FILES, headers)

```php
namespace NatanPHP\Core;

class Request
{
    public function get($key, $default = null) { }
    public function post($key, $default = null) { }
    public function file($key) { }
    // ...
}
```

**Uso:**
```php
$request = new Request();
$nombre = $request->input('nombre');
```

#### RouteRegistrar.php
Registra y gestiona configuración de rutas individuales

```php
namespace NatanPHP\Core;

class RouteRegistrar
{
    public function middleware($middleware) { }
    public function name($name) { }
}
```

**Uso:**
```php
Router::get('/admin', 'AdminController@index')
    ->middleware('auth')
    ->name('admin.dashboard');
```

#### helpers.php
Funciones helper globales disponibles en toda la aplicación

```php
function dd(...$vars) { }
function env($key, $default = null) { }
function url($path = '') { }
function asset($path) { }
// ... 10 helpers en total
```

**Uso:**
```php
$dbHost = env('DB_HOST', 'localhost');
$logo = asset('images/logo.png');
dd($usuario);
```

> ⚠️ **Importante:** Generalmente NO debes modificar archivos en `core/` a menos que estés contribuyendo al framework.

---

## Directorio App

### 📦 app/
**Tu aplicación** - Aquí va TODO tu código personalizado

```
app/
├── Web/                  # Frontend/Web
│   └── Controllers/      # Controladores web
│       ├── HomeController.php
│       ├── ProductosController.php
│       └── UsuariosController.php
│
└── Api/                  # Backend/API
    └── Controllers/      # Controladores API
        ├── ApiController.php
        ├── UsersController.php
        └── ProductsController.php
```

### Web/ - Aplicación Web

**Propósito:** Controladores que manejan páginas web (HTML)

**Namespace:** `NatanPHP\App\Web\Controllers`

**Ejemplo:**
```php
// app/Web/Controllers/HomeController.php
namespace NatanPHP\App\Web\Controllers;

class HomeController
{
    public function index()
    {
        echo "<h1>Bienvenido a NatanPHP</h1>";
    }
}
```

**Ruta asociada:**
```php
// routes/web.php
Router::get('/', 'HomeController@index');
```

**¿Cuándo usar?**
- ✅ Páginas HTML tradicionales
- ✅ Formularios web
- ✅ Vistas del usuario
- ✅ Dashboard de administración

### Api/ - API REST

**Propósito:** Controladores que responden con JSON

**Namespace:** `NatanPHP\App\Api\Controllers`

**Ejemplo:**
```php
// app/Api/Controllers/UsersController.php
namespace NatanPHP\App\Api\Controllers;

class UsersController
{
    public function index()
    {
        $users = $this->getUsers();
        return json(['data' => $users]);
    }
}
```

**Ruta asociada:**
```php
// routes/api.php
Router::get('/users', 'Api\UsersController@index');
```

**¿Cuándo usar?**
- ✅ Endpoints JSON
- ✅ APIs para aplicaciones móviles
- ✅ APIs para SPAs (React, Vue, Angular)
- ✅ Integraciones con otros servicios

### Agregar Nuevos Controladores

**Web:**
```bash
# Crear nuevo controlador web
touch docroot/app/Web/Controllers/BlogController.php
```

```php
namespace NatanPHP\App\Web\Controllers;

class BlogController
{
    public function index()
    {
        // Listar posts
    }
    
    public function show($slug)
    {
        // Mostrar un post
    }
}
```

**API:**
```bash
# Crear nuevo controlador API
touch docroot/app/Api/Controllers/PostsController.php
```

```php
namespace NatanPHP\App\Api\Controllers;

class PostsController
{
    public function index()
    {
        return json(['posts' => $this->getPosts()]);
    }
}
```

---

## Directorio Public

### 🌐 public/
**Único directorio accesible desde web** - Punto de entrada y assets

```
public/
├── index.php         # Front controller (punto de entrada)
│
└── assets/           # Archivos estáticos
    ├── css/          # Hojas de estilo
    │   ├── app.css
    │   └── bootstrap.min.css
    │
    ├── js/           # JavaScript
    │   ├── app.js
    │   └── jquery.min.js
    │
    ├── images/       # Imágenes
    │   ├── logo.png
    │   ├── favicon.ico
    │   └── avatars/
    │
    └── fonts/        # Fuentes
        └── roboto.woff2
```

### index.php - Front Controller

**El corazón del framework** - Primer archivo que se ejecuta

```php
<?php

// Cargar autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar helpers
require_once __DIR__ . '/../core/helpers.php';

// Cargar rutas
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// Obtener URI y método
$request = new NatanPHP\Core\Request();
$uri = $request->uri();
$method = $request->method();

// Resolver ruta
NatanPHP\Core\Router::resolve($uri, $method);
```

**Flujo:**
1. Carga dependencias (Composer)
2. Carga helpers globales
3. Registra todas las rutas
4. Obtiene URI y método HTTP
5. Resuelve y ejecuta la ruta correspondiente

> 🔒 **Seguridad:** Solo `public/` es accesible desde el navegador. Los directorios `core/`, `app/`, `routes/` están protegidos.

### assets/ - Archivos Estáticos

**CSS:**
```
assets/css/
├── app.css           # Estilos principales
├── admin.css         # Estilos del admin
└── vendor/           # CSS de terceros
    └── bootstrap.min.css
```

**JavaScript:**
```
assets/js/
├── app.js            # JS principal
├── admin.js          # JS del admin
└── vendor/           # JS de terceros
    ├── jquery.min.js
    └── vue.min.js
```

**Imágenes:**
```
assets/images/
├── logo.png          # Logo de la app
├── favicon.ico       # Icono del navegador
├── avatars/          # Avatares de usuarios
└── productos/        # Imágenes de productos
```

**Acceder desde código:**
```php
// Helper asset()
$css = asset('css/app.css');
// http://localhost:8080/assets/css/app.css

$logo = asset('images/logo.png');
// http://localhost:8080/assets/images/logo.png
```

**En HTML:**
```html
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<script src="<?= asset('js/app.js') ?>"></script>
<img src="<?= asset('images/logo.png') ?>" alt="Logo">
```

---

## Directorio Routes

### 🚦 routes/
**Definición de rutas** - Mapeo de URLs a controladores

```
routes/
├── web.php     # Rutas web (páginas HTML)
└── api.php     # Rutas API (JSON)
```

### web.php - Rutas Web

**Para páginas HTML tradicionales**

```php
<?php

use NatanPHP\Core\Router;

// Página de inicio
Router::get('/', 'HomeController@index');

// Blog
Router::get('/blog', 'BlogController@index');
Router::get('/blog/{slug}', 'BlogController@show');

// Productos
Router::get('/productos', 'ProductosController@index');
Router::get('/productos/{id}', 'ProductosController@show');

// Formularios
Router::get('/contacto', 'ContactoController@show');
Router::post('/contacto', 'ContactoController@enviar');

// Admin (protegido con middleware)
Router::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Router::get('/dashboard', 'Admin\DashboardController@index');
    Router::resource('productos', 'Admin\ProductosController');
});
```

**Características:**
- ✅ URLs amigables para SEO
- ✅ Respuestas HTML
- ✅ Sesiones y cookies
- ✅ Vistas y templates

### api.php - Rutas API

**Para endpoints JSON**

```php
<?php

use NatanPHP\Core\Router;

// Prefijo /api para todas las rutas
Router::group(['prefix' => 'api'], function() {
    
    // Versión 1
    Router::group(['prefix' => 'v1'], function() {
        
        // Públicas
        Router::post('/login', 'Api\AuthController@login');
        Router::post('/register', 'Api\AuthController@register');
        
        // Protegidas (requieren token)
        Router::group(['middleware' => 'auth:api'], function() {
            Router::apiResource('users', 'Api\UsersController');
            Router::apiResource('posts', 'Api\PostsController');
            Router::get('/me', 'Api\ProfileController@show');
        });
    });
});
```

**Características:**
- ✅ Respuestas JSON
- ✅ Autenticación con tokens
- ✅ RESTful
- ✅ Versionado (v1, v2)

**URLs generadas:**
```
POST   /api/v1/login
POST   /api/v1/register
GET    /api/v1/users
POST   /api/v1/users
GET    /api/v1/users/{id}
PUT    /api/v1/users/{id}
DELETE /api/v1/users/{id}
```

---

## Directorio Tests

### 🧪 tests/
**Tests automatizados** - Asegura que todo funciona correctamente

```
tests/
├── RouterTest.php       # Tests del Router (45 tests)
├── RequestTest.php      # Tests de Request (61 tests)
└── HelpersTest.php      # Tests de helpers (34 tests)
```

**Total:** 140 tests automatizados ✅

### Ejecutar Tests

```bash
# Todos los tests
composer test

# Solo un archivo
./vendor/bin/phpunit tests/RouterTest.php

# Con coverage
composer test:coverage
```

**Ejemplo de test:**
```php
// tests/RouterTest.php
class RouterTest extends TestCase
{
    public function testGetRouteRegistration()
    {
        Router::get('/test', 'TestController@index');
        
        $this->assertTrue(Router::hasRoute('GET', '/test'));
    }
}
```

---

## Convenciones de Nombres

### Controladores

**Nomenclatura:**
- Singular
- PascalCase
- Sufijo `Controller`

```
✅ CORRECTO:
- HomeController.php
- ProductoController.php
- UsuarioController.php
- BlogPostController.php

❌ INCORRECTO:
- home.php
- productosController.php
- usuario_controller.php
- Blog.php
```

### Métodos de Controladores

**Nomenclatura:**
- camelCase
- Verbos descriptivos

```php
✅ CORRECTO:
public function index() { }
public function show($id) { }
public function store() { }
public function update($id) { }
public function destroy($id) { }

❌ INCORRECTO:
public function Index() { }
public function show_user($id) { }
public function SaveUser() { }
```

### Archivos y Carpetas

**Nomenclatura:**
- Carpetas: minúsculas
- Archivos: PascalCase para clases, minúsculas para otros

```
✅ CORRECTO:
app/Web/Controllers/HomeController.php
routes/web.php
core/Router.php

❌ INCORRECTO:
app/web/controllers/homeController.php
routes/Web.php
core/router.php
```

---

## Agregar Nuevas Carpetas

### Models (futuro)

```bash
mkdir -p docroot/app/Models
```

```php
// app/Models/Usuario.php
namespace NatanPHP\App\Models;

class Usuario
{
    // Lógica del modelo
}
```

### Middleware (futuro)

```bash
mkdir -p docroot/app/Middleware
```

```php
// app/Middleware/AuthMiddleware.php
namespace NatanPHP\App\Middleware;

class AuthMiddleware
{
    // Lógica de autenticación
}
```

### Services (futuro)

```bash
mkdir -p docroot/app/Services
```

```php
// app/Services/EmailService.php
namespace NatanPHP\App\Services;

class EmailService
{
    // Lógica de envío de emails
}
```

---

## Mejores Prácticas

### ✅ Hacer

**1. Mantén la estructura estándar**
```
✅ Usa las carpetas existentes
✅ Sigue las convenciones de nombres
✅ Organiza por tipo (Controllers, Models, etc.)
```

**2. Separa Web y API**
```
✅ app/Web/Controllers/ para páginas
✅ app/Api/Controllers/ para JSON
✅ routes/web.php para rutas web
✅ routes/api.php para rutas API
```

**3. Protege archivos sensibles**
```
✅ Solo public/ es accesible
✅ .env en .gitignore
✅ core/ protegido
```

### ❌ Evitar

**1. No coloques código en public/**
```
❌ public/login.php
❌ public/admin.php
✅ routes/web.php + Controllers
```

**2. No modifiques core/ sin razón**
```
❌ Editar core/Router.php directamente
✅ Extender clases si es necesario
✅ Contribuir al proyecto si encuentras bugs
```

**3. No mezcles responsabilidades**
```
❌ Lógica de BD en controladores
❌ HTML en modelos
✅ Separación de responsabilidades
```

---

## Siguientes Pasos

Ahora que entiendes la estructura, continúa con:

- [🚀 Installation](./installation.md) - Instalar el framework
- [🚪 Routing](./basics/routing.md) - Definir rutas en `routes/`
- [🎮 Controllers](./basics/controllers.md) - Crear controladores en `app/`
- [⚙️ Configuration](./configuration.md) - Configurar `.env`

---

## Ayuda y Soporte

¿Dudas sobre la estructura?

- [Ver código en GitHub](https://github.com/jhonatanfdez/natan-php)
- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Guía de contribución](./contributions/contribution-guide.md)

---

> 📁 **Tip:** Mantén esta estructura incluso cuando el proyecto crezca. La consistencia es clave.

> 🔐 **Seguridad:** Nunca coloques archivos ejecutables PHP directamente en `public/`. Usa siempre el sistema de routing.

> 📚 **Organización:** Cuando agregues nuevas carpetas, documéntalas en tu proyecto para que otros desarrolladores entiendan su propósito.
