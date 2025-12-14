<?php

namespace NatanPHP\Tests\Integration;

use PHPUnit\Framework\TestCase;
use NatanPHP\Core\Router;
use NatanPHP\Core\Request;

/**
 * ============================================================================
 * FrameworkIntegrationTest - Tests de integración del framework completo
 * ============================================================================
 * 
 * ¿QUÉ CONTIENE?
 * - Tests de integración entre Router y Request
 * - Tests de helpers integrados con componentes core
 * - Tests de flujo completo de peticiones
 * - Tests de detección automática Web vs API
 * - Tests de extracción y uso de parámetros
 * 
 * OBJETIVO: Verificar que todos los componentes trabajan juntos
 * correctamente, no solo de forma aislada.
 * 
 * @package NatanPHP\Tests\Integration
 */
class FrameworkIntegrationTest extends TestCase
{
    // ========================================================================
    // SETUP Y TEARDOWN
    // ========================================================================

    protected function setUp(): void
    {
        parent::setUp();
        
        // Limpiar rutas y superglobales
        Router::$routes = [];
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_FILES = [];
    }

    protected function tearDown(): void
    {
        Router::$routes = [];
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_FILES = [];
        
        parent::tearDown();
    }

    // ========================================================================
    // GRUPO 1: INTEGRACIÓN ROUTER + REQUEST
    // ========================================================================

    /**
     * Test: Router y Request trabajan juntos correctamente
     * 
     * EXPLICACIÓN:
     * Este test verifica que Router puede usar un objeto Request
     * para obtener información de la petición HTTP y registrar rutas.
     */
    public function testRouterAndRequestWorkTogether(): void
    {
        // Configurar petición simulada
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/usuarios';
        
        $request = new Request();
        
        // Registrar ruta
        Router::get('/usuarios', 'UsuariosController@index');
        
        // Verificar que Request proporciona info correcta
        $this->assertEquals('GET', $request->method());
        $this->assertEquals('/usuarios', $request->uri());
        
        // Verificar que Router registró la ruta
        $routes = Router::getRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('/usuarios', $routes[0]['uri']);
    }

    /**
     * Test: Request detecta correctamente peticiones API
     * 
     * EXPLICACIÓN:
     * Request debe detectar si es una petición API basándose
     * en la URI (/api/*) o el header Accept.
     */
    public function testRequestDetectsApiRequests(): void
    {
        // Test 1: Detectar por URI /api/
        $_SERVER['REQUEST_URI'] = '/api/usuarios';
        $request1 = new Request();
        
        $this->assertEquals('/api/usuarios', $request1->uri());
        $this->assertStringContainsString('/api', $request1->uri());
        
        // Test 2: Detectar por header Accept
        $_SERVER['REQUEST_URI'] = '/usuarios';
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $request2 = new Request();
        
        $this->assertTrue($request2->wantsJson());
    }

    /**
     * Test: Router extrae parámetros que Request puede usar
     * 
     * EXPLICACIÓN:
     * Los parámetros extraídos por Router de la URL deben estar
     * disponibles para ser usados en conjunto con datos de Request.
     */
    public function testRouterExtractsParametersForRequest(): void
    {
        // Registrar ruta con parámetros
        Router::get('/usuario/{id}/posts/{postId}', 'PostsController@show');
        
        $routes = Router::getRoutes();
        $this->assertEquals('/usuario/{id}/posts/{postId}', $routes[0]['uri']);
        
        // Simular que también hay datos GET
        $_GET['formato'] = 'json';
        $_SERVER['REQUEST_URI'] = '/usuario/123/posts/456?formato=json';
        
        $request = new Request();
        
        // Request debe tener acceso a parámetros GET
        $this->assertEquals('json', $request->get('formato'));
        
        // Router debe poder extraer parámetros de la ruta
        // (esto se verificaría en el método resolve, pero aquí 
        // confirmamos que la estructura está lista)
        $this->assertStringContainsString('{id}', $routes[0]['uri']);
        $this->assertStringContainsString('{postId}', $routes[0]['uri']);
    }

    // ========================================================================
    // GRUPO 2: INTEGRACIÓN DE HELPERS CON COMPONENTES
    // ========================================================================

    /**
     * Test: Helper url() se integra con configuración
     * 
     * EXPLICACIÓN:
     * El helper url() debe generar URLs basándose en la configuración
     * del servidor actual.
     */
    public function testUrlHelperIntegratesWithServer(): void
    {
        // Configurar entorno
        $_SERVER['HTTP_HOST'] = 'ejemplo.com';
        $_SERVER['HTTPS'] = 'on';
        
        $url = url('/api/usuarios');
        
        // url() debe generar URL completa considerando el host
        $this->assertStringContainsString('/api/usuarios', $url);
    }

    /**
     * Test: Helper route() se integraría con Router (preparado para futuro)
     * 
     * EXPLICACIÓN:
     * Aunque route() actualmente es básico, verificamos que existe
     * y está preparado para integrarse con rutas nombradas del Router.
     */
    public function testRouteHelperExistsForFutureIntegration(): void
    {
        // Registrar ruta con nombre
        Router::get('/perfil', 'PerfilController@show')->name('perfil.show');
        
        $routes = Router::getRoutes();
        
        // Verificar que la ruta tiene nombre
        $this->assertEquals('perfil.show', $routes[0]['name']);
        
        // Verificar que helper route() existe
        $this->assertTrue(function_exists('route'));
        
        // route() actualmente genera URL básica
        $routeUrl = route('perfil.show');
        $this->assertIsString($routeUrl);
    }

    /**
     * Test: Helper asset() genera URLs para recursos estáticos
     * 
     * EXPLICACIÓN:
     * asset() debe generar URLs consistentes independientemente
     * de la ruta actual de Router.
     */
    public function testAssetHelperGeneratesConsistentUrls(): void
    {
        // Registrar varias rutas
        Router::get('/', 'HomeController@index');
        Router::get('/admin/dashboard', 'AdminController@dashboard');
        Router::get('/api/data', 'ApiController@data');
        
        // asset() debe generar misma URL sin importar la ruta actual
        $css1 = asset('css/styles.css');
        $css2 = asset('/css/styles.css');
        
        $this->assertStringContainsString('css/styles.css', $css1);
        $this->assertStringContainsString('css/styles.css', $css2);
    }

    // ========================================================================
    // GRUPO 3: FLUJO COMPLETO DE PETICIONES
    // ========================================================================

    /**
     * Test: Flujo completo GET request → Router → Parámetros
     * 
     * EXPLICACIÓN:
     * Simular el flujo completo de una petición GET con parámetros
     * tanto en URL como en query string.
     */
    public function testCompleteGetRequestFlow(): void
    {
        // 1. Configurar petición
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/productos?categoria=tecnologia&orden=precio';
        $_GET['categoria'] = 'tecnologia';
        $_GET['orden'] = 'precio';
        
        // 2. Crear Request
        $request = new Request();
        
        // 3. Registrar ruta
        Router::get('/productos', 'ProductosController@index');
        
        // 4. Verificar flujo completo
        $this->assertTrue($request->isGet());
        $this->assertEquals('/productos', $request->uri());
        $this->assertEquals('tecnologia', $request->get('categoria'));
        $this->assertEquals('precio', $request->get('orden'));
        
        $routes = Router::getRoutes();
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('/productos', $routes[0]['uri']);
    }

    /**
     * Test: Flujo completo POST request con datos
     * 
     * EXPLICACIÓN:
     * Simular envío de formulario con datos POST.
     */
    public function testCompletePostRequestFlow(): void
    {
        // 1. Configurar petición POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/usuarios';
        $_POST['nombre'] = 'Juan Pérez';
        $_POST['email'] = 'juan@ejemplo.com';
        $_POST['password'] = 'secreto123';
        
        // 2. Crear Request
        $request = new Request();
        
        // 3. Registrar ruta
        Router::post('/usuarios', 'UsuariosController@store');
        
        // 4. Verificar flujo POST
        $this->assertTrue($request->isPost());
        $this->assertEquals('Juan Pérez', $request->post('nombre'));
        $this->assertEquals('juan@ejemplo.com', $request->post('email'));
        
        // 5. Verificar filtrado de datos sensibles
        $datosSeguros = $request->except(['password']);
        $this->assertArrayHasKey('nombre', $datosSeguros);
        $this->assertArrayHasKey('email', $datosSeguros);
        $this->assertArrayNotHasKey('password', $datosSeguros);
        
        // 6. Verificar ruta registrada
        $routes = Router::getRoutes();
        $this->assertEquals('POST', $routes[0]['method']);
    }

    /**
     * Test: Flujo con archivo subido
     * 
     * EXPLICACIÓN:
     * Simular subida de archivo junto con datos de formulario.
     */
    public function testFileUploadFlow(): void
    {
        // 1. Configurar petición con archivo
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/perfil/avatar';
        $_POST['user_id'] = '123';
        $_FILES['avatar'] = [
            'name' => 'foto-perfil.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => '/tmp/phpABCDEF',
            'error' => UPLOAD_ERR_OK,
            'size' => 2048
        ];
        
        // 2. Crear Request
        $request = new Request();
        
        // 3. Registrar ruta
        Router::post('/perfil/avatar', 'PerfilController@updateAvatar');
        
        // 4. Verificar Request maneja archivo
        $this->assertTrue($request->hasFile('avatar'));
        $this->assertEquals('foto-perfil.jpg', $request->file('avatar')['name']);
        $this->assertEquals('123', $request->post('user_id'));
        
        // 5. Verificar ruta
        $routes = Router::getRoutes();
        $this->assertEquals('POST', $routes[0]['method']);
        $this->assertEquals('/perfil/avatar', $routes[0]['uri']);
    }

    // ========================================================================
    // GRUPO 4: INTEGRACIÓN GRUPOS Y MIDDLEWARE
    // ========================================================================

    /**
     * Test: Grupos de rutas con middleware se integran correctamente
     * 
     * EXPLICACIÓN:
     * Verificar que los grupos de Router aplican middleware
     * que podría verificar datos de Request.
     */
    public function testRouteGroupsWithMiddlewareIntegration(): void
    {
        // Crear grupo admin con middleware auth
        Router::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
            Router::get('/usuarios', 'Admin\UsuariosController@index');
            Router::post('/usuarios', 'Admin\UsuariosController@store');
            Router::delete('/usuario/{id}', 'Admin\UsuariosController@destroy');
        });
        
        $routes = Router::getRoutes();
        
        // Verificar 3 rutas creadas con prefijo
        $this->assertCount(3, $routes);
        
        // Verificar prefijos
        $this->assertEquals('/admin/usuarios', $routes[0]['uri']);
        $this->assertEquals('/admin/usuarios', $routes[1]['uri']);
        $this->assertEquals('/admin/usuario/{id}', $routes[2]['uri']);
        
        // Verificar middleware en todas
        foreach ($routes as $route) {
            $this->assertContains('auth', $route['middleware']);
            $this->assertContains('admin', $route['middleware']);
        }
    }

    /**
     * Test: API routes group detecta formato JSON automáticamente
     * 
     * EXPLICACIÓN:
     * Las rutas API agrupadas bajo /api/ deben trabajar
     * con Request que detecta wantsJson().
     */
    public function testApiRoutesGroupWithJsonDetection(): void
    {
        // Configurar petición API
        $_SERVER['REQUEST_URI'] = '/api/v1/usuarios';
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $request = new Request();
        
        // Crear grupo API
        Router::group(['prefix' => 'api/v1'], function() {
            Router::get('/usuarios', 'Api\UsuariosController@index');
            Router::post('/usuarios', 'Api\UsuariosController@store');
        });
        
        // Verificar detección de API
        $this->assertTrue($request->wantsJson());
        $this->assertStringContainsString('/api', $request->uri());
        
        // Verificar rutas API registradas
        $routes = Router::getRoutes();
        $this->assertEquals('/api/v1/usuarios', $routes[0]['uri']);
        $this->assertEquals('/api/v1/usuarios', $routes[1]['uri']);
    }

    // ========================================================================
    // GRUPO 5: CASOS REALISTAS COMPLETOS
    // ========================================================================

    /**
     * Test: Escenario RESTful completo para un recurso
     * 
     * EXPLICACIÓN:
     * Simular un CRUD completo (Create, Read, Update, Delete)
     * para un recurso, integrando Router, Request y parámetros.
     */
    public function testCompleteRestfulResourceScenario(): void
    {
        // Registrar rutas RESTful para recurso "posts"
        Router::get('/posts', 'PostsController@index');              // Listar
        Router::post('/posts', 'PostsController@store');             // Crear
        Router::get('/posts/{id}', 'PostsController@show');          // Ver uno
        Router::put('/posts/{id}', 'PostsController@update');        // Actualizar
        Router::delete('/posts/{id}', 'PostsController@destroy');    // Eliminar
        
        $routes = Router::getRoutes();
        
        // Verificar 5 rutas RESTful registradas
        $this->assertCount(5, $routes);
        
        // Verificar métodos HTTP correctos
        $methods = array_column($routes, 'method');
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
        $this->assertContains('DELETE', $methods);
        
        // Simular diferentes peticiones
        
        // 1. GET /posts (listar)
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/posts';
        $request1 = new Request();
        $this->assertTrue($request1->isGet());
        $this->assertEquals('/posts', $request1->uri());
        
        // 2. POST /posts (crear)
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/posts';
        $_POST = ['titulo' => 'Mi Post', 'contenido' => 'Contenido...'];
        $request2 = new Request();
        $this->assertTrue($request2->isPost());
        $this->assertTrue($request2->filled('titulo'));
        
        // 3. PUT /posts/123 (actualizar)
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = '/posts/123';
        $request3 = new Request();
        $this->assertEquals('PUT', $request3->method());
        $this->assertStringContainsString('123', $request3->uri());
    }

    /**
     * Test: Petición AJAX con datos JSON
     * 
     * EXPLICACIÓN:
     * Simular una petición AJAX típica de una SPA (Single Page Application).
     */
    public function testAjaxRequestWithJsonData(): void
    {
        // Configurar petición AJAX
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/api/comentarios';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $_POST = ['post_id' => 123, 'texto' => 'Gran artículo!'];
        
        $request = new Request();
        
        // Registrar ruta API
        Router::post('/api/comentarios', 'Api\ComentariosController@store')
               ->middleware('api');
        
        // Verificar detección AJAX
        $this->assertTrue($request->isAjax());
        $this->assertTrue($request->wantsJson());
        $this->assertEquals('application/json', $request->header('content-type'));
        
        // Verificar datos
        $this->assertEquals(123, $request->post('post_id'));
        $this->assertTrue($request->filled('texto'));
        
        // Verificar ruta con middleware API
        $routes = Router::getRoutes();
        $this->assertContains('api', $routes[0]['middleware']);
    }

    /**
     * Test: Integración completa con headers personalizados
     * 
     * EXPLICACIÓN:
     * Verificar que Request extrae headers correctamente y Router
     * puede usar esa información para routing.
     */
    public function testCustomHeadersIntegration(): void
    {
        // Configurar petición con headers personalizados
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/datos';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer token123456';
        $_SERVER['HTTP_X_API_VERSION'] = 'v2';
        $_SERVER['HTTP_USER_AGENT'] = 'MiApp/1.0';
        
        $request = new Request();
        
        // Registrar ruta API
        Router::get('/api/datos', 'Api\DatosController@index')
               ->middleware(['auth', 'api']);
        
        // Verificar headers extraídos
        $this->assertEquals('Bearer token123456', $request->header('authorization'));
        $this->assertEquals('v2', $request->header('x-api-version'));
        $this->assertEquals('MiApp/1.0', $request->userAgent());
        
        // Verificar ruta con middleware
        $routes = Router::getRoutes();
        $this->assertContains('auth', $routes[0]['middleware']);
        $this->assertContains('api', $routes[0]['middleware']);
    }

    /**
     * Test: Detección automática de entorno Web vs API
     * 
     * EXPLICACIÓN:
     * El framework debe detectar automáticamente si es una petición
     * web o API basándose en URI y headers.
     */
    public function testAutoDetectWebVsApiEnvironment(): void
    {
        // Caso 1: Petición WEB típica
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/productos';
        $_SERVER['HTTP_ACCEPT'] = 'text/html';
        
        $webRequest = new Request();
        
        $this->assertFalse($webRequest->wantsJson());
        $this->assertStringNotContainsString('/api', $webRequest->uri());
        
        // Caso 2: Petición API por URI
        $_SERVER['REQUEST_URI'] = '/api/productos';
        $_SERVER['HTTP_ACCEPT'] = 'text/html'; // Aunque no pida JSON
        
        $apiRequest1 = new Request();
        
        $this->assertStringContainsString('/api', $apiRequest1->uri());
        
        // Caso 3: Petición API por header Accept
        $_SERVER['REQUEST_URI'] = '/productos';
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        
        $apiRequest2 = new Request();
        
        $this->assertTrue($apiRequest2->wantsJson());
    }
}
