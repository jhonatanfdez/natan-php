<?php

namespace NatanPHP\App\Api\Controllers;

/**
 * HomeController API - Controlador para endpoints principales de la API REST
 * 
 * Proporciona información básica del framework a través de endpoints JSON
 * para integraciones, monitoreo y desarrollo de aplicaciones que consuman la API.
 * 
 * Endpoints disponibles:
 * - GET /api           → Información completa del framework
 * - GET /api/version   → Solo información de versión
 * - GET /api/health    → Health check del sistema
 * 
 * Todos los endpoints retornan JSON con estructura consistente y headers apropiados.
 * Ideal para:
 * - Monitoreo de servicios
 * - Integraciones con otras aplicaciones
 * - Dashboards de administración
 * - Testing automatizado
 * 
 * Ejemplo de respuesta:
 * ```json
 * {
 *   "success": true,
 *   "data": {...},
 *   "message": "Información obtenida correctamente",
 *   "timestamp": "2025-11-04 21:30:00"
 * }
 * ```
 * 
 * @package NatanPHP\App\Api\Controllers
 * @author Natan PHP Framework
 */
class HomeController extends ApiController
{
    /**
     * Información general del framework (GET /api)
     * 
     * Endpoint principal de la API que proporciona información completa
     * sobre el framework, sus características y endpoints disponibles.
     * 
     * @return void Respuesta JSON con información del framework
     */
    public function index(): void
    {
        $data = [
            'framework' => 'NatanPHP',
            'version' => version(),
            'description' => 'Framework PHP MVC Simple, Moderno e Innovador',
            'status' => 'active',
            'environment' => config('app.env', 'production'),
            'features' => [
                'Router dinámico con parámetros {id}, {slug}',
                'Sistema de peticiones HTTP completo (20+ métodos)',
                'Helpers utilitarios (9 funciones esenciales)',
                'Separación clara Web/API controllers',
                'Autoloading PSR-4 configurado',
                'Arquitectura educativa y documentada',
                'Gestión centralizada de versiones',
                'Patrón Fluent Interface para rutas',
                'Inyección automática de parámetros',
                'Manejo robusto de errores'
            ],
            'endpoints' => [
                'home_web' => url('/'),
                'api_info' => url('/api'),
                'framework_version' => url('/api/version'),
                'health_check' => url('/api/health'),
            ],
            'documentation' => [
                'github' => 'https://github.com/jhonatanfdez/natan-php',
                'readme' => 'https://github.com/jhonatanfdez/natan-php#readme',
                'changelog' => 'https://github.com/jhonatanfdez/natan-php/blob/main/CHANGELOG.md'
            ]
        ];
        
        $this->successResponse($data, 'Información del framework NatanPHP');
    }
    
    /**
     * Versión del framework (GET /api/version)
     * 
     * Endpoint específico para obtener únicamente la versión actual
     * del framework. Útil para verificaciones automáticas.
     * 
     * @return void Respuesta JSON con versión
     */
    public function version(): void
    {
        $data = [
            'framework' => 'NatanPHP',
            'version' => version(),
            'release_date' => '2025-11-04',
            'is_stable' => true,
            'major' => 0,
            'minor' => 1,
            'patch' => 3,
            'stage' => 'development'
        ];
        
        $this->successResponse($data, 'Versión actual del framework');
    }
    
    /**
     * Estado de salud del framework (GET /api/health)
     * 
     * Endpoint para verificar que el framework está funcionando
     * correctamente. Útil para monitoreo y health checks.
     * 
     * @return void Respuesta JSON con estado de salud
     */
    public function health(): void
    {
        $data = [
            'status' => 'healthy',
            'framework' => 'NatanPHP',
            'version' => version(),
            'uptime' => $this->getUptime(),
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'php_version' => PHP_VERSION,
            'checks' => [
                'autoloader' => true,
                'helpers' => function_exists('version'),
                'router' => class_exists('NatanPHP\\Core\\Router'),
                'request' => class_exists('NatanPHP\\Core\\Request'),
                'environment' => !empty(config('app.name')),
            ]
        ];
        
        $this->successResponse($data, 'Framework funcionando correctamente');
    }
    
    /**
     * Obtener tiempo de actividad aproximado
     * 
     * @return string Tiempo de actividad formateado
     */
    private function getUptime(): string
    {
        // Tiempo aproximado desde que se cargó el script
        return '< 1 segundo';
    }
    
    /**
     * Formatear bytes a formato legible
     * 
     * @param int $bytes Cantidad de bytes
     * @return string Bytes formateados
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }
}