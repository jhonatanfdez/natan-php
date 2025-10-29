<?php

/**
 * NatanPHP Framework - Helpers Globales
 * 
 * Funciones auxiliares disponibles en toda la aplicación
 * Estas funciones están siempre disponibles gracias al autoload
 * 
 * @package NatanPHP
 * @version 1.0.0
 */

if (!function_exists('dd')) {
    /**
     * Dump and Die - Para debugging
     * 
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars) {
        echo "<style>
            .dd-container { 
                background: #1e1e1e; 
                color: #fff; 
                padding: 20px; 
                margin: 10px; 
                border-radius: 8px;
                font-family: 'Consolas', 'Monaco', monospace;
            }
            .dd-title { 
                color: #ff6b6b; 
                font-weight: bold; 
                margin-bottom: 10px;
            }
        </style>";
        
        echo "<div class='dd-container'>";
        echo "<div class='dd-title'>🚨 NatanPHP Debug & Die</div>";
        
        foreach ($vars as $var) {
            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        }
        
        echo "</div>";
        die();
    }
}

if (!function_exists('env')) {
    /**
     * Obtener variable de entorno
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Convertir strings especiales
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        
        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Obtener configuración
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null) {
        static $config = [];
        
        $keys = explode('.', $key);
        $file = array_shift($keys);
        
        // Cargar archivo de configuración si no está cargado
        if (!isset($config[$file])) {
            $configFile = __DIR__ . "/../config/{$file}.php";
            if (file_exists($configFile)) {
                $config[$file] = require $configFile;
            } else {
                return $default;
            }
        }
        
        // Navegar por las claves anidadas
        $value = $config[$file];
        foreach ($keys as $key) {
            if (is_array($value) && isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
}

if (!function_exists('asset')) {
    /**
     * Generar URL para assets
     * 
     * @param string $path
     * @return string
     */
    function asset($path) {
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        return $baseUrl . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generar URL absoluta
     * 
     * @param string $path
     * @return string
     */
    function url($path = '') {
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireccionar a una URL
     * 
     * @param string $url
     * @param int $code
     * @return void
     */
    function redirect($url, $code = 302) {
        header("Location: $url", true, $code);
        exit();
    }
}

if (!function_exists('request')) {
    /**
     * Obtener instancia del request actual
     * 
     * @return \Core\Request
     */
    function request() {
        return \Core\Request::getInstance();
    }
}

if (!function_exists('old')) {
    /**
     * Obtener valor anterior de formulario
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old($key, $default = null) {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generar token CSRF
     * 
     * @return string
     */
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generar campo hidden con token CSRF
     * 
     * @return string
     */
    function csrf_field() {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    /**
     * Generar campo hidden para method spoofing
     * 
     * @param string $method
     * @return string
     */
    function method_field($method) {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('str_slug')) {
    /**
     * Convertir string a slug
     * 
     * @param string $string
     * @param string $separator
     * @return string
     */
    function str_slug($string, $separator = '-') {
        // Convertir a minúsculas
        $string = strtolower($string);
        
        // Reemplazar caracteres especiales
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        
        // Remover caracteres no alfanuméricos
        $string = preg_replace('/[^a-z0-9\-\s]/', '', $string);
        
        // Reemplazar espacios y guiones múltiples
        $string = preg_replace('/[\s\-]+/', $separator, $string);
        
        // Remover separadores del inicio y final
        return trim($string, $separator);
    }
}

if (!function_exists('str_limit')) {
    /**
     * Limitar longitud de string
     * 
     * @param string $string
     * @param int $limit
     * @param string $end
     * @return string
     */
    function str_limit($string, $limit = 100, $end = '...') {
        if (strlen($string) <= $limit) {
            return $string;
        }
        
        return substr($string, 0, $limit) . $end;
    }
}

if (!function_exists('collect')) {
    /**
     * Crear una colección simple
     * 
     * @param array $items
     * @return array
     */
    function collect($items = []) {
        // Por ahora retorna el array, más adelante implementaremos Collection class
        return $items;
    }
}

if (!function_exists('app_path')) {
    /**
     * Obtener ruta de la aplicación
     * 
     * @param string $path
     * @return string
     */
    function app_path($path = '') {
        return __DIR__ . '/../app/' . ltrim($path, '/');
    }
}

if (!function_exists('storage_path')) {
    /**
     * Obtener ruta de storage
     * 
     * @param string $path
     * @return string
     */
    function storage_path($path = '') {
        return __DIR__ . '/../storage/' . ltrim($path, '/');
    }
}

if (!function_exists('public_path')) {
    /**
     * Obtener ruta pública
     * 
     * @param string $path
     * @return string
     */
    function public_path($path = '') {
        return __DIR__ . '/../public/' . ltrim($path, '/');
    }
}

if (!function_exists('view')) {
    /**
     * Renderizar una vista
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    function view($template, $data = []) {
        return \Core\View::render($template, $data);
    }
}

if (!function_exists('json_response')) {
    /**
     * Crear respuesta JSON
     * 
     * @param mixed $data
     * @param int $status
     * @return void
     */
    function json_response($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }
}