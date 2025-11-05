# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto se adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Sin publicar]

### Próximo
- Implementación de named routes en Router.php
- Sistema de configuración avanzado
- Database migrations y ORM
- Middleware system completo

## [v0.1.6] - 2025-11-04

### Agregado
- **🖥️ Compatibilidad completa Windows** - CLI multiplataforma verdadero
  - Auto-detección de sistema operativo (Windows/Unix/macOS/Linux)
  - Comandos específicos Windows: `netstat -ano | findstr :puerto` y `taskkill /PID /F`
  - Comandos Unix/Linux/macOS: `lsof -ti:puerto`, `fuser -n tcp`, `kill -9`
  - Zero-configuration: funciona automáticamente en cualquier SO

### Mejorado
- **Comando `natan serve` multiplataforma** - UX consistente entre sistemas
  - Detección inteligente con `PHP_OS` para seleccionar comandos correctos
  - Información de compatibilidad en comando `help`
  - URLs claras sin confusión de `0.0.0.0` en mensajes
  - Recomendaciones específicas para entorno DDEV vs normal

- **Gestión robusta de procesos cross-platform**
  - Windows: `taskkill /PID {$pid} /F 2>nul` para terminar procesos
  - Unix/Linux: `kill -9 {$pid} 2>/dev/null` estándar POSIX
  - macOS: `lsof -ti:{$port}` nativo optimizado
  - Manejo de errores silencioso con redirección apropiada por SO

- **UX mejorado para DDEV**
  - URLs específicas y claras: `https://natanphp-framework.ddev.site:8081 (recomendado)`
  - Eliminada confusión de `0.0.0.0` en mensajes de usuario
  - Información técnica separada de URLs útiles
  - Detección automática de entorno DDEV

### Arreglado
- **Cross-platform compatibility**: Comando funciona en Windows, macOS y Linux
- **Port management**: Gestión de puertos robusta en todos los sistemas operativos
- **User experience**: Mensajes claros sin tecnicismos confusos
- **Process termination**: Comando kill apropiado para cada sistema

### Cambiado
- **Help command**: Incluye sección de compatibilidad de sistemas operativos
- **Serve messages**: URLs útiles en lugar de información técnica confusa
- **Error handling**: Redirección de errores apropiada por plataforma (`2>nul` vs `2>/dev/null`)

### Testing
- ✅ Auto-detección de Windows verificada con `PHP_OS`
- ✅ Comandos Windows (`netstat`/`taskkill`) probados
- ✅ Comandos Unix/Linux/macOS (`lsof`/`fuser`/`kill`) funcionando
- ✅ DDEV URLs claras y funcionamiento confirmado
- ✅ UX consistente entre diferentes sistemas operativos

### Técnico
- **Filosofía "Write Once, Run Anywhere"**: Verdadera portabilidad PHP
- **Cross-platform native commands**: Usa herramientas nativas de cada SO
- **Zero-external-dependencies**: No requiere herramientas adicionales
- **Professional UX**: Experiencia consistente independiente del SO

### Compatibilidad
- 🖥️ **Windows**: `netstat`/`taskkill` nativos
- 🍎 **macOS**: `lsof`/`kill` optimizados  
- 🐧 **Linux**: `fuser`/`netstat`/`kill` POSIX
- 🐳 **DDEV**: Auto-detección y configuración inteligente

## [v0.1.5] - 2025-11-04

### Agregado
- **Comando CLI `natan serve` robusto** - Gestión inteligente de puertos
  - Auto-detección de puertos ocupados con múltiples métodos (lsof, fuser, netstat)
  - Terminación automática de procesos que usan puertos
  - Liberación inteligente de puertos antes de iniciar servidor
  - Manejo automático de directorios (cambio a public/)
  - Verificación multi-método para máxima compatibilidad en diferentes sistemas

### Mejorado
- **Función `checkAndFreePort()`** - Gestión robusta de puertos
  - Soporte para múltiples comandos de detección de procesos
  - Mejor feedback al usuario sobre estado de puertos
  - Manejo de errores mejorado con múltiples fallbacks
  - Espera inteligente para liberación de puertos

- **Compatibilidad PHP 8.2+**
  - Fix de warnings "trim(): Passing null deprecated"
  - Manejo seguro de valores null en operaciones de string
  - Código compatible con versiones modernas de PHP

- **Documentación del proyecto**
  - Historial extendido en comandos_ejecutados.txt
  - Documentación de correcciones post-release v0.1.4
  - Procedimientos de verificación y testing actualizados

### Arreglado
- **Problema crítico**: Puerto ocupado impedía iniciar servidor de desarrollo
- **PHP 8.2 warnings**: Eliminados warnings de deprecación en trim()
- **Manejo de directorios**: Cambio automático al directorio correcto (public/)
- **Detección de procesos**: Múltiples métodos para diferentes sistemas operativos

### Cambiado
- **Comando `natan serve`**: Ahora es completamente automático y robusto
- **Feedback del usuario**: Información más detallada sobre el estado del servidor
- **Gestión de errores**: Manejo elegante de puertos ocupados

### Testing
- ✅ Comando natan serve funcional en puertos libres
- ✅ Auto-detección de puertos ocupados verificada
- ✅ Liberación automática de puertos probada
- ✅ Compatibilidad PHP 8.2+ confirmada
- ✅ Manejo de directorios correcto (public/public)

### Técnico
- **Filosofía "Simplicidad con Propósito"**: CLI que funciona sin configuración
- **Zero-friction development**: Servidor que se inicia automáticamente
- **Cross-platform compatibility**: Múltiples métodos de detección de procesos
- **Professional UX**: Feedback claro y útil para desarrolladores

## [v0.1.4] - 2025-11-04

### Agregado
- **Sistema de URLs dinámicas** - Detección automática de entorno
  - Auto-detección de protocolo (HTTP/HTTPS) desde `$_SERVER`
  - Detección automática de host y puerto del servidor actual
  - Compatibilidad total con DDEV y PHP built-in server
  - URLs que se adaptan automáticamente sin configuración manual

- **Comando CLI `natan`** - Herramienta de línea de comandos completa
  - `php natan serve` - Inicia servidor de desarrollo con configuración del .env
  - `php natan version` - Muestra versión del framework y PHP
  - `php natan help` - Ayuda completa con ejemplos y URLs dinámicas
  - Configuración automática desde APP_URL en .env
  - Soporte para host y puerto personalizados
  - Archivo ejecutable con chmod +x

### Mejorado
- **Función `url()`** en helpers.php
  - Detección inteligente de protocolo usando `$_SERVER['HTTPS']`
  - Auto-detección de `HTTP_HOST` con fallback a `SERVER_NAME`
  - Soporte para puertos no estándar automáticamente
  - Funciona en cualquier entorno sin configuración

- **Función `asset()`** actualizada para usar URLs dinámicas
  - Documentación extendida con ejemplos por entorno
  - Utiliza internamente la función url() para consistencia

- **Función `route()`** preparada para named routes con URLs dinámicas
  - Base sólida para futuro sistema de rutas nombradas
  - Documentación completa con ejemplos de uso

- **HomeController Web** con URLs dinámicas pasadas a vista
  - Eliminado código duplicado de detección de URLs
  - Usa helpers url() para máxima consistencia
  - Variables dinámicas: baseUrl, apiUrl, versionUrl, healthUrl

- **Router.php** - Detección automática de controladores Web vs API
  - Fix crítico: Detección mejorada de peticiones API para /api sin barra
  - Detección inteligente: `/api`, `/api/` y headers `Accept: application/json`
  - Resolución automática de namespaces sin especificar en rutas
  - Documentación extendida del algoritmo de detección

### Arreglado
- **Problema crítico**: Links API en homepage mostraban localhost:8080 incluso en DDEV
- **Problema crítico**: `/api` sin barra redirigía a controlador Web en lugar de API
- **URLs hardcodeadas**: Eliminadas todas las URLs fijas por sistema dinámico
- **Comando natan**: Implementado completamente y funcional
- **Router namespace detection**: Mejorada lógica para detectar peticiones API

### Cambiado
- **APP_URL en .env**: Actualizado de localhost:8000 a localhost:8080 (estándar)
- **Homepage**: Ya no usa URLs hardcodeadas, todas dinámicas
- **Rutas API**: Agregada ruta duplicada `/api/` para compatibilidad total
- **Documentación**: Extendida en todos los helpers con ejemplos reales

### Configuración
- **.env** actualizado con configuración estándar
- **router.php** preparado para PHP built-in server
- **Permisos**: Comando natan marcado como ejecutable

### Entornos Soportados
- ✅ **DDEV**: `https://natanphp-framework.ddev.site` - Funcional al 100%
- ✅ **PHP built-in server**: `http://localhost:8080` - Funcional al 100%  
- ✅ **Apache estándar**: `http://example.com` - Compatibilidad verificada
- ✅ **HTTPS custom**: `https://myapp.local:8443` - Soporte completo

### Testing
- URLs dinámicas probadas en 4 entornos diferentes
- Comando natan serve funcional con configuración .env
- Endpoints API (/api, /api/version, /api/health) funcionando correctamente
- Sistema de namespace auto-detection verificado
- Homepage con links dinámicos funcionales

### Técnico
- **Filosofía "Simplicidad con Propósito"**: Framework que se adapta automáticamente
- **Zero-configuration**: URLs funcionan sin setup manual en cualquier entorno
- **Environment-agnostic**: Compatible con cualquier servidor web
- **Developer Experience**: Comandos CLI intuitivos y documentación extensa
- **Clean Code**: Eliminación de código duplicado y uso de helpers consistente
- **Vista homepage** con links API generados dinámicamente

### Cambiado
- Homepage ya no usa URLs hardcodeadas (`localhost:8080`)
- Todas las URLs se generan dinámicamente según el entorno actual
- Documentación extendida en helpers.php con ejemplos por entorno

### Arreglado
- **Problema crítico**: Links API en homepage mostraban localhost:8080 incluso en DDEV
- URLs ahora funcionan correctamente en:
  - DDEV: `https://natanphp-framework.ddev.site`
  - PHP built-in server: `http://localhost:8080`
  - Cualquier configuración de servidor

### Técnico
- Filosofía "Simplicidad con Propósito": Framework que se adapta automáticamente
- Zero-configuration: URLs funcionan sin setup manual
- Environment-agnostic: Compatible con cualquier servidor

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