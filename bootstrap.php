<?php

/**
 * Bootstrap - Punto de entrada principal del framework NatanPHP
 * 
 * Este archivo inicializa completamente el framework y maneja el ciclo
 * de vida de las peticiones HTTP. Es el corazón del sistema que conecta
 * todas las piezas: autoloading, rutas, controladores y respuestas.
 * 
 * Proceso de inicialización:
 * 1. Carga autoloader de Composer (PSR-4 + helpers)
 * 2. Configura variables de entorno (.env)
 * 3. Crea instancia de Request para analizar petición HTTP
 * 4. Carga rutas Web y API desde archivos separados
 * 5. Resuelve ruta actual usando Router dinámico
 * 6. Ejecuta controlador correspondiente con inyección de parámetros
 * 7. Retorna respuesta (HTML para Web, JSON para API)
 * 8. Maneja errores 404 con respuestas apropiadas según contexto
 * 
 * Características del manejo de errores:
 * - Detección automática Web vs API
 * - Respuestas 404 en HTML con TailwindCSS para Web
 * - Respuestas 404 en JSON estructurado para API
 * - Headers HTTP apropiados según tipo de contenido
 * 
 * @package NatanPHP
 * @author Natan PHP Framework
 */

// Autoloading de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Los helpers ya se cargan automáticamente por Composer

// Importar clases principales
use NatanPHP\Core\Router;
use NatanPHP\Core\Request;

// Crear instancia de Request
$request = new Request();

try {
    // Cargar rutas web
    require_once __DIR__ . '/routes/web.php';
    
    // Cargar rutas API
    require_once __DIR__ . '/routes/api.php';
    
    // Resolver la ruta actual y ejecutar el controlador
    $response = Router::resolve($request);
    
    // Si el controlador retorna contenido, mostrarlo
    if ($response) {
        echo $response;
    }
    
} catch (Exception $e) {
    // Manejo básico de errores
    // TODO: Implementar sistema robusto de manejo de errores en v0.2.0
    
    http_response_code(404);
    
    if ($request->wantsJson()) {
        // Respuesta JSON para APIs
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Ruta no encontrada',
            'message' => $e->getMessage(),
            'status' => 404
        ], JSON_PRETTY_PRINT);
    } else {
        // Respuesta HTML para web
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - NatanPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-400 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Página no encontrada</h2>
        <p class="text-gray-600 mb-6">' . htmlspecialchars($e->getMessage()) . '</p>
        <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg">
            Volver al inicio
        </a>
    </div>
</body>
</html>';
    }
}