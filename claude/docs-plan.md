# 📖 Plan Maestro de Documentación - NatanPHP Framework

**Fecha de creación:** 14 diciembre 2024  
**Versión del framework:** v0.2.0  
**Estado:** 🚧 EN ANÁLISIS

---

## 🎯 OBJETIVO

Crear documentación completa y profesional del framework NatanPHP siguiendo los estándares de Laravel, ubicada en `/docs` y publicada vía GitHub Pages.

---

## 📊 ANÁLISIS DEL FRAMEWORK ACTUAL

### **Estado General**
- **Versión:** v0.2.0
- **Testing:** 140 tests, 320+ assertions, 100% cobertura
- **Componentes core:** 3 clases (Router, Request, RouteRegistrar)
- **Helpers:** 22 funciones globales
- **Controllers:** Estructura Web/API separada
- **CLI:** Comando `natan` funcional

---

## 🔍 INVENTARIO COMPLETO DE COMPONENTES

### **1. CORE CLASSES (3 clases)**

#### **Router** (`core/Router.php`)
- ✅ Métodos HTTP: `get()`, `post()`, `put()`, `delete()`, `patch()`
- ✅ Métodos avanzados: `match()`, `any()`
- ✅ Grupos: `group()` con prefijos y middleware
- ✅ Parámetros dinámicos: `{id}`, `{slug}`, `{param?}`
- ✅ Resolución: `resolve()`, `getParameters()`, `getParameter()`
- ✅ Resource routes: `resource()`, `apiResource()`
- ✅ Fluent interface: RouteRegistrar

**Características:**
- Parámetros opcionales con `?`
- Middleware por ruta o grupo
- Resolución automática Web vs API
- Inyección de parámetros en controladores

#### **Request** (`core/Request.php`)
- ✅ Métodos HTTP: `method()`, `isGet()`, `isPost()`, `isPut()`, `isDelete()`
- ✅ Datos: `get()`, `post()`, `input()`, `all()`, `only()`, `except()`
- ✅ Query string: `query()`
- ✅ Headers: `header()`, `headers()`, `hasHeader()`
- ✅ Archivos: `file()`, `hasFile()`, `files()`
- ✅ Cookies: `cookie()`, `hasCookie()`, `cookies()`
- ✅ Path/URL: `path()`, `url()`, `fullUrl()`
- ✅ Detección: `ajax()`, `wantsJson()`, `ip()`, `userAgent()`

**Características:**
- Prioridad POST sobre GET en `input()`
- Headers case-insensitive
- Detección de proxies para IP real
- Soporte para AJAX y JSON

#### **RouteRegistrar** (`core/Router.php`)
- ✅ `middleware()`: Asignar middleware a ruta
- ✅ `name()`: Nombrar ruta
- ✅ Fluent interface para encadenamiento

---

### **2. HELPERS (22 funciones)**

#### **Debugging**
1. ✅ `dd(...$vars)` - Debug and die
2. ✅ `dump(...$vars)` - Debug sin terminar ejecución

#### **Configuración**
3. ✅ `env($key, $default)` - Variables de entorno
4. ✅ `config($key, $default)` - Acceso a configuración

#### **URLs y Assets**
5. ✅ `url($path)` - Generar URL absoluta
6. ✅ `asset($path)` - URL de assets estáticos
7. ✅ `route($name, $params)` - Generar URL de ruta nombrada

#### **HTTP Helpers**
8. ✅ `redirect($path, $status)` - Redirecciones HTTP
9. ✅ `abort($code, $message)` - Respuestas de error

#### **Formularios**
10. ✅ `old($key, $default)` - Recuperar valores de formulario
11. ✅ `csrf_token()` - Generar token CSRF
12. ✅ `csrf_field()` - Campo HTML CSRF
13. ✅ `method_field($method)` - Campo de método HTTP

#### **Strings**
14. ✅ `str_slug($text, $separator)` - Convertir a slug
15. ✅ `str_contains($haystack, $needle)` - Buscar en string
16. ✅ `str_starts_with($haystack, $needle)` - Verificar inicio
17. ✅ `str_ends_with($haystack, $needle)` - Verificar fin
18. ✅ `e($value)` - Escapar HTML (XSS protection)

#### **Validación y Arrays**
19. ✅ `blank($value)` - Verificar si está vacío
20. ✅ `filled($value)` - Verificar si tiene valor
21. ✅ `array_get($array, $key, $default)` - Acceso con dot notation

#### **Utilidades**
22. ✅ `version()` - Versión del framework
23. ✅ `value($value)` - Resolver valor o closure
24. ✅ `class_basename($class)` - Nombre base de clase

---

### **3. ESTRUCTURA DEL PROYECTO**

```
NatanPHP-Framework/
├── app/
│   ├── Web/
│   │   └── Controllers/        # Controladores web
│   ├── Api/
│   │   └── Controllers/        # Controladores API
│   ├── Database/
│   │   └── Models/            # Modelos (futuro)
│   └── Shared/
│       └── Middleware/        # Middleware compartido
├── core/
│   ├── helpers.php            # Funciones globales
│   ├── Request.php            # Manejo de peticiones
│   └── Router.php             # Sistema de rutas
├── routes/
│   ├── web.php                # Rutas web
│   └── api.php                # Rutas API
├── config/
│   ├── app.php                # Configuración app
│   ├── database.php           # Configuración DB
│   └── cache.php              # Configuración cache
├── public/
│   └── index.php              # Punto de entrada
├── tests/
│   ├── Unit/                  # Tests unitarios
│   └── Integration/           # Tests integración
├── bootstrap.php              # Inicialización
└── natan                      # CLI tool
```

---

### **4. CLI TOOL**

#### **Comando `natan serve`**
- ✅ Servidor de desarrollo built-in PHP
- ✅ Gestión automática de puertos
- ✅ Auto-detección de SO (Windows/macOS/Linux)
- ✅ Liberación inteligente de puertos ocupados
- ✅ Compatibilidad multiplataforma

**Características:**
- Puerto por defecto: 8000
- Auto-navegación al public/
- Terminación automática de procesos
- Mensajes claros para usuario

---

## 📚 ESTRUCTURA DE DOCUMENTACIÓN PROPUESTA

### **Basada en Laravel Docs Structure**

```
docs/
├── index.md                   # Home - Introducción general
├── installation.md            # Instalación y requisitos
├── configuration.md           # Configuración del framework
├── directory-structure.md     # Estructura de directorios
│
├── basics/
│   ├── routing.md            # Sistema de rutas completo
│   ├── middleware.md         # Middleware (básico)
│   ├── controllers.md        # Controladores Web/API
│   ├── requests.md           # Clase Request
│   └── responses.md          # Respuestas HTTP
│
├── frontend/
│   ├── views.md              # Vistas (futuro)
│   └── assets.md             # Assets estáticos
│
├── digging-deeper/
│   ├── helpers.md            # Todas las funciones helper
│   ├── collections.md        # Arrays y colecciones (futuro)
│   └── error-handling.md     # Manejo de errores
│
├── database/
│   ├── getting-started.md    # Introducción (futuro)
│   ├── query-builder.md      # Query Builder (futuro)
│   └── migrations.md         # Migraciones (futuro)
│
├── testing/
│   ├── getting-started.md    # Introducción a testing
│   ├── phpunit.md            # PHPUnit setup
│   └── testing-helpers.md    # Helpers de testing
│
└── contributions/
    ├── contribution-guide.md # Cómo contribuir
    └── code-of-conduct.md    # Código de conducta
```

---

## 🎨 BENCHMARKING: DOCUMENTACIÓN DE LARAVEL

### **Lo que Laravel hace bien y debemos adoptar:**

1. **Estructura clara por niveles:**
   - Prologue (introducción)
   - Getting Started (primeros pasos)
   - The Basics (fundamentos)
   - Digging Deeper (avanzado)

2. **Cada página incluye:**
   - Tabla de contenidos automática
   - Ejemplos de código claros
   - Notas, warnings y tips destacados
   - Links relacionados

3. **Código de ejemplo:**
   - Sintaxis highlighting
   - Comentarios explicativos
   - Casos de uso reales
   - Output esperado

4. **Navegación:**
   - Sidebar fijo con todas las secciones
   - Breadcrumbs
   - Previous/Next en cada página
   - Búsqueda integrada

5. **Estilo de escritura:**
   - Tono educativo pero profesional
   - Explicación del "por qué", no solo el "cómo"
   - Casos de uso antes del código
   - Progresión de simple a complejo

---

## 📋 PLAN DE IMPLEMENTACIÓN

### **FASE 1: ESTRUCTURA INICIAL** ⏱️ ~2 horas

**Objetivo:** Crear estructura de carpetas y archivos base

**Tareas:**
1. ✅ Crear carpeta `/docs` en docroot
2. ✅ Crear subcarpetas: basics/, frontend/, digging-deeper/, testing/, contributions/
3. ✅ Crear archivos `.md` vacíos para todas las páginas
4. ✅ Crear `index.md` con índice general y enlaces
5. ✅ Crear `_sidebar.md` para navegación (si usamos Docsify)
6. ✅ Configurar GitHub Pages apuntando a `/docs`

**Resultado esperado:**
- Estructura completa de documentación
- Archivos listos para contenido
- GitHub Pages configurado

---

### **FASE 2: PÁGINAS ESENCIALES** ⏱️ ~4 horas

**Objetivo:** Documentar lo más importante primero

**Páginas prioritarias:**
1. ✅ `index.md` - Home con introducción y quick start
2. ✅ `installation.md` - Requisitos, instalación, primer proyecto
3. ✅ `configuration.md` - Archivos config/, .env, helpers de config
4. ✅ `directory-structure.md` - Explicación de carpetas
5. ✅ `basics/routing.md` - Router completo (PRIORIDAD #1)
6. ✅ `basics/requests.md` - Clase Request completa (PRIORIDAD #2)
7. ✅ `digging-deeper/helpers.md` - Todas las 24 funciones helper

**Contenido de cada página:**
- Introducción clara
- Tabla de contenidos
- Secciones lógicas
- Ejemplos de código con comentarios
- Casos de uso reales
- Tips y warnings donde aplique
- Links a páginas relacionadas

---

### **FASE 3: PÁGINAS BÁSICAS** ⏱️ ~3 horas

**Objetivo:** Completar sección "The Basics"

**Páginas:**
1. ✅ `basics/controllers.md` - Controladores Web y API
2. ✅ `basics/middleware.md` - Middleware básico
3. ✅ `basics/responses.md` - Respuestas HTTP
4. ✅ `frontend/assets.md` - asset() helper y gestión

**Contenido:**
- Crear controladores
- Separación Web vs API
- Ejemplos de CRUD
- Middleware básico
- Respuestas JSON y HTML

---

### **FASE 4: TESTING Y CONTRIBUCIÓN** ⏱️ ~2 horas

**Objetivo:** Documentar testing y guías de contribución

**Páginas:**
1. ✅ `testing/getting-started.md` - Introducción a testing
2. ✅ `testing/phpunit.md` - Setup y comandos PHPUnit
3. ✅ `testing/testing-helpers.md` - Helpers para tests
4. ✅ `contributions/contribution-guide.md` - Cómo contribuir
5. ✅ `contributions/code-of-conduct.md` - Código de conducta

**Contenido:**
- Setup de PHPUnit
- Estructura de tests
- Comandos disponibles
- Cobertura actual (140 tests)
- Guías para contribuidores

---

### **FASE 5: PÁGINAS AVANZADAS (FUTURO)** ⏱️ ~3 horas

**Objetivo:** Documentar características futuras

**Páginas placeholder:**
1. ⏳ `database/getting-started.md`
2. ⏳ `database/query-builder.md`
3. ⏳ `database/migrations.md`
4. ⏳ `frontend/views.md`
5. ⏳ `digging-deeper/collections.md`
6. ⏳ `digging-deeper/error-handling.md`

**Contenido:**
- Nota: "Próximamente"
- Breve descripción de qué incluirá
- Link al roadmap

---

### **FASE 6: MEJORAS Y PULIDO** ⏱️ ~2 horas

**Objetivo:** Mejorar experiencia de usuario

**Tareas:**
1. ✅ Agregar ejemplos adicionales
2. ✅ Screenshots donde sea útil
3. ✅ Mejorar navegación
4. ✅ Links cruzados entre páginas
5. ✅ Revisar ortografía y gramática
6. ✅ Optimizar para mobile
7. ✅ Agregar meta tags para SEO

---

## 🎯 TEMPLATES DE PÁGINAS

### **Template: Página de Característica (Routing, Request, etc.)**

```markdown
# [Nombre de la Característica]

## Introducción

[Párrafo explicando qué es y por qué es importante]

## Tabla de Contenidos

- [Conceptos Básicos](#conceptos-basicos)
- [Uso Avanzado](#uso-avanzado)
- [Ejemplos](#ejemplos)
- [API Reference](#api-reference)

## Conceptos Básicos

### [Concepto 1]

[Explicación clara]

```php
// Ejemplo de código con comentarios
Router::get('/usuarios', 'UsuariosController@index');
```

**Output esperado:**
```
[Mostrar resultado]
```

### [Concepto 2]

...

## Uso Avanzado

...

## Ejemplos Reales

### Ejemplo 1: [Caso de uso]

[Explicación del problema]

```php
// Solución con código
```

[Explicación de la solución]

## API Reference

| Método | Descripción | Parámetros | Retorno |
|--------|-------------|------------|---------|
| get()  | ...         | ...        | ...     |

## Tips y Tricks

> 💡 **Tip:** [Consejo útil]

> ⚠️ **Warning:** [Advertencia importante]

## Siguientes Pasos

- [Link a página relacionada 1](./related1.md)
- [Link a página relacionada 2](./related2.md)
```

---

## 📊 MÉTRICAS DE ÉXITO

### **Cuantitativas:**
- ✅ 20+ páginas de documentación
- ✅ 100+ ejemplos de código
- ✅ Cobertura de todas las características v0.2.0
- ✅ < 3 segundos de carga en GitHub Pages
- ✅ Mobile-friendly (responsive)

### **Cualitativas:**
- ✅ Fácil de navegar
- ✅ Ejemplos claros y funcionales
- ✅ Progresión lógica de simple a complejo
- ✅ Tono educativo consistente
- ✅ Búsqueda efectiva

---

## 🛠️ HERRAMIENTAS Y TECNOLOGÍAS

### **Opción 1: GitHub Pages + Jekyll** (Nativo)
- ✅ Integración nativa con GitHub
- ✅ Markdown automático
- ✅ Temas predefinidos
- ❌ Menos flexible

### **Opción 2: Docsify** (RECOMENDADA)
- ✅ Sin build step
- ✅ 100% Markdown
- ✅ Sidebar automático
- ✅ Búsqueda integrada
- ✅ Plugins y temas
- ✅ Mobile-friendly

**Setup básico:**
```html
<!-- docs/index.html -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>NatanPHP Framework</title>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/docsify/themes/vue.css">
</head>
<body>
  <div id="app"></div>
  <script>
    window.$docsify = {
      name: 'NatanPHP',
      repo: 'jhonatanfdez/natan-php',
      loadSidebar: true,
      subMaxLevel: 3,
      search: 'auto'
    }
  </script>
  <script src="//cdn.jsdelivr.net/npm/docsify/lib/docsify.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>
</body>
</html>
```

### **Opción 3: MkDocs Material**
- ✅ Material Design
- ✅ Muy profesional
- ✅ Búsqueda avanzada
- ❌ Requiere Python
- ❌ Build step necesario

**Recomendación:** **Docsify** por simplicidad y resultado profesional.

---

## 📝 CONVENCIONES DE ESCRITURA

### **Estilo:**
- **Tono:** Educativo, amigable pero profesional
- **Persona:** Segunda persona ("Puedes...", "Debes...")
- **Tiempo:** Presente
- **Idioma:** Español (principal), Inglés (opcional futuro)

### **Código:**
- Siempre con syntax highlighting (\`\`\`php)
- Comentarios explicativos en español
- Output esperado cuando sea relevante
- Casos de uso reales, no ejemplos triviales

### **Formato:**
- Headers: `#` para título, `##` para secciones, `###` para subsecciones
- Listas: `-` para bullets, `1.` para numeradas
- Énfasis: `**negrita**` para conceptos clave, `*cursiva*` para énfasis
- Código inline: \`código\` para menciones de funciones/clases
- Bloques especiales: `> 💡`, `> ⚠️`, `> ℹ️` para tips/warnings/info

### **Links:**
- Relativos para navegación interna: `[Routing](./basics/routing.md)`
- Absolutos para externos: `[Laravel Docs](https://laravel.com/docs)`
- Descriptivos: NO usar "click aquí", SÍ "ver guía de instalación"

---

## 🚀 PRÓXIMOS PASOS

### **Inmediatos:**
1. ✅ Crear estructura de carpetas `/docs`
2. ✅ Configurar Docsify básico
3. ✅ Crear `index.md` con introducción
4. ✅ Documentar Router (página más importante)
5. ✅ Documentar Request
6. ✅ Documentar Helpers

### **Corto plazo (esta semana):**
7. ✅ Completar sección "The Basics"
8. ✅ Agregar ejemplos de código funcionales
9. ✅ Configurar GitHub Pages
10. ✅ Testing en móvil y desktop

### **Mediano plazo (próximas semanas):**
11. ✅ Documentar testing
12. ✅ Guías de contribución
13. ✅ Screenshots y diagramas
14. ✅ SEO optimization

---

## 📌 NOTAS IMPORTANTES

### **Qué documentar primero:**
1. **Router** - Es el core del framework
2. **Request** - Esencial para todo
3. **Helpers** - Muy usado por developers
4. **Controllers** - Para empezar a crear apps
5. **Testing** - Mostrar la calidad del framework

### **Qué dejar para después:**
- Database (no implementado aún)
- Views (no implementado aún)
- Middleware avanzado (básico sí, avanzado después)
- Collections (no implementado aún)

### **Referencias:**
- Laravel Docs: https://laravel.com/docs
- Symfony Docs: https://symfony.com/doc
- Docsify: https://docsify.js.org
- GitHub Pages: https://pages.github.com

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

### **FASE 1: Estructura**
- [ ] Crear `/docs`
- [ ] Crear subcarpetas
- [ ] Configurar Docsify
- [ ] Crear `index.html`
- [ ] Crear `_sidebar.md`
- [ ] Configurar GitHub Pages

### **FASE 2: Contenido Esencial**
- [ ] index.md
- [ ] installation.md
- [ ] configuration.md
- [ ] directory-structure.md
- [ ] basics/routing.md
- [ ] basics/requests.md
- [ ] digging-deeper/helpers.md

### **FASE 3: Contenido Básico**
- [ ] basics/controllers.md
- [ ] basics/middleware.md
- [ ] basics/responses.md
- [ ] frontend/assets.md

### **FASE 4: Testing**
- [ ] testing/getting-started.md
- [ ] testing/phpunit.md
- [ ] testing/testing-helpers.md

### **FASE 5: Contribución**
- [ ] contributions/contribution-guide.md
- [ ] contributions/code-of-conduct.md

### **FASE 6: Pulido**
- [ ] Links cruzados
- [ ] Ejemplos adicionales
- [ ] Screenshots
- [ ] Mobile testing
- [ ] SEO

---

**Total estimado:** ~16 horas de trabajo
**Prioridad:** ALTA - Documentación es clave para adopción del framework
**Fecha objetivo:** 20 diciembre 2024

---

## 🎓 APRENDIZAJES ESPERADOS

Al final de esta documentación, un desarrollador debe poder:

1. ✅ Instalar NatanPHP en < 5 minutos
2. ✅ Crear su primera ruta en < 2 minutos
3. ✅ Entender el sistema de rutas completo
4. ✅ Usar todos los helpers disponibles
5. ✅ Crear controladores Web y API
6. ✅ Escribir tests para su código
7. ✅ Contribuir al framework

**Eslogan de la docs:** 
> "De principiante a contributor en un día" 🚀

---

**Siguiente archivo a crear:** `/docs/index.md` (Homepage de la documentación)
