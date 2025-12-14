# NatanPHP Framework

> Framework PHP MVC Simple, Moderno e Innovador

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://php.net)
[![Version](https://img.shields.io/badge/version-0.2.0-brightgreen)](https://github.com/jhonatanfdez/natan-php/releases)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-140%20passing-success)](https://github.com/jhonatanfdez/natan-php)

---

## 👋 Bienvenido

**NatanPHP** es un framework PHP diseñado para enseñar y aprender cómo funcionan los frameworks modernos por dentro. Con una arquitectura simple pero poderosa, NatanPHP te permite construir aplicaciones web y APIs de forma rápida y educativa.

### ✨ Características Principales

- 🛣️ **Router Dinámico** - Sistema de rutas con parámetros, grupos y middleware
- 📨 **Request Completo** - Manejo robusto de peticiones HTTP
- 🛠️ **24 Helper Functions** - Funciones utilitarias listas para usar
- 🧪 **100% Testeado** - 140 tests con cobertura completa
- 🖥️ **CLI Tool** - Comando `natan` para desarrollo rápido
- 📚 **Documentación Completa** - Guías detalladas y ejemplos reales
- 🌐 **Web & API** - Estructura separada para controladores

---

## 🚀 Inicio Rápido

### Instalación

```bash
# Clonar el repositorio
git clone https://github.com/jhonatanfdez/natan-php.git
cd natan-php/docroot

# Instalar dependencias
composer install

# Iniciar servidor de desarrollo
php natan serve
```

### Tu Primera Ruta

```php
// routes/web.php
use NatanPHP\Core\Router;

Router::get('/hola', function() {
    echo "¡Hola desde NatanPHP!";
});
```

Visita `http://localhost:8000/hola` y verás tu mensaje.

### Tu Primer Controlador

```php
// app/Web/Controllers/WelcomeController.php
namespace NatanPHP\App\Web\Controllers;

class WelcomeController
{
    public function index()
    {
        echo "<h1>Bienvenido a NatanPHP Framework</h1>";
    }
}
```

```php
// routes/web.php
Router::get('/welcome', 'WelcomeController@index');
```

---

## 📖 Guías de Documentación

### Para Principiantes

Si eres nuevo en frameworks PHP, empieza aquí:

1. [⚡ Instalación](installation.md) - Configura tu entorno
2. [📁 Estructura de Directorios](directory-structure.md) - Entiende la organización
3. [🛣️ Routing](basics/routing.md) - Aprende a crear rutas
4. [🎮 Controllers](basics/controllers.md) - Maneja la lógica de tu app

### Para Desarrolladores

Si ya conoces PHP y frameworks MVC:

- [🛣️ Routing Avanzado](basics/routing.md#routing-avanzado) - Grupos, middleware, parámetros
- [📨 Request](basics/requests.md) - Acceso a datos HTTP
- [🛠️ Helpers](digging-deeper/helpers.md) - 24 funciones útiles
- [🧪 Testing](testing/getting-started.md) - Escribe tests con PHPUnit

---

## 💡 ¿Por Qué NatanPHP?

### Educativo y Práctico

A diferencia de otros frameworks, NatanPHP está diseñado específicamente para **aprender**:

- ✅ Código fuente legible y bien comentado
- ✅ Sin "magia" - todo es explícito y comprensible
- ✅ Documentación detallada con explicaciones del "por qué"
- ✅ Ejemplos reales, no triviales

### Moderno pero Simple

- ✅ PHP 8.0+ con tipado estricto
- ✅ PSR-4 Autoloading
- ✅ Composer para dependencias
- ✅ PHPUnit para testing
- ✅ Arquitectura MVC clara

### Listo para Producción

Aunque es educativo, NatanPHP es un framework funcional:

- ✅ 140 tests, 320+ assertions
- ✅ 100% de cobertura en componentes core
- ✅ Sistema de rutas robusto
- ✅ Manejo completo de peticiones HTTP
- ✅ CLI tool para desarrollo

---

## 🎯 Casos de Uso

### APIs RESTful

```php
// routes/api.php
Router::group(['prefix' => 'api/v1'], function() {
    Router::get('/usuarios', 'Api\UsuariosController@index');
    Router::post('/usuarios', 'Api\UsuariosController@store');
    Router::get('/usuarios/{id}', 'Api\UsuariosController@show');
    Router::put('/usuarios/{id}', 'Api\UsuariosController@update');
    Router::delete('/usuarios/{id}', 'Api\UsuariosController@destroy');
});
```

### Aplicaciones Web

```php
// routes/web.php
Router::get('/', 'HomeController@index');
Router::get('/productos', 'ProductosController@index');
Router::get('/productos/{id}', 'ProductosController@show');
Router::post('/contacto', 'ContactoController@enviar');
```

### Proyectos Educativos

Ideal para:
- Aprender arquitectura MVC
- Entender cómo funcionan los frameworks
- Enseñar PHP moderno en universidades
- Proyectos de estudiantes

---

## 📊 Estadísticas del Proyecto

```
Versión:             v0.2.0
Tests:               140 tests pasando
Assertions:          320+
Cobertura:           100% componentes core
Helpers:             24 funciones
Líneas de código:    ~2,500 LOC
Documentación:       Completa
Licencia:            MIT
```

---

## 🤝 Comunidad y Soporte

### Reportar Problemas

¿Encontraste un bug? [Abre un issue](https://github.com/jhonatanfdez/natan-php/issues)

### Contribuir

¿Quieres mejorar NatanPHP? Lee nuestra [Guía de Contribución](contributions/contribution-guide.md)

### Preguntas

¿Tienes dudas? Revisa:
- [Documentación completa](/)
- [Ejemplos de código](https://github.com/jhonatanfdez/natan-php/tree/main/docroot)
- [Issues resueltos](https://github.com/jhonatanfdez/natan-php/issues?q=is%3Aissue+is%3Aclosed)

---

## 📚 Recursos Adicionales

### En Este Sitio

- [Instalación Completa](installation.md)
- [Guía de Routing](basics/routing.md)
- [Referencia de Helpers](digging-deeper/helpers.md)
- [Testing con PHPUnit](testing/getting-started.md)

### Enlaces Externos

- [Repositorio GitHub](https://github.com/jhonatanfdez/natan-php)
- [Changelog](https://github.com/jhonatanfdez/natan-php/blob/main/CHANGELOG.md)
- [Licencia MIT](https://github.com/jhonatanfdez/natan-php/blob/main/LICENSE)

---

## 🚦 Roadmap

### v0.2.0 (Actual) ✅
- ✅ Router completo con parámetros dinámicos
- ✅ Request con 20+ métodos
- ✅ 24 Helper functions
- ✅ 140 tests con 100% cobertura
- ✅ CLI tool `natan serve`
- ✅ Documentación completa

### v0.3.0 (Próximo) 🚧
- ⏳ Sistema de vistas/templates
- ⏳ Middleware avanzado
- ⏳ Validación de formularios
- ⏳ Session management

### v0.4.0 (Futuro) 💭
- 💡 Database Query Builder
- 💡 ORM básico
- 💡 Migraciones
- 💡 Authentication

---

## ⚖️ Licencia

NatanPHP Framework es software de código abierto licenciado bajo [MIT](https://github.com/jhonatanfdez/natan-php/blob/main/LICENSE).

---

## 💖 Hecho con Amor

Desarrollado por [JhonatanFdez](https://github.com/jhonatanfdez) con el objetivo de hacer PHP más accesible y enseñar cómo funcionan los frameworks modernos.

**¿Te gusta NatanPHP?** Dale una ⭐ en [GitHub](https://github.com/jhonatanfdez/natan-php)

---

<div style="text-align: center; padding: 40px 0;">
  <h3>¿Listo para empezar?</h3>
  <p>
    <a href="installation" style="display: inline-block; padding: 12px 30px; background: #4ecdc4; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">📦 Instalar NatanPHP</a>
  </p>
</div>
