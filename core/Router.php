<?php

namespace NatanPHP\Core;

/**
 * Router - Sistema de rutas del framework NatanPHP
 * 
 * Gestiona el enrutamiento de la aplicación, mapeando URLs a controladores
 * y manejando parámetros dinámicos con soporte para middleware.
 * 
 * @package NatanPHP\Core
 * @author Natan PHP Framework
 */
class Router
{
    /**
     * Colección de rutas registradas
     * 
     * @var array
     */
    public static array $routes = [];

    /**
     * Instancia de Request para acceder a datos HTTP
     * 
     * @var Request|null
     */
    protected static ?Request $request = null;

    /**
     * Parámetros extraídos de la URL actual
     * 
     * @var array
     */
    protected static array $parameters = [];

    /**
     * Registrar una ruta GET
     * 
     * Registra una nueva ruta que responde únicamente a peticiones HTTP GET.
     * Ideal para mostrar información, páginas y recursos de solo lectura.
     * 
     * Ejemplo de uso:
     * Router::get('/usuarios', 'UsuariosController@index');
     * Router::get('/usuario/{id}', 'UsuariosController@show');
     * 
     * @param string $uri URI de la ruta (ej: '/usuarios', '/usuario/{id}')
     * @param string $action Controlador@método (ej: 'UsuariosController@index')
     * @return RouteRegistrar Instancia para encadenar configuraciones adicionales
     */
    public static function get(string $uri, string $action): RouteRegistrar
    {
        return static::addRoute('GET', $uri, $action);
    }

    /**
     * Registrar una ruta POST
     * 
     * Registra una nueva ruta que responde únicamente a peticiones HTTP POST.
     * Ideal para crear nuevos recursos, procesar formularios y envío de datos.
     * 
     * Ejemplo de uso:
     * Router::post('/usuarios', 'UsuariosController@store');
     * Router::post('/login', 'AuthController@authenticate');
     * 
     * @param string $uri URI de la ruta (ej: '/usuarios', '/login')
     * @param string $action Controlador@método (ej: 'UsuariosController@store')
     * @return RouteRegistrar Instancia para encadenar configuraciones adicionales
     */
    public static function post(string $uri, string $action): RouteRegistrar
    {
        return static::addRoute('POST', $uri, $action);
    }

    /**
     * Registrar una ruta PUT
     * 
     * Registra una nueva ruta que responde únicamente a peticiones HTTP PUT.
     * Ideal para actualizar recursos completos de forma idempotente.
     * 
     * Ejemplo de uso:
     * Router::put('/usuario/{id}', 'UsuariosController@update');
     * Router::put('/perfil', 'PerfilController@update');
     * 
     * @param string $uri URI de la ruta (ej: '/usuario/{id}', '/perfil')
     * @param string $action Controlador@método (ej: 'UsuariosController@update')
     * @return RouteRegistrar Instancia para encadenar configuraciones adicionales
     */
    public static function put(string $uri, string $action): RouteRegistrar
    {
        return static::addRoute('PUT', $uri, $action);
    }

    /**
     * Registrar una ruta DELETE
     * 
     * Registra una nueva ruta que responde únicamente a peticiones HTTP DELETE.
     * Ideal para eliminar recursos de forma permanente.
     * 
     * Ejemplo de uso:
     * Router::delete('/usuario/{id}', 'UsuariosController@destroy');
     * Router::delete('/posts/{slug}', 'PostsController@delete');
     * 
     * @param string $uri URI de la ruta (ej: '/usuario/{id}', '/posts/{slug}')
     * @param string $action Controlador@método (ej: 'UsuariosController@destroy')
     * @return RouteRegistrar Instancia para encadenar configuraciones adicionales
     */
    public static function delete(string $uri, string $action): RouteRegistrar
    {
        return static::addRoute('DELETE', $uri, $action);
    }

    /**
     * Registrar una ruta PATCH
     * 
     * @param string $uri URI de la ruta
     * @param string $action Controlador@método
     * @return RouteRegistrar
     */
    public static function patch(string $uri, string $action): RouteRegistrar
    {
        return static::addRoute('PATCH', $uri, $action);
    }

    /**
     * Registrar múltiples métodos HTTP para una ruta
     * 
     * Permite que una misma ruta responda a múltiples métodos HTTP específicos.
     * Útil cuando necesitas que una URL maneje varios tipos de peticiones.
     * 
     * Ejemplo de uso:
     * Router::match(['GET', 'POST'], '/contacto', 'ContactoController@handle');
     * Router::match(['PUT', 'PATCH'], '/usuario/{id}', 'UsuariosController@update');
     * 
     * @param array $methods Métodos HTTP permitidos (ej: ['GET', 'POST'])
     * @param string $uri URI de la ruta (ej: '/contacto', '/usuario/{id}')
     * @param string $action Controlador@método (ej: 'ContactoController@handle')
     * @return RouteRegistrar Instancia para encadenar configuraciones adicionales
     */
    public static function match(array $methods, string $uri, string $action): RouteRegistrar
    {
        $registrar = null;
        foreach ($methods as $method) {
            $registrar = static::addRoute(strtoupper($method), $uri, $action);
        }
        return $registrar;
    }

    /**
     * Registrar una ruta para todos los métodos HTTP
     * 
     * @param string $uri URI de la ruta
     * @param string $action Controlador@método
     * @return RouteRegistrar
     */
    public static function any(string $uri, string $action): RouteRegistrar
    {
        return static::match(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], $uri, $action);
    }

    /**
     * Agrupar rutas con configuración compartida
     * 
     * Permite agrupar múltiples rutas que comparten configuración común como
     * prefijos de URL, middleware, o namespaces. Es fundamental para organizar
     * APIs y secciones administrativas.
     * 
     * Características:
     * - Prefijos: Agregan un prefijo común a todas las rutas del grupo
     * - Middleware: Aplica middleware a todas las rutas del grupo
     * - Anidamiento: Los grupos pueden contener otros grupos
     * 
     * Ejemplo de uso:
     * Router::group(['prefix' => 'api/v1'], function() {
     *     Router::get('/usuarios', 'Api\UsuariosController@index');
     *     Router::post('/usuarios', 'Api\UsuariosController@store');
     * });
     * 
     * Router::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
     *     Router::get('/dashboard', 'Admin\DashboardController@index');
     * });
     * 
     * @param array $attributes Atributos del grupo (prefix, middleware, etc.)
     * @param callable $callback Función que define las rutas del grupo
     * @return void
     */
    public static function group(array $attributes, callable $callback): void
    {
        $previousPrefix = static::getCurrentPrefix();
        $previousMiddleware = static::getCurrentMiddleware();
        
        if (isset($attributes['prefix'])) {
            static::setCurrentPrefix($previousPrefix . '/' . trim($attributes['prefix'], '/'));
        }
        
        if (isset($attributes['middleware'])) {
            static::setCurrentMiddleware(array_merge($previousMiddleware, (array)$attributes['middleware']));
        }
        
        $callback();
        
        static::setCurrentPrefix($previousPrefix);
        static::setCurrentMiddleware($previousMiddleware);
    }

    /**
     * Resolver la ruta actual
     * 
     * Este es el método principal del Router que analiza la petición HTTP actual
     * y determina qué controlador y método debe ejecutarse. Realiza todo el
     * proceso de routing del framework.
     * 
     * Proceso de resolución:
     * 1. Extrae el método HTTP (GET, POST, etc.) de la petición
     * 2. Normaliza la URI eliminando parámetros de consulta
     * 3. Recorre todas las rutas registradas buscando coincidencias
     * 4. Extrae parámetros dinámicos de la URL (ej: {id}, {slug})
     * 5. Instancia el controlador y ejecuta el método correspondiente
     * 6. Inyecta automáticamente los parámetros en el método del controlador
     * 
     * Ejemplo de flujo:
     * - Petición: GET /usuario/123
     * - Ruta registrada: Router::get('/usuario/{id}', 'UsuariosController@show')
     * - Resultado: Ejecuta UsuariosController->show(123)
     * 
     * @param Request $request Instancia de Request con datos de la petición HTTP
     * @return mixed Resultado de la ejecución del controlador (HTML, JSON, etc.)
     * @throws \Exception Si no se encuentra la ruta (404) o el controlador
     */
    public static function resolve(Request $request)
    {
        static::$request = $request;
        
        $method = $request->method();
        $uri = static::formatUri($request->uri());
        
        foreach (static::$routes as $route) {
            if ($route['method'] === $method && static::matchRoute($route['uri'], $uri)) {
                static::$parameters = static::extractParameters($route['uri'], $uri);
                return static::callAction($route['action'], $route['middleware'] ?? []);
            }
        }
        
        throw new \Exception("Ruta no encontrada: {$method} {$uri}", 404);
    }

    /**
     * Agregar una ruta al registro
     * 
     * @param string $method Método HTTP
     * @param string $uri URI de la ruta
     * @param string $action Controlador@método
     * @return RouteRegistrar
     */
    protected static function addRoute(string $method, string $uri, string $action): RouteRegistrar
    {
        $uri = static::formatUri(static::getCurrentPrefix() . '/' . $uri);
        
        $route = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'action' => $action,
            'middleware' => static::getCurrentMiddleware()
        ];
        
        static::$routes[] = $route;
        
        return new RouteRegistrar(count(static::$routes) - 1);
    }

    /**
     * Formatear URI eliminando barras múltiples
     * 
     * @param string $uri URI a formatear
     * @return string URI formateada
     */
    protected static function formatUri(string $uri): string
    {
        return '/' . trim(preg_replace('/\/+/', '/', $uri), '/');
    }

    /**
     * Verificar si una ruta coincide con la URI
     * 
     * @param string $routeUri Patrón de la ruta
     * @param string $requestUri URI de la petición
     * @return bool True si coincide
     */
    protected static function matchRoute(string $routeUri, string $requestUri): bool
    {
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $requestUri);
    }

    /**
     * Extraer parámetros de la URI
     * 
     * Compara el patrón de la ruta registrada con la URI de la petición actual
     * para extraer los valores de los parámetros dinámicos definidos entre llaves.
     * 
     * Proceso de extracción:
     * 1. Identifica los nombres de parámetros en el patrón (ej: {id}, {slug})
     * 2. Convierte el patrón en una expresión regular
     * 3. Aplica la regex a la URI actual para capturar valores
     * 4. Asocia cada valor capturado con su nombre de parámetro
     * 
     * Ejemplo:
     * - Patrón: '/usuario/{id}/posts/{slug}'
     * - URI: '/usuario/123/posts/mi-primer-post'
     * - Resultado: ['id' => '123', 'slug' => 'mi-primer-post']
     * 
     * @param string $routeUri Patrón de la ruta con parámetros (ej: '/usuario/{id}')
     * @param string $requestUri URI de la petición actual (ej: '/usuario/123')
     * @return array Array asociativo con parámetros extraídos ['id' => '123']
     */
    protected static function extractParameters(string $routeUri, string $requestUri): array
    {
        $parameters = [];
        
        preg_match_all('/\{([^}]+)\}/', $routeUri, $paramNames);
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $requestUri, $values)) {
            array_shift($values); // Remover la coincidencia completa
            
            foreach ($paramNames[1] as $index => $name) {
                $parameters[$name] = $values[$index] ?? null;
            }
        }
        
        return $parameters;
    }

    /**
     * Ejecutar la acción del controlador
     * 
     * Este método maneja la instanciación y ejecución del controlador correspondiente.
     * Incluye la lógica para determinar si usar controladores Web o API, cargar
     * las clases dinámicamente y ejecutar el método con inyección de parámetros.
     * 
     * Proceso de ejecución:
     * 1. Ejecuta middleware asociado a la ruta (preparado para implementación futura)
     * 2. Parsea la acción para separar controlador y método
     * 3. Determina el namespace correcto (Web vs API) basado en la petición
     * 4. Verifica que la clase del controlador exista
     * 5. Instancia el controlador y verifica que el método exista
     * 6. Ejecuta el método inyectando automáticamente los parámetros de la ruta
     * 
     * Detección Web vs API:
     * - Si la petición quiere JSON (Accept: application/json) → API
     * - Si la URI comienza con '/api/' → API
     * - En cualquier otro caso → Web
     * 
     * Ejemplo:
     * - Acción: 'UsuariosController@show'
     * - Parámetros: ['id' => '123']
     * - Resultado: Ejecuta UsuariosController->show('123')
     * 
     * @param string $action Controlador@método (ej: 'UsuariosController@show')
     * @param array $middleware Array de clases middleware a ejecutar antes del controlador
     * @return mixed Resultado de la ejecución del método del controlador
     * @throws \Exception Si el controlador o método no existe
     */
    protected static function callAction(string $action, array $middleware = [])
    {
        // Ejecutar middleware antes del controlador
        foreach ($middleware as $middlewareClass) {
            // TODO: Implementar sistema de middleware en versión futura
        }
        
        [$controller, $method] = explode('@', $action);
        
        // Determinar el namespace del controlador
        $isApiRequest = static::$request->wantsJson() || strpos(static::$request->uri(), '/api/') === 0;
        $namespace = $isApiRequest ? 'NatanPHP\\App\\Api\\Controllers\\' : 'NatanPHP\\App\\Web\\Controllers\\';
        
        $controllerClass = $namespace . $controller;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controlador no encontrado: {$controllerClass}", 404);
        }
        
        $controllerInstance = new $controllerClass();
        
        if (!method_exists($controllerInstance, $method)) {
            throw new \Exception("Método no encontrado: {$controllerClass}@{$method}", 404);
        }
        
        // Inyectar parámetros en el método del controlador
        return call_user_func_array([$controllerInstance, $method], static::$parameters);
    }

    /**
     * Obtener todos los parámetros de la ruta actual
     * 
     * @return array Parámetros de la ruta
     */
    public static function getParameters(): array
    {
        return static::$parameters;
    }

    /**
     * Obtener un parámetro específico de la ruta
     * 
     * @param string $key Nombre del parámetro
     * @param mixed $default Valor por defecto
     * @return mixed Valor del parámetro
     */
    public static function getParameter(string $key, $default = null)
    {
        return static::$parameters[$key] ?? $default;
    }

    /**
     * Obtener todas las rutas registradas
     * 
     * @return array Rutas registradas
     */
    public static function getRoutes(): array
    {
        return static::$routes;
    }

    /**
     * Variables para manejo de grupos de rutas
     */
    protected static string $currentPrefix = '';
    protected static array $currentMiddleware = [];

    /**
     * Obtener el prefijo actual para grupos
     * 
     * @return string
     */
    protected static function getCurrentPrefix(): string
    {
        return static::$currentPrefix;
    }

    /**
     * Establecer el prefijo actual para grupos
     * 
     * @param string $prefix
     * @return void
     */
    protected static function setCurrentPrefix(string $prefix): void
    {
        static::$currentPrefix = $prefix;
    }

    /**
     * Obtener el middleware actual para grupos
     * 
     * @return array
     */
    protected static function getCurrentMiddleware(): array
    {
        return static::$currentMiddleware;
    }

    /**
     * Establecer el middleware actual para grupos
     * 
     * @param array $middleware
     * @return void
     */
    protected static function setCurrentMiddleware(array $middleware): void
    {
        static::$currentMiddleware = $middleware;
    }
}

/**
 * RouteRegistrar - Maneja el registro fluido de rutas
 * 
 * Esta clase permite agregar configuraciones adicionales a las rutas
 * de forma encadenada después de su registro inicial. Implementa el
 * patrón Fluent Interface para una sintaxis más elegante.
 * 
 * Funcionalidades:
 * - Agregar middleware a rutas específicas
 * - Asignar nombres a las rutas para generación de URLs
 * - Extensible para futuras configuraciones (grupos, prefijos, etc.)
 * 
 * Ejemplo de uso fluido:
 * Router::get('/admin/dashboard', 'AdminController@index')
 *     ->middleware(['auth', 'admin'])
 *     ->name('admin.dashboard');
 * 
 * @package NatanPHP\Core
 */
class RouteRegistrar
{
    /**
     * Índice de la ruta en el array de rutas
     * 
     * @var int
     */
    protected int $routeIndex;

    /**
     * Constructor
     * 
     * @param int $routeIndex Índice de la ruta
     */
    public function __construct(int $routeIndex)
    {
        $this->routeIndex = $routeIndex;
    }

    /**
     * Agregar middleware a la ruta
     * 
     * Permite agregar uno o múltiples middleware a una ruta específica.
     * Los middleware se ejecutan antes del controlador y pueden realizar
     * tareas como autenticación, autorización, logging, etc.
     * 
     * Características:
     * - Acepta string individual o array de middleware
     * - Se pueden encadenar múltiples llamadas
     * - Los middleware se ejecutan en el orden que se agregan
     * - Compatible con middleware de grupos de rutas
     * 
     * Ejemplo de uso:
     * Router::get('/admin', 'AdminController@index')->middleware('auth');
     * Router::post('/api/users', 'UsersController@store')->middleware(['auth', 'api']);
     * 
     * @param string|array $middleware Middleware a agregar (ej: 'auth' o ['auth', 'admin'])
     * @return RouteRegistrar Instancia actual para encadenamiento fluido
     */
    public function middleware($middleware): RouteRegistrar
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        
        if (!isset(Router::$routes[$this->routeIndex]['middleware'])) {
            Router::$routes[$this->routeIndex]['middleware'] = [];
        }
        
        Router::$routes[$this->routeIndex]['middleware'] = array_merge(
            Router::$routes[$this->routeIndex]['middleware'],
            $middleware
        );
        
        return $this;
    }

    /**
     * Establecer nombre a la ruta
     * 
     * Asigna un nombre único a la ruta que puede ser usado posteriormente
     * para generar URLs de forma dinámica. Esto hace que las URLs sean
     * más mantenibles y permite cambiar rutas sin afectar el código.
     * 
     * Ventajas de nombres de rutas:
     * - Generación dinámica de URLs
     * - Mayor mantenibilidad del código
     * - Independencia entre URLs y lógica de aplicación
     * - Facilita refactoring de rutas
     * 
     * Ejemplo de uso:
     * Router::get('/usuario/{id}', 'UsuariosController@show')->name('usuarios.show');
     * 
     * // Luego en vistas o controladores:
     * url('usuarios.show', ['id' => 123]) // Genera: /usuario/123
     * 
     * @param string $name Nombre único para la ruta (ej: 'usuarios.show', 'admin.dashboard')
     * @return RouteRegistrar Instancia actual para encadenamiento fluido
     */
    public function name(string $name): RouteRegistrar
    {
        Router::$routes[$this->routeIndex]['name'] = $name;
        return $this;
    }
}
