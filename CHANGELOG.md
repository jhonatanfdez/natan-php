# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto se adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Sin publicar]

### Próximo
- Implementación de `core/Request.php` para manejo de peticiones HTTP

## [v0.1.1] - 2025-11-04

### Cambiado
- Simplificación de `core/helpers.php` de 20+ funciones a 8 funciones esenciales
- Reorganización en secciones claras: Debugging, Configuración, URLs, Strings, Utilidades
- Mejora significativa de documentación con comentarios detallados y ejemplos
- Estrategia incremental: agregar funciones solo cuando se necesiten
- Actualización de README.md y documentación reflejando cambios reales

### Funciones Mantenidas
- **Debugging**: `dd()` - Debug con var_dump y terminación
- **Configuración**: `env()`, `config()` - Variables y configuración
- **URLs**: `url()`, `asset()` - URLs absolutas y assets
- **Strings**: `str_slug()` - Conversión a slug
- **Utilidades**: `blank()`, `filled()` - Validación de contenido

### Eliminado
- Funciones no prioritarias que se agregarán según necesidades
- Backup mantenido en `helpers_backup.php`

### Mejorado
- Documentación precisa y honesta del estado real del framework
- Comentarios PHPDoc detallados con ejemplos de uso
- Estrategia de desarrollo incremental establecida

## [v0.1.0] - 2025-10-28

### Agregado
- Estructura inicial del framework NatanPHP
- Separación clara de carpetas Web/API
- Configuración de autoloading PSR-4 con Composer
- Instalación de dependencias básicas (`vlucas/phpdotenv`)
- Archivos de configuración base (app, database, cache)
- Comando CLI `natan` preparado
- Archivo `.env.example` con configuración DDEV
- Archivos core vacíos listos para implementación
- Sistema de rutas separadas (web.php, api.php)

### Configurado
- Composer con namespaces `Core\` y `App\`
- DDEV para desarrollo local
- Git con repositorio inicial
- README.md completo con documentación

### Estructura
```
natan-php/
├── core/                    # Núcleo del framework
├── app/
│   ├── Web/                # Funcionalidad web
│   ├── Api/                # Funcionalidad API
│   ├── Shared/             # Compartido entre Web/API
│   └── Database/           # Migraciones y seeds
├── routes/                 # Rutas web y API
├── config/                 # Configuración
├── storage/                # Cache, logs, uploads
└── public/                 # Punto de entrada
```

### Notas
- Framework diseñado con propósito educativo
- Filosofía "Simplicidad con Propósito"
- Separación innovadora Web/API desde el diseño
- Core accesible para aprendizaje