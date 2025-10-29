<div align="center">
  <img src="logo.jpeg" alt="NatanPHP Logo" width="200"/>
  
  # NatanPHP Framework
  
  *Framework PHP MVC Simple, Moderno e Innovador*
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://php.net)
  [![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
  [![Educational](https://img.shields.io/badge/Purpose-Educational-orange)](https://github.com/jhonatanfdez/natan-php)
  
  **Un framework PHP diseñado para enseñar y aprender cómo funcionan los frameworks modernos por dentro**
</div>

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
