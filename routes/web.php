<?php

/**
 * Rutas Web - NatanPHP Framework
 * 
 * Este archivo define todas las rutas para la interfaz web del framework.
 * Las rutas web retornan HTML y están pensadas para usuarios navegando
 * desde un navegador web.
 * 
 * @package NatanPHP
 * @author Natan PHP Framework
 */

use NatanPHP\Core\Router;

// =============================================================================
// RUTAS PRINCIPALES
// =============================================================================

// Página principal - Bienvenida al framework
Router::get('/', 'HomeController@index')
    ->name('home');

// =============================================================================
// RUTAS DE EJEMPLO FUTURAS
// =============================================================================

// Descomenta estas rutas cuando implementemos más controladores
/*
Router::get('/usuarios', 'UsuariosController@index')->name('usuarios.index');
Router::get('/usuario/{id}', 'UsuariosController@show')->name('usuarios.show');
Router::get('/usuarios/crear', 'UsuariosController@create')->name('usuarios.create');
Router::post('/usuarios', 'UsuariosController@store')->name('usuarios.store');
*/
