# Middleware

> El middleware proporciona un mecanismo conveniente para filtrar y procesar peticiones HTTP antes de que lleguen a tus controladores.

---

## 📖 Índice

- [Introducción](#introducción)
- [¿Qué es Middleware?](#qué-es-middleware)
- [Casos de Uso](#casos-de-uso)
- [Estado Actual](#estado-actual)
- [Arquitectura Planificada](#arquitectura-planificada)
- [Ejemplos de Implementación](#ejemplos-de-implementación)
- [Middleware Comunes](#middleware-comunes)
- [Buenas Prácticas](#buenas-prácticas)
- [Roadmap](#roadmap)

---

## Introducción

El **middleware** actúa como una capa intermedia entre la petición HTTP entrante y tu aplicación. Es como un filtro que puede:

- ✅ Inspeccionar la petición
- ✅ Modificar la petición o respuesta
- ✅ Bloquear peticiones (autenticación, autorización)
- ✅ Registrar información (logging)
- ✅ Modificar headers HTTP

### Flujo de Middleware

```
┌─────────────┐
│   Cliente   │
└──────┬──────┘
       │ HTTP Request
       ▼
┌─────────────────┐
│   Middleware 1  │  ← Autenticación
└──────┬──────────┘
       │ (si pasa)
       ▼
┌─────────────────┐
│   Middleware 2  │  ← CORS Headers
└──────┬──────────┘
       │ (si pasa)
       ▼
┌─────────────────┐
│   Controlador   │  ← Lógica de negocio
└──────┬──────────┘
       │ Response
       ▼
┌─────────────────┐
│   Middleware 2  │  ← Puede modificar respuesta
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│   Middleware 1  │  ← Puede modificar respuesta
└──────┬──────────┘
       │ HTTP Response
       ▼
┌─────────────┐
│   Cliente   │
└─────────────┘
```

---

## ¿Qué es Middleware?

El middleware es una **clase PHP** que intercepta las peticiones HTTP. Cada middleware implementa un método `handle()` que recibe la petición y decide qué hacer:

### Estructura Básica

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class AuthMiddleware
{
    /**
     * Manejar la petición entrante
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // 1. Código ANTES de ejecutar el controlador
        if (!$this->isAuthenticated()) {
            // Bloquear la petición
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        // 2. Continuar con el siguiente middleware o controlador
        $response = $next($request);
        
        // 3. Código DESPUÉS de ejecutar el controlador
        // Puedes modificar la respuesta aquí
        
        return $response;
    }
    
    private function isAuthenticated(): bool
    {
        // Verificar autenticación
        return isset($_SESSION['user_id']);
    }
}
```

---

## Casos de Uso

### 1. **Autenticación** 🔐

Verificar que el usuario está autenticado antes de acceder a rutas protegidas.

```php
// Middleware de autenticación
if (!isset($_SESSION['user_id'])) {
    redirect('/login');
}
```

### 2. **Autorización** 👮

Verificar que el usuario tiene permisos para realizar una acción.

```php
// Middleware de admin
if (!$user->isAdmin()) {
    abort(403, 'Forbidden');
}
```

### 3. **CORS (Cross-Origin Resource Sharing)** 🌐

Agregar headers HTTP para permitir peticiones desde otros dominios.

```php
// Middleware de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### 4. **Logging** 📝

Registrar todas las peticiones para auditoría.

```php
// Middleware de logging
$log = "[" . date('Y-m-d H:i:s') . "] " . 
       $request->method() . " " . 
       $request->path();
file_put_contents('logs/requests.log', $log . PHP_EOL, FILE_APPEND);
```

### 5. **Rate Limiting** ⏱️

Limitar el número de peticiones por usuario/IP.

```php
// Middleware de rate limiting
$key = $request->ip();
$requests = cache()->get($key, 0);

if ($requests > 100) {
    abort(429, 'Too Many Requests');
}

cache()->increment($key);
```

### 6. **Transformación de Datos** 🔄

Modificar la petición o respuesta automáticamente.

```php
// Middleware para trimear inputs
foreach ($request->all() as $key => $value) {
    if (is_string($value)) {
        $request->merge([$key => trim($value)]);
    }
}
```

---

## Estado Actual

> ⚠️ **Nota Importante**: El sistema de middleware está **planificado pero no implementado** en NatanPHP v0.2.1.

### Lo que Existe Ahora

El `Router` de NatanPHP ya tiene **soporte preparado** para middleware:

```php
// core/Router.php (líneas 369-376)
protected static function callAction(string $action, array $middleware = [])
{
    // Ejecutar middleware antes del controlador
    foreach ($middleware as $middlewareClass) {
        // TODO: Implementar sistema de middleware en versión futura
    }
    
    // Resto del código...
}
```

### Uso en Grupos (Preparado)

```php
// routes/web.php
Router::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Router::get('/dashboard', 'DashboardController@index');
    Router::get('/users', 'UsersController@index');
});
```

**Estado:** La sintaxis está preparada pero el middleware **no se ejecuta** todavía.

---

## Arquitectura Planificada

### Estructura de Archivos

```
app/
└── Middleware/
    ├── AuthMiddleware.php          # Autenticación
    ├── AdminMiddleware.php         # Verificar admin
    ├── CorsMiddleware.php          # Headers CORS
    ├── LoggingMiddleware.php       # Logging de requests
    ├── RateLimitMiddleware.php     # Limitar peticiones
    └── TrimStringsMiddleware.php   # Limpiar inputs
```

### Interfaz de Middleware

```php
<?php

namespace NatanPHP\Core\Contracts;

use NatanPHP\Core\Request;

interface MiddlewareInterface
{
    /**
     * Manejar una petición entrante
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next);
}
```

### Kernel de Middleware

```php
<?php

namespace NatanPHP\Core;

/**
 * Kernel - Gestiona el stack de middleware
 */
class Kernel
{
    /**
     * Middleware global (se ejecuta en todas las peticiones)
     */
    protected array $middleware = [
        \NatanPHP\App\Middleware\CorsMiddleware::class,
        \NatanPHP\App\Middleware\TrimStringsMiddleware::class,
    ];
    
    /**
     * Middleware de rutas (se asigna manualmente a rutas)
     */
    protected array $routeMiddleware = [
        'auth' => \NatanPHP\App\Middleware\AuthMiddleware::class,
        'admin' => \NatanPHP\App\Middleware\AdminMiddleware::class,
        'throttle' => \NatanPHP\App\Middleware\RateLimitMiddleware::class,
    ];
    
    /**
     * Ejecutar el stack de middleware
     */
    public function handle(Request $request, array $middleware): mixed
    {
        $pipeline = array_reduce(
            array_reverse($middleware),
            $this->carry(),
            function($request) {
                // Aquí se ejecuta el controlador
                return Router::dispatch();
            }
        );
        
        return $pipeline($request);
    }
    
    /**
     * Crear la función de transporte del pipeline
     */
    protected function carry(): \Closure
    {
        return function($stack, $pipe) {
            return function($request) use ($stack, $pipe) {
                $middleware = new $pipe();
                return $middleware->handle($request, $stack);
            };
        };
    }
}
```

---

## Ejemplos de Implementación

### Ejemplo 1: Middleware de Autenticación

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class AuthMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        // Verificar si hay sesión activa
        if (!isset($_SESSION['user_id'])) {
            // Para APIs: respuesta JSON
            if ($request->wantsJson()) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error' => 'Please login to continue'
                ]);
                exit;
            }
            
            // Para Web: redirigir a login
            header('Location: /login');
            exit;
        }
        
        // Usuario autenticado, continuar
        return $next($request);
    }
}
```

**Uso:**

```php
// routes/api.php
Router::group(['middleware' => 'auth'], function() {
    Router::get('/profile', 'ProfileController@show');
    Router::put('/profile', 'ProfileController@update');
    Router::delete('/account', 'AccountController@destroy');
});
```

---

### Ejemplo 2: Middleware de CORS

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class CorsMiddleware
{
    private array $allowedOrigins = [
        'http://localhost:3000',
        'https://myapp.com',
    ];
    
    public function handle(Request $request, \Closure $next)
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // Verificar si el origen está permitido
        if (in_array($origin, $this->allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$origin}");
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // 24 horas
        
        // Manejar preflight request (OPTIONS)
        if ($request->method() === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        return $next($request);
    }
}
```

**Uso:**

```php
// Aplicar globalmente en bootstrap/app.php
$kernel->pushMiddleware(CorsMiddleware::class);

// O en rutas API específicas
Router::group(['middleware' => 'cors'], function() {
    Router::get('/public-api', 'ApiController@index');
});
```

---

### Ejemplo 3: Middleware de Logging

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class LoggingMiddleware
{
    private string $logFile = __DIR__ . '/../../storage/logs/requests.log';
    
    public function handle(Request $request, \Closure $next)
    {
        $startTime = microtime(true);
        
        // Continuar con la petición
        $response = $next($request);
        
        // Calcular tiempo de ejecución
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        // Preparar datos del log
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request->method(),
            'uri' => $request->uri(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'duration_ms' => $duration,
            'status' => http_response_code(),
        ];
        
        // Guardar en archivo
        $logLine = json_encode($logData) . PHP_EOL;
        file_put_contents($this->logFile, $logLine, FILE_APPEND);
        
        return $response;
    }
}
```

**Ejemplo de Log Generado:**

```json
{"timestamp":"2024-12-14 10:30:45","method":"GET","uri":"/api/users","ip":"192.168.1.100","user_agent":"Mozilla/5.0","duration_ms":24.5,"status":200}
{"timestamp":"2024-12-14 10:31:12","method":"POST","uri":"/api/users","ip":"192.168.1.100","user_agent":"Mozilla/5.0","duration_ms":156.8,"status":201}
{"timestamp":"2024-12-14 10:32:00","method":"GET","uri":"/api/users/123","ip":"192.168.1.100","user_agent":"Mozilla/5.0","duration_ms":18.2,"status":404}
```

---

### Ejemplo 4: Middleware de Rate Limiting

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class RateLimitMiddleware
{
    private int $maxAttempts = 60; // Máximo de peticiones
    private int $decayMinutes = 1;  // Por minuto
    
    public function handle(Request $request, \Closure $next)
    {
        $key = $this->resolveRequestSignature($request);
        
        $attempts = $this->getAttempts($key);
        
        if ($attempts >= $this->maxAttempts) {
            $retryAfter = $this->getTimeUntilReset($key);
            
            http_response_code(429);
            header("Retry-After: {$retryAfter}");
            header("X-RateLimit-Limit: {$this->maxAttempts}");
            header("X-RateLimit-Remaining: 0");
            
            echo json_encode([
                'success' => false,
                'message' => 'Too Many Requests',
                'retry_after' => $retryAfter
            ]);
            exit;
        }
        
        $this->incrementAttempts($key);
        
        $response = $next($request);
        
        // Agregar headers de rate limit
        $remaining = $this->maxAttempts - $this->getAttempts($key);
        header("X-RateLimit-Limit: {$this->maxAttempts}");
        header("X-RateLimit-Remaining: {$remaining}");
        
        return $response;
    }
    
    private function resolveRequestSignature(Request $request): string
    {
        // Usar IP + URI como firma única
        return sha1($request->ip() . '|' . $request->uri());
    }
    
    private function getAttempts(string $key): int
    {
        $cacheFile = __DIR__ . "/../../storage/cache/rate_limit_{$key}";
        
        if (!file_exists($cacheFile)) {
            return 0;
        }
        
        $data = json_decode(file_get_contents($cacheFile), true);
        
        // Verificar si ha expirado
        if ($data['expires_at'] < time()) {
            unlink($cacheFile);
            return 0;
        }
        
        return $data['attempts'];
    }
    
    private function incrementAttempts(string $key): void
    {
        $cacheFile = __DIR__ . "/../../storage/cache/rate_limit_{$key}";
        $attempts = $this->getAttempts($key) + 1;
        
        $data = [
            'attempts' => $attempts,
            'expires_at' => time() + ($this->decayMinutes * 60)
        ];
        
        file_put_contents($cacheFile, json_encode($data));
    }
    
    private function getTimeUntilReset(string $key): int
    {
        $cacheFile = __DIR__ . "/../../storage/cache/rate_limit_{$key}";
        
        if (!file_exists($cacheFile)) {
            return 0;
        }
        
        $data = json_decode(file_get_contents($cacheFile), true);
        return max(0, $data['expires_at'] - time());
    }
}
```

**Uso:**

```php
// routes/api.php
Router::group(['middleware' => 'throttle'], function() {
    Router::post('/login', 'AuthController@login');
    Router::post('/register', 'AuthController@register');
});
```

---

### Ejemplo 5: Middleware de Admin

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class AdminMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        // Primero verificar que esté autenticado
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
            exit;
        }
        
        // Verificar que sea admin
        $userId = $_SESSION['user_id'];
        $user = $this->getUser($userId);
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Forbidden',
                'error' => 'Admin access required'
            ]);
            exit;
        }
        
        // Es admin, continuar
        return $next($request);
    }
    
    private function getUser(int $userId): ?array
    {
        // Simulación - en producción buscarías en DB
        $users = [
            1 => ['id' => 1, 'name' => 'Admin', 'role' => 'admin'],
            2 => ['id' => 2, 'name' => 'User', 'role' => 'user'],
        ];
        
        return $users[$userId] ?? null;
    }
}
```

**Uso:**

```php
// routes/web.php
Router::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    Router::get('/dashboard', 'Admin\DashboardController@index');
    Router::get('/users', 'Admin\UsersController@index');
    Router::delete('/users/{id}', 'Admin\UsersController@destroy');
});
```

---

### Ejemplo 6: Middleware de Transformación

```php
<?php

namespace NatanPHP\App\Middleware;

use NatanPHP\Core\Request;

class TrimStringsMiddleware
{
    /**
     * Campos que NO deben ser trimmed
     */
    protected array $except = [
        'password',
        'password_confirmation',
    ];
    
    public function handle(Request $request, \Closure $next)
    {
        // Obtener todos los datos del request
        $data = $request->all();
        
        // Trimear recursivamente
        $trimmed = $this->trimArray($data);
        
        // Reemplazar los datos en $_POST y $_GET
        $_POST = $trimmed;
        $_GET = $trimmed;
        
        return $next($request);
    }
    
    /**
     * Trimear array recursivamente
     */
    private function trimArray(array $data): array
    {
        $result = [];
        
        foreach ($data as $key => $value) {
            // Saltar campos excluidos
            if (in_array($key, $this->except)) {
                $result[$key] = $value;
                continue;
            }
            
            if (is_array($value)) {
                $result[$key] = $this->trimArray($value);
            } elseif (is_string($value)) {
                $result[$key] = trim($value);
            } else {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}
```

---

## Middleware Comunes

### Lista de Middleware Útiles

| Middleware | Propósito | Cuándo Usar |
|------------|-----------|-------------|
| **AuthMiddleware** | Verificar autenticación | Rutas protegidas |
| **AdminMiddleware** | Verificar rol admin | Panel de administración |
| **CorsMiddleware** | Headers CORS | APIs públicas |
| **LoggingMiddleware** | Registrar peticiones | Auditoría, debugging |
| **RateLimitMiddleware** | Limitar peticiones | Login, APIs públicas |
| **TrimStringsMiddleware** | Limpiar inputs | Todas las peticiones |
| **JsonMiddleware** | Forzar JSON | APIs REST |
| **MaintenanceMiddleware** | Modo mantenimiento | Deploys, updates |
| **LocaleMiddleware** | Idioma del usuario | Apps multi-idioma |
| **CompressionMiddleware** | Comprimir respuestas | Optimización |

---

## Buenas Prácticas

### ✅ DO (Hacer)

#### 1. Middleware Específico y Pequeño

```php
// ✅ BIEN - Una responsabilidad
class AuthMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!$this->isAuthenticated()) {
            abort(401);
        }
        return $next($request);
    }
}

// ❌ MAL - Múltiples responsabilidades
class MegaMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        // Autenticación
        // CORS
        // Logging
        // Rate limiting
        // ... 200 líneas más
    }
}
```

#### 2. Usar Nombres Descriptivos

```php
// ✅ BIEN
'auth' => AuthMiddleware::class,
'admin' => AdminMiddleware::class,
'throttle' => RateLimitMiddleware::class,

// ❌ MAL
'mid1' => AuthMiddleware::class,
'check' => AdminMiddleware::class,
'limit' => RateLimitMiddleware::class,
```

#### 3. Encadenar Middleware

```php
// ✅ BIEN - Múltiples middleware específicos
Router::group(['middleware' => ['auth', 'admin', 'throttle']], function() {
    Router::get('/sensitive', 'SensitiveController@index');
});

// Cada middleware hace una cosa bien
```

#### 4. Respuestas Consistentes

```php
// ✅ BIEN
public function handle(Request $request, \Closure $next)
{
    if (!$condition) {
        if ($request->wantsJson()) {
            return $this->jsonError('Unauthorized', 401);
        }
        return redirect('/login');
    }
    return $next($request);
}
```

#### 5. Documentar el Propósito

```php
/**
 * AuthMiddleware - Verificar que el usuario esté autenticado
 * 
 * Este middleware verifica que exista una sesión activa.
 * Si no, redirige a login (Web) o devuelve 401 (API).
 * 
 * Uso:
 * Router::group(['middleware' => 'auth'], function() {
 *     // rutas protegidas
 * });
 */
class AuthMiddleware
{
    // ...
}
```

### ❌ DON'T (Evitar)

#### 1. No Hacer Lógica de Negocio

```php
// ❌ MAL - Lógica de negocio en middleware
class OrderMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        // 100 líneas procesando la orden
        // Esto debería estar en un servicio/controlador
        return $next($request);
    }
}

// ✅ BIEN - Solo validación
class OrderMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!$this->canCreateOrder()) {
            abort(403);
        }
        return $next($request);
    }
}
```

#### 2. No Modificar Demasiado la Petición

```php
// ❌ MAL - Cambia demasiado el request
class ModifyRequestMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $_POST['user_id'] = 123;
        $_POST['token'] = 'abc';
        $_POST['timestamp'] = time();
        // ... muchos cambios
        return $next($request);
    }
}
```

#### 3. No Olvidar Llamar a next()

```php
// ❌ MAL - Se olvidó de continuar
class BadMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if ($condition) {
            // Hacer algo
        }
        // Falta: return $next($request);
    }
}

// ✅ BIEN - Siempre continúa
class GoodMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if ($condition) {
            // Hacer algo
        }
        return $next($request);
    }
}
```

---

## Roadmap

### Versión 0.3.0 (Próxima)

**Implementación Básica:**

- ✅ Interfaz `MiddlewareInterface`
- ✅ Clase `Kernel` para gestionar middleware
- ✅ Integración con `Router`
- ✅ Middleware global
- ✅ Middleware de rutas
- ✅ Documentación completa

**Middleware Incluidos:**

- `AuthMiddleware` - Autenticación básica
- `CorsMiddleware` - Headers CORS
- `LoggingMiddleware` - Logging de peticiones

### Versión 0.4.0 (Futura)

**Características Avanzadas:**

- Middleware de grupos
- Prioridad de middleware
- Middleware parametrizables
- Middleware condicionales
- Cache de middleware

**Middleware Adicionales:**

- `RateLimitMiddleware` - Rate limiting
- `TrimStringsMiddleware` - Limpiar inputs
- `MaintenanceMiddleware` - Modo mantenimiento
- `CompressionMiddleware` - Comprimir respuestas

### Versión 0.5.0 (Visión a Futuro)

**Features Avanzadas:**

- Middleware pipeline optimization
- Middleware testing helpers
- Middleware analytics/metrics
- Middleware hooks/events

---

## Resumen

### Conceptos Clave

- **Middleware**: Filtros que procesan peticiones HTTP
- **Pipeline**: Flujo de middleware → controlador → respuesta
- **Stack**: Lista ordenada de middleware a ejecutar
- **Kernel**: Gestor central de middleware

### Estado en NatanPHP v0.2.1

| Característica | Estado | Versión |
|----------------|--------|---------|
| Sintaxis en Router | ✅ Preparado | v0.2.1 |
| Middleware Groups | ✅ Preparado | v0.2.1 |
| Ejecución de Middleware | ⏳ Planificado | v0.3.0 |
| Middleware Kernel | ⏳ Planificado | v0.3.0 |
| Middleware Básicos | ⏳ Planificado | v0.3.0 |

### Próximos Pasos

1. **v0.3.0**: Implementar sistema completo de middleware
2. **v0.4.0**: Agregar middleware avanzados
3. **v0.5.0**: Features enterprise

---

## Ejemplos de Uso Futuro

### Rutas con Middleware

```php
// routes/api.php

// Middleware único
Router::get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Múltiples middleware
Router::post('/admin/users', 'Admin\UsersController@store')
    ->middleware(['auth', 'admin', 'throttle']);

// Grupos con middleware
Router::group(['middleware' => 'auth'], function() {
    Router::get('/dashboard', 'DashboardController@index');
    Router::get('/settings', 'SettingsController@index');
});

// Grupos anidados
Router::group(['middleware' => 'auth'], function() {
    Router::group(['middleware' => 'admin'], function() {
        Router::get('/admin/dashboard', 'Admin\DashboardController@index');
    });
});
```

---

## Recursos Adicionales

- 📘 [Controllers](basics/controllers.md) - Cómo usar middleware en controllers
- 📘 [Routing](basics/routing.md) - Aplicar middleware a rutas
- 📘 [Requests](basics/requests.md) - Acceder al request en middleware

---

**¿Tienes dudas?** Consulta la [documentación completa](/) o visita el [repositorio en GitHub](https://github.com/jhonatanfdez/natan-php).

---

> **Nota**: Esta documentación describe el sistema de middleware planificado para NatanPHP. La implementación completa estará disponible en la versión 0.3.0. El código mostrado es de ejemplo y referencia para el diseño futuro.
