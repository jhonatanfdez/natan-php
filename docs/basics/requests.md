# Request (Peticiones HTTP)

> Maneja datos de peticiones HTTP de forma segura y conveniente

---

## Introducción

La clase **Request** es tu interfaz unificada para acceder a todos los datos de las peticiones HTTP en NatanPHP. Proporciona métodos convenientes y seguros para trabajar con parámetros GET, POST, archivos, headers y más.

### ¿Por Qué Usar Request?

En lugar de acceder directamente a las superglobales de PHP (`$_GET`, `$_POST`, `$_FILES`, etc.), la clase Request ofrece:

- ✅ **Interfaz unificada** - Un solo objeto para todo
- ✅ **Valores por defecto** - Evita errores de "undefined index"
- ✅ **Métodos convenientes** - `has()`, `filled()`, `only()`, `except()`
- ✅ **Detección automática** - AJAX, JSON, método HTTP
- ✅ **Seguridad** - Manejo seguro de datos de entrada

### Comparación: Antes vs Después

```php
// ❌ Forma antigua (propensa a errores)
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : 'Anónimo';
$email = $_GET['email'] ?? '';
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    // ...
}

// ✅ Con Request (limpio y seguro)
$request = new Request();
$nombre = $request->input('nombre', 'Anónimo');
$email = $request->get('email', '');
if ($request->hasFile('avatar')) {
    // ...
}
```

---

## Tabla de Contenidos

- [Instanciar Request](#instanciar-request)
- [Métodos HTTP](#métodos-http)
- [Obtener Datos](#obtener-datos)
- [Trabajar con Archivos](#trabajar-con-archivos)
- [Headers HTTP](#headers-http)
- [Información del Cliente](#información-del-cliente)
- [Detección de Tipo de Petición](#detección-de-tipo-de-petición)
- [Ejemplos Completos](#ejemplos-completos)
- [API Reference](#api-reference)

---

## Instanciar Request

### En Controladores

La forma más común de usar Request es instanciarla en tus controladores:

```php
namespace NatanPHP\App\Web\Controllers;

use NatanPHP\Core\Request;

class UsuariosController
{
    public function store()
    {
        // Crear instancia de Request
        $request = new Request();
        
        // Usar la instancia
        $nombre = $request->input('nombre');
        $email = $request->input('email');
    }
}
```

### En Rutas con Closures

También puedes usar Request directamente en rutas:

```php
Router::post('/contacto', function() {
    $request = new Request();
    
    $mensaje = $request->input('mensaje');
    $email = $request->input('email');
    
    // Procesar contacto...
});
```

### Una Instancia por Petición

Request captura automáticamente los datos de la petición HTTP actual cuando se instancia:

```php
$request = new Request();
// Captura: $_GET, $_POST, $_FILES, $_SERVER, headers
```

---

## Métodos HTTP

### Obtener el Método HTTP

Obtén el método HTTP de la petición actual:

```php
$request = new Request();

$metodo = $request->method();
// Retorna: "GET", "POST", "PUT", "DELETE", "PATCH"

echo "Método: " . $metodo;
```

### Verificar Método Específico

#### isMethod() - Verificar Cualquier Método

```php
if ($request->isMethod('POST')) {
    echo "Es una petición POST";
}

if ($request->isMethod('PUT')) {
    echo "Es una actualización";
}
```

#### isGet() - Verificar GET

```php
if ($request->isGet()) {
    // Mostrar formulario o página
    echo view('productos/formulario');
}
```

#### isPost() - Verificar POST

```php
if ($request->isPost()) {
    // Procesar formulario enviado
    $this->procesarFormulario($request);
}
```

### Ejemplo: Mismo Método para GET y POST

```php
class ContactoController
{
    public function handle()
    {
        $request = new Request();
        
        if ($request->isGet()) {
            // Mostrar formulario
            return view('contacto/formulario');
        }
        
        if ($request->isPost()) {
            // Procesar envío
            $nombre = $request->input('nombre');
            $mensaje = $request->input('mensaje');
            
            // Enviar email...
            return redirect('/gracias');
        }
    }
}
```

---

## Obtener Datos

### get() - Parámetros GET

Obtiene valores de la query string (`?param=valor`):

```php
// URL: /productos?categoria=tecnologia&precio=100

$request = new Request();

$categoria = $request->get('categoria');
// "tecnologia"

$precio = $request->get('precio');
// "100"

$descuento = $request->get('descuento', 0);
// 0 (no existe, usa valor por defecto)
```

**Casos de uso:**
- Búsquedas: `/buscar?q=php`
- Filtros: `/productos?categoria=tecnologia&min=100&max=500`
- Paginación: `/posts?page=2`
- Ordenamiento: `/usuarios?sort=nombre&order=asc`

### post() - Parámetros POST

Obtiene valores enviados por formularios:

```php
// Formulario:
// <input name="nombre" value="Juan">
// <input name="email" value="juan@ejemplo.com">

$request = new Request();

$nombre = $request->post('nombre');
// "Juan"

$email = $request->post('email');
// "juan@ejemplo.com"

$telefono = $request->post('telefono', '');
// "" (no existe, usa valor por defecto)
```

**Casos de uso:**
- Formularios de registro/login
- Crear/editar recursos
- Enviar datos sensibles (no aparecen en URL)

### input() - GET o POST Automático

Busca primero en POST, luego en GET. Ideal para formularios flexibles:

```php
$request = new Request();

// Funciona para GET: /buscar?q=php
// Y para POST: <input name="q" value="php">

$busqueda = $request->input('q', '');
// Obtiene el valor esté en GET o POST

$nombre = $request->input('nombre', 'Anónimo');
// Con valor por defecto si no existe
```

**Cuándo usar input():**
- No te importa si viene de GET o POST
- Formularios que aceptan ambos métodos
- APIs flexibles

### all() - Todos los Datos

Obtiene todos los parámetros GET y POST combinados:

```php
// URL: /registro?utm_source=google
// POST: nombre=Juan&email=juan@ejemplo.com

$request = new Request();

$todosLosDatos = $request->all();
/*
[
    'utm_source' => 'google',
    'nombre' => 'Juan',
    'email' => 'juan@ejemplo.com'
]
*/
```

**Nota:** Si una clave existe en GET y POST, POST tiene prioridad.

### only() - Solo Campos Específicos

Filtra para obtener solo ciertos campos:

```php
$request = new Request();

// Solo obtener nombre y email (ignorar todo lo demás)
$datos = $request->only(['nombre', 'email']);
/*
[
    'nombre' => 'Juan',
    'email' => 'juan@ejemplo.com'
]
*/
```

**Casos de uso:**
- Guardar solo campos permitidos en base de datos
- Validar solo campos específicos
- Registros de log con datos selectivos

### except() - Excluir Campos Específicos

Obtiene todo excepto ciertos campos:

```php
$request = new Request();

// Obtener todo excepto contraseñas
$datos = $request->except(['password', 'password_confirmation']);
/*
[
    'nombre' => 'Juan',
    'email' => 'juan@ejemplo.com',
    'telefono' => '555-0123'
    // Sin 'password' ni 'password_confirmation'
]
*/
```

**Casos de uso:**
- Excluir campos sensibles de logs
- Remover campos de confirmación
- Limpiar datos antes de procesarlos

### has() - Verificar si Existe

Verifica si un parámetro existe (incluso si está vacío):

```php
$request = new Request();

if ($request->has('email')) {
    echo "El campo email fue enviado";
    // Existe, aunque sea vacío: email=""
}
```

### filled() - Verificar si Tiene Contenido

Verifica si existe Y tiene contenido real:

```php
$request = new Request();

if ($request->filled('nombre')) {
    echo "El campo nombre tiene contenido";
    // No solo existe, tiene un valor no vacío
}

// Diferencia:
$request->has('campo');    // true si existe, incluso vacío
$request->filled('campo'); // true solo si tiene contenido real
```

**Ejemplo práctico:**

```php
// Formulario opcional de dirección
if ($request->filled('direccion')) {
    // Usuario proporcionó una dirección
    $usuario->direccion = $request->input('direccion');
} else {
    // Campo vacío o no enviado, usar null
    $usuario->direccion = null;
}
```

---

## Trabajar con Archivos

### file() - Obtener Información de Archivo

Obtiene información de un archivo subido:

```php
// Formulario:
// <form method="POST" enctype="multipart/form-data">
//   <input type="file" name="avatar">
// </form>

$request = new Request();

$archivo = $request->file('avatar');
/*
[
    'name' => 'foto.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => '/tmp/phpXXXXXX',
    'error' => 0,
    'size' => 51234
]
*/

if ($archivo) {
    echo "Archivo: " . $archivo['name'];
    echo "Tamaño: " . $archivo['size'] . " bytes";
}
```

### hasFile() - Verificar Subida Exitosa

Verifica si un archivo se subió correctamente:

```php
$request = new Request();

if ($request->hasFile('avatar')) {
    // Archivo se subió sin errores y tiene contenido
    $archivo = $request->file('avatar');
    
    // Mover archivo
    $destino = '/uploads/' . $archivo['name'];
    move_uploaded_file($archivo['tmp_name'], $destino);
    
    echo "Archivo subido exitosamente";
} else {
    echo "No se subió ningún archivo o hubo un error";
}
```

### Ejemplo Completo: Subir Avatar

```php
class PerfilController
{
    public function updateAvatar()
    {
        $request = new Request();
        
        // Verificar que se subió un archivo
        if (!$request->hasFile('avatar')) {
            return json(['error' => 'No se seleccionó ningún archivo']);
        }
        
        $archivo = $request->file('avatar');
        
        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            return json(['error' => 'Solo se permiten imágenes JPG, PNG o GIF']);
        }
        
        // Validar tamaño (máximo 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB en bytes
        if ($archivo['size'] > $maxSize) {
            return json(['error' => 'El archivo es muy grande (máx 2MB)']);
        }
        
        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreUnico = uniqid() . '.' . $extension;
        
        // Mover a carpeta de uploads
        $destino = __DIR__ . '/../../public/uploads/avatars/' . $nombreUnico;
        
        if (move_uploaded_file($archivo['tmp_name'], $destino)) {
            // Guardar en base de datos
            // $usuario->avatar = '/uploads/avatars/' . $nombreUnico;
            // $usuario->save();
            
            return json([
                'success' => true,
                'url' => '/uploads/avatars/' . $nombreUnico
            ]);
        }
        
        return json(['error' => 'Error al subir el archivo']);
    }
}
```

### Errores de Subida

PHP proporciona códigos de error en `$archivo['error']`:

```php
$archivo = $request->file('documento');

if ($archivo) {
    switch ($archivo['error']) {
        case UPLOAD_ERR_OK:
            echo "Subida exitosa";
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "El archivo es muy grande";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "El archivo se subió parcialmente";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "No se seleccionó ningún archivo";
            break;
        default:
            echo "Error desconocido";
    }
}
```

---

## Headers HTTP

### header() - Obtener Header Específico

Obtiene el valor de un header HTTP:

```php
$request = new Request();

// Content-Type del request
$contentType = $request->header('Content-Type');
// "application/json" o "text/html"

// User Agent del navegador
$userAgent = $request->header('User-Agent');
// "Mozilla/5.0 (Windows NT 10.0; Win64; x64)..."

// Header con valor por defecto
$auth = $request->header('Authorization', 'Bearer token-default');
```

### Headers Comunes

```php
$request = new Request();

// Accept - Tipos de contenido aceptados
$accept = $request->header('Accept');
// "text/html,application/json,*/*"

// Accept-Language - Idiomas aceptados
$idioma = $request->header('Accept-Language');
// "es-ES,es;q=0.9,en;q=0.8"

// Referer - Página de origen
$referer = $request->header('Referer');
// "https://google.com"

// Authorization - Token de autenticación
$token = $request->header('Authorization');
// "Bearer eyJhbGciOiJIUzI1NiIs..."
```

### Ejemplo: API con Token

```php
class ApiController
{
    public function index()
    {
        $request = new Request();
        
        // Obtener token del header Authorization
        $authHeader = $request->header('Authorization', '');
        
        // Formato esperado: "Bearer token-aqui"
        if (strpos($authHeader, 'Bearer ') !== 0) {
            return json(['error' => 'Token no proporcionado'], 401);
        }
        
        $token = substr($authHeader, 7); // Remover "Bearer "
        
        // Validar token
        if (!$this->validarToken($token)) {
            return json(['error' => 'Token inválido'], 401);
        }
        
        // Token válido, continuar
        return json(['data' => $this->obtenerDatos()]);
    }
}
```

---

## Información del Cliente

### ip() - Obtener IP del Cliente

Obtiene la dirección IP del cliente, considerando proxies:

```php
$request = new Request();

$ip = $request->ip();
// "192.168.1.100" o "203.0.113.42"

echo "Tu IP es: " . $ip;
```

**Nota:** Este método considera headers de proxies comunes:
- `X-Forwarded-For` (load balancers)
- `X-Real-IP` (nginx)
- `Client-IP`
- `REMOTE_ADDR` (directo)

### userAgent() - User Agent del Navegador

Obtiene el User Agent string del navegador:

```php
$request = new Request();

$userAgent = $request->userAgent();
// "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36..."

echo "Navegador: " . $userAgent;
```

### uri() - URI Actual

Obtiene la URI de la petición (sin query string):

```php
$request = new Request();

// URL: http://localhost/productos/crear?debug=1

$uri = $request->uri();
// "/productos/crear"
```

### fullUrl() - URL Completa

Obtiene la URL completa con query string:

```php
$request = new Request();

// URL: http://localhost/productos?categoria=tecnologia&precio=100

$fullUrl = $request->fullUrl();
// "/productos?categoria=tecnologia&precio=100"
```

### Ejemplo: Logging de Peticiones

```php
class LogMiddleware
{
    public function handle()
    {
        $request = new Request();
        
        $log = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];
        
        // Guardar en archivo de log
        file_put_contents(
            'logs/access.log',
            json_encode($log) . "\n",
            FILE_APPEND
        );
    }
}
```

---

## Detección de Tipo de Petición

### isAjax() - Detectar Peticiones AJAX

Verifica si la petición es AJAX (vía XMLHttpRequest):

```php
$request = new Request();

if ($request->isAjax()) {
    // Es una petición AJAX
    return json(['success' => true, 'data' => $datos]);
} else {
    // Es una petición normal
    return view('pagina', $datos);
}
```

**Cómo funciona:** Verifica el header `X-Requested-With: XMLHttpRequest` que envían jQuery, Axios y fetch con configuración personalizada.

### wantsJson() - Detectar si Espera JSON

Verifica si el cliente espera una respuesta JSON:

```php
$request = new Request();

if ($request->wantsJson()) {
    // Cliente espera JSON (API)
    return json(['usuarios' => $usuarios]);
} else {
    // Cliente espera HTML (navegador)
    return view('usuarios/index', ['usuarios' => $usuarios]);
}
```

**Cómo funciona:** Verifica el header `Accept: application/json`.

### Ejemplo: Respuesta Adaptativa

```php
class UsuariosController
{
    public function index()
    {
        $request = new Request();
        $usuarios = $this->obtenerUsuarios();
        
        // Responder según el tipo de cliente
        if ($request->wantsJson() || $request->isAjax()) {
            // API o AJAX - Responder con JSON
            return json([
                'success' => true,
                'data' => $usuarios,
                'total' => count($usuarios)
            ]);
        }
        
        // Navegador normal - Responder con HTML
        return view('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }
}
```

---

## Ejemplos Completos

### 1. Formulario de Registro Completo

```php
class RegistroController
{
    public function handle()
    {
        $request = new Request();
        
        // Mostrar formulario en GET
        if ($request->isGet()) {
            return view('auth/registro');
        }
        
        // Procesar registro en POST
        if ($request->isPost()) {
            // Validar que campos requeridos existan y tengan contenido
            $camposRequeridos = ['nombre', 'email', 'password'];
            foreach ($camposRequeridos as $campo) {
                if (!$request->filled($campo)) {
                    return json(['error' => "El campo $campo es requerido"], 400);
                }
            }
            
            // Obtener solo campos permitidos
            $datos = $request->only([
                'nombre',
                'email',
                'password',
                'telefono'
            ]);
            
            // Validar email
            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                return json(['error' => 'Email inválido'], 400);
            }
            
            // Hashear contraseña
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            // Guardar en base de datos
            // $usuario = Usuario::create($datos);
            
            return json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente'
            ]);
        }
    }
}
```

### 2. API con Autenticación

```php
class ApiProductosController
{
    public function index()
    {
        $request = new Request();
        
        // Verificar autenticación
        $token = $request->header('Authorization', '');
        if (!$this->validarToken($token)) {
            return json(['error' => 'No autorizado'], 401);
        }
        
        // Obtener filtros de query string
        $categoria = $request->get('categoria', 'todas');
        $minPrecio = $request->get('min_precio', 0);
        $maxPrecio = $request->get('max_precio', 99999);
        $ordenar = $request->get('ordenar', 'nombre');
        $pagina = $request->get('page', 1);
        
        // Filtrar productos
        $productos = $this->filtrarProductos([
            'categoria' => $categoria,
            'min_precio' => $minPrecio,
            'max_precio' => $maxPrecio,
            'ordenar' => $ordenar,
            'pagina' => $pagina
        ]);
        
        // Responder con JSON
        return json([
            'success' => true,
            'data' => $productos,
            'pagination' => [
                'current_page' => $pagina,
                'total' => $this->totalProductos()
            ]
        ]);
    }
    
    public function store()
    {
        $request = new Request();
        
        // Solo aceptar JSON
        if (!$request->wantsJson()) {
            return json(['error' => 'Content-Type debe ser application/json'], 400);
        }
        
        // Obtener datos del producto
        $datos = $request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria_id'
        ]);
        
        // Validaciones
        if (!$request->filled('nombre')) {
            return json(['error' => 'Nombre requerido'], 400);
        }
        
        if (!$request->filled('precio') || $datos['precio'] <= 0) {
            return json(['error' => 'Precio inválido'], 400);
        }
        
        // Crear producto
        // $producto = Producto::create($datos);
        
        return json([
            'success' => true,
            'message' => 'Producto creado',
            'data' => $datos
        ], 201);
    }
}
```

### 3. Upload de Múltiples Archivos

```php
class GaleriaController
{
    public function uploadMultiple()
    {
        $request = new Request();
        
        // Verificar que se subieron archivos
        if (!$request->hasFile('imagenes')) {
            return json(['error' => 'No se seleccionaron imágenes'], 400);
        }
        
        $archivosSubidos = [];
        $errores = [];
        
        // PHP convierte imagenes[] en un array estructurado
        $imagenes = $_FILES['imagenes'];
        $totalArchivos = count($imagenes['name']);
        
        for ($i = 0; $i < $totalArchivos; $i++) {
            // Construir array de archivo individual
            $archivo = [
                'name' => $imagenes['name'][$i],
                'type' => $imagenes['type'][$i],
                'tmp_name' => $imagenes['tmp_name'][$i],
                'error' => $imagenes['error'][$i],
                'size' => $imagenes['size'][$i]
            ];
            
            // Validar
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $errores[] = "Error al subir {$archivo['name']}";
                continue;
            }
            
            // Validar tipo
            if (!str_starts_with($archivo['type'], 'image/')) {
                $errores[] = "{$archivo['name']} no es una imagen";
                continue;
            }
            
            // Validar tamaño (máx 5MB)
            if ($archivo['size'] > 5 * 1024 * 1024) {
                $errores[] = "{$archivo['name']} es muy grande";
                continue;
            }
            
            // Mover archivo
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreUnico = uniqid() . '.' . $extension;
            $destino = __DIR__ . '/../../public/uploads/galeria/' . $nombreUnico;
            
            if (move_uploaded_file($archivo['tmp_name'], $destino)) {
                $archivosSubidos[] = '/uploads/galeria/' . $nombreUnico;
            } else {
                $errores[] = "Error al mover {$archivo['name']}";
            }
        }
        
        return json([
            'success' => count($archivosSubidos) > 0,
            'archivos_subidos' => $archivosSubidos,
            'total' => count($archivosSubidos),
            'errores' => $errores
        ]);
    }
}
```

### 4. Búsqueda Avanzada

```php
class BusquedaController
{
    public function buscar()
    {
        $request = new Request();
        
        // Obtener término de búsqueda
        $q = $request->input('q', '');
        
        if (strlen($q) < 3) {
            return json([
                'error' => 'El término debe tener al menos 3 caracteres'
            ], 400);
        }
        
        // Obtener filtros opcionales
        $filtros = [
            'categoria' => $request->get('categoria'),
            'fecha_desde' => $request->get('fecha_desde'),
            'fecha_hasta' => $request->get('fecha_hasta'),
            'ordenar' => $request->get('ordenar', 'relevancia'),
            'limite' => $request->get('limite', 20)
        ];
        
        // Remover filtros vacíos
        $filtros = array_filter($filtros, function($valor) {
            return !is_null($valor) && $valor !== '';
        });
        
        // Realizar búsqueda
        $resultados = $this->buscarConFiltros($q, $filtros);
        
        // Log de búsqueda
        $this->registrarBusqueda([
            'termino' => $q,
            'filtros' => $filtros,
            'resultados' => count($resultados),
            'ip' => $request->ip(),
            'timestamp' => time()
        ]);
        
        // Respuesta adaptativa
        if ($request->wantsJson()) {
            return json([
                'query' => $q,
                'filtros' => $filtros,
                'resultados' => $resultados,
                'total' => count($resultados)
            ]);
        }
        
        return view('busqueda/resultados', [
            'query' => $q,
            'resultados' => $resultados
        ]);
    }
}
```

---

## API Reference

### Métodos de Instanciación

| Método | Firma | Descripción |
|--------|-------|-------------|
| `__construct()` | `new Request()` | Crea instancia y captura datos de petición actual |

### Métodos HTTP

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `method()` | `->method(): string` | `string` | Obtiene método HTTP (GET, POST, etc.) |
| `isMethod()` | `->isMethod(string $method): bool` | `bool` | Verifica si es método específico |
| `isGet()` | `->isGet(): bool` | `bool` | Verifica si es petición GET |
| `isPost()` | `->isPost(): bool` | `bool` | Verifica si es petición POST |

### Obtener Datos

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `get()` | `->get(string $key, $default = null): mixed` | `mixed` | Obtiene parámetro GET |
| `post()` | `->post(string $key, $default = null): mixed` | `mixed` | Obtiene parámetro POST |
| `input()` | `->input(string $key, $default = null): mixed` | `mixed` | Obtiene de POST o GET |
| `all()` | `->all(): array` | `array` | Todos los datos (GET + POST) |
| `only()` | `->only(array $keys): array` | `array` | Solo campos especificados |
| `except()` | `->except(array $keys): array` | `array` | Todos excepto especificados |
| `has()` | `->has(string $key): bool` | `bool` | Verifica si parámetro existe |
| `filled()` | `->filled(string $key): bool` | `bool` | Verifica si existe y tiene contenido |

### Archivos

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `file()` | `->file(string $key): ?array` | `array\|null` | Obtiene información de archivo |
| `hasFile()` | `->hasFile(string $key): bool` | `bool` | Verifica subida exitosa |

### Headers

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `header()` | `->header(string $key, $default = null): mixed` | `mixed` | Obtiene header específico |

### Información del Cliente

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `ip()` | `->ip(): string` | `string` | Obtiene IP del cliente |
| `userAgent()` | `->userAgent(): string` | `string` | Obtiene User Agent |
| `uri()` | `->uri(): string` | `string` | URI actual sin query string |
| `fullUrl()` | `->fullUrl(): string` | `string` | URL completa con query string |

### Detección de Tipo

| Método | Firma | Retorno | Descripción |
|--------|-------|---------|-------------|
| `isAjax()` | `->isAjax(): bool` | `bool` | Verifica si es petición AJAX |
| `wantsJson()` | `->wantsJson(): bool` | `bool` | Verifica si espera respuesta JSON |

---

## Tips y Mejores Prácticas

### ✅ Buenas Prácticas

**1. Siempre usa valores por defecto**

```php
// ✅ Bueno - Con valor por defecto
$nombre = $request->input('nombre', 'Anónimo');
$pagina = $request->get('page', 1);

// ❌ Evitar - Sin valor por defecto (puede ser null)
$nombre = $request->input('nombre');
```

**2. Usa `only()` para campos permitidos**

```php
// ✅ Bueno - Solo campos seguros
$datos = $request->only(['nombre', 'email', 'telefono']);

// ❌ Evitar - Todos los datos (inseguro)
$datos = $request->all();
// Un atacante podría enviar: role=admin&is_verified=1
```

**3. Usa `filled()` para campos opcionales**

```php
// ✅ Bueno - Verifica contenido real
if ($request->filled('direccion')) {
    $usuario->direccion = $request->input('direccion');
}

// ❌ Evitar - Puede guardar string vacío
if ($request->has('direccion')) {
    $usuario->direccion = $request->input('direccion'); // Puede ser ""
}
```

**4. Valida siempre los archivos**

```php
// ✅ Bueno - Validación completa
if ($request->hasFile('avatar')) {
    $archivo = $request->file('avatar');
    
    // Validar tipo
    $tiposPermitidos = ['image/jpeg', 'image/png'];
    if (!in_array($archivo['type'], $tiposPermitidos)) {
        return json(['error' => 'Tipo de archivo no permitido'], 400);
    }
    
    // Validar tamaño
    if ($archivo['size'] > 2 * 1024 * 1024) {
        return json(['error' => 'Archivo muy grande'], 400);
    }
    
    // Procesar...
}

// ❌ Evitar - Sin validación
$archivo = $request->file('avatar');
move_uploaded_file($archivo['tmp_name'], '/uploads/' . $archivo['name']);
```

### ⚠️ Errores Comunes

**1. No verificar si el archivo existe**

```php
// ❌ Problema - Error si no hay archivo
$archivo = $request->file('avatar');
move_uploaded_file($archivo['tmp_name'], '/uploads/' . $archivo['name']);
// Fatal error si $archivo es null

// ✅ Solución - Verificar primero
if ($request->hasFile('avatar')) {
    $archivo = $request->file('avatar');
    // Ahora es seguro procesar
}
```

**2. Confundir `has()` con `filled()`**

```php
// ❌ Problema - Acepta strings vacíos
if ($request->has('email')) {
    // Esto se ejecuta incluso si email=""
    $usuario->email = $request->input('email'); // ""
}

// ✅ Solución - Usar filled()
if ($request->filled('email')) {
    // Solo se ejecuta si email tiene contenido real
    $usuario->email = $request->input('email');
}
```

**3. No sanitizar datos de entrada**

```php
// ❌ Problema - Riesgo de XSS
$comentario = $request->input('comentario');
echo $comentario; // Puede contener <script>alert('XSS')</script>

// ✅ Solución - Sanitizar salida
$comentario = $request->input('comentario');
echo htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8');
```

---

## Siguientes Pasos

Ahora que dominas Request, continúa aprendiendo:

- [🎮 Controllers](./controllers.md) - Organiza la lógica de tu aplicación
- [📤 Responses](./responses.md) - Envía respuestas HTTP personalizadas
- [🚪 Routing](./routing.md) - Define rutas con parámetros dinámicos
- [🔒 Middleware](./middleware.md) - Valida y filtra peticiones

---

## Ayuda y Soporte

¿Tienes dudas sobre Request?

- [Ver código de Request](https://github.com/jhonatanfdez/natan-php/blob/main/docroot/core/Request.php)
- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Ejemplos en el repositorio](https://github.com/jhonatanfdez/natan-php/tree/main/docroot/app/Web/Controllers)

---

> 💡 **Tip:** La clase Request en NatanPHP sigue patrones similares a Laravel y Symfony, lo que hace que el conocimiento sea transferible entre frameworks.

> ⚠️ **Seguridad:** Siempre valida y sanitiza los datos de entrada antes de usarlos. Nunca confíes ciegamente en los datos del usuario.
