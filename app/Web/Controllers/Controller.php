<?php

namespace NatanPHP\App\Web\Controllers;

/**
 * Controller - Controlador base para toda la aplicación Web
 * 
 * Proporciona funcionalidades comunes para todos los controladores web
 * como manejo de vistas, respuestas HTTP, redirecciones y utilidades
 * para desarrollo de interfaces web tradicionales.
 * 
 * Características principales:
 * - Renderizado de vistas con datos dinámicos
 * - Manejo de respuestas HTTP con códigos de estado
 * - Redirecciones a otras URLs
 * - Extracción automática de variables para vistas
 * - Base para implementar motor de plantillas futuro
 * 
 * Ejemplo de uso:
 * ```php
 * class UsuariosController extends Controller {
 *     public function index() {
 *         $usuarios = ['Juan', 'María'];
 *         return $this->view('usuarios/index', compact('usuarios'));
 *     }
 * }
 * ```
 * 
 * @package NatanPHP\App\Web\Controllers
 * @author Natan PHP Framework
 */
abstract class Controller
{
    /**
     * Renderizar una vista
     * 
     * Por ahora retorna contenido simple, se expandirá cuando tengamos
     * el motor de plantillas en futuras versiones.
     * 
     * @param string $view Nombre de la vista
     * @param array $data Datos a pasar a la vista
     * @return string Contenido renderizado
     */
    protected function view(string $view, array $data = []): string
    {
        // Extraer variables para la vista
        extract($data);
        
        // Por ahora incluimos archivos PHP directamente
        // TODO: Implementar motor de plantillas en v0.2.0
        $viewPath = __DIR__ . "/../../Web/Views/{$view}.php";
        
        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            return ob_get_clean();
        }
        
        return "Vista no encontrada: {$view}";
    }
    
    /**
     * Crear una respuesta HTTP
     * 
     * @param string $content Contenido de la respuesta
     * @param int $status Código de estado HTTP
     * @return void
     */
    protected function response(string $content, int $status = 200): void
    {
        http_response_code($status);
        echo $content;
    }
    
    /**
     * Redirección HTTP
     * 
     * @param string $url URL de destino
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}