# Routing (Enrutamiento)

> Sistema de rutas dinámico y flexible para aplicaciones web y APIs

---

## Introducción

El **Router** es el corazón del framework NatanPHP. Es el componente que mapea las URLs de tu aplicación a controladores específicos, permitiéndote definir cómo tu aplicación responde a diferentes peticiones HTTP.

### ¿Qué es el Routing?

El routing (enrutamiento) es el proceso de determinar qué código debe ejecutarse cuando un usuario visita una URL específica. Por ejemplo:

- `/usuarios` → Mostrar lista de usuarios
- `/productos/123` → Mostrar producto con ID 123
- `/api/posts` → Endpoint de API para posts

### ¿Por Qué es Importante?

- ✅ **URLs limpias y amigables** - No más `index.php?page=users&id=123`
- ✅ **Organización clara** - Cada ruta sabe exactamente qué hacer
- ✅ **Parámetros dinámicos** - Captura valores de la URL automáticamente
- ✅ **RESTful APIs** - Soporta todos los métodos HTTP
- ✅ **Middleware** - Protege rutas con autenticación, logging, etc.

---

## Tabla de Contenidos

- [Rutas Básicas](#rutas-básicas)
- [Parámetros de Ruta](#parámetros-de-ruta)
- [Grupos de Rutas](#grupos-de-rutas)
- [Middleware](#middleware)
- [Resource Routes](#resource-routes)
- [Rutas Nombradas](#rutas-nombradas)
- [Ejemplos Avanzados](#ejemplos-avanzados)
- [API Reference](#api-reference)

---

## Rutas Básicas

### Definir Rutas

Las rutas se definen en los archivos `routes/web.php` (para páginas web) o `routes/api.php` (para APIs):

```php
// routes/web.php
use NatanPHP\Core\Router;

// Ruta simple
Router::get('/hola', function() {
    echo "¡Hola Mundo!";
});

// Ruta con controlador
Router::get('/usuarios', 'UsuariosController@index');
```

### Métodos HTTP Disponibles

NatanPHP soporta todos los métodos HTTP estándar:

#### GET - Obtener Recursos

Usa `GET` para mostrar información o recursos de solo lectura:

```php
// Página de inicio
Router::get('/', 'HomeController@index');

// Lista de productos
Router::get('/productos', 'ProductosController@index');

// Ver un producto específico
Router::get('/productos/{id}', 'ProductosController@show');
```

**Cuándo usar GET:**
- Mostrar páginas
- Listar recursos
- Ver detalles de un recurso
- Búsquedas

#### POST - Crear Recursos

Usa `POST` para crear nuevos recursos o procesar formularios:

```php
// Procesar formulario de contacto
Router::post('/contacto', 'ContactoController@enviar');

// Crear nuevo usuario
Router::post('/usuarios', 'UsuariosController@store');

// Login
Router::post('/login', 'AuthController@authenticate');
```

**Cuándo usar POST:**
- Crear nuevos recursos
- Enviar formularios
- Login/registro
- Operaciones que modifican datos

#### PUT - Actualizar Recursos Completos

Usa `PUT` para actualizar un recurso completo:

```php
// Actualizar usuario completo
Router::put('/usuarios/{id}', 'UsuariosController@update');

// Actualizar perfil
Router::put('/perfil', 'PerfilController@update');
```

**Cuándo usar PUT:**
- Actualizar un recurso completo
- Reemplazar todos los datos de un recurso

#### PATCH - Actualizar Recursos Parcialmente

Usa `PATCH` para actualizar solo algunos campos:

```php
// Actualizar solo el email del usuario
Router::patch('/usuarios/{id}/email', 'UsuariosController@updateEmail');

// Cambiar estado de un pedido
Router::patch('/pedidos/{id}/estado', 'PedidosController@cambiarEstado');
```

**Cuándo usar PATCH:**
- Actualizar solo algunos campos
- Modificaciones parciales

#### DELETE - Eliminar Recursos

Usa `DELETE` para eliminar recursos:

```php
// Eliminar usuario
Router::delete('/usuarios/{id}', 'UsuariosController@destroy');

// Eliminar post
Router::delete('/posts/{id}', 'PostsController@delete');
```

**Cuándo usar DELETE:**
- Eliminar recursos permanentemente
- Operaciones destructivas

---

### Métodos Avanzados

#### match() - Múltiples Métodos HTTP

Permite que una ruta responda a varios métodos HTTP específicos:

```php
// Manejar GET y POST en la misma ruta
Router::match(['GET', 'POST'], '/contacto', 'ContactoController@handle');

// PUT y PATCH para actualizar
Router::match(['PUT', 'PATCH'], '/usuarios/{id}', 'UsuariosController@update');
```

**Ejemplo de uso:**

```php
// ContactoController.php
class ContactoController
{
    public function handle()
    {
        $request = new Request();
        
        if ($request->isGet()) {
            // Mostrar formulario de contacto
            echo view('contacto/formulario');
        }
        
        if ($request->isPost()) {
            // Procesar formulario enviado
            $this->enviarEmail($request->all());
            redirect('/gracias');
        }
    }
}
```

#### any() - Todos los Métodos HTTP

Responde a cualquier método HTTP (GET, POST, PUT, DELETE, PATCH):

```php
// Responde a TODOS los métodos
Router::any('/webhook', 'WebhookController@handle');

// Útil para debugging
Router::any('/debug', function() {
    $request = new Request();
    dd($request->method(), $request->all());
});
```

> ⚠️ **Advertencia:** Usa `any()` con precaución. Es mejor ser explícito sobre qué métodos acepta cada ruta.

---

## Parámetros de Ruta

Los parámetros te permiten capturar valores dinámicos de la URL.

### Parámetros Requeridos

Define parámetros usando llaves `{}`:

```php
// Capturar ID de usuario
Router::get('/usuarios/{id}', 'UsuariosController@show');
// URLs válidas: /usuarios/1, /usuarios/123, /usuarios/abc

// Capturar múltiples parámetros
Router::get('/posts/{postId}/comentarios/{comentarioId}', 'ComentariosController@show');
// URL: /posts/5/comentarios/42
```

### Acceder a Parámetros en el Controlador

Los parámetros se inyectan automáticamente en tu método del controlador:

```php
// UsuariosController.php
namespace NatanPHP\App\Web\Controllers;

class UsuariosController
{
    // Parámetro simple
    public function show($id)
    {
        echo "Mostrando usuario con ID: " . $id;
        
        // Aquí buscarías el usuario en la base de datos
        // $usuario = Usuario::find($id);
    }
    
    // Múltiples parámetros
    public function actualizarPerfil($usuarioId, $perfilId)
    {
        echo "Usuario: $usuarioId, Perfil: $perfilId";
    }
}
```

### Parámetros Opcionales

Haz un parámetro opcional agregando `?` al final:

```php
// Parámetro opcional
Router::get('/buscar/{termino?}', 'BusquedaController@buscar');
// URLs válidas: /buscar, /buscar/php, /buscar/framework
```

**Ejemplo con valor por defecto:**

```php
// BusquedaController.php
class BusquedaController
{
    public function buscar($termino = '')
    {
        if (empty($termino)) {
            echo "Muestra página de búsqueda";
        } else {
            echo "Buscando: " . $termino;
        }
    }
}
```

### Nombres Descriptivos

Usa nombres de parámetros descriptivos para mejor legibilidad:

```php
// ✅ Bueno - Nombres claros
Router::get('/productos/{slug}', 'ProductosController@show');
Router::get('/blog/{categoria}/{slug}', 'BlogController@show');
Router::get('/usuarios/{username}/posts', 'PostsController@porUsuario');

// ❌ Evitar - Nombres genéricos
Router::get('/items/{a}/{b}', 'ItemsController@show');
```

---

## Grupos de Rutas

Los grupos te permiten aplicar configuración compartida a múltiples rutas.

### Grupos con Prefijos

Agrupa rutas que comparten un prefijo común:

```php
// Grupo API v1
Router::group(['prefix' => 'api/v1'], function() {
    Router::get('/usuarios', 'Api\UsuariosController@index');
    Router::post('/usuarios', 'Api\UsuariosController@store');
    Router::get('/usuarios/{id}', 'Api\UsuariosController@show');
});

// Genera las rutas:
// GET  /api/v1/usuarios
// POST /api/v1/usuarios
// GET  /api/v1/usuarios/{id}
```

### Grupos con Middleware

Aplica middleware a todas las rutas del grupo:

```php
// Proteger rutas de administración
Router::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Router::get('/dashboard', 'Admin\DashboardController@index');
    Router::get('/usuarios', 'Admin\UsuariosController@index');
    Router::get('/configuracion', 'Admin\ConfigController@index');
});

// Todas estas rutas requieren autenticación
```

### Grupos Anidados

Los grupos pueden contener otros grupos:

```php
// API con versiones y recursos
Router::group(['prefix' => 'api'], function() {
    
    // Versión 1
    Router::group(['prefix' => 'v1'], function() {
        Router::get('/usuarios', 'Api\V1\UsuariosController@index');
        Router::get('/productos', 'Api\V1\ProductosController@index');
    });
    
    // Versión 2
    Router::group(['prefix' => 'v2'], function() {
        Router::get('/usuarios', 'Api\V2\UsuariosController@index');
        Router::get('/productos', 'Api\V2\ProductosController@index');
    });
});

// Genera:
// GET /api/v1/usuarios
// GET /api/v1/productos
// GET /api/v2/usuarios
// GET /api/v2/productos
```

### Acumulación de Atributos

Los grupos anidados acumulan prefijos y middleware:

```php
Router::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    
    Router::group(['prefix' => 'usuarios', 'middleware' => 'can-manage-users'], function() {
        
        Router::get('/', 'Admin\UsuariosController@index');
        // Ruta final: GET /admin/usuarios
        // Middleware: ['auth', 'can-manage-users']
        
        Router::get('/{id}', 'Admin\UsuariosController@show');
        // Ruta final: GET /admin/usuarios/{id}
        // Middleware: ['auth', 'can-manage-users']
    });
});
```

---

## Middleware

El middleware te permite ejecutar código antes de que una petición llegue a tu controlador.

> 💡 **Nota:** El sistema de middleware en NatanPHP está en desarrollo básico. Esta sección documenta la API actual.

### Asignar Middleware a Rutas

```php
// Middleware en ruta individual
Router::get('/perfil', 'PerfilController@show')
    ->middleware('auth');

// Múltiples middleware
Router::post('/admin/usuarios', 'Admin\UsuariosController@store')
    ->middleware(['auth', 'admin', 'verified']);
```

### Middleware en Grupos

```php
// Todo el grupo requiere autenticación
Router::group(['middleware' => 'auth'], function() {
    Router::get('/dashboard', 'DashboardController@index');
    Router::get('/perfil', 'PerfilController@show');
    Router::post('/perfil', 'PerfilController@update');
});
```

### Orden de Ejecución

El middleware se ejecuta en el orden definido:

```php
Router::get('/admin/panel', 'AdminController@panel')
    ->middleware(['auth', 'admin', 'log']);

// Orden de ejecución:
// 1. auth     - Verifica autenticación
// 2. admin    - Verifica permisos de admin
// 3. log      - Registra el acceso
// 4. AdminController->panel() - Ejecuta el controlador
```

---

## Resource Routes

Los resource routes generan automáticamente rutas para operaciones CRUD (Create, Read, Update, Delete).

### Router::resource()

Crea las 7 rutas RESTful estándar:

```php
// Genera automáticamente 7 rutas CRUD
Router::resource('productos', 'ProductosController');
```

**Rutas generadas:**

| Método HTTP | URI                    | Acción      | Descripción              |
|-------------|------------------------|-------------|--------------------------|
| GET         | `/productos`           | `index`     | Listar todos             |
| GET         | `/productos/create`    | `create`    | Formulario de creación   |
| POST        | `/productos`           | `store`     | Guardar nuevo            |
| GET         | `/productos/{id}`      | `show`      | Ver uno específico       |
| GET         | `/productos/{id}/edit` | `edit`      | Formulario de edición    |
| PUT/PATCH   | `/productos/{id}`      | `update`    | Actualizar               |
| DELETE      | `/productos/{id}`      | `destroy`   | Eliminar                 |

### Router::apiResource()

Para APIs, omite las rutas de formularios (`create` y `edit`):

```php
// Genera 5 rutas para API (sin formularios)
Router::apiResource('posts', 'Api\PostsController');
```

**Rutas generadas:**

| Método HTTP | URI              | Acción      | Descripción        |
|-------------|------------------|-------------|--------------------|
| GET         | `/posts`         | `index`     | Listar todos       |
| POST        | `/posts`         | `store`     | Crear nuevo        |
| GET         | `/posts/{id}`    | `show`      | Ver uno            |
| PUT/PATCH   | `/posts/{id}`    | `update`    | Actualizar         |
| DELETE      | `/posts/{id}`    | `destroy`   | Eliminar           |

### Implementar el Controlador

Tu controlador debe implementar los métodos correspondientes:

```php
// ProductosController.php
namespace NatanPHP\App\Web\Controllers;

class ProductosController
{
    // GET /productos
    public function index()
    {
        echo "Lista de todos los productos";
    }
    
    // GET /productos/create
    public function create()
    {
        echo "Formulario para crear producto";
    }
    
    // POST /productos
    public function store()
    {
        echo "Guardar nuevo producto";
    }
    
    // GET /productos/{id}
    public function show($id)
    {
        echo "Mostrar producto: " . $id;
    }
    
    // GET /productos/{id}/edit
    public function edit($id)
    {
        echo "Formulario para editar producto: " . $id;
    }
    
    // PUT/PATCH /productos/{id}
    public function update($id)
    {
        echo "Actualizar producto: " . $id;
    }
    
    // DELETE /productos/{id}
    public function destroy($id)
    {
        echo "Eliminar producto: " . $id;
    }
}
```

---

## Rutas Nombradas

Las rutas nombradas te permiten generar URLs sin hardcodear las rutas.

> 💡 **Nota:** Las rutas nombradas están en desarrollo. Usa el helper `route()` para generar URLs.

### Asignar Nombre a una Ruta

```php
// Asignar nombre con ->name()
Router::get('/perfil', 'PerfilController@show')
    ->name('perfil');

Router::post('/productos', 'ProductosController@store')
    ->name('productos.store');
```

### Generar URLs con route()

```php
// Usar el helper route() para generar URL
$url = route('perfil');
// Genera: /perfil

$url = route('productos.store');
// Genera: /productos
```

### Convenciones de Nombres

Sigue estas convenciones para nombres consistentes:

```php
// Recursos con punto (resource.action)
Router::get('/usuarios', 'UsuariosController@index')
    ->name('usuarios.index');
    
Router::post('/usuarios', 'UsuariosController@store')
    ->name('usuarios.store');
    
Router::get('/usuarios/{id}', 'UsuariosController@show')
    ->name('usuarios.show');

// Rutas simples con punto también
Router::get('/admin/dashboard', 'AdminController@dashboard')
    ->name('admin.dashboard');
```

---

## Ejemplos Avanzados

### API RESTful Completa

```php
// routes/api.php
use NatanPHP\Core\Router;

// API v1 con autenticación
Router::group(['prefix' => 'api/v1', 'middleware' => 'api'], function() {
    
    // Autenticación pública
    Router::post('/login', 'Api\AuthController@login');
    Router::post('/register', 'Api\AuthController@register');
    
    // Rutas protegidas
    Router::group(['middleware' => 'auth:api'], function() {
        
        // Usuarios
        Router::apiResource('usuarios', 'Api\UsuariosController');
        
        // Posts
        Router::apiResource('posts', 'Api\PostsController');
        
        // Comentarios de un post
        Router::get('/posts/{postId}/comentarios', 'Api\ComentariosController@index');
        Router::post('/posts/{postId}/comentarios', 'Api\ComentariosController@store');
        
        // Perfil del usuario autenticado
        Router::get('/me', 'Api\PerfilController@show');
        Router::put('/me', 'Api\PerfilController@update');
        Router::post('/me/avatar', 'Api\PerfilController@uploadAvatar');
    });
});
```

### Panel de Administración

```php
// routes/web.php

// Admin panel con múltiples niveles de protección
Router::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    
    // Dashboard
    Router::get('/', 'Admin\DashboardController@index')
        ->name('admin.dashboard');
    
    // Gestión de usuarios (super admin)
    Router::group(['middleware' => 'super-admin'], function() {
        Router::resource('usuarios', 'Admin\UsuariosController');
        Router::post('/usuarios/{id}/ban', 'Admin\UsuariosController@ban');
        Router::post('/usuarios/{id}/unban', 'Admin\UsuariosController@unban');
    });
    
    // Contenido (todos los admin)
    Router::resource('posts', 'Admin\PostsController');
    Router::resource('categorias', 'Admin\CategoriasController');
    
    // Configuración
    Router::get('/configuracion', 'Admin\ConfigController@show')
        ->name('admin.config');
    Router::put('/configuracion', 'Admin\ConfigController@update');
});
```

### Sitio Multi-idioma

```php
// Rutas con prefijo de idioma
Router::group(['prefix' => '{lang}'], function() {
    
    Router::get('/', 'HomeController@index');
    Router::get('/acerca', 'AboutController@index');
    Router::get('/contacto', 'ContactoController@show');
    
    // Productos por idioma
    Router::group(['prefix' => 'productos'], function() {
        Router::get('/', 'ProductosController@index');
        Router::get('/{slug}', 'ProductosController@show');
    });
});

// URLs generadas:
// /es/
// /es/productos
// /es/productos/laptop-gaming
// /en/
// /en/productos
// /en/productos/laptop-gaming
```

### E-commerce Completo

```php
// routes/web.php

// Catálogo público
Router::get('/', 'HomeController@index')->name('home');
Router::get('/productos', 'ProductosController@index')->name('productos');
Router::get('/productos/{slug}', 'ProductosController@show')->name('productos.show');
Router::get('/categorias/{slug}', 'CategoriasController@show')->name('categorias.show');

// Carrito de compras
Router::group(['prefix' => 'carrito'], function() {
    Router::get('/', 'CarritoController@index')->name('carrito');
    Router::post('/agregar/{id}', 'CarritoController@agregar')->name('carrito.agregar');
    Router::delete('/eliminar/{id}', 'CarritoController@eliminar')->name('carrito.eliminar');
    Router::post('/actualizar', 'CarritoController@actualizar')->name('carrito.actualizar');
});

// Checkout (requiere autenticación)
Router::group(['prefix' => 'checkout', 'middleware' => 'auth'], function() {
    Router::get('/', 'CheckoutController@index')->name('checkout');
    Router::post('/procesar', 'CheckoutController@procesar')->name('checkout.procesar');
    Router::get('/confirmacion/{pedidoId}', 'CheckoutController@confirmacion')->name('checkout.confirmacion');
});

// Cuenta de usuario (requiere autenticación)
Router::group(['prefix' => 'cuenta', 'middleware' => 'auth'], function() {
    Router::get('/perfil', 'CuentaController@perfil')->name('cuenta.perfil');
    Router::put('/perfil', 'CuentaController@actualizarPerfil');
    Router::get('/pedidos', 'CuentaController@pedidos')->name('cuenta.pedidos');
    Router::get('/pedidos/{id}', 'CuentaController@verPedido')->name('cuenta.pedido');
    Router::get('/direcciones', 'CuentaController@direcciones')->name('cuenta.direcciones');
});
```

---

## API Reference

### Métodos Principales

| Método | Firma | Descripción |
|--------|-------|-------------|
| `get()` | `Router::get(string $uri, string $action): RouteRegistrar` | Registrar ruta GET |
| `post()` | `Router::post(string $uri, string $action): RouteRegistrar` | Registrar ruta POST |
| `put()` | `Router::put(string $uri, string $action): RouteRegistrar` | Registrar ruta PUT |
| `delete()` | `Router::delete(string $uri, string $action): RouteRegistrar` | Registrar ruta DELETE |
| `patch()` | `Router::patch(string $uri, string $action): RouteRegistrar` | Registrar ruta PATCH |
| `match()` | `Router::match(array $methods, string $uri, string $action): RouteRegistrar` | Múltiples métodos HTTP |
| `any()` | `Router::any(string $uri, string $action): RouteRegistrar` | Todos los métodos HTTP |

### Métodos de Agrupación

| Método | Firma | Descripción |
|--------|-------|-------------|
| `group()` | `Router::group(array $attributes, callable $callback): void` | Agrupar rutas con configuración compartida |
| `resource()` | `Router::resource(string $name, string $controller): void` | Generar 7 rutas CRUD |
| `apiResource()` | `Router::apiResource(string $name, string $controller): void` | Generar 5 rutas API |

### Métodos de Configuración (RouteRegistrar)

| Método | Firma | Descripción |
|--------|-------|-------------|
| `middleware()` | `->middleware(string\|array $middleware): RouteRegistrar` | Asignar middleware |
| `name()` | `->name(string $name): RouteRegistrar` | Asignar nombre a la ruta |

### Parámetros de `group()`

| Clave | Tipo | Descripción | Ejemplo |
|-------|------|-------------|---------|
| `prefix` | `string` | Prefijo para todas las rutas del grupo | `'api/v1'` |
| `middleware` | `string\|array` | Middleware para todo el grupo | `['auth', 'admin']` |

---

## Tips y Mejores Prácticas

### ✅ Buenas Prácticas

**1. Organiza tus rutas lógicamente**

```php
// ✅ Bueno - Agrupadas por recurso
Router::group(['prefix' => 'usuarios'], function() {
    Router::get('/', 'UsuariosController@index');
    Router::get('/{id}', 'UsuariosController@show');
    Router::post('/', 'UsuariosController@store');
});
```

**2. Usa resource routes cuando sea posible**

```php
// ✅ Bueno - Resource automático
Router::resource('productos', 'ProductosController');

// ❌ Evitar - Definir cada ruta manualmente
Router::get('/productos', 'ProductosController@index');
Router::get('/productos/{id}', 'ProductosController@show');
Router::post('/productos', 'ProductosController@store');
// ... etc
```

**3. Nombres de parámetros descriptivos**

```php
// ✅ Bueno
Router::get('/blog/{categoria}/{slug}', 'BlogController@show');

// ❌ Evitar
Router::get('/blog/{a}/{b}', 'BlogController@show');
```

**4. Separar rutas web y API**

```php
// ✅ Bueno - En archivos separados
// routes/web.php - Rutas web
// routes/api.php - Rutas API con prefijo /api

// ❌ Evitar - Todo mezclado en un archivo
```

### ⚠️ Errores Comunes

**1. Orden de las rutas**

```php
// ❌ Problema - La ruta específica nunca se alcanzará
Router::get('/usuarios/{id}', 'UsuariosController@show');
Router::get('/usuarios/nuevo', 'UsuariosController@create');
// /usuarios/nuevo será capturado por {id}

// ✅ Solución - Rutas específicas primero
Router::get('/usuarios/nuevo', 'UsuariosController@create');
Router::get('/usuarios/{id}', 'UsuariosController@show');
```

**2. Olvidar el namespace en controladores**

```php
// ❌ Problema
Router::get('/api/users', 'UsersController@index');
// Buscará en Web\Controllers por defecto

// ✅ Solución - Especificar namespace completo
Router::get('/api/users', 'Api\UsersController@index');
```

---

## Siguientes Pasos

Ahora que dominas el routing, continúa aprendiendo:

- [📨 Request](./requests.md) - Maneja datos de peticiones HTTP
- [🎮 Controllers](./controllers.md) - Organiza la lógica de tu aplicación
- [🔒 Middleware](./middleware.md) - Protege y filtra peticiones
- [🛠️ Helpers](../digging-deeper/helpers.md) - Funciones útiles como `route()` y `url()`

---

## Ayuda y Soporte

¿Tienes dudas sobre routing?

- [Ver ejemplos en el repositorio](https://github.com/jhonatanfdez/natan-php/tree/main/docroot/routes)
- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Ver código del Router](https://github.com/jhonatanfdez/natan-php/blob/main/docroot/core/Router.php)

---

> 💡 **Tip:** El Router de NatanPHP está inspirado en Laravel pero simplificado para fines educativos. Es un excelente punto de partida para entender cómo funcionan los routers modernos.

> ⚠️ **Nota:** Algunas características como rutas nombradas completas y middleware avanzado están en desarrollo. Consulta el [Roadmap](/#roadmap) para más información.
