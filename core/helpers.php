<?php

/**
 * NatanPHP Framework - Helpers Esenciales
 * 
 * Funciones auxiliares básicas disponibles en toda la aplicación.
 * Solo incluye las funciones más prioritarias para el desarrollo inicial.
 * Se irán agregando más funciones según se necesiten.
 * 
 * @package NatanPHP\Core
 * @version 0.1.2
 * @author Jhonatan Fernández
 */

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
     * Obtiene un valor de configuración de la aplicación
     * 
     * Sistema básico para acceder a configuración usando notación de punto.
     * Por ahora es simple, se expandirá cuando tengamos archivos de config.
     * 
     * @param string $key Clave de configuración en notación punto (ej: 'app.name')
     * @param mixed $default Valor por defecto si no existe
     * @return mixed El valor de configuración o el default
     * 
     * @example config('app.name', 'NatanPHP'); // Obtiene nombre de la app
     */
    function config($key, $default = null) {
        // Por ahora retornamos valores básicos hardcodeados
        // TODO: Implementar sistema completo de configuración en v0.2.0
        $configs = [
            'app.name' => env('APP_NAME', 'NatanPHP Framework'),
            'app.env' => env('APP_ENV', 'local'),
            'app.debug' => env('APP_DEBUG', true),
            'app.url' => env('APP_URL', 'http://localhost:8000'),
        ];
        
        return $configs[$key] ?? $default;
    }
}

// =============================================================================
// URLs - Generación de URLs y rutas de assets
// =============================================================================

if (!function_exists('url')) {
    /**
     * Genera una URL absoluta para la aplicación
     * 
     * Construye URLs completas basadas en la URL base de la aplicación.
     * Esencial para enlaces y redirecciones.
     * 
     * @param string $path Ruta relativa (ej: '/productos', '/login')
     * @return string URL absoluta completa
     * 
     * @example url('/productos'); // http://localhost:8000/productos
     */
    function url($path = '') {
        $baseUrl = rtrim(config('app.url'), '/');
        $path = ltrim($path, '/');
        
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

if (!function_exists('asset')) {
    /**
     * Genera URL para archivos estáticos (CSS, JS, imágenes)
     * 
     * Crea URLs para recursos en la carpeta public/assets.
     * Fundamental para vincular estilos, scripts e imágenes.
     * 
     * @param string $path Ruta del asset relativa a public/assets/
     * @return string URL completa del asset
     * 
     * @example asset('css/app.css'); // http://localhost:8000/assets/css/app.css
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