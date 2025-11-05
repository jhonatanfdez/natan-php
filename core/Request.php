<?php

namespace Core;

/**
 * Request - Manejo de peticiones HTTP del framework NatanPHP
 * 
 * Proporciona una interfaz unificada para acceder a todos los datos
 * de las peticiones HTTP de forma segura y conveniente.
 * 
 * @package NatanPHP\Core
 * @author Natan PHP Framework
 */
class Request
{
    /**
     * Datos GET de la petición
     * 
     * @var array
     */
    protected $get;

    /**
     * Datos POST de la petición
     * 
     * @var array
     */
    protected $post;

    /**
     * Archivos subidos en la petición
     * 
     * @var array
     */
    protected $files;

    /**
     * Headers de la petición
     * 
     * @var array
     */
    protected $headers;

    /**
     * Información del servidor
     * 
     * @var array
     */
    protected $server;

    /**
     * Constructor - Inicializa la petición con datos actuales
     * 
     * Captura automáticamente todos los datos de la petición HTTP actual
     * incluyendo GET, POST, archivos, headers y información del servidor.
     */
    public function __construct()
    {
        $this->get = $_GET ?? [];
        $this->post = $_POST ?? [];
        $this->files = $_FILES ?? [];
        $this->server = $_SERVER ?? [];
        $this->headers = $this->extractHeaders();
    }

    /**
     * Obtiene el método HTTP de la petición
     * 
     * Retorna el método HTTP usado (GET, POST, PUT, DELETE, etc.)
     * 
     * @return string Método HTTP en mayúsculas
     * 
     * @example 
     * $request = new Request();
     * echo $request->method(); // "GET" o "POST"
     */
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Verifica si la petición es de un método específico
     * 
     * @param string $method Método a verificar (GET, POST, PUT, DELETE)
     * @return bool True si coincide el método
     * 
     * @example
     * if ($request->isMethod('POST')) {
     *     // Procesar datos del formulario
     * }
     */
    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    /**
     * Verifica si la petición es GET
     * 
     * @return bool True si es petición GET
     */
    public function isGet(): bool
    {
        return $this->isMethod('GET');
    }

    /**
     * Verifica si la petición es POST
     * 
     * @return bool True si es petición POST
     */
    public function isPost(): bool
    {
        return $this->isMethod('POST');
    }

    /**
     * Obtiene la URL actual de la petición
     * 
     * Retorna la URI completa sin el dominio
     * 
     * @return string URI de la petición
     * 
     * @example
     * // Si la URL es: http://localhost/productos/crear
     * echo $request->uri(); // "/productos/crear"
     */
    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        
        // Remover query string si existe
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return $uri;
    }

    /**
     * Obtiene la URL completa con query string
     * 
     * @return string URL completa
     * 
     * @example
     * // URL: http://localhost/productos?categoria=tecnologia
     * echo $request->fullUrl(); // "/productos?categoria=tecnologia"
     */
    public function fullUrl(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    /**
     * Obtiene un valor específico de GET
     * 
     * @param string $key Clave del parámetro GET
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor del parámetro o default
     * 
     * @example
     * // URL: /productos?categoria=tecnologia&precio=100
     * echo $request->get('categoria'); // "tecnologia"
     * echo $request->get('descuento', 0); // 0 (no existe, usa default)
     */
    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Obtiene un valor específico de POST
     * 
     * @param string $key Clave del parámetro POST
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor del parámetro o default
     * 
     * @example
     * // Formulario: <input name="nombre" value="Juan">
     * echo $request->post('nombre'); // "Juan"
     * echo $request->post('apellido', ''); // "" (no existe)
     */
    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Obtiene un valor de entrada (GET o POST)
     * 
     * Busca primero en POST, luego en GET. Útil para formularios
     * que pueden enviarse por ambos métodos.
     * 
     * @param string $key Clave del parámetro
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor encontrado o default
     * 
     * @example
     * // Funciona tanto para GET como POST
     * $nombre = $request->input('nombre', 'Anónimo');
     */
    public function input(string $key, $default = null)
    {
        return $this->post($key) ?? $this->get($key) ?? $default;
    }

    /**
     * Obtiene todos los datos de entrada (GET + POST)
     * 
     * Combina todos los parámetros GET y POST en un solo array.
     * Los valores POST tienen prioridad sobre GET en caso de conflicto.
     * 
     * @return array Todos los datos de entrada
     * 
     * @example
     * $todosLosDatos = $request->all();
     * // ['nombre' => 'Juan', 'email' => 'juan@ejemplo.com', ...]
     */
    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    /**
     * Obtiene solo los campos especificados
     * 
     * @param array $keys Lista de claves a obtener
     * @return array Array con solo los campos solicitados
     * 
     * @example
     * // Solo obtener nombre y email
     * $datos = $request->only(['nombre', 'email']);
     */
    public function only(array $keys): array
    {
        $result = [];
        $allData = $this->all();
        
        foreach ($keys as $key) {
            if (array_key_exists($key, $allData)) {
                $result[$key] = $allData[$key];
            }
        }
        
        return $result;
    }

    /**
     * Obtiene todos los campos excepto los especificados
     * 
     * @param array $keys Lista de claves a excluir
     * @return array Array sin los campos especificados
     * 
     * @example
     * // Obtener todo excepto la contraseña
     * $datos = $request->except(['password', 'password_confirmation']);
     */
    public function except(array $keys): array
    {
        $allData = $this->all();
        
        foreach ($keys as $key) {
            unset($allData[$key]);
        }
        
        return $allData;
    }

    /**
     * Verifica si existe un parámetro específico
     * 
     * @param string $key Clave del parámetro
     * @return bool True si existe el parámetro
     * 
     * @example
     * if ($request->has('email')) {
     *     $email = $request->input('email');
     * }
     */
    public function has(string $key): bool
    {
        $allData = $this->all();
        return array_key_exists($key, $allData);
    }

    /**
     * Verifica si un parámetro existe y tiene contenido
     * 
     * Usa la función helper filled() para verificar contenido real
     * 
     * @param string $key Clave del parámetro
     * @return bool True si existe y tiene contenido
     * 
     * @example
     * if ($request->filled('nombre')) {
     *     // El campo nombre tiene contenido real
     * }
     */
    public function filled(string $key): bool
    {
        return $this->has($key) && filled($this->input($key));
    }

    /**
     * Obtiene información de un archivo subido
     * 
     * @param string $key Nombre del campo file del formulario
     * @return array|null Información del archivo o null si no existe
     * 
     * @example
     * // <input type="file" name="avatar">
     * $archivo = $request->file('avatar');
     * if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
     *     // Procesar archivo
     * }
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Verifica si se subió un archivo específico
     * 
     * @param string $key Nombre del campo file
     * @return bool True si hay archivo y se subió correctamente
     */
    public function hasFile(string $key): bool
    {
        $file = $this->file($key);
        return $file && $file['error'] === UPLOAD_ERR_OK && $file['size'] > 0;
    }

    /**
     * Obtiene un header específico
     * 
     * @param string $key Nombre del header
     * @param mixed $default Valor por defecto
     * @return mixed Valor del header o default
     * 
     * @example
     * $contentType = $request->header('Content-Type');
     * $userAgent = $request->header('User-Agent', 'Desconocido');
     */
    public function header(string $key, $default = null)
    {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }

    /**
     * Obtiene la IP del cliente
     * 
     * Considera proxies y load balancers
     * 
     * @return string IP del cliente
     */
    public function ip(): string
    {
        // Verificar headers de proxies comunes
        $ipKeys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipKeys as $key) {
            if (!empty($this->server[$key])) {
                $ip = $this->server[$key];
                
                // Si hay múltiples IPs (proxies), tomar la primera
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                
                return trim($ip);
            }
        }
        
        return '127.0.0.1'; // Fallback
    }

    /**
     * Obtiene el User Agent del navegador
     * 
     * @return string User Agent string
     */
    public function userAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Verifica si la petición es AJAX
     * 
     * @return bool True si es petición AJAX
     */
    public function isAjax(): bool
    {
        return strtolower($this->header('X-Requested-With', '')) === 'xmlhttprequest';
    }

    /**
     * Verifica si la petición espera JSON
     * 
     * @return bool True si acepta JSON
     */
    public function wantsJson(): bool
    {
        $accept = $this->header('Accept', '');
        return strpos($accept, 'application/json') !== false;
    }

    /**
     * Extrae headers HTTP de $_SERVER
     * 
     * Convierte headers del formato SERVER a formato estándar
     * 
     * @return array Headers normalizados
     */
    protected function extractHeaders(): array
    {
        $headers = [];
        
        foreach ($this->server as $key => $value) {
            // Headers HTTP empiezan con HTTP_
            if (strpos($key, 'HTTP_') === 0) {
                // Convertir HTTP_CONTENT_TYPE a content-type
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$headerName] = $value;
            }
            
            // Headers especiales que no empiezan con HTTP_
            $specialHeaders = [
                'CONTENT_TYPE' => 'content-type',
                'CONTENT_LENGTH' => 'content-length'
            ];
            
            if (isset($specialHeaders[$key])) {
                $headers[$specialHeaders[$key]] = $value;
            }
        }
        
        return $headers;
    }
}
