<?php

/**
 * Router Script para PHP Built-in Server
 * 
 * Este archivo permite que el servidor PHP built-in maneje correctamente
 * las rutas del framework redirigiendo todo a index.php.
 * 
 * Uso desde carpeta raíz:
 * php -S localhost:8080 -t public/ public/router.php
 * 
 * Uso desde carpeta public:
 * cd public/ && php -S localhost:8080 router.php
 * 
 * URLs disponibles:
 * - http://localhost:8080/     → Página principal (HTML)
 * - http://localhost:8080/api  → API info (JSON)
 * - http://localhost:8080/api/version → Solo versión (JSON)
 * - http://localhost:8080/api/health  → Health check (JSON)
 * 
 * @package NatanPHP
 * @author Natan PHP Framework
 */

// Obtener la URI solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si el archivo existe físicamente (CSS, JS, imágenes), servirlo directamente
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Servir archivo estático
}

// Para todas las demás rutas, usar el framework
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';