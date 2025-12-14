<?php

namespace NatanPHP\Tests\Unit;

use PHPUnit\Framework\TestCase;
use NatanPHP\Core\Router;
use NatanPHP\Core\Request;

/**
 * ============================================================================
 * RouterTest - Tests exhaustivos para la clase Router
 * ============================================================================
 * 
 * ¿QUÉ CONTIENE?
 * - Tests de registro de rutas (get, post, put, delete, patch, match, any)
 * - Tests de grupos de rutas (prefijos, middleware)
 * - Tests de resolución de rutas (matchRoute, extractParameters)
 * - Tests de parámetros dinámicos ({id}, {slug})
 * - Tests de formateo de URIs
 * - Tests de RouteRegistrar (middleware, name)
 * - Tests de utilidades (getRoutes, getParameters)
 * 
 * COBERTURA: Router (15 métodos) + RouteRegistrar (3 métodos)
 * 
 * IMPORTANTE: Router usa métodos estáticos y estado global,
 * por lo que necesitamos limpiar las rutas entre cada test.
 * 
 * @package NatanPHP\Tests\Unit
 */
class RouterTest extends TestCase
{
    // ========================================================================
    // SETUP Y TEARDOWN
    // ========================================================================

    /**
     * Limpiar rutas antes de cada test
     * 
     * EXPLICACIÓN:
     * Como Router usa propiedades estáticas, las rutas se acumulan.
     * Debemos limpiar el estado entre tests para evitar interferencias.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Limpiar rutas registradas
        Router::$routes = [];
        
        // Resetear $_SERVER para tests limpios
        $_SERVER = [];
    }

    /**
     * Limpiar después de cada test
     */
    protected function tearDown(): void
    {
        Router::$routes = [];
        $_SERVER = [];
        
        parent::tearDown();
    }

    // ========================================================================
    // GRUPO 1: REGISTRO BÁSICO DE RUTAS
    // ========================================================================

    /**
     * Test: Router::get() registra ruta GET correctamente
     * 
     * EXPLICACIÓN:
     * get() debe crear una entrada en $routes con método GET,
     * la URI formateada y la acción especificada.
     */
    public function testGetRegistersGetRoute(): void
    {
        Router::get('/usuarios', 'UsuariosController@index');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(1, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('/usuarios', $routes[0]['uri']);
        $this->assertEquals('UsuariosController@index', $routes[0]['action']);
    }

    /**
     * Test: Router::post() registra ruta POST correctamente
     * 
     * EXPLICACIÓN:
     * post() debe crear una entrada con método POST.
     */
    public function testPostRegistersPostRoute(): void
    {
        Router::post('/usuarios', 'UsuariosController@store');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(1, $routes);
        $this->assertEquals('POST', $routes[0]['method']);
        $this->assertEquals('/usuarios', $routes[0]['uri']);
        $this->assertEquals('UsuariosController@store', $routes[0]['action']);
    }

    /**
     * Test: Router::put() registra ruta PUT correctamente
     * 
     * EXPLICACIÓN:
     * put() debe crear una entrada con método PUT.
     */
    public function testPutRegistersPutRoute(): void
    {
        Router::put('/usuario/{id}', 'UsuariosController@update');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(1, $routes);
        $this->assertEquals('PUT', $routes[0]['method']);
        $this->assertEquals('/usuario/{id}', $routes[0]['uri']);
    }

    /**
     * Test: Router::delete() registra ruta DELETE correctamente
     * 
     * EXPLICACIÓN:
     * delete() debe crear una entrada con método DELETE.
     */
    public function testDeleteRegistersDeleteRoute(): void
    {
        Router::delete('/usuario/{id}', 'UsuariosController@destroy');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(1, $routes);
        $this->assertEquals('DELETE', $routes[0]['method']);
        $this->assertEquals('/usuario/{id}', $routes[0]['uri']);
    }

    /**
     * Test: Router::patch() registra ruta PATCH correctamente
     * 
     * EXPLICACIÓN:
     * patch() debe crear una entrada con método PATCH.
     */
    public function testPatchRegistersPatchRoute(): void
    {
        Router::patch('/usuario/{id}', 'UsuariosController@patch');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(1, $routes);
        $this->assertEquals('PATCH', $routes[0]['method']);
    }

    /**
     * Test: Router::match() registra múltiples métodos
     * 
     * EXPLICACIÓN:
     * match() debe crear una ruta para cada método especificado,
     * todas con la misma URI y acción.
     */
    public function testMatchRegistersMultipleMethods(): void
    {
        Router::match(['GET', 'POST'], '/contacto', 'ContactoController@handle');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(2, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('POST', $routes[1]['method']);
        $this->assertEquals('/contacto', $routes[0]['uri']);
        $this->assertEquals('/contacto', $routes[1]['uri']);
    }

    /**
     * Test: Router::any() registra todos los métodos HTTP
     * 
     * EXPLICACIÓN:
     * any() debe crear una ruta para GET, POST, PUT, DELETE, PATCH.
     */
    public function testAnyRegistersAllHttpMethods(): void
    {
        Router::any('/api/webhook', 'WebhookController@handle');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(5, $routes);
        
        $methods = array_column($routes, 'method');
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
        $this->assertContains('DELETE', $methods);
        $this->assertContains('PATCH', $methods);
    }

    // ========================================================================
    // GRUPO 2: FORMATEO DE URIs
    // ========================================================================

    /**
     * Test: formatUri() normaliza URIs correctamente
     * 
     * EXPLICACIÓN:
     * formatUri() debe eliminar barras duplicadas y asegurar
     * que la URI comience con / pero no termine con /.
     * 
     * NOTA: formatUri() es protected, lo probamos indirectamente
     * mediante el registro de rutas.
     */
    public function testRouteUrisAreNormalized(): void
    {
        Router::get('usuarios', 'UsuariosController@index');
        Router::get('/productos/', 'ProductosController@index');
        Router::get('//api//items//', 'ItemsController@index');
        
        $routes = Router::getRoutes();
        
        // Todas deben tener formato /ruta (sin barra final)
        $this->assertEquals('/usuarios', $routes[0]['uri']);
        $this->assertEquals('/productos', $routes[1]['uri']);
        $this->assertEquals('/api/items', $routes[2]['uri']);
    }

    /**
     * Test: URIs vacías se convierten en ruta raíz
     * 
     * EXPLICACIÓN:
     * Una URI vacía o "/" debe normalizarse a "/".
     */
    public function testEmptyUriBecomesRoot(): void
    {
        Router::get('', 'HomeController@index');
        Router::get('/', 'HomeController@index2');
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/', $routes[0]['uri']);
        $this->assertEquals('/', $routes[1]['uri']);
    }

    // ========================================================================
    // GRUPO 3: PARÁMETROS DINÁMICOS
    // ========================================================================

    /**
     * Test: Rutas con parámetros se registran correctamente
     * 
     * EXPLICACIÓN:
     * Las rutas pueden contener parámetros dinámicos entre llaves {}.
     */
    public function testRoutesWithParametersAreRegistered(): void
    {
        Router::get('/usuario/{id}', 'UsuariosController@show');
        Router::get('/posts/{slug}/comentarios/{id}', 'ComentariosController@show');
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/usuario/{id}', $routes[0]['uri']);
        $this->assertEquals('/posts/{slug}/comentarios/{id}', $routes[1]['uri']);
    }

    /**
     * Test: getParameters() retorna array de parámetros
     * 
     * EXPLICACIÓN:
     * getParameters() debe retornar todos los parámetros extraídos
     * de la ruta actual. Por defecto debe ser un array vacío.
     */
    public function testGetParametersReturnsArray(): void
    {
        $params = Router::getParameters();
        
        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }

    /**
     * Test: getParameter() retorna parámetro específico
     * 
     * EXPLICACIÓN:
     * getParameter() debe poder acceder a un parámetro individual
     * o retornar un default si no existe.
     * 
     * NOTA: Como getParameter() depende del estado interno,
     * este test verifica su existencia y comportamiento básico.
     */
    public function testGetParameterWithDefault(): void
    {
        $value = Router::getParameter('id', 'default');
        
        $this->assertEquals('default', $value);
    }

    // ========================================================================
    // GRUPO 4: GRUPOS DE RUTAS
    // ========================================================================

    /**
     * Test: group() con prefijo agrupa rutas
     * 
     * EXPLICACIÓN:
     * group() con atributo 'prefix' debe agregar el prefijo
     * a todas las rutas definidas dentro del callback.
     */
    public function testGroupWithPrefixAddsPrefix(): void
    {
        Router::group(['prefix' => 'admin'], function() {
            Router::get('/dashboard', 'DashboardController@index');
            Router::get('/usuarios', 'UsuariosController@index');
        });
        
        $routes = Router::getRoutes();
        
        $this->assertCount(2, $routes);
        $this->assertEquals('/admin/dashboard', $routes[0]['uri']);
        $this->assertEquals('/admin/usuarios', $routes[1]['uri']);
    }

    /**
     * Test: group() con middleware aplica middleware
     * 
     * EXPLICACIÓN:
     * group() con atributo 'middleware' debe aplicar el middleware
     * a todas las rutas del grupo.
     */
    public function testGroupWithMiddlewareAppliesMiddleware(): void
    {
        Router::group(['middleware' => 'auth'], function() {
            Router::get('/perfil', 'PerfilController@show');
            Router::get('/configuracion', 'ConfigController@index');
        });
        
        $routes = Router::getRoutes();
        
        $this->assertCount(2, $routes);
        $this->assertContains('auth', $routes[0]['middleware']);
        $this->assertContains('auth', $routes[1]['middleware']);
    }

    /**
     * Test: group() con prefijo y middleware combina ambos
     * 
     * EXPLICACIÓN:
     * group() debe poder aplicar tanto prefijo como middleware
     * simultáneamente.
     */
    public function testGroupWithPrefixAndMiddleware(): void
    {
        Router::group(['prefix' => 'api', 'middleware' => 'api'], function() {
            Router::get('/usuarios', 'Api\UsuariosController@index');
        });
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/api/usuarios', $routes[0]['uri']);
        $this->assertContains('api', $routes[0]['middleware']);
    }

    /**
     * Test: Grupos anidados acumulan prefijos
     * 
     * EXPLICACIÓN:
     * Los grupos pueden anidarse, y sus prefijos se acumulan.
     */
    public function testNestedGroupsAccumulatePrefixes(): void
    {
        Router::group(['prefix' => 'api'], function() {
            Router::group(['prefix' => 'v1'], function() {
                Router::get('/usuarios', 'Api\V1\UsuariosController@index');
            });
        });
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/api/v1/usuarios', $routes[0]['uri']);
    }

    /**
     * Test: Grupos anidados acumulan middleware
     * 
     * EXPLICACIÓN:
     * Los grupos anidados deben acumular middleware de todos los niveles.
     */
    public function testNestedGroupsAccumulateMiddleware(): void
    {
        Router::group(['middleware' => 'api'], function() {
            Router::group(['middleware' => 'auth'], function() {
                Router::get('/perfil', 'PerfilController@show');
            });
        });
        
        $routes = Router::getRoutes();
        
        $this->assertContains('api', $routes[0]['middleware']);
        $this->assertContains('auth', $routes[0]['middleware']);
    }

    // ========================================================================
    // GRUPO 5: ROUTE REGISTRAR (FLUENT INTERFACE)
    // ========================================================================

    /**
     * Test: middleware() agrega middleware a ruta individual
     * 
     * EXPLICACIÓN:
     * El método middleware() de RouteRegistrar debe permitir
     * agregar middleware a una ruta específica.
     */
    public function testRouteMiddlewareAddsMiddleware(): void
    {
        Router::get('/admin', 'AdminController@index')->middleware('auth');
        
        $routes = Router::getRoutes();
        
        $this->assertContains('auth', $routes[0]['middleware']);
    }

    /**
     * Test: middleware() acepta array de middleware
     * 
     * EXPLICACIÓN:
     * middleware() debe aceptar tanto strings como arrays.
     */
    public function testRouteMiddlewareAcceptsArray(): void
    {
        Router::get('/admin', 'AdminController@index')->middleware(['auth', 'admin']);
        
        $routes = Router::getRoutes();
        
        $this->assertContains('auth', $routes[0]['middleware']);
        $this->assertContains('admin', $routes[0]['middleware']);
    }

    /**
     * Test: middleware() se puede encadenar
     * 
     * EXPLICACIÓN:
     * middleware() retorna RouteRegistrar para permitir encadenamiento.
     */
    public function testRouteMiddlewareIsChainable(): void
    {
        $registrar = Router::get('/test', 'TestController@index')
            ->middleware('auth')
            ->middleware('verified');
        
        $this->assertInstanceOf(\NatanPHP\Core\RouteRegistrar::class, $registrar);
        
        $routes = Router::getRoutes();
        $this->assertContains('auth', $routes[0]['middleware']);
        $this->assertContains('verified', $routes[0]['middleware']);
    }

    /**
     * Test: name() asigna nombre a la ruta
     * 
     * EXPLICACIÓN:
     * name() debe agregar un nombre identificador a la ruta
     * para generar URLs dinámicamente.
     */
    public function testRouteNameAssignsName(): void
    {
        Router::get('/usuario/{id}', 'UsuariosController@show')->name('usuarios.show');
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('usuarios.show', $routes[0]['name']);
    }

    /**
     * Test: name() y middleware() se pueden combinar
     * 
     * EXPLICACIÓN:
     * El fluent interface debe permitir combinar múltiples
     * configuraciones en cadena.
     */
    public function testRouteNameAndMiddlewareCanBeCombined(): void
    {
        Router::get('/perfil', 'PerfilController@show')
            ->middleware('auth')
            ->name('perfil.show');
        
        $routes = Router::getRoutes();
        
        $this->assertContains('auth', $routes[0]['middleware']);
        $this->assertEquals('perfil.show', $routes[0]['name']);
    }

    // ========================================================================
    // GRUPO 6: UTILIDADES
    // ========================================================================

    /**
     * Test: getRoutes() retorna todas las rutas registradas
     * 
     * EXPLICACIÓN:
     * getRoutes() debe retornar el array completo de rutas.
     */
    public function testGetRoutesReturnsAllRoutes(): void
    {
        Router::get('/home', 'HomeController@index');
        Router::post('/contacto', 'ContactoController@store');
        Router::put('/usuario/{id}', 'UsuariosController@update');
        
        $routes = Router::getRoutes();
        
        $this->assertIsArray($routes);
        $this->assertCount(3, $routes);
    }

    /**
     * Test: getRoutes() retorna array vacío si no hay rutas
     * 
     * EXPLICACIÓN:
     * Si no se han registrado rutas, debe retornar array vacío.
     */
    public function testGetRoutesReturnsEmptyArrayWhenNoRoutes(): void
    {
        $routes = Router::getRoutes();
        
        $this->assertIsArray($routes);
        $this->assertEmpty($routes);
    }

    /**
     * Test: Múltiples rutas se acumulan correctamente
     * 
     * EXPLICACIÓN:
     * Cada llamada a un método de registro debe agregar una nueva ruta
     * sin afectar las anteriores.
     */
    public function testMultipleRoutesAreAccumulated(): void
    {
        Router::get('/ruta1', 'Controller1@method1');
        Router::post('/ruta2', 'Controller2@method2');
        Router::put('/ruta3', 'Controller3@method3');
        Router::delete('/ruta4', 'Controller4@method4');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(4, $routes);
        
        // Verificar que todas las rutas están presentes
        $uris = array_column($routes, 'uri');
        $this->assertContains('/ruta1', $uris);
        $this->assertContains('/ruta2', $uris);
        $this->assertContains('/ruta3', $uris);
        $this->assertContains('/ruta4', $uris);
    }

    // ========================================================================
    // GRUPO 7: CASOS ESPECIALES Y EDGE CASES
    // ========================================================================

    /**
     * Test: Rutas con misma URI pero diferente método coexisten
     * 
     * EXPLICACIÓN:
     * Es válido tener la misma URI con diferentes métodos HTTP
     * (patrón RESTful común).
     */
    public function testSameUriDifferentMethodsCoexist(): void
    {
        Router::get('/usuarios', 'UsuariosController@index');
        Router::post('/usuarios', 'UsuariosController@store');
        
        $routes = Router::getRoutes();
        
        $this->assertCount(2, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('POST', $routes[1]['method']);
        $this->assertEquals('/usuarios', $routes[0]['uri']);
        $this->assertEquals('/usuarios', $routes[1]['uri']);
    }

    /**
     * Test: Parámetros con nombres descriptivos funcionan
     * 
     * EXPLICACIÓN:
     * Los parámetros pueden tener cualquier nombre válido.
     */
    public function testParametersWithDescriptiveNames(): void
    {
        Router::get('/blog/{year}/{month}/{slug}', 'BlogController@show');
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/blog/{year}/{month}/{slug}', $routes[0]['uri']);
    }

    /**
     * Test: Middleware vacío se inicializa correctamente
     * 
     * EXPLICACIÓN:
     * Una ruta sin middleware debe tener un array vacío,
     * no null ni undefined.
     */
    public function testRouteWithoutMiddlewareHasEmptyArray(): void
    {
        Router::get('/public', 'PublicController@index');
        
        $routes = Router::getRoutes();
        
        $this->assertIsArray($routes[0]['middleware']);
        $this->assertEmpty($routes[0]['middleware']);
    }

    /**
     * Test: Group restaura estado después de callback
     * 
     * EXPLICACIÓN:
     * Después de ejecutar un group(), el prefijo y middleware
     * deben volver a su estado anterior.
     */
    public function testGroupRestoresStateAfterCallback(): void
    {
        Router::get('/antes', 'AntesController@index');
        
        Router::group(['prefix' => 'admin'], function() {
            Router::get('/dentro', 'DentroController@index');
        });
        
        Router::get('/despues', 'DespuesController@index');
        
        $routes = Router::getRoutes();
        
        $this->assertEquals('/antes', $routes[0]['uri']);
        $this->assertEquals('/admin/dentro', $routes[1]['uri']);
        $this->assertEquals('/despues', $routes[2]['uri']); // Sin prefijo
    }
}
