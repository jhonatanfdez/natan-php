# Helpers (Funciones Auxiliares)

> Funciones globales útiles para simplificar tareas comunes en tu aplicación

---

## Introducción

Los **Helpers** son funciones globales disponibles en todo el framework que simplifican tareas comunes como debugging, manejo de URLs, manipulación de strings y más.

### ¿Por Qué Usar Helpers?

- ✅ **Acceso global** - Disponibles en cualquier parte de tu código
- ✅ **Sintaxis limpia** - Código más legible y expresivo
- ✅ **Tareas comunes** - Soluciones listas para problemas frecuentes
- ✅ **Sin instanciar** - No necesitas crear objetos ni clases
- ✅ **PHP puro** - Funciones nativas de PHP mejoradas

### Ejemplo Rápido

```php
// Sin helpers (código verboso)
$value = isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost';
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/assets/css/app.css';

// Con helpers (limpio y expresivo)
$value = env('DB_HOST', 'localhost');
$url = asset('css/app.css');
```

---

## Tabla de Contenidos

- [Debugging](#debugging)
- [Configuración y Entorno](#configuración-y-entorno)
- [URLs y Rutas](#urls-y-rutas)
- [Strings](#strings)
- [Validación](#validación)
- [Información del Framework](#información-del-framework)
- [API Reference](#api-reference)

---

## Debugging

### dd() - Dump and Die

Muestra el contenido de variables y termina la ejecución. **Esencial para debugging**.

**Firma:**
```php
dd(mixed ...$vars): void
```

**Parámetros:**
- `...$vars` - Una o más variables a mostrar

**Retorna:** Nada (termina la ejecución)

**Ejemplos:**

```php
// Debugging simple
$usuario = ['nombre' => 'Juan', 'email' => 'juan@ejemplo.com'];
dd($usuario);
// Muestra el array y termina

// Múltiples variables
$nombre = 'Juan';
$edad = 30;
$activo = true;
dd($nombre, $edad, $activo);
// Muestra las 3 variables numeradas

// En controladores
class UsuariosController
{
    public function show($id)
    {
        $usuario = $this->buscarUsuario($id);
        dd($usuario); // Ver qué contiene antes de continuar
        
        // El código siguiente nunca se ejecuta
        return view('usuarios/show', ['usuario' => $usuario]);
    }
}

// Debugging de peticiones
Router::post('/api/usuarios', function() {
    $request = new Request();
    dd($request->all()); // Ver todos los datos recibidos
});
```

**Características:**
- ✅ Formato legible con colores
- ✅ Numeración automática de variables
- ✅ Muestra tipos de datos (int, string, array, object)
- ✅ Termina la ejecución (no continúa el código)

**Cuándo usar:**
- 🐛 Debugging durante desarrollo
- 🔍 Ver contenido de variables complejas
- 📊 Verificar datos de peticiones HTTP
- 🎯 Comprobar valores antes de procesarlos

> ⚠️ **Importante:** Nunca uses `dd()` en producción. Elimina todos los `dd()` antes de deploy.

---

## Configuración y Entorno

### env() - Variable de Entorno

Obtiene el valor de una variable de entorno definida en `.env` o en el sistema.

**Firma:**
```php
env(string $key, mixed $default = null): mixed
```

**Parámetros:**
- `$key` - Nombre de la variable de entorno
- `$default` - Valor por defecto si no existe (opcional)

**Retorna:** Valor de la variable o default

**Ejemplos:**

```php
// Configuración de base de datos
$dbHost = env('DB_HOST', 'localhost');
$dbName = env('DB_NAME', 'mi_base_datos');
$dbUser = env('DB_USER', 'root');
$dbPass = env('DB_PASSWORD', '');

// URL de la aplicación
$appUrl = env('APP_URL', 'http://localhost:8080');
$appEnv = env('APP_ENV', 'development');

// Claves de API
$stripeKey = env('STRIPE_SECRET_KEY');
$mailApiKey = env('MAILGUN_API_KEY');

// Valores booleanos (auto-convertidos)
$debugMode = env('APP_DEBUG', false); // true o false
$cacheEnabled = env('CACHE_ENABLED', true);
```

**Archivo .env:**
```bash
# .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miapp.com

DB_HOST=localhost
DB_NAME=natan_db
DB_USER=root
DB_PASSWORD=secreto123

STRIPE_SECRET_KEY=sk_test_123456789
MAILGUN_API_KEY=key-abc123xyz
```

**Conversiones Automáticas:**

```php
// En .env: APP_DEBUG=true
env('APP_DEBUG'); // Retorna: boolean true (no string "true")

// En .env: CACHE_ENABLED=false  
env('CACHE_ENABLED'); // Retorna: boolean false

// En .env: DB_PASSWORD=null
env('DB_PASSWORD'); // Retorna: null (no string "null")

// En .env: API_KEY=empty
env('API_KEY'); // Retorna: '' (string vacío)
```

**Valores especiales convertidos:**
- `'true'` → `true` (boolean)
- `'false'` → `false` (boolean)
- `'null'` → `null`
- `'empty'` → `''` (string vacío)

**Cuándo usar:**
- 🔐 Credenciales sensibles (passwords, API keys)
- 🌍 Configuración por entorno (dev, staging, production)
- 🔧 Variables que cambian entre despliegues
- 🎛️ Configuraciones que no deben estar en el código

> 💡 **Tip:** Nunca hagas commit del archivo `.env` al repositorio. Usa `.env.example` como plantilla.

---

### config() - Configuración

Accede a valores de configuración del sistema usando notación de puntos.

**Firma:**
```php
config(string $key, mixed $default = null): mixed
```

**Parámetros:**
- `$key` - Clave de configuración con notación de puntos
- `$default` - Valor por defecto si no existe (opcional)

**Retorna:** Valor de configuración o default

**Ejemplos:**

```php
// Configuración de aplicación
$appName = config('app.name', 'NatanPHP');
$appVersion = config('app.version', '0.2.0');

// Configuración de base de datos
$dbConnection = config('database.connection', 'mysql');
$dbHost = config('database.host', 'localhost');

// Configuración de cache
$cacheDriver = config('cache.driver', 'file');
$cacheTtl = config('cache.ttl', 3600);

// Valores por defecto
$maxUpload = config('upload.max_size', 2048); // 2MB por defecto
```

> 📝 **Nota:** En la versión actual (v0.2.0), `config()` retorna el valor por defecto. Versiones futuras incluirán archivos de configuración completos en `config/`.

**Uso futuro planeado:**

```php
// En config/app.php (futuro)
return [
    'name' => env('APP_NAME', 'NatanPHP'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
];

// En tu código
$appName = config('app.name'); // 'NatanPHP'
$isDebug = config('app.debug'); // false
```

---

### route() - Ruta Nombrada

Genera URL para una ruta nombrada del sistema.

**Firma:**
```php
route(string $name, array $params = []): string
```

**Parámetros:**
- `$name` - Nombre de la ruta
- `$params` - Parámetros opcionales para rutas dinámicas (opcional)

**Retorna:** URL completa de la ruta

**Ejemplos:**

```php
// Rutas simples
$homeUrl = route('home');
// http://localhost:8080/

$apiUrl = route('api');
// http://localhost:8080/api

// Rutas con parámetros (futuro)
$userUrl = route('usuarios.show', ['id' => 123]);
// http://localhost:8080/usuarios/123

$postUrl = route('blog.post', ['slug' => 'mi-articulo']);
// http://localhost:8080/blog/mi-articulo
```

**En vistas:**

```php
<!-- Enlaces dinámicos -->
<a href="<?= route('home') ?>">Inicio</a>
<a href="<?= route('contacto') ?>">Contacto</a>
<a href="<?= route('productos') ?>">Productos</a>

<!-- Formularios -->
<form action="<?= route('login') ?>" method="POST">
    <!-- campos -->
</form>
```

**Ventajas:**
- ✅ URLs centralizadas (cambias la ruta en un lugar)
- ✅ No hardcodeas rutas en múltiples archivos
- ✅ Refactorización fácil de URLs

> 📝 **Nota:** Actualmente es una implementación simplificada. Futuras versiones incluirán sistema completo de named routes.

---

## URLs y Rutas

### url() - URL Absoluta

Genera una URL absoluta para tu aplicación con detección automática del servidor.

**Firma:**
```php
url(string $path = ''): string
```

**Parámetros:**
- `$path` - Ruta relativa (opcional)

**Retorna:** URL absoluta completa

**Ejemplos:**

```php
// URL base
$base = url();
// http://localhost:8080/

// URLs con path
$productos = url('/productos');
// http://localhost:8080/productos

$api = url('/api/v1/users');
// http://localhost:8080/api/v1/users

$perfil = url('/usuarios/123/perfil');
// http://localhost:8080/usuarios/123/perfil
```

**Detección Automática:**

```php
// En DDEV
url('/api');
// https://natanphp-framework.ddev.site/api

// En servidor local PHP
url('/api');
// http://localhost:8080/api

// En producción
url('/api');
// https://miapp.com/api
```

**Características:**
- ✅ Detecta protocolo automáticamente (HTTP/HTTPS)
- ✅ Detecta host y puerto automáticamente
- ✅ Compatible con DDEV, PHP built-in, Apache, Nginx
- ✅ Fallback seguro para CLI

**En controladores:**

```php
class ProductosController
{
    public function store()
    {
        // Guardar producto...
        
        // Redireccionar con URL absoluta
        $redirectUrl = url('/productos/' . $producto->id);
        header('Location: ' . $redirectUrl);
        exit;
    }
}
```

**En APIs:**

```php
Router::get('/api/productos', function() {
    $productos = obtenerProductos();
    
    // Agregar URLs absolutas
    foreach ($productos as &$producto) {
        $producto['url'] = url('/productos/' . $producto['id']);
    }
    
    return json(['data' => $productos]);
});
```

---

### asset() - Archivos Estáticos

Genera URL para archivos estáticos (CSS, JS, imágenes) en `public/assets/`.

**Firma:**
```php
asset(string $path): string
```

**Parámetros:**
- `$path` - Ruta del asset relativa a `public/assets/`

**Retorna:** URL completa del asset

**Ejemplos:**

```php
// CSS
$appCss = asset('css/app.css');
// http://localhost:8080/assets/css/app.css

$styleCss = asset('css/style.css');
// http://localhost:8080/assets/css/style.css

// JavaScript
$appJs = asset('js/app.js');
// http://localhost:8080/assets/js/app.js

$jquery = asset('js/jquery.min.js');
// http://localhost:8080/assets/js/jquery.min.js

// Imágenes
$logo = asset('images/logo.png');
// http://localhost:8080/assets/images/logo.png

$avatar = asset('images/avatars/user-123.jpg');
// http://localhost:8080/assets/images/avatars/user-123.jpg

// Otros archivos
$pdf = asset('docs/manual.pdf');
// http://localhost:8080/assets/docs/manual.pdf
```

**En vistas HTML:**

```html
<!DOCTYPE html>
<html>
<head>
    <title>Mi App</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= asset('images/favicon.ico') ?>">
</head>
<body>
    <!-- Logo -->
    <img src="<?= asset('images/logo.png') ?>" alt="Logo">
    
    <!-- Contenido -->
    <div class="container">
        <!-- ... -->
    </div>
    
    <!-- JavaScript -->
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
```

**Estructura de carpetas esperada:**

```
public/
├── assets/
│   ├── css/
│   │   ├── app.css
│   │   └── bootstrap.min.css
│   ├── js/
│   │   ├── app.js
│   │   └── jquery.min.js
│   ├── images/
│   │   ├── logo.png
│   │   └── avatars/
│   └── docs/
│       └── manual.pdf
└── index.php
```

**Ventajas:**
- ✅ URLs dinámicas según entorno
- ✅ No hardcodeas rutas de assets
- ✅ Fácil migración entre servidores
- ✅ Compatible con CDN (futuro)

---

## Strings

### str_slug() - Convertir a Slug

Convierte texto en un slug amigable para URLs (minúsculas, sin espacios, sin acentos).

**Firma:**
```php
str_slug(string $string, string $separator = '-'): string
```

**Parámetros:**
- `$string` - Texto a convertir
- `$separator` - Separador a usar (opcional, por defecto `-`)

**Retorna:** Slug generado

**Ejemplos:**

```php
// Texto simple
$slug = str_slug('Mi Artículo Genial');
// 'mi-articulo-genial'

// Con acentos
$slug = str_slug('Introducción al Framework PHP');
// 'introduccion-al-framework-php'

// Con caracteres especiales
$slug = str_slug('¿Cómo usar NatanPHP?');
// 'como-usar-natanphp'

// Con separador personalizado
$slug = str_slug('Mi Artículo', '_');
// 'mi_articulo'

// Espacios múltiples
$slug = str_slug('Muchos    Espacios    Aquí');
// 'muchos-espacios-aqui'

// Mayúsculas y minúsculas
$slug = str_slug('TODO EN MAYÚSCULAS');
// 'todo-en-mayusculas'
```

**Uso en blogs:**

```php
class PostsController
{
    public function store()
    {
        $request = new Request();
        $titulo = $request->input('titulo');
        
        // Generar slug automático del título
        $slug = str_slug($titulo);
        
        // Guardar post con slug
        $post = [
            'titulo' => $titulo,
            'slug' => $slug,
            'contenido' => $request->input('contenido')
        ];
        
        // URL amigable: /blog/introduccion-al-framework-php
        // En lugar de: /blog?id=123
    }
}
```

**Uso en productos:**

```php
// Crear slug para productos
$nombreProducto = 'Laptop Gaming ASUS ROG 2024';
$slug = str_slug($nombreProducto);
// 'laptop-gaming-asus-rog-2024'

// URL del producto
$url = url('/productos/' . $slug);
// http://localhost:8080/productos/laptop-gaming-asus-rog-2024
```

**Transformaciones aplicadas:**
1. ✅ Convierte a minúsculas
2. ✅ Reemplaza acentos (á→a, é→e, etc.)
3. ✅ Remueve caracteres especiales
4. ✅ Convierte espacios en separadores
5. ✅ Limpia separadores al inicio/final

**SEO Friendly:**
- ✅ URLs legibles para humanos
- ✅ Mejor para motores de búsqueda
- ✅ Fácil de compartir y recordar

---

## Validación

### blank() - Verificar Vacío

Determina si un valor está "vacío" según criterios estrictos.

**Firma:**
```php
blank(mixed $value): bool
```

**Parámetros:**
- `$value` - Valor a evaluar

**Retorna:** `true` si está vacío, `false` si tiene contenido

**Ejemplos:**

```php
// Strings
blank('');           // true - string vacío
blank('  ');         // true - solo espacios
blank('texto');      // false - tiene contenido

// Null
blank(null);         // true

// Arrays
blank([]);           // true - array vacío
blank([1, 2, 3]);    // false - tiene elementos

// Números
blank(0);            // true - cero es considerado vacío
blank(1);            // false - número diferente de cero
blank('0');          // true - string "0"

// Booleanos
blank(false);        // true
blank(true);         // false
```

**En validaciones:**

```php
class UsuariosController
{
    public function store()
    {
        $request = new Request();
        
        $nombre = $request->input('nombre');
        $email = $request->input('email');
        
        // Validar campos requeridos
        if (blank($nombre)) {
            return json(['error' => 'El nombre es requerido'], 400);
        }
        
        if (blank($email)) {
            return json(['error' => 'El email es requerido'], 400);
        }
        
        // Campos válidos, continuar...
    }
}
```

**En condiciones:**

```php
// Verificar si hay filtros
$filtros = $request->get('filtros', []);

if (!blank($filtros)) {
    // Hay filtros, aplicarlos
    $productos = filtrarProductos($filtros);
} else {
    // No hay filtros, mostrar todos
    $productos = obtenerTodos();
}
```

**Diferencia con empty():**

```php
$valor = '0';

empty($valor);  // true (PHP considera '0' como empty)
blank($valor);  // true (blank también considera '0' como vacío)

$valor = '  ';

empty($valor);  // false (PHP no considera espacios como empty)
blank($valor);  // true (blank considera espacios como vacío)
```

---

### filled() - Verificar Contenido

Determina si un valor tiene contenido (opuesto de `blank()`).

**Firma:**
```php
filled(mixed $value): bool
```

**Parámetros:**
- `$value` - Valor a evaluar

**Retorna:** `true` si tiene contenido, `false` si está vacío

**Ejemplos:**

```php
// Strings
filled('texto');     // true - tiene contenido
filled('');          // false - vacío
filled('  ');        // false - solo espacios

// Arrays
filled([1, 2, 3]);   // true - tiene elementos
filled([]);          // false - vacío

// Números
filled(1);           // true
filled(0);           // false

// Null
filled(null);        // false
```

**En lógica de negocio:**

```php
class PerfilController
{
    public function update()
    {
        $request = new Request();
        
        // Campos opcionales - solo actualizar si tienen contenido
        if (filled($request->input('direccion'))) {
            $usuario->direccion = $request->input('direccion');
        }
        
        if (filled($request->input('telefono'))) {
            $usuario->telefono = $request->input('telefono');
        }
        
        if (filled($request->input('biografia'))) {
            $usuario->biografia = $request->input('biografia');
        }
        
        // Si están vacíos, no se actualizan (mantienen valor anterior)
    }
}
```

**Validar campos dinámicos:**

```php
// Formulario con campos condicionales
$tipoUsuario = $request->input('tipo');

if ($tipoUsuario === 'empresa') {
    // Si es empresa, validar campos adicionales
    if (!filled($request->input('razon_social'))) {
        return json(['error' => 'Razón social requerida'], 400);
    }
    
    if (!filled($request->input('ruc'))) {
        return json(['error' => 'RUC requerido'], 400);
    }
}
```

**Atajos útiles:**

```php
// En lugar de:
if ($request->has('campo') && !blank($request->input('campo'))) {
    // ...
}

// Usa:
if (filled($request->input('campo'))) {
    // ...
}
```

---

## Información del Framework

### version() - Versión del Framework

Obtiene la versión actual de NatanPHP.

**Firma:**
```php
version(): string
```

**Retorna:** String con la versión (ej: "v0.2.0")

**Ejemplos:**

```php
// Obtener versión
$version = version();
// "v0.2.0"

// En footer de tu app
echo "Powered by NatanPHP " . version();
// "Powered by NatanPHP v0.2.0"

// En API
Router::get('/api/version', function() {
    return json([
        'framework' => 'NatanPHP',
        'version' => version()
    ]);
});
```

**En vistas:**

```html
<footer>
    <p>
        Creado con ❤️ usando 
        <a href="https://github.com/jhonatanfdez/natan-php">
            NatanPHP <?= version() ?>
        </a>
    </p>
</footer>
```

---

## Ejemplos Completos

### 1. Configuración de Base de Datos

```php
// config/database.php
class Database
{
    private $connection;
    
    public function __construct()
    {
        // Usar helpers para configuración limpia
        $host = env('DB_HOST', 'localhost');
        $name = env('DB_NAME', 'natan_db');
        $user = env('DB_USER', 'root');
        $pass = env('DB_PASSWORD', '');
        
        try {
            $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";
            $this->connection = new PDO($dsn, $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Debugging en desarrollo
            if (env('APP_ENV') === 'development') {
                dd('Error de conexión:', $e->getMessage());
            }
            die('Error de conexión a la base de datos');
        }
    }
}
```

### 2. Sistema de Blog con Slugs

```php
class BlogController
{
    public function store()
    {
        $request = new Request();
        
        // Validar campos requeridos
        $titulo = $request->input('titulo');
        $contenido = $request->input('contenido');
        
        if (blank($titulo)) {
            return json(['error' => 'El título es requerido'], 400);
        }
        
        if (blank($contenido)) {
            return json(['error' => 'El contenido es requerido'], 400);
        }
        
        // Generar slug automático
        $slug = str_slug($titulo);
        
        // Verificar si el slug ya existe
        $existe = $this->slugExiste($slug);
        if ($existe) {
            // Agregar sufijo numérico
            $contador = 1;
            $slugOriginal = $slug;
            while ($this->slugExiste($slug)) {
                $slug = $slugOriginal . '-' . $contador;
                $contador++;
            }
        }
        
        // Crear post
        $post = [
            'titulo' => $titulo,
            'slug' => $slug,
            'contenido' => $contenido,
            'autor_id' => $this->getUsuarioActual(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Guardar en BD
        $postId = $this->guardarPost($post);
        
        // Responder con URLs
        return json([
            'success' => true,
            'post' => $post,
            'urls' => [
                'ver' => url('/blog/' . $slug),
                'editar' => url('/admin/posts/' . $postId . '/edit'),
                'eliminar' => url('/admin/posts/' . $postId)
            ]
        ]);
    }
    
    public function show($slug)
    {
        // Buscar por slug en lugar de ID
        $post = $this->buscarPorSlug($slug);
        
        if (blank($post)) {
            header('HTTP/1.0 404 Not Found');
            echo view('errors/404');
            exit;
        }
        
        return view('blog/show', ['post' => $post]);
    }
}
```

### 3. Subida de Archivos con Validación

```php
class UploadController
{
    public function uploadAvatar()
    {
        $request = new Request();
        
        // Verificar que se subió archivo
        if (!$request->hasFile('avatar')) {
            return json(['error' => 'No se seleccionó archivo'], 400);
        }
        
        $archivo = $request->file('avatar');
        
        // Validaciones
        $errores = [];
        
        // 1. Validar tipo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            $errores[] = 'Solo se permiten imágenes JPG, PNG o GIF';
        }
        
        // 2. Validar tamaño (máx 2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($archivo['size'] > $maxSize) {
            $errores[] = 'El archivo es muy grande (máx 2MB)';
        }
        
        // Si hay errores, retornar
        if (!blank($errores)) {
            return json(['errors' => $errores], 400);
        }
        
        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreUnico = uniqid() . '-' . str_slug(pathinfo($archivo['name'], PATHINFO_FILENAME));
        $nombreArchivo = $nombreUnico . '.' . $extension;
        
        // Ruta de destino
        $carpeta = __DIR__ . '/../../public/assets/images/avatars/';
        
        // Crear carpeta si no existe
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        
        $destino = $carpeta . $nombreArchivo;
        
        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $destino)) {
            // URL del archivo usando helper asset()
            $url = asset('images/avatars/' . $nombreArchivo);
            
            return json([
                'success' => true,
                'message' => 'Avatar subido exitosamente',
                'archivo' => $nombreArchivo,
                'url' => $url
            ]);
        }
        
        return json(['error' => 'Error al subir archivo'], 500);
    }
}
```

### 4. API con Configuración Dinámica

```php
class ApiController
{
    public function info()
    {
        // Información del sistema usando helpers
        return json([
            'app' => [
                'name' => config('app.name', 'NatanPHP Framework'),
                'version' => version(),
                'environment' => env('APP_ENV', 'production'),
                'debug' => env('APP_DEBUG', false)
            ],
            'urls' => [
                'base' => url(),
                'api' => url('/api'),
                'docs' => url('/api/docs')
            ],
            'assets' => [
                'css' => asset('css/app.css'),
                'js' => asset('js/app.js'),
                'logo' => asset('images/logo.png')
            ]
        ]);
    }
    
    public function productos()
    {
        $request = new Request();
        
        // Filtros opcionales
        $categoria = $request->get('categoria');
        $busqueda = $request->get('q');
        
        // Obtener productos
        $productos = $this->obtenerProductos();
        
        // Filtrar si hay parámetros
        if (filled($categoria)) {
            $productos = array_filter($productos, function($p) use ($categoria) {
                return $p['categoria'] === $categoria;
            });
        }
        
        if (filled($busqueda)) {
            $productos = array_filter($productos, function($p) use ($busqueda) {
                return stripos($p['nombre'], $busqueda) !== false;
            });
        }
        
        // Agregar URLs a cada producto
        foreach ($productos as &$producto) {
            $slug = str_slug($producto['nombre']);
            $producto['urls'] = [
                'detalle' => url('/productos/' . $producto['id']),
                'slug' => url('/productos/' . $slug),
                'imagen' => asset('images/productos/' . $producto['imagen'])
            ];
        }
        
        return json([
            'success' => true,
            'total' => count($productos),
            'data' => array_values($productos)
        ]);
    }
}
```

---

## API Reference

### Tabla Completa de Helpers

| Helper | Firma | Categoría | Descripción |
|--------|-------|-----------|-------------|
| `dd()` | `dd(mixed ...$vars): void` | Debugging | Dump and die - Muestra variables y termina |
| `env()` | `env(string $key, $default = null): mixed` | Configuración | Obtiene variable de entorno |
| `config()` | `config(string $key, $default = null): mixed` | Configuración | Obtiene configuración del sistema |
| `route()` | `route(string $name, array $params = []): string` | URLs | Genera URL de ruta nombrada |
| `url()` | `url(string $path = ''): string` | URLs | Genera URL absoluta |
| `asset()` | `asset(string $path): string` | URLs | Genera URL de asset estático |
| `str_slug()` | `str_slug(string $string, string $separator = '-'): string` | Strings | Convierte texto en slug |
| `blank()` | `blank(mixed $value): bool` | Validación | Verifica si valor está vacío |
| `filled()` | `filled(mixed $value): bool` | Validación | Verifica si valor tiene contenido |
| `version()` | `version(): string` | Framework | Obtiene versión del framework |

---

## Tips y Mejores Prácticas

### ✅ Buenas Prácticas

**1. Usa helpers para código más limpio**

```php
// ✅ Bueno - Código limpio con helpers
$dbHost = env('DB_HOST', 'localhost');
$logo = asset('images/logo.png');
$slug = str_slug($titulo);

// ❌ Evitar - Código verboso sin helpers
$dbHost = isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost';
$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$logo = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png';
$slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $titulo));
```

**2. Siempre usa valores por defecto**

```php
// ✅ Bueno - Con default
$apiKey = env('API_KEY', 'clave-default');
$limite = config('pagination.limit', 10);

// ❌ Evitar - Sin default (puede ser null)
$apiKey = env('API_KEY');
```

**3. Usa filled() para campos opcionales**

```php
// ✅ Bueno - Verifica contenido real
if (filled($request->input('observaciones'))) {
    $pedido->observaciones = $request->input('observaciones');
}

// ❌ Evitar - Puede guardar string vacío
if ($request->has('observaciones')) {
    $pedido->observaciones = $request->input('observaciones'); // Puede ser ""
}
```

**4. Valida con blank() antes de procesar**

```php
// ✅ Bueno - Validación antes de usar
$email = $request->input('email');
if (blank($email)) {
    return json(['error' => 'Email requerido'], 400);
}
// Ahora es seguro usar $email

// ❌ Evitar - Usar sin validar
$email = $request->input('email');
enviarCorreo($email); // Puede fallar si está vacío
```

### ⚠️ Errores Comunes

**1. Usar dd() en producción**

```php
// ❌ NUNCA en producción
dd($usuario); // Expone información sensible

// ✅ Usar en desarrollo, eliminar antes de deploy
if (env('APP_ENV') === 'development') {
    dd($usuario);
}
```

**2. No sanitizar slugs**

```php
// ❌ Problema - Slug sin sanitizar
$slug = strtolower(str_replace(' ', '-', $titulo));
// "introducción-al-php" mantiene acentos

// ✅ Solución - Usar str_slug()
$slug = str_slug($titulo);
// "introduccion-al-php" sin acentos
```

**3. Hardcodear URLs**

```php
// ❌ Problema - URL hardcodeada
$logoUrl = 'http://localhost:8080/assets/images/logo.png';
// Falla al cambiar de servidor

// ✅ Solución - Usar asset()
$logoUrl = asset('images/logo.png');
// Funciona en cualquier servidor
```

---

## Siguientes Pasos

Ahora que dominas los helpers, continúa aprendiendo:

- [🚪 Routing](../basics/routing.md) - Define rutas y usa helpers de URL
- [📨 Request](../basics/requests.md) - Maneja peticiones con validaciones
- [🎮 Controllers](../basics/controllers.md) - Organiza tu código
- [⚙️ Configuration](../configuration.md) - Configuración avanzada

---

## Ayuda y Soporte

¿Tienes dudas sobre helpers?

- [Ver código de helpers.php](https://github.com/jhonatanfdez/natan-php/blob/main/docroot/core/helpers.php)
- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Contribuir con nuevos helpers](../contributions/contribution-guide.md)

---

> 💡 **Tip:** Los helpers de NatanPHP están inspirados en Laravel para facilitar la transición entre frameworks, pero son más simples y educativos.

> 🎯 **Performance:** Los helpers son funciones simples sin overhead. No afectan el rendimiento de tu aplicación.

> 🔮 **Futuro:** Más helpers serán agregados en futuras versiones. ¡Revisa el [Roadmap](../#roadmap)!
