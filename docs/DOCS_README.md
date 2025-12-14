# 📚 Documentación de NatanPHP Framework

> Documentación completa en español para el framework PHP educativo NatanPHP v0.2.2

---

## 🌐 Ver Documentación Online

**GitHub Pages:** [https://jhonatanfdez.github.io/natan-php/](https://jhonatanfdez.github.io/natan-php/)

---

## 📖 Contenido

### 🚀 Primeros Pasos
- [**Inicio**](README.md) - Introducción, características y roadmap
- [**Instalación**](installation.md) - Guía completa de instalación con requisitos y troubleshooting

### 📘 Conceptos Básicos
- [**Routing**](basics/routing.md) - Sistema de rutas con parámetros dinámicos, grupos y middleware (~850 líneas)
- [**Request**](basics/requests.md) - Manejo de peticiones HTTP, datos, archivos y headers (~1,180 líneas)
- [**Controllers**](basics/controllers.md) - Controladores Web y API con ejemplos CRUD completos (~1,163 líneas) **✨ NUEVO**
- [**Middleware**](basics/middleware.md) - Sistema de middleware con arquitectura y ejemplos (~1,450 líneas) **✨ NUEVO**
- [**Responses**](basics/responses.md) - Respuestas JSON, HTML, redirects y códigos HTTP (~1,650 líneas) **✨ NUEVO**

### 🔧 Profundizando
- [**Helpers**](digging-deeper/helpers.md) - 10 funciones auxiliares globales con ejemplos completos (~1,450 líneas)

### ⚙️ Configuración
- [**Configuration**](configuration.md) - Variables de entorno y configuración por entorno (~670 líneas)
- [**Directory Structure**](directory-structure.md) - Estructura completa de carpetas y convenciones (~980 líneas)

---

## 📊 Estadísticas

- **📄 10 páginas completas** de documentación (+3 en v0.2.2)
- **📏 ~9,943 líneas** de contenido (~4,263 líneas nuevas)
- **💻 200+ ejemplos** de código funcional (+50 ejemplos nuevos)
- **📊 30+ tablas** de referencia (+10 tablas nuevas)
- **🎯 25+ ejemplos completos** de casos reales
- **🌍 100% en español** (traducción al inglés planeada)

---

## 🛠️ Tecnología

Esta documentación está construida con:

- [**Docsify**](https://docsify.js.org/) - Generador de sitios de documentación
- **Markdown** - Formato simple y legible
- **GitHub Pages** - Hosting gratuito

### Características:

- ✅ Búsqueda en tiempo real
- ✅ Navegación con sidebar
- ✅ Syntax highlighting para PHP, Bash, JSON
- ✅ Copy-to-clipboard en bloques de código
- ✅ Paginación entre documentos
- ✅ Zoom en imágenes
- ✅ Emojis nativos
- ✅ 100% responsive

---

## 🚀 Ejecutar Localmente

### Opción 1: Con Docsify CLI

```bash
# Instalar docsify-cli globalmente
npm i docsify-cli -g

# Servir documentación
cd docs/
docsify serve

# Abrir en navegador
open http://localhost:3000
```

### Opción 2: Con Servidor PHP

```bash
# Desde la carpeta docs
cd docs/
php -S localhost:8080

# Abrir en navegador
open http://localhost:8080
```

### Opción 3: Con Python

```bash
# Python 3
cd docs/
python3 -m http.server 8080

# Abrir en navegador
open http://localhost:8080
```

---

## 📝 Contribuir

¿Quieres mejorar la documentación?

1. **Fork** el repositorio
2. **Edita** los archivos Markdown en `docs/`
3. **Prueba** localmente con `docsify serve`
4. **Crea** un Pull Request

### Guía de Estilo

- ✅ Usar español claro y conciso
- ✅ Incluir ejemplos de código funcionales
- ✅ Agregar emojis para mejor legibilidad
- ✅ Explicar casos de uso reales
- ✅ Incluir advertencias de seguridad cuando sea necesario
- ✅ Links a documentos relacionados

---

## 📂 Estructura de Archivos

```
docs/
├── index.html              # Configuración de Docsify
├── _sidebar.md             # Navegación del sidebar
├── README.md               # Página principal
├── installation.md         # Guía de instalación
├── configuration.md        # Configuración y .env
├── directory-structure.md  # Estructura de carpetas
│
├── basics/                 # Conceptos básicos
│   ├── routing.md          # Sistema de routing
│   └── requests.md         # Manejo de peticiones
│
└── digging-deeper/         # Conceptos avanzados
    └── helpers.md          # Funciones helper
```

---

## 🗓️ Roadmap de Documentación

### ✅ FASE 1: Estructura (Completado)
- Configuración de Docsify
- Sidebar y navegación
- Landing page

### ✅ FASE 2: Páginas Esenciales (Completado)
- README principal
- Installation
- Routing (~850 líneas)
- Request (~1,180 líneas)
- Helpers (~1,450 líneas)
- Configuration (~670 líneas)
- Directory Structure (~980 líneas)

### 🚧 FASE 3: Páginas Básicas (Planeado)
- Controllers
- Middleware
- Responses

### 📋 FASE 4: Frontend (Planeado)
- Assets (CSS, JS, imágenes)

### 🧪 FASE 5: Testing (Planeado)
- Getting Started
- PHPUnit
- Testing Helpers

### 🤝 FASE 6: Contribuciones (Planeado)
- Contribution Guide
- Code of Conduct

---

## 📜 Licencia

NatanPHP Framework y su documentación están bajo la licencia MIT.

---

## 🙋 Soporte

- **GitHub Issues:** [Reportar problema](https://github.com/jhonatanfdez/natan-php/issues)
- **Repositorio:** [jhonatanfdez/natan-php](https://github.com/jhonatanfdez/natan-php)
- **Versión:** v0.2.0
- **Última actualización:** Diciembre 2025

---

> 💡 **Tip:** Esta documentación fue creada con amor ❤️ para ayudar a desarrolladores a aprender PHP moderno.

> 🌟 Si esta documentación te ayuda, considera darle una ⭐ al repositorio.
