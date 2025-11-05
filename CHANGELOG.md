# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto se adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Sin publicar]

### Próximo
- Implementación de controladores de ejemplo para probar Router.php

## [v0.1.3] - 2025-11-04

### Agregado
- Implementación completa de `core/Router.php` - Sistema de rutas dinámico
  - Soporte para métodos HTTP: GET, POST, PUT, DELETE, PATCH, MATCH, ANY
  - Parámetros dinámicos en rutas: `/usuario/{id}`, `/posts/{slug}`
  - Grupos de rutas con prefijos y middleware compartido
  - Resolución automática de controladores Web vs API
  - Inyección automática de parámetros en métodos de controladores
  - Patrón Fluent Interface para configuración avanzada
  - RouteRegistrar para middleware y nombres de rutas
  - Comentarios educativos extensos para facilitar aprendizaje

### Cambiado
- Centralización de gestión de versiones del framework
  - Nueva función `version()` en helpers.php como única fuente
  - Eliminadas versiones hardcodeadas de archivos individuales
  - Garantizada consistencia de versión en todo el framework

### Mejorado
- Documentación de helpers en README.md con función `version()`
- Comentarios más detallados y educativos en todo el código Router.php
- Mejor organización de código con Single Source of Truth para versiones

## [v0.1.2] - 2025-11-04

### Agregado
- Implementación completa de `core/Request.php` para manejo de peticiones HTTP
- Más de 20 métodos públicos para acceso a datos de peticiones
- Soporte completo para GET, POST, FILES, headers y detección de métodos HTTP
- Integración con helpers existentes del framework (`filled()`)
- Detección automática de peticiones AJAX y JSON para APIs
- Manejo seguro de archivos subidos con validación
- Soporte para proxies y load balancers en detección de IP

### Mejorado
- Documentación del framework actualizada con funcionalidades de Request.php
- Roadmap actualizado reflejando progreso en clases core

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