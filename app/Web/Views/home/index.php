<!--
    Vista Principal - NatanPHP Framework
    
    Página de bienvenida con diseño moderno y responsivo usando TailwindCSS.
    Muestra información del framework, características principales y enlaces.
    
    Características del diseño:
    - Gradiente de fondo dinámico (gris-azul-púrpura)
    - Header con logo y versión actual
    - Hero section con tipografía atractiva
    - Grid de características con iconos SVG
    - Sección destacada para enlace API
    - Footer informativo
    - Partículas animadas de fondo
    - Colores de marca personalizados
    - Efectos hover y transiciones suaves
    
    Variables disponibles (pasadas desde HomeController):
    - $title: Título de la página
    - $version: Versión actual del framework (desde function version())
    - $message: Mensaje descriptivo del framework
    - $baseUrl: URL base dinámica (detectada automáticamente)
    - $apiUrl: URL completa del endpoint API principal
    - $versionUrl: URL del endpoint de versión (/api/version)
    - $healthUrl: URL del endpoint de health check (/api/health)
    
    Características del sistema de URLs:
    - URLs completamente dinámicas según servidor actual
    - Funciona tanto en DDEV (https://natanphp-framework.ddev.site)
    - Como en servidor PHP built-in (http://localhost:8080)
    - Detección automática de protocolo HTTP/HTTPS
    - Host detection para generar enlaces correctos
    
    @package NatanPHP\App\Web\Views
    @author Natan PHP Framework
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'natan-blue': '#1e40af',
                        'natan-purple': '#7c3aed',
                        'natan-pink': '#ec4899',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 min-h-screen">
    <!-- Header -->
    <header class="bg-black/20 backdrop-blur-sm border-b border-white/10">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-natan-blue to-natan-purple rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">N</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">NatanPHP</h1>
                </div>
                <div class="bg-natan-purple/20 px-3 py-1 rounded-full border border-natan-purple/30">
                    <span class="text-purple-300 text-sm font-medium">v<?= $version ?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="container mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-bold text-white mb-4">
                Bienvenido a 
                <span class="bg-gradient-to-r from-natan-blue via-natan-purple to-natan-pink bg-clip-text text-transparent">
                    NatanPHP
                </span>
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                <?= $message ?>
            </p>
            <div class="inline-flex items-center bg-green-500/20 border border-green-500/30 px-4 py-2 rounded-lg">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                <span class="text-green-300 font-medium">Framework Funcionando</span>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Feature 1: Router -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 hover:bg-white/10 transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Router Dinámico</h3>
                <p class="text-gray-400 text-sm">Sistema de rutas con parámetros dinámicos, middleware y resolución automática Web/API.</p>
            </div>

            <!-- Feature 2: Request -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 hover:bg-white/10 transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Request HTTP</h3>
                <p class="text-gray-400 text-sm">Manejo completo de peticiones con 20+ métodos, archivos, headers y detección AJAX.</p>
            </div>

            <!-- Feature 3: Helpers -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 hover:bg-white/10 transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Helpers Útiles</h3>
                <p class="text-gray-400 text-sm">9 funciones esenciales para debugging, configuración, URLs y utilidades de strings.</p>
            </div>
        </div>

        <!-- API Section -->
        <div class="bg-gradient-to-r from-natan-purple/20 to-natan-pink/20 border border-purple-500/30 rounded-xl p-8 text-center">
            <h3 class="text-2xl font-bold text-white mb-4">🚀 Explora la API</h3>
            <p class="text-gray-300 mb-6">
                Accede a la información del framework en formato JSON para integraciones y desarrollo de APIs.
            </p>
            
            <!-- Botones de API -->
            <div class="flex flex-wrap justify-center gap-4 mb-6">
                <a href="<?= $apiUrl ?>" 
                   target="_blank"
                   class="inline-flex items-center bg-gradient-to-r from-natan-purple to-natan-pink hover:from-natan-pink hover:to-natan-purple text-white font-semibold px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info General
                </a>
                
                <a href="<?= $versionUrl ?>" 
                   target="_blank"
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-300 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Versión
                </a>
                
                <a href="<?= $healthUrl ?>" 
                   target="_blank"
                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-300 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Health Check
                </a>
            </div>
            
            <p class="text-gray-400 text-sm">
                Endpoints RESTful con respuestas JSON estructuradas
            </p>
        </div>

        <!-- Version & Status -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center bg-black/20 border border-white/10 px-4 py-2 rounded-lg">
                <span class="text-gray-400 text-sm">Versión actual: </span>
                <span class="text-white font-semibold ml-2">v<?= $version ?></span>
                <div class="w-2 h-2 bg-green-400 rounded-full ml-3"></div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/10 mt-12">
        <div class="container mx-auto px-6 py-6 text-center">
            <p class="text-gray-400 text-sm">
                NatanPHP Framework - Hecho con ❤️ para la comunidad educativa
            </p>
        </div>
    </footer>

    <!-- Floating Particles Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-1 h-1 bg-purple-400 rounded-full opacity-30 animate-bounce"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1.5 h-1.5 bg-pink-400 rounded-full opacity-25 animate-ping"></div>
    </div>
</body>
</html>