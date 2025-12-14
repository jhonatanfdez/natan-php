<?php

namespace NatanPHP\Tests\Unit;

use PHPUnit\Framework\TestCase;
use NatanPHP\Core\Request;

/**
 * ============================================================================
 * RequestTest - Tests exhaustivos para la clase Request
 * ============================================================================
 * 
 * ¿QUÉ CONTIENE?
 * - Tests de métodos HTTP (isGet, isPost, isMethod, method)
 * - Tests de URIs (uri, fullUrl)
 * - Tests de acceso a datos (get, post, input, all, only, except)
 * - Tests de validación (has, filled)
 * - Tests de archivos (file, hasFile)
 * - Tests de headers (header, extractHeaders)
 * - Tests de información del cliente (ip, userAgent, isAjax, wantsJson)
 * 
 * COBERTURA: 23 métodos públicos de Request
 * 
 * @package NatanPHP\Tests\Unit
 */
class RequestTest extends TestCase
{
    // ========================================================================
    // GRUPO 1: MÉTODOS HTTP
    // ========================================================================

    /**
     * Test: Verificar que method() retorna el método HTTP correcto
     * 
     * EXPLICACIÓN:
     * El método method() debe leer REQUEST_METHOD de $_SERVER y retornarlo
     * en mayúsculas. Por defecto debería retornar 'GET' si no existe.
     */
    public function testMethodReturnsCorrectHttpMethod(): void
    {
        // Simular petición GET
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $this->assertEquals('GET', $request->method());

        // Simular petición POST
        $_SERVER['REQUEST_METHOD'] = 'post'; // En minúsculas para probar conversión
        $request = new Request();
        $this->assertEquals('POST', $request->method());
    }

    /**
     * Test: method() retorna GET por defecto si REQUEST_METHOD no existe
     * 
     * EXPLICACIÓN:
     * Si REQUEST_METHOD no está definido, debe retornar 'GET' como fallback.
     */
    public function testMethodReturnsDefaultGet(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        $request = new Request();
        $this->assertEquals('GET', $request->method());
    }

    /**
     * Test: isMethod() verifica correctamente el método HTTP
     * 
     * EXPLICACIÓN:
     * isMethod() debe comparar el método actual con el proporcionado,
     * sin importar mayúsculas/minúsculas.
     */
    public function testIsMethodVerifiesHttpMethod(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $this->assertTrue($request->isMethod('POST'));
        $this->assertTrue($request->isMethod('post')); // Case insensitive
        $this->assertFalse($request->isMethod('GET'));
    }

    /**
     * Test: isGet() retorna true solo en peticiones GET
     * 
     * EXPLICACIÓN:
     * Método helper que simplifica la verificación de peticiones GET.
     */
    public function testIsGetReturnsTrueForGetRequests(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $this->assertTrue($request->isGet());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        $this->assertFalse($request->isGet());
    }

    /**
     * Test: isPost() retorna true solo en peticiones POST
     * 
     * EXPLICACIÓN:
     * Método helper que simplifica la verificación de peticiones POST.
     */
    public function testIsPostReturnsTrueForPostRequests(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        $this->assertTrue($request->isPost());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $this->assertFalse($request->isPost());
    }

    // ========================================================================
    // GRUPO 2: URIs Y RUTAS
    // ========================================================================

    /**
     * Test: uri() retorna la URI sin query string
     * 
     * EXPLICACIÓN:
     * uri() debe retornar solo el path, eliminando cualquier query string.
     * Ejemplo: /productos?categoria=tech → /productos
     */
    public function testUriReturnsPathWithoutQueryString(): void
    {
        $_SERVER['REQUEST_URI'] = '/productos/crear';
        $request = new Request();
        $this->assertEquals('/productos/crear', $request->uri());

        // Con query string - debe eliminarla
        $_SERVER['REQUEST_URI'] = '/productos?categoria=tecnologia&precio=100';
        $request = new Request();
        $this->assertEquals('/productos', $request->uri());
    }

    /**
     * Test: uri() retorna '/' por defecto
     * 
     * EXPLICACIÓN:
     * Si REQUEST_URI no existe, debe retornar '/' como ruta raíz.
     */
    public function testUriReturnsSlashByDefault(): void
    {
        unset($_SERVER['REQUEST_URI']);
        $request = new Request();
        $this->assertEquals('/', $request->uri());
    }

    /**
     * Test: fullUrl() retorna la URL completa con query string
     * 
     * EXPLICACIÓN:
     * A diferencia de uri(), fullUrl() debe mantener el query string intacto.
     */
    public function testFullUrlReturnsCompleteUrl(): void
    {
        $_SERVER['REQUEST_URI'] = '/productos?categoria=tecnologia&precio=100';
        $request = new Request();
        $this->assertEquals('/productos?categoria=tecnologia&precio=100', $request->fullUrl());

        $_SERVER['REQUEST_URI'] = '/usuarios/perfil';
        $request = new Request();
        $this->assertEquals('/usuarios/perfil', $request->fullUrl());
    }

    // ========================================================================
    // GRUPO 3: ACCESO A DATOS GET
    // ========================================================================

    /**
     * Test: get() retorna valores GET correctamente
     * 
     * EXPLICACIÓN:
     * get() debe acceder a parámetros de la query string ($_GET).
     */
    public function testGetReturnsGetParameters(): void
    {
        $_GET = ['categoria' => 'tecnologia', 'precio' => '100'];
        $request = new Request();
        
        $this->assertEquals('tecnologia', $request->get('categoria'));
        $this->assertEquals('100', $request->get('precio'));
    }

    /**
     * Test: get() retorna default si el parámetro no existe
     * 
     * EXPLICACIÓN:
     * Si la clave no existe en $_GET, debe retornar el valor por defecto.
     */
    public function testGetReturnsDefaultWhenNotFound(): void
    {
        $_GET = ['nombre' => 'Juan'];
        $request = new Request();
        
        $this->assertEquals('Anónimo', $request->get('apellido', 'Anónimo'));
        $this->assertNull($request->get('email')); // Default null
    }

    // ========================================================================
    // GRUPO 4: ACCESO A DATOS POST
    // ========================================================================

    /**
     * Test: post() retorna valores POST correctamente
     * 
     * EXPLICACIÓN:
     * post() debe acceder a datos del cuerpo de la petición ($_POST).
     */
    public function testPostReturnsPostParameters(): void
    {
        $_POST = ['nombre' => 'Juan', 'email' => 'juan@ejemplo.com'];
        $request = new Request();
        
        $this->assertEquals('Juan', $request->post('nombre'));
        $this->assertEquals('juan@ejemplo.com', $request->post('email'));
    }

    /**
     * Test: post() retorna default si el parámetro no existe
     * 
     * EXPLICACIÓN:
     * Si la clave no existe en $_POST, debe retornar el valor por defecto.
     */
    public function testPostReturnsDefaultWhenNotFound(): void
    {
        $_POST = ['nombre' => 'Juan'];
        $request = new Request();
        
        $this->assertEquals('', $request->post('apellido', ''));
        $this->assertNull($request->post('telefono'));
    }

    // ========================================================================
    // GRUPO 5: ACCESO COMBINADO A DATOS
    // ========================================================================

    /**
     * Test: input() busca en POST primero, luego en GET
     * 
     * EXPLICACIÓN:
     * input() es un método universal que busca primero en POST,
     * luego en GET, útil para formularios flexibles.
     * 
     * IMPORTANTE: POST tiene prioridad sobre GET.
     */
    public function testInputChecksPostBeforeGet(): void
    {
        $_GET = ['campo' => 'valor_get'];
        $_POST = ['campo' => 'valor_post'];
        $request = new Request();
        
        // POST tiene prioridad
        $this->assertEquals('valor_post', $request->input('campo'));
    }

    /**
     * Test: input() retorna GET si no está en POST
     * 
     * EXPLICACIÓN:
     * Si el campo no está en POST, debe buscarlo en GET.
     */
    public function testInputReturnsGetWhenNotInPost(): void
    {
        $_GET = ['categoria' => 'deportes'];
        $_POST = ['nombre' => 'Juan'];
        $request = new Request();
        
        $this->assertEquals('deportes', $request->input('categoria'));
        $this->assertEquals('Juan', $request->input('nombre'));
    }

    /**
     * Test: input() retorna default si no existe en ninguno
     * 
     * EXPLICACIÓN:
     * Si no está ni en POST ni en GET, retorna el valor por defecto.
     */
    public function testInputReturnsDefaultWhenNotFound(): void
    {
        $_GET = [];
        $_POST = [];
        $request = new Request();
        
        $this->assertEquals('predeterminado', $request->input('campo', 'predeterminado'));
    }

    /**
     * Test: all() combina GET y POST
     * 
     * EXPLICACIÓN:
     * all() debe retornar un array con todos los parámetros,
     * donde POST sobrescribe GET en caso de claves duplicadas.
     */
    public function testAllCombinesGetAndPost(): void
    {
        $_GET = ['get_campo' => 'valor_get', 'compartido' => 'get'];
        $_POST = ['post_campo' => 'valor_post', 'compartido' => 'post'];
        $request = new Request();
        
        $all = $request->all();
        
        $this->assertArrayHasKey('get_campo', $all);
        $this->assertArrayHasKey('post_campo', $all);
        $this->assertEquals('valor_get', $all['get_campo']);
        $this->assertEquals('valor_post', $all['post_campo']);
        $this->assertEquals('post', $all['compartido']); // POST sobrescribe GET
    }

    /**
     * Test: only() retorna solo los campos especificados
     * 
     * EXPLICACIÓN:
     * only() es un filtro que retorna únicamente las claves solicitadas,
     * útil para validación y procesamiento selectivo.
     */
    public function testOnlyReturnsSpecifiedFields(): void
    {
        $_POST = [
            'nombre' => 'Juan',
            'email' => 'juan@ejemplo.com',
            'password' => 'secreto123',
            'telefono' => '555-1234'
        ];
        $request = new Request();
        
        $filtered = $request->only(['nombre', 'email']);
        
        $this->assertCount(2, $filtered);
        $this->assertArrayHasKey('nombre', $filtered);
        $this->assertArrayHasKey('email', $filtered);
        $this->assertArrayNotHasKey('password', $filtered);
        $this->assertArrayNotHasKey('telefono', $filtered);
    }

    /**
     * Test: only() no incluye claves que no existen
     * 
     * EXPLICACIÓN:
     * Si se solicita una clave que no existe, simplemente no se incluye.
     */
    public function testOnlyDoesNotIncludeNonExistentKeys(): void
    {
        $_POST = ['nombre' => 'Juan'];
        $request = new Request();
        
        $filtered = $request->only(['nombre', 'email', 'telefono']);
        
        $this->assertCount(1, $filtered);
        $this->assertArrayHasKey('nombre', $filtered);
        $this->assertArrayNotHasKey('email', $filtered);
    }

    /**
     * Test: except() excluye campos especificados
     * 
     * EXPLICACIÓN:
     * except() es el opuesto de only(): retorna todo excepto
     * las claves especificadas. Útil para remover datos sensibles.
     */
    public function testExceptExcludesSpecifiedFields(): void
    {
        $_POST = [
            'nombre' => 'Juan',
            'email' => 'juan@ejemplo.com',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123'
        ];
        $request = new Request();
        
        $filtered = $request->except(['password', 'password_confirmation']);
        
        $this->assertCount(2, $filtered);
        $this->assertArrayHasKey('nombre', $filtered);
        $this->assertArrayHasKey('email', $filtered);
        $this->assertArrayNotHasKey('password', $filtered);
        $this->assertArrayNotHasKey('password_confirmation', $filtered);
    }

    // ========================================================================
    // GRUPO 6: VALIDACIÓN DE DATOS
    // ========================================================================

    /**
     * Test: has() verifica existencia de parámetros
     * 
     * EXPLICACIÓN:
     * has() verifica si una clave existe en GET o POST,
     * sin importar su valor (puede ser null o vacío).
     */
    public function testHasChecksParameterExistence(): void
    {
        $_GET = ['campo_get' => 'valor'];
        $_POST = ['campo_post' => '', 'campo_null' => null];
        $request = new Request();
        
        $this->assertTrue($request->has('campo_get'));
        $this->assertTrue($request->has('campo_post')); // Existe aunque vacío
        $this->assertTrue($request->has('campo_null')); // Existe aunque null
        $this->assertFalse($request->has('campo_inexistente'));
    }

    /**
     * Test: filled() verifica existencia Y contenido
     * 
     * EXPLICACIÓN:
     * filled() es más estricto que has(): verifica que exista
     * Y que tenga contenido real (usa el helper filled()).
     * 
     * IMPORTANTE: Usa la lógica de filled() que vimos en FASE 1.
     */
    public function testFilledChecksExistenceAndContent(): void
    {
        $_POST = [
            'nombre' => 'Juan',
            'email' => '',
            'telefono' => '   ',
            'edad' => 0
        ];
        $request = new Request();
        
        $this->assertTrue($request->filled('nombre')); // Tiene contenido
        $this->assertFalse($request->filled('email')); // Vacío
        $this->assertFalse($request->filled('telefono')); // Solo espacios
        $this->assertFalse($request->filled('edad')); // 0 es blank
        $this->assertFalse($request->filled('inexistente')); // No existe
    }

    // ========================================================================
    // GRUPO 7: ARCHIVOS SUBIDOS
    // ========================================================================

    /**
     * Test: file() retorna información del archivo
     * 
     * EXPLICACIÓN:
     * file() debe acceder al array $_FILES y retornar
     * la información completa del archivo subido.
     */
    public function testFileReturnsFileInformation(): void
    {
        $_FILES = [
            'avatar' => [
                'name' => 'foto.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/phpXXXXXX',
                'error' => UPLOAD_ERR_OK,
                'size' => 1024
            ]
        ];
        $request = new Request();
        
        $file = $request->file('avatar');
        
        $this->assertIsArray($file);
        $this->assertEquals('foto.jpg', $file['name']);
        $this->assertEquals('image/jpeg', $file['type']);
        $this->assertEquals(1024, $file['size']);
    }

    /**
     * Test: file() retorna null si no existe
     * 
     * EXPLICACIÓN:
     * Si no hay archivo con ese nombre, debe retornar null.
     */
    public function testFileReturnsNullWhenNotFound(): void
    {
        $_FILES = [];
        $request = new Request();
        
        $this->assertNull($request->file('avatar'));
    }

    /**
     * Test: hasFile() verifica archivo subido correctamente
     * 
     * EXPLICACIÓN:
     * hasFile() es más estricto: verifica que el archivo exista,
     * no tenga errores (UPLOAD_ERR_OK) y tenga tamaño > 0.
     */
    public function testHasFileVerifiesSuccessfulUpload(): void
    {
        // Archivo válido
        $_FILES = [
            'avatar' => [
                'name' => 'foto.jpg',
                'error' => UPLOAD_ERR_OK,
                'size' => 1024
            ]
        ];
        $request = new Request();
        $this->assertTrue($request->hasFile('avatar'));

        // Archivo con error
        $_FILES = [
            'documento' => [
                'name' => 'doc.pdf',
                'error' => UPLOAD_ERR_NO_FILE,
                'size' => 0
            ]
        ];
        $request = new Request();
        $this->assertFalse($request->hasFile('documento'));

        // Archivo vacío
        $_FILES = [
            'vacio' => [
                'name' => 'empty.txt',
                'error' => UPLOAD_ERR_OK,
                'size' => 0
            ]
        ];
        $request = new Request();
        $this->assertFalse($request->hasFile('vacio'));
    }

    // ========================================================================
    // GRUPO 8: HEADERS HTTP
    // ========================================================================

    /**
     * Test: header() retorna headers HTTP
     * 
     * EXPLICACIÓN:
     * header() debe acceder a los headers extraídos de $_SERVER.
     * Los nombres deben ser case-insensitive (minúsculas).
     */
    public function testHeaderReturnsHttpHeaders(): void
    {
        $_SERVER = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
            'HTTP_ACCEPT' => 'text/html',
            'CONTENT_TYPE' => 'application/json'
        ];
        $request = new Request();
        
        $this->assertEquals('Mozilla/5.0', $request->header('user-agent'));
        $this->assertEquals('text/html', $request->header('accept'));
        $this->assertEquals('application/json', $request->header('content-type'));
    }

    /**
     * Test: header() retorna default si no existe
     * 
     * EXPLICACIÓN:
     * Si el header no existe, debe retornar el valor por defecto.
     */
    public function testHeaderReturnsDefaultWhenNotFound(): void
    {
        $_SERVER = [];
        $request = new Request();
        
        $this->assertEquals('Desconocido', $request->header('user-agent', 'Desconocido'));
        $this->assertNull($request->header('accept'));
    }

    /**
     * Test: extractHeaders() normaliza headers de $_SERVER
     * 
     * EXPLICACIÓN:
     * extractHeaders() debe convertir headers del formato $_SERVER
     * (HTTP_CONTENT_TYPE) al formato estándar (content-type).
     * 
     * IMPORTANTE: También debe manejar headers especiales como
     * CONTENT_TYPE y CONTENT_LENGTH que no tienen prefijo HTTP_.
     */
    public function testExtractHeadersNormalizesServerHeaders(): void
    {
        $_SERVER = [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'HTTP_AUTHORIZATION' => 'Bearer token123',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
            'CONTENT_LENGTH' => '1234',
            'REQUEST_METHOD' => 'POST', // No es header, no debe incluirse
            'SERVER_NAME' => 'localhost' // No es header, no debe incluirse
        ];
        $request = new Request();
        
        // Verificar headers normalizados
        $this->assertEquals('application/json', $request->header('accept'));
        $this->assertEquals('XMLHttpRequest', $request->header('x-requested-with'));
        $this->assertEquals('Bearer token123', $request->header('authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->header('content-type'));
        $this->assertEquals('1234', $request->header('content-length'));
    }

    // ========================================================================
    // GRUPO 9: INFORMACIÓN DEL CLIENTE
    // ========================================================================

    /**
     * Test: ip() retorna la IP del cliente
     * 
     * EXPLICACIÓN:
     * ip() debe obtener la IP real del cliente, considerando
     * proxies y load balancers comunes.
     * 
     * ORDEN DE PRIORIDAD:
     * 1. HTTP_X_FORWARDED_FOR (proxies)
     * 2. HTTP_X_REAL_IP (nginx)
     * 3. HTTP_CLIENT_IP (otros proxies)
     * 4. REMOTE_ADDR (directo)
     * 5. 127.0.0.1 (fallback)
     */
    public function testIpReturnsClientIpAddress(): void
    {
        // IP directa
        $_SERVER = ['REMOTE_ADDR' => '192.168.1.100'];
        $request = new Request();
        $this->assertEquals('192.168.1.100', $request->ip());

        // IP desde proxy
        $_SERVER = [
            'HTTP_X_FORWARDED_FOR' => '203.0.113.1',
            'REMOTE_ADDR' => '10.0.0.1'
        ];
        $request = new Request();
        $this->assertEquals('203.0.113.1', $request->ip());
    }

    /**
     * Test: ip() maneja múltiples IPs de proxies
     * 
     * EXPLICACIÓN:
     * Cuando hay múltiples proxies, X-Forwarded-For puede contener
     * múltiples IPs separadas por comas. Debe tomar la primera.
     */
    public function testIpHandlesMultipleProxyIps(): void
    {
        $_SERVER = [
            'HTTP_X_FORWARDED_FOR' => '203.0.113.1, 198.51.100.1, 192.0.2.1'
        ];
        $request = new Request();
        
        $this->assertEquals('203.0.113.1', $request->ip());
    }

    /**
     * Test: ip() retorna fallback cuando no hay IP
     * 
     * EXPLICACIÓN:
     * Si no hay ninguna IP disponible, debe retornar 127.0.0.1.
     */
    public function testIpReturnsFallbackWhenNoIp(): void
    {
        $_SERVER = [];
        $request = new Request();
        
        $this->assertEquals('127.0.0.1', $request->ip());
    }

    /**
     * Test: userAgent() retorna el User Agent
     * 
     * EXPLICACIÓN:
     * userAgent() debe retornar el string del navegador/cliente.
     */
    public function testUserAgentReturnsUserAgentString(): void
    {
        $_SERVER = ['HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'];
        $request = new Request();
        
        $this->assertEquals('Mozilla/5.0 (Windows NT 10.0; Win64; x64)', $request->userAgent());
    }

    /**
     * Test: userAgent() retorna string vacío si no existe
     * 
     * EXPLICACIÓN:
     * Si no hay User Agent, retorna string vacío (no null).
     */
    public function testUserAgentReturnsEmptyStringWhenNotFound(): void
    {
        $_SERVER = [];
        $request = new Request();
        
        $this->assertEquals('', $request->userAgent());
    }

    /**
     * Test: isAjax() detecta peticiones AJAX
     * 
     * EXPLICACIÓN:
     * isAjax() verifica si el header X-Requested-With
     * contiene 'XMLHttpRequest' (estándar de jQuery y otros).
     */
    public function testIsAjaxDetectsAjaxRequests(): void
    {
        // Petición AJAX
        $_SERVER = ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'];
        $request = new Request();
        $this->assertTrue($request->isAjax());

        // Petición normal
        $_SERVER = [];
        $request = new Request();
        $this->assertFalse($request->isAjax());

        // Case insensitive
        $_SERVER = ['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest'];
        $request = new Request();
        $this->assertTrue($request->isAjax());
    }

    /**
     * Test: wantsJson() detecta si espera respuesta JSON
     * 
     * EXPLICACIÓN:
     * wantsJson() verifica si el header Accept contiene
     * 'application/json', indicando que el cliente espera JSON.
     */
    public function testWantsJsonDetectsJsonRequests(): void
    {
        // Cliente espera JSON
        $_SERVER = ['HTTP_ACCEPT' => 'application/json'];
        $request = new Request();
        $this->assertTrue($request->wantsJson());

        // Cliente espera HTML
        $_SERVER = ['HTTP_ACCEPT' => 'text/html'];
        $request = new Request();
        $this->assertFalse($request->wantsJson());

        // Accept múltiple con JSON
        $_SERVER = ['HTTP_ACCEPT' => 'text/html, application/json, */*'];
        $request = new Request();
        $this->assertTrue($request->wantsJson());
    }

    // ========================================================================
    // TEARDOWN: Limpiar $_SERVER, $_GET, $_POST, $_FILES después de cada test
    // ========================================================================

    /**
     * Limpiar superglobales después de cada test
     * 
     * EXPLICACIÓN:
     * Es crucial limpiar las superglobales para evitar que los tests
     * se afecten entre sí (test isolation).
     */
    protected function tearDown(): void
    {
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_FILES = [];
        
        parent::tearDown();
    }
}
