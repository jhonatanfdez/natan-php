<?php

/**
 * Rutas API - NatanPHP Framework
 * 
 * Este archivo define todas las rutas para la API REST del framework.
 * Las rutas API retornan JSON y están pensadas para consumo programático
 * desde aplicaciones, JavaScript, móviles, etc.
 * 
 * @package NatanPHP
 * @author Natan PHP Framework
 */

use NatanPHP\Core\Router;

// =============================================================================
// RUTAS API PRINCIPALES
// =============================================================================

// Información general del framework
Router::get('/api', 'HomeController@index')
    ->name('api.home');

// Versión específica del framework
Router::get('/api/version', 'HomeController@version')
    ->name('api.version');

// Health check del framework
Router::get('/api/health', 'HomeController@health')
    ->name('api.health');

// =============================================================================
// RUTAS API DE EJEMPLO FUTURAS
// =============================================================================

// Descomenta estas rutas cuando implementemos más controladores API
/*
Router::group(['prefix' => 'api'], function() {
    // CRUD de usuarios en JSON
    Router::get('/usuarios', 'UsuariosController@index')->name('api.usuarios.index');
    Router::get('/usuario/{id}', 'UsuariosController@show')->name('api.usuarios.show');
    Router::post('/usuarios', 'UsuariosController@store')->name('api.usuarios.store');
    Router::put('/usuario/{id}', 'UsuariosController@update')->name('api.usuarios.update');
    Router::delete('/usuario/{id}', 'UsuariosController@destroy')->name('api.usuarios.destroy');
});
*/
