<?php

/**
 * Helpers del Framework NatanPHP
 * Funciones utilitarias esenciales para el funcionamiento del framework
 * 
 * @package NatanPHP\Core
 * @version 0.1.3
 * @author Natan PHP Framework
 */

/**
 * Obtener la versión actual del framework
 * 
 * @return string Versión del framework
 */
    function version() {
        return 'v0.1.4';
    }

// =============================================================================
// DEBUGGING - Funciones para depuración durante desarrollo
// =============================================================================

if (!function_exists('dd')) {
    /**
     * Dump and Die - Muestra información de variables y termina la ejecución
     * 
     * Función esencial para debugging durante desarrollo. Muestra el contenido
     * de una o más variables de forma legible y termina el script.
     * 
     * @param mixed ...$vars Una o más variables a mostrar
     * @return void Termina la ejecución del script
     * 
     * @example dd($usuario, $productos); // Muestra ambas variables y termina
     */
    function dd(...$vars) {
        echo "<style>
            .dd-container { background: #1a1a1a; color: #e6e6e6; padding: 20px; margin: 10px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 14px; }
            .dd-title { color: #ff6b6b; font-weight: bold; margin-bottom: 10px; }
        </style>";
        
        echo "<div class='dd-container'>";
        echo "<div class='dd-title'>🐛 NatanPHP Debug Output</div>";
        
        foreach ($vars as $index => $var) {
            echo "<h4 style='color: #4ecdc4;'>Variable #" . ($index + 1) . ":</h4>";
            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        }
        
        echo "</div>";
        exit(1);
    }
}

// =============================================================================
// CONFIGURACIÓN - Acceso a variables de entorno y configuración
// =============================================================================

if (!function_exists('env')) {
    /**
     * Obtiene el valor de una variable de entorno
     * 
     * Accede a variables definidas en .env o en el sistema.
     * Fundamental para configuración de base de datos, URLs, etc.
     * 
     * @param string $key Nombre de la variable de entorno
     * @param mixed $default Valor por defecto si la variable no existe
     * @return mixed El valor de la variable o el default
     * 
     * @example env('DB_HOST', 'localhost'); // Retorna valor de DB_HOST o 'localhost'
     */
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Convertir strings especiales a tipos apropiados
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
        
        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Accede a valores de configuración del framework
     * 
     * Proporciona acceso centralizado a configuraciones del sistema
     * mediante notación de puntos. Esencial para gestión modular
     * de parámetros de aplicación, base de datos y entorno.
     * 
     * Características:
     * - Sintaxis intuitiva con notación de puntos
     * - Valores por defecto configurables
     * - Preparado para sistema de configuración robusto
     * - Compatible con arrays multidimensionales
     * 
     * Casos de uso típicos:
     * - Configuración de base de datos
     * - Variables de entorno de desarrollo
     * - Parámetros de aplicación globales
     * - Configuraciones por módulo
     * 
     * @param string $key Clave de configuración usando notación de puntos (ej: 'database.host')
     * @param mixed $default Valor por defecto si la clave no existe
     * @return mixed Valor de configuración encontrado o valor por defecto
     * 
     * @example config('app.name', 'NatanPHP'); // 'NatanPHP Framework'
     * @example config('database.host', 'localhost'); // Configuración de BD
     * 
     * @note Implementación simplificada, versiones futuras incluirán archivos de configuración
     */
    function config($key, $default = null) {
        // Implementación simplificada
        // En versiones futuras se integrará sistema completo de configuración
        return $default;
    }
}

if (!function_exists('route')) {
    /**
     * Genera URL para rutas nombradas del sistema
     * 
     * Construye URLs dinámicas para rutas definidas en el framework.
     * Esencial para navegación consistente entre controladores y vistas,
     * con soporte completo para múltiples entornos de desarrollo.
     * 
     * Características:
     * - URLs completamente dinámicas según servidor
     * - Compatible con DDEV y PHP built-in server
     * - Detección automática de protocolo y host
     * - Soporte para parámetros de ruta opcionales
     * 
     * Ejemplos según entorno:
     * - DDEV: route('home') → https://natanphp-framework.ddev.site/
     * - PHP built-in: route('api') → http://localhost:8080/api
     * 
     * @param string $name Nombre de la ruta registrada en routes/ (ej: 'home', 'api.version')
     * @param array $params Parámetros opcionales para rutas dinámicas
     * @return string URL completa de la ruta detectada dinámicamente
     * 
     * @example route('home'); // https://natanphp-framework.ddev.site/
     * @example route('api.users', ['id' => 123]); // http://localhost:8080/api/users/123
     * 
     * @note Actualmente simplificado, versiones futuras incluirán named routes
     */
    function route($name, $params = []) {
        // Por ahora, implementación simplificada
        // En futuras versiones se integrarán named routes
        return url($name);
    }
}

// =============================================================================
// URLs - Generación de URLs y rutas de assets
// =============================================================================

if (!function_exists('url')) {
    /**
     * Genera una URL absoluta para la aplicación
     * 
     * Construye URLs completas basadas en la detección automática del servidor
     * actual. Funciona tanto en DDEV como en servidor PHP built-in, detectando
     * automáticamente el protocolo y host correctos.
     * 
     * Detección automática:
     * - Protocolo: HTTP o HTTPS según $_SERVER['HTTPS']
     * - Host: $_SERVER['HTTP_HOST'] con fallback a localhost:8080
     * - Puerto: Incluido automáticamente en HTTP_HOST
     * 
     * Ejemplos según entorno:
     * - DDEV: url('/api') → https://natanphp-framework.ddev.site/api
     * - PHP built-in: url('/api') → http://localhost:8080/api
     * - Servidor personalizado: url('/api') → http://example.com:3000/api
     * 
     * @param string $path Ruta relativa (ej: '/productos', '/api/users')
     * @return string URL absoluta completa detectada dinámicamente
     * 
     * @example url('/productos'); // https://natanphp-framework.ddev.site/productos
     * @example url('/api/users'); // http://localhost:8080/api/users
     */
    function url($path = '') {
        // Detectar protocolo automáticamente
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        
        // Detectar host con fallback para línea de comandos
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
        
        // Construir URL base dinámica
        $baseUrl = $protocol . '://' . $host;
        
        // Limpiar y agregar path
        $path = ltrim($path, '/');
        
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

if (!function_exists('asset')) {
    /**
     * Genera URL para archivos estáticos (CSS, JS, imágenes)
     * 
     * Crea URLs dinámicas para recursos en la carpeta public/assets
     * utilizando detección automática del servidor actual. Fundamental
     * para vincular estilos, scripts e imágenes desde cualquier entorno.
     * 
     * Características:
     * - URLs completamente dinámicas según servidor
     * - Compatible con DDEV y PHP built-in server
     * - Detección automática de protocolo y host
     * - Fallback seguro para desarrollo local
     * 
     * Ejemplos según entorno:
     * - DDEV: asset('css/app.css') → https://natanphp-framework.ddev.site/assets/css/app.css
     * - PHP built-in: asset('js/app.js') → http://localhost:8080/assets/js/app.js
     * 
     * @param string $path Ruta del asset relativa a public/assets/ (ej: 'css/app.css')
     * @return string URL completa del asset detectada dinámicamente
     * 
     * @example asset('css/app.css'); // https://natanphp-framework.ddev.site/assets/css/app.css
     * @example asset('js/framework.js'); // http://localhost:8080/assets/js/framework.js
     */
    function asset($path) {
        $path = ltrim($path, '/');
        return url('assets/' . $path);
    }
}

// =============================================================================
// STRINGS - Utilidades básicas para manipulación de texto
// =============================================================================

if (!function_exists('str_slug')) {
    /**
     * Convierte un string en un slug amigable para URLs
     * 
     * Transforma texto con espacios y caracteres especiales en formato
     * adecuado para URLs (minúsculas, sin espacios, sin acentos).
     * 
     * @param string $string Texto a convertir
     * @param string $separator Separador a usar (por defecto guión)
     * @return string Slug generado
     * 
     * @example str_slug('Mi Artículo Genial'); // 'mi-articulo-genial'
     */
    function str_slug($string, $separator = '-') {
        // Convertir a minúsculas
        $string = strtolower($string);
        
        // Reemplazar caracteres especiales
        $string = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'u'],
            $string
        );
        
        // Remover caracteres no alfanuméricos
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        
        // Reemplazar espacios múltiples con el separador
        $string = preg_replace('/\s+/', $separator, $string);
        
        // Limpiar separadores al inicio y final
        return trim($string, $separator);
    }
}

// =============================================================================
// UTILIDADES - Funciones de uso general
// =============================================================================

if (!function_exists('blank')) {
    /**
     * Determina si un valor está "vacío" según criterios de Laravel
     * 
     * Considera vacío: null, string vacío, array vacío, espacios en blanco.
     * Útil para validaciones y condiciones.
     * 
     * @param mixed $value Valor a evaluar
     * @return bool true si está vacío, false si tiene contenido
     * 
     * @example blank('  '); // true
     * @example blank('texto'); // false
     */
    function blank($value) {
        if (is_null($value)) {
            return true;
        }
        
        if (is_string($value)) {
            return trim($value) === '';
        }
        
        if (is_array($value)) {
            return empty($value);
        }
        
        return empty($value);
    }
}

if (!function_exists('filled')) {
    /**
     * Determina si un valor tiene contenido (opuesto de blank)
     * 
     * @param mixed $value Valor a evaluar
     * @return bool true si tiene contenido, false si está vacío
     * 
     * @example filled('texto'); // true
     * @example filled(''); // false
     */
    function filled($value) {
        return !blank($value);
    }
}