<div align="center">
  <img src="public/logo.jpeg" alt="NatanPHP Logo" width="200"/>
  
  # NatanPHP Framework
  
  *Framework PHP MVC Simple, Moderno e Innovador*
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://php.net)
  [![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
  [![Educational](https://img.shields.io/badge/Purpose-Educational-orange)](https://github.com/jhonatanfdez/natan-php)
  
  **Un framework PHP diseñado para enseñar y aprender cómo funcionan los frameworks modernos por dentro**
  
  📍 **Estado actual: v0.1.0** — Framework base con estructura Web/API, 20+ helpers funcionales, autoloading PSR-4, y preparado para desarrollo de clases core.
  
  • **Changelog**: ver [v0.1.0 en CHANGELOG.md](CHANGELOG.md#v010---2025-10-28) · **Tag**: [v0.1.0](https://github.com/jhonatanfdez/natan-php/releases/tag/v0.1.0)
</div>

---

## 📰 **Novedades Recientes**

- **v0.1.0**: 🎉 **Framework base establecido** - Estructura de carpetas Web/API implementada, autoloading PSR-4 configurado, sistema de helpers con 20+ funciones (dd(), env(), config(), asset(), url(), csrf_token(), str_slug(), etc.), comando CLI `natan` preparado.
- **Helpers completos**: Debugging (dd), configuración (env, config), URLs (asset, url), seguridad (csrf_token, csrf_field), utilidades de strings (str_slug, str_limit), y más funciones esenciales.
- **Arquitectura innovadora**: Separación clara entre Web y API, estructura educativa con core/ visible, filosofía "Simplicidad con Propósito".
- **Base sólida**: Composer con PSR-4, dependencias instaladas, entorno DDEV configurado, git inicializado.

## ⚡ **Funcionalidades Actuales (v0.1.0)**

### 🏗️ **Infraestructura Base**
- **Autoloading PSR-4** ✅ Completo
  - Namespaces `Core\` y `App\` configurados
  - Carga automática de helpers globales
  - Integración con Composer

- **Estructura Innovadora Web/API** ✅ Completo
  - Separación clara: `app/Web/`, `app/Api/`, `app/Shared/`
  - Organización por función, no por tipo de archivo
  - Escalabilidad desde proyectos pequeños hasta complejos

### 🛠️ **Sistema de Helpers (20+ funciones)**
- **Debugging** ✅ Funcional
  - `dd($var)` - Debug elegante con var_dump y salida
  
- **Configuración** ✅ Funcional
  - `env($key, $default)` - Variables de entorno
  - `config($key, $default)` - Configuración de aplicación
  
- **URLs y Assets** ✅ Funcional
  - `asset($path)` - URLs de recursos estáticos
  - `url($path)` - URLs absolutas de la aplicación
  
- **Seguridad** ✅ Funcional
  - `csrf_token()` - Generación de tokens CSRF
  - `csrf_field()` - Campo HTML con token
  
- **Utilidades de String** ✅ Funcional
  - `str_slug($string)` - Conversión a URL amigable
  - `str_limit($string, $limit)` - Limitar longitud de texto
  - `str_random($length)` - Generar cadena aleatoria
  
- **Utilidades Generales** ✅ Funcional
  - `collect($array)` - Wrapper para arrays
  - `now()` - Fecha/hora actual
  - `today()` - Fecha actual
  - `blank($value)` - Verificar si está vacío
  - `filled($value)` - Verificar si tiene contenido

### 📁 **Gestión de Proyecto**
- **Entorno de Desarrollo** ✅ DDEV configurado
  - Base de datos MySQL lista
  - PHP 8.0+ configurado
  - Variables de entorno (.env.example)
  
- **Control de Versiones** ✅ Git inicializado
  - Repositorio configurado
  - .gitignore optimizado para PHP
  - Commits en español con convención

### ⚙️ **Comando CLI `natan`**
- **Base preparada** ✅ Script ejecutable
  - Comando `php natan` listo para extensión
  - Estructura para comandos de generación
  - Sistema de ayuda implementable

### 🔜 **Próximo en Desarrollo**
**PASO 8: Implementar Core/Request.php**
- Clase para manejo de peticiones HTTP (GET, POST, PUT, DELETE)
- Métodos para acceder a parámetros, headers, archivos
- Validación de entrada y sanitización
- Base para el sistema de routing que viene después

---

## 🚀 **¿Qué es NatanPHP?**

NatanPHP es un framework PHP MVC completo diseñado con fines **educativos** pero totalmente **funcional**. A diferencia de Laravel que puede ser complejo para principiantes, NatanPHP tiene una arquitectura **simple, clara e innovadora** que permite entender fácilmente cómo funcionan los frameworks modernos por dentro.

## 🎯 **Filosofía: "Simplicidad con Propósito"**

### ✨ Diferencias clave con Laravel:

| Laravel | NatanPHP |
|---------|----------|
| `app/Http/Controllers/` | `app/Web/Controllers/` y `app/Api/Controllers/` |
| `php artisan` | `php natan` |
| Core complejo en vendor | Core educativo en `core/` |
| Todo mezclado | Separación clara Web/API |

## 🏗️ **Estructura Innovadora**

```
natan-php/
├── 🧠 core/                    # El cerebro del framework
│   ├── Router.php              # Sistema de rutas
│   ├── Database.php            # Conexión y ORM
│   ├── View.php                # Motor de plantillas
│   └── Console.php             # Sistema de comandos
├── 🏠 app/                     # Tu aplicación
│   ├── Web/                    # TODO lo relacionado con WEB
│   │   ├── Controllers/        # Controladores web
│   │   └── Views/              # Vistas y plantillas
│   ├── Api/                    # TODO lo relacionado con API
│   │   ├── Controllers/        # Controladores API
│   │   └── Resources/          # Transformadores JSON
│   ├── Shared/                 # Compartido entre Web y API
│   │   ├── Models/             # Modelos de datos
│   │   ├── Services/           # Lógica de negocio
│   │   └── Middleware/         # Middleware compartido
│   └── Database/               # Base de datos
│       ├── migrations/         # Migraciones
│       └── seeds/              # Datos de prueba
├── 📁 public/                  # Punto de entrada
├── 🛣️ routes/                  # Rutas separadas
│   ├── web.php                 # Rutas web
│   └── api.php                 # Rutas API
├── ⚙️ config/                  # Configuración
└── natan                       # Comando CLI
```

## 🛠️ **Características Principales**

### 🎨 **Motor de Plantillas Personalizado**
```php
@extends('layouts.app')

@section('content')
<h1>{{ $title }}</h1>
@foreach($products as $product)
    <div>{{ $product->name }} - ${{ $product->price }}</div>
@endforeach
@endsection
```

### 🗄️ **ORM Potente y Simple**
```php
// Consultas intuitivas
$users = User::where('active', 1)->get();
$user = User::find(1);

// Relaciones fluidas
$posts = $user->posts()->paginate(10);

// Creación fácil
User::create(['name' => 'Juan', 'email' => 'juan@example.com']);
```

### 🚀 **Separación Clara Web/API**
```php
// Controlador Web
app/Web/Controllers/ProductController.php
→ return $this->view('products.index', $data);

// Controlador API  
app/Api/Controllers/ProductController.php
→ return $this->jsonResponse($data);
```

### ⚡ **Comando CLI Intuitivo "natan"**
```bash
# Crear CRUD completo para WEB
php natan create crud Product --web

# Crear CRUD completo para API
php natan create crud Product --api

# Crear CRUD completo (Web + API)
php natan create crud Product --full

# Agregar campos interactivamente
php natan field add Product

# Ver estructura de tabla
php natan table describe Product
```

## 🚀 **Roadmap de Desarrollo**

### ✅ **v0.1.0 - Estructura Base (Completado)**
- **Infraestructura** ✅ Estructura de carpetas Web/API implementada
- **Autoloading** ✅ PSR-4 configurado y funcionando
- **Helpers** ✅ 20+ funciones esenciales implementadas
- **Entorno** ✅ DDEV, Composer, dependencias instaladas
- **CLI** ✅ Comando `natan` base preparado

### 🔄 **v0.2.0 - Core Classes (En Desarrollo)**
- **Core/Request.php** 🔄 **PRÓXIMO** - Manejo de peticiones HTTP
- **Core/Router.php** ⏳ Pendiente - Sistema de rutas dinámico
- **Core/View.php** ⏳ Pendiente - Motor de plantillas tipo Blade
- **Core/Database.php** ⏳ Pendiente - ORM y Query Builder

### 📋 **v0.3.0 - Sistema CLI (Planificado)**
- **Generadores de Código** ⏳ CRUDs automáticos
- **Comandos de BD** ⏳ Migraciones y seeds
- **Utilidades** ⏳ Optimización y limpieza

### 🎯 **v1.0.0 - Framework Completo (Meta)**
- **Todas las características** ⏳ Framework funcional completo
- **Documentación** ⏳ Guías y ejemplos completos
- **Testing** ⏳ Suite de pruebas automatizadas

### 📋 **Funciones Helpers Implementadas (v0.1.0)**
```php
// Debugging ✅ FUNCIONANDO
dd($variable);                          // Debug elegante con var_dump y exit

// Configuración ✅ FUNCIONANDO
env('APP_NAME', 'NatanPHP');           // Variables de entorno con default
config('app.name', 'Framework');       // Configuración de aplicación

// URLs y Assets ✅ FUNCIONANDO
asset('css/app.css');                   // Assets: /public/assets/css/app.css
url('/productos');                      // URLs absolutas de la aplicación

// Seguridad ✅ FUNCIONANDO
csrf_token();                           // Token CSRF único por sesión
csrf_field();                           // Campo HTML: <input type="hidden" name="_token" value="...">

// Utilidades de String ✅ FUNCIONANDO
str_slug('Mi Título Genial');           // Resultado: "mi-titulo-genial"
str_limit($texto, 100, '...');          // Limitar texto con sufijo
str_random(16);                         // Cadena aleatoria segura

// Utilidades Generales ✅ FUNCIONANDO
collect([1, 2, 3]);                     // Wrapper para arrays con métodos útiles
now();                                  // DateTime actual
today();                                // DateTime solo fecha
blank($value);                          // true si es null, '', 0, [], false
filled($value);                         // Opuesto de blank()
```

## 🚀 **Inicio Rápido**

### 1. **Instalación**
```bash
# Clonar el repositorio
git clone https://github.com/jhonatanfdez/natan-php.git
cd natan-php

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php natan install
```

### 2. **Configurar Base de Datos**
```bash
# Editar .env con tus datos de BD
DB_HOST=localhost
DB_NAME=mi_base_datos
DB_USER=usuario
DB_PASS=contraseña

# Ejecutar migraciones
php natan migrate
```

### 3. **Crear tu primer módulo**
```bash
# CRUD completo para web
php natan create crud Product --web

# O para API
php natan create crud Product --api

# O ambos
php natan create crud Product --full
```

### 4. **Iniciar servidor**
```bash
php natan serve
# Visita: http://localhost:8000
```

## 📚 **Ejemplos de Código**

### **Modelo con Relaciones**
```php
// app/Shared/Models/Product.php
class Product extends Model 
{
    protected $fillable = ['name', 'price', 'description'];
    
    public function category() 
    {
        return $this->belongsTo(Category::class);
    }
    
    public function reviews() 
    {
        return $this->hasMany(Review::class);
    }
}
```

### **Controlador Web**
```php
// app/Web/Controllers/ProductController.php
class ProductController extends Controller 
{
    public function index() 
    {
        $products = Product::with('category')->paginate(10);
        return $this->view('products.index', compact('products'));
    }
    
    public function store() 
    {
        $product = Product::create(request()->all());
        return redirect('/products');
    }
}
```

### **Controlador API**
```php
// app/Api/Controllers/ProductController.php
class ProductController extends ApiController 
{
    public function index() 
    {
        $products = Product::with('category')->paginate(10);
        return $this->successResponse($products);
    }
    
    public function store() 
    {
        $product = Product::create(request()->json());
        return $this->successResponse($product, 'Producto creado');
    }
}
```

### **Vista Web**
```php
<!-- app/Web/Views/products/index.natan.php -->
@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
<div class="container">
    <h1>Nuestros Productos</h1>
    
    <div class="row">
        @foreach($products->items() as $product)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>{{ $product->description }}</p>
                    <span class="price">${{ $product->price }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{ $products->links() }}
</div>
@endsection
```

## 🎓 **Propósito Educativo**

NatanPHP está diseñado para enseñar:

- **🏗️ Arquitectura MVC**: Cómo separar correctamente responsabilidades
- **🔄 Patrones de Diseño**: Active Record, Factory, Observer
- **📦 Autoloading PSR-4**: Cómo funcionan los namespaces
- **🗄️ ORM**: Construcción de Query Builders y relaciones
- **🎨 Motor de Plantillas**: Compilación y cache de vistas
- **⚡ CLI**: Creación de comandos de consola
- **🚀 APIs REST**: Separación y diseño de endpoints
- **📊 Paginación**: Implementación propia sin librerías

## 🛠️ **Comandos Disponibles**

### **Creación**
```bash
php natan create controller UserController --web
php natan create controller UserController --api
php natan create model User
php natan create crud Product --full
```

### **Base de Datos**
```bash
php natan field add User          # Agregar campos interactivo
php natan field modify User email # Modificar campo
php natan table describe User     # Ver estructura
php natan migrate                 # Ejecutar migraciones
```

### **Proyecto**
```bash
php natan project mode web   # Solo funcionalidad web
php natan project mode api   # Solo funcionalidad API
php natan project mode full  # Funcionalidad completa
```

### **Utilidades**
```bash
php natan routes list        # Listar rutas
php natan serve             # Servidor desarrollo
php natan project optimize  # Limpiar archivos no usados
```

## 🔧 **Configuración**

### **Modos de Proyecto**

**Web Only** - Para aplicaciones tradicionales:
```bash
php natan project mode web
# Elimina: app/Api/, routes/api.php
# Optimiza: Solo funcionalidad web
```

**API Only** - Para APIs REST:
```bash
php natan project mode api  
# Elimina: app/Web/, vistas
# Optimiza: Solo endpoints JSON
```

**Full** - Funcionalidad completa:
```bash
php natan project mode full
# Mantiene: Web + API
# Funcionalidad: Completa
```

## 🌟 **¿Por qué NatanPHP?**

### ✅ **Para Estudiantes:**
- **Core visible**: Puedes leer y entender el código del framework
- **Estructura clara**: No te pierdes en carpetas complicadas
- **Progresión**: Empieza simple, crece según necesidades
- **Documentado**: Cada archivo explica qué hace y por qué

### ✅ **Para Desarrolladores:**
- **Productivo**: CRUD completo en un comando
- **Flexible**: Separa web y API claramente
- **Moderno**: Sintaxis familiar a Laravel
- **Ligero**: Sin complejidad innecesaria

### ✅ **Para Proyectos:**
- **Escalable**: Crece con tu aplicación
- **Mantenible**: Código limpio y organizado
- **Rápido**: Sin overhead de frameworks pesados
- **Educativo**: Tu equipo aprende mientras desarrolla

## 📖 **Documentación**

- [📋 Guía de Inicio](docs/getting-started.md)
- [🏗️ Arquitectura](docs/architecture.md)
- [⚡ Comandos CLI](docs/commands.md)
- [🗄️ ORM y Base de Datos](docs/database.md)
- [🎨 Motor de Plantillas](docs/templating.md)
- [🚀 API REST](docs/api.md)

## 🤝 **Contribuir**

¡Las contribuciones son bienvenidas! Este es un proyecto educativo y cualquier mejora ayuda a la comunidad.

1. Fork el proyecto
2. Crea tu rama de feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 **Licencia**

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 👨‍💻 **Autor**

**Jhonatan Fernández** - [@jhonatanfdez](https://github.com/jhonatanfdez)

---

<div align="center">
  
  
  **⭐ Si te gusta NatanPHP, ¡dale una estrella! ⭐**
  
  *Hecho con ❤️ para la comunidad educativa*
  
</div>
