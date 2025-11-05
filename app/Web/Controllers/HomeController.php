<?php

namespace NatanPHP\App\Web\Controllers;

/**
 * HomeController Web - Controlador para la página principal del sitio web
 * 
 * Maneja la página de inicio con interfaz visual moderna usando TailwindCSS.
 * Proporciona información del framework, características principales y enlaces
 * a recursos adicionales como la API.
 * 
 * Funcionalidades implementadas:
 * - Página principal con diseño responsive y atractivo
 * - Información del framework y versión actual
 * - Grid de características principales (Router, Request, Helpers)
 * - Enlace directo a explorar la API
 * - Animaciones CSS y gradientes modernos
 * - Indicadores de estado del framework
 * 
 * Diseño y UX:
 * - TailwindCSS con configuración personalizada
 * - Colores de marca (natan-blue, natan-purple, natan-pink)
 * - Efectos de hover y transiciones suaves
 * - Partículas animadas de fondo
 * - Layout responsive para móviles y desktop
 * 
 * @package NatanPHP\App\Web\Controllers
 * @author Natan PHP Framework
 */
class HomeController extends Controller
{
    /**
     * Mostrar la página principal del sitio web
     * 
     * Renderiza la vista de bienvenida con información del framework,
     * versión actual y enlaces disponibles. Genera URLs dinámicamente
     * basadas en la petición actual para garantizar funcionamiento
     * tanto en DDEV como en servidor PHP built-in.
     * 
     * Características de la página:
     * - URLs completamente dinámicas según el servidor actual
     * - Información de versión obtenida de helpers centralizados
     * - Enlaces a API que funcionan en cualquier entorno
     * - Diseño responsive con TailwindCSS
     * - Animaciones y efectos visuales modernos
     * 
     * @return string Vista HTML de la página principal
     */
    public function index(): string
    {
        // Detectar la URL base dinámicamente según la petición actual
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
        $baseUrl = $protocol . '://' . $host;
        
        $data = [
            'title' => 'Bienvenido a NatanPHP Framework',
            'version' => version(), // Versión centralizada desde helpers
            'message' => 'Framework PHP MVC Simple, Moderno e Innovador',
            'baseUrl' => $baseUrl, // URL base dinámica
            'apiUrl' => $baseUrl . '/api', // URL API dinámica
            'versionUrl' => $baseUrl . '/api/version', // URL versión dinámica
            'healthUrl' => $baseUrl . '/api/health', // URL health dinámica
        ];
        
        return $this->view('home/index', $data);
    }
}