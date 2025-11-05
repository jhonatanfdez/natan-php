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
     * versión actual y enlaces disponibles.
     * 
     * @return string Vista HTML de la página principal
     */
    public function index(): string
    {
        $data = [
            'title' => 'Bienvenido a NatanPHP Framework',
            'version' => version(),
            'message' => 'Framework PHP MVC Simple, Moderno e Innovador',
            'apiUrl' => url('/api'),
        ];
        
        return $this->view('home/index', $data);
    }
}