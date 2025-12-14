# 📋 Plan de Trabajo: Testing Completo del Framework NatanPHP

## 🔍 ANÁLISIS EXHAUSTIVO DEL FRAMEWORK

### 📊 Resumen Ejecutivo
- **Total de funciones helpers**: 9 funciones globales
- **Total de clases core**: 3 clases (Router, Request, RouteRegistrar)
- **Métodos en Request**: 23 métodos públicos/protegidos
- **Métodos en Router**: 12 métodos estáticos + 3 métodos RouteRegistrar
- **Tests existentes**: 2 archivos (FirstTest, HelpersTest) con 8 tests
- **Cobertura actual estimada**: ~20% (solo helpers básicos)

---

## 📦 INVENTARIO COMPLETO DE FUNCIONALIDADES

### 🔧 **HELPERS (core/helpers.php)** - 9 funciones

#### ✅ **Ya testeadas** (tests existentes):
1. ✅ `version()` - Retorna versión del framework
2. ✅ `env()` - Variables de entorno (test parcial)
3. ✅ `str_slug()` - Conversión a slug (test parcial)
4. ✅ `blank()` - Verificar si está vacío (test parcial)
5. ✅ `filled()` - Verificar si tiene contenido (test parcial)

#### ❌ **SIN TESTEAR** (pendientes):
6. ❌ `dd()` - Debug and die (no testeada)
7. ❌ `config()` - Configuración del framework (no testeada)
8. ❌ `route()` - URLs de rutas nombradas (no testeada)
9. ❌ `url()` - URLs absolutas (no testeada)
10. ❌ `asset()` - URLs de assets estáticos (no testeada)

---

### 🌐 **REQUEST CLASS (core/Request.php)** - 23 métodos

#### ❌ **TODOS SIN TESTEAR** (0% cobertura):

**Métodos de Método HTTP:**
1. ❌ `method()` - Obtener método HTTP
2. ❌ `isMethod($method)` - Verificar método específico
3. ❌ `isGet()` - Verificar si es GET
4. ❌ `isPost()` - Verificar si es POST

**Métodos de URI:**
5. ❌ `uri()` - Obtener URI sin query string
6. ❌ `fullUrl()` - Obtener URL completa

**Métodos de Datos:**
7. ❌ `get($key, $default)` - Obtener dato GET
8. ❌ `post($key, $default)` - Obtener dato POST
9. ❌ `input($key, $default)` - Obtener dato GET o POST
10. ❌ `all()` - Obtener todos los datos
11. ❌ `only($keys)` - Filtrar datos específicos
12. ❌ `except($keys)` - Excluir datos específicos

**Métodos de Validación:**
13. ❌ `has($key)` - Verificar existencia de dato
14. ❌ `filled($key)` - Verificar que dato tiene contenido

**Métodos de Archivos:**
15. ❌ `file($key)` - Obtener archivo subido
16. ❌ `hasFile($key)` - Verificar si hay archivo

**Métodos de Headers:**
17. ❌ `header($key, $default)` - Obtener header
18. ❌ `extractHeaders()` - Extraer headers (protected)

**Métodos de Información:**
19. ❌ `ip()` - Obtener IP del cliente
20. ❌ `userAgent()` - Obtener user agent
21. ❌ `isAjax()` - Verificar si es AJAX
22. ❌ `wantsJson()` - Verificar si espera JSON

**Constructor:**
23. ❌ `__construct()` - Inicialización

---

### 🛣️ **ROUTER CLASS (core/Router.php)** - 15 métodos

#### ❌ **TODOS SIN TESTEAR** (0% cobertura):

**Métodos de Registro de Rutas:**
1. ❌ `Router::get($uri, $action)` - Registrar ruta GET
2. ❌ `Router::post($uri, $action)` - Registrar ruta POST
3. ❌ `Router::put($uri, $action)` - Registrar ruta PUT
4. ❌ `Router::delete($uri, $action)` - Registrar ruta DELETE
5. ❌ `Router::patch($uri, $action)` - Registrar ruta PATCH
6. ❌ `Router::match($methods, $uri, $action)` - Ruta múltiples métodos
7. ❌ `Router::any($uri, $action)` - Ruta para todos los métodos

**Métodos de Grupos:**
8. ❌ `Router::group($attributes, $callback)` - Agrupar rutas

**Métodos de Resolución:**
9. ❌ `Router::resolve($request)` - Resolver ruta actual

**Métodos de Parámetros:**
10. ❌ `Router::getParameters()` - Obtener todos los parámetros
11. ❌ `Router::getParameter($key, $default)` - Obtener parámetro específico

**Métodos Utilitarios:**
12. ❌ `Router::getRoutes()` - Obtener todas las rutas registradas

**RouteRegistrar (Fluent Interface):**
13. ❌ `middleware($middleware)` - Asignar middleware a ruta
14. ❌ `name($name)` - Asignar nombre a ruta
15. ❌ `__construct($routeIndex)` - Constructor RouteRegistrar

---

## 🎯 PLAN DE TRABAJO DETALLADO

### **FASE 1: Completar Tests de Helpers** ⏱️ Estimado: 2-3 horas

#### Archivo: `tests/Unit/HelpersAdvancedTest.php`

**Prioridad: ALTA** 🔴

**Funciones a testear:**
1. **dd()** - Debug and die
   - ✓ Test: Verificar que termina ejecución (difícil - requires output buffering)
   - ✓ Test: Verificar que genera output HTML
   - ✓ Test: Verificar que acepta múltiples variables
   
2. **config()** - Configuración
   - ✓ Test: Retorna default cuando no existe configuración
   - ✓ Test: Maneja notación de puntos
   - ✓ Test: Retorna null si no hay default

3. **route()** - Rutas nombradas
   - ✓ Test: Existe la función
   - ✓ Test: Genera URL básica
   - ✓ Test: Acepta parámetros (aunque aún no implementado)

4. **url()** - URLs absolutas
   - ✓ Test: Genera URL con protocolo HTTP
   - ✓ Test: Genera URL con protocolo HTTPS
   - ✓ Test: Maneja paths con y sin barra inicial
   - ✓ Test: Usa fallback cuando no hay HTTP_HOST
   - ✓ Test: Detecta protocolo desde $_SERVER['HTTPS']

5. **asset()** - Assets estáticos
   - ✓ Test: Genera URL de asset
   - ✓ Test: Agrega prefijo 'assets/'
   - ✓ Test: Maneja paths con y sin barra

**Tests adicionales para funciones ya parcialmente testeadas:**
6. **env()** - Casos adicionales
   - ✓ Test: Conversión de 'true' a boolean
   - ✓ Test: Conversión de 'false' a boolean
   - ✓ Test: Conversión de 'null' a null
   - ✓ Test: Conversión de 'empty' a string vacío
   - ✓ Test: Retorna valor real de variable de entorno

7. **str_slug()** - Casos adicionales
   - ✓ Test: Maneja acentos y caracteres especiales
   - ✓ Test: Maneja separador personalizado
   - ✓ Test: Elimina caracteres no alfanuméricos
   - ✓ Test: Limpia separadores al inicio/final
   - ✓ Test: Maneja espacios múltiples

8. **blank()** - Casos adicionales
   - ✓ Test: Maneja null
   - ✓ Test: Maneja arrays vacíos
   - ✓ Test: Maneja espacios en blanco
   - ✓ Test: Maneja valores 0 y false

9. **filled()** - Casos adicionales
   - ✓ Test: Complemento de todos los casos de blank()

**Total tests FASE 1: ~35 tests**

---

### **FASE 2: Tests Completos de Request** ⏱️ Estimado: 4-5 horas

#### Archivo: `tests/Unit/RequestTest.php`

**Prioridad: ALTA** 🔴

**Grupo 1: Métodos HTTP (5 tests)**
- ✓ Test: `method()` retorna método correcto
- ✓ Test: `isMethod()` verifica método específico
- ✓ Test: `isGet()` detecta peticiones GET
- ✓ Test: `isPost()` detecta peticiones POST
- ✓ Test: Constructor inicializa correctamente superglobals

**Grupo 2: URIs (2 tests)**
- ✓ Test: `uri()` retorna URI sin query string
- ✓ Test: `fullUrl()` retorna URL completa con query string

**Grupo 3: Datos de Entrada (6 tests)**
- ✓ Test: `get()` obtiene datos GET
- ✓ Test: `get()` retorna default cuando no existe
- ✓ Test: `post()` obtiene datos POST
- ✓ Test: `post()` retorna default cuando no existe
- ✓ Test: `input()` busca en GET y POST
- ✓ Test: `all()` retorna todos los datos combinados

**Grupo 4: Filtrado de Datos (2 tests)**
- ✓ Test: `only()` filtra solo keys especificadas
- ✓ Test: `except()` excluye keys especificadas

**Grupo 5: Validación (2 tests)**
- ✓ Test: `has()` detecta existencia de dato
- ✓ Test: `filled()` verifica que dato tiene contenido

**Grupo 6: Archivos (2 tests)**
- ✓ Test: `file()` obtiene archivo subido
- ✓ Test: `hasFile()` verifica existencia de archivo

**Grupo 7: Headers (2 tests)**
- ✓ Test: `header()` obtiene header específico
- ✓ Test: `extractHeaders()` extrae headers de $_SERVER

**Grupo 8: Información del Cliente (4 tests)**
- ✓ Test: `ip()` obtiene IP del cliente
- ✓ Test: `ip()` maneja proxies (X-Forwarded-For)
- ✓ Test: `userAgent()` obtiene user agent
- ✓ Test: `isAjax()` detecta peticiones AJAX (X-Requested-With)

**Grupo 9: Content Negotiation (1 test)**
- ✓ Test: `wantsJson()` detecta si espera JSON

**Total tests FASE 2: ~26 tests**

---

### **FASE 3: Tests Completos de Router** ⏱️ Estimado: 5-6 horas

#### Archivo: `tests/Unit/RouterTest.php`

**Prioridad: MEDIA** 🟡

**Grupo 1: Registro Básico de Rutas (7 tests)**
- ✓ Test: `Router::get()` registra ruta GET
- ✓ Test: `Router::post()` registra ruta POST
- ✓ Test: `Router::put()` registra ruta PUT
- ✓ Test: `Router::delete()` registra ruta DELETE
- ✓ Test: `Router::patch()` registra ruta PATCH
- ✓ Test: `Router::match()` registra ruta con múltiples métodos
- ✓ Test: `Router::any()` registra ruta para todos los métodos

**Grupo 2: Parámetros Dinámicos (4 tests)**
- ✓ Test: Rutas con parámetro simple {id}
- ✓ Test: Rutas con múltiples parámetros {category}/{slug}
- ✓ Test: `getParameters()` retorna todos los parámetros
- ✓ Test: `getParameter()` retorna parámetro específico

**Grupo 3: Grupos de Rutas (3 tests)**
- ✓ Test: `group()` con prefijo
- ✓ Test: `group()` con middleware compartido
- ✓ Test: `group()` anidados

**Grupo 4: Fluent Interface (2 tests)**
- ✓ Test: `middleware()` asigna middleware a ruta
- ✓ Test: `name()` asigna nombre a ruta

**Grupo 5: Resolución de Rutas (5 tests)**
- ✓ Test: `resolve()` encuentra ruta correcta
- ✓ Test: `resolve()` extrae parámetros correctamente
- ✓ Test: `resolve()` diferencia entre Web y API
- ✓ Test: `resolve()` lanza excepción en 404
- ✓ Test: `resolve()` ejecuta controlador correctamente

**Grupo 6: Utilidades (1 test)**
- ✓ Test: `getRoutes()` retorna todas las rutas registradas

**Total tests FASE 3: ~22 tests**

---

### **FASE 4: Tests de Integración** ⏱️ Estimado: 3-4 horas

#### Archivo: `tests/Integration/FrameworkIntegrationTest.php`

**Prioridad: MEDIA** 🟡

**Tests de integración entre componentes:**
1. ✓ Test: Router + Request funcionan juntos
2. ✓ Test: Helpers url() + Router integrados
3. ✓ Test: Request detecta rutas API correctamente
4. ✓ Test: Parámetros de Router accesibles en controladores
5. ✓ Test: Middleware execution flow completo
6. ✓ Test: Named routes con route() helper

**Total tests FASE 4: ~6 tests**

---

### **FASE 5: Tests Edge Cases y Error Handling** ⏱️ Estimado: 2-3 horas

#### Archivo: `tests/Unit/EdgeCasesTest.php`

**Prioridad: BAJA** 🟢

**Tests de casos extremos:**
1. ✓ Test: Helpers con valores null
2. ✓ Test: Helpers con valores muy largos
3. ✓ Test: Request con datos malformados
4. ✓ Test: Router con URIs inválidas
5. ✓ Test: Caracteres especiales en parámetros
6. ✓ Test: Unicode en helpers
7. ✓ Test: Headers malformados en Request
8. ✓ Test: Router con rutas duplicadas

**Total tests FASE 5: ~8 tests**

---

## 📈 RESUMEN ESTADÍSTICO

### **Cobertura Actual:**
- ✅ Helpers básicos: 5/9 funciones (~56%)
- ❌ Helpers avanzados: 0/4 adicionales (0%)
- ❌ Request: 0/23 métodos (0%)
- ❌ Router: 0/15 métodos (0%)
- **TOTAL ACTUAL: ~20% cobertura estimada**

### **Cobertura Objetivo:**
- ✅ FASE 1: Helpers 100% (~35 tests adicionales)
- ✅ FASE 2: Request 100% (~26 tests)
- ✅ FASE 3: Router 100% (~22 tests)
- ✅ FASE 4: Integración (~6 tests)
- ✅ FASE 5: Edge cases (~8 tests)
- **TOTAL OBJETIVO: ~100% cobertura**

### **Tests Totales:**
- **Existentes**: 8 tests
- **A crear**: ~97 tests
- **Total final**: ~105 tests

### **Tiempo Estimado Total:**
- FASE 1: 2-3 horas
- FASE 2: 4-5 horas
- FASE 3: 5-6 horas
- FASE 4: 3-4 horas
- FASE 5: 2-3 horas
- **TOTAL: 16-21 horas de trabajo**

---

## 🚀 ORDEN DE EJECUCIÓN RECOMENDADO

### **Sprint 1: Fundamentos (FASE 1)**
Completar tests de todos los helpers para tener base sólida

### **Sprint 2: Request (FASE 2)**
Testear completamente la clase Request que es crítica para el framework

### **Sprint 3: Router (FASE 3)**
Testear el sistema de rutas completo incluyendo parámetros y grupos

### **Sprint 4: Integración (FASE 4)**
Verificar que todos los componentes funcionan juntos correctamente

### **Sprint 5: Polish (FASE 5)**
Cubrir edge cases y asegurar robustez completa

---

## ✅ CRITERIOS DE ACEPTACIÓN

Cada fase se considera COMPLETA cuando:

1. ✅ **Todos los tests pasan** con éxito
2. ✅ **Cobertura 100%** de funcionalidad de esa fase
3. ✅ **Documentación clara** en cada test (qué se testea y por qué)
4. ✅ **Sin warnings** ni deprecations de PHPUnit
5. ✅ **Assertions significativas** (no solo assertTrue genéricos)
6. ✅ **Tests independientes** (no dependen de orden de ejecución)

---

## 📝 NOTAS IMPORTANTES

### **Desafíos Técnicos Identificados:**

1. **dd() es difícil de testear** - Usa exit() que termina ejecución
   - Solución: Usar output buffering y try-catch personalizado

2. **Request requiere mocking de superglobals** - $_GET, $_POST, $_SERVER
   - Solución: Inyectar valores en constructor o usar ReflectionClass

3. **Router es stateful** - Rutas se acumulan entre tests
   - Solución: Reset de rutas en setUp() de cada test

4. **url() depende de $_SERVER** - Dificulta testing
   - Solución: Mock de $_SERVER en cada test case

### **Convenciones de Testing:**

- 📛 **Nombres descriptivos**: `testMethodDoesSpecificThing()`
- 📝 **Comentarios claros**: Explicar qué se testea y por qué
- 🎯 **Un concepto por test**: No testear múltiples cosas en un test
- 🔄 **Setup/Teardown**: Limpiar estado entre tests
- 📊 **Assertions claras**: Mensajes de error descriptivos

---

## 🎯 ESTADO ACTUAL

**Actualizado**: 14 de diciembre de 2025
**Tests existentes**: 8 tests en 2 archivos
**Cobertura actual**: ~20%
**Objetivo**: 100% cobertura

---

**✨ LISTO PARA COMENZAR - Avísame cuando quieras que empiece con la FASE 1 ✨**
