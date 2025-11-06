<?php

/**
 * Bootstrap mínimo para Testing en NatanPHP Framework
 * 
 * ¿PARA QUÉ SIRVE ESTE ARCHIVO?
 * - Es lo ÚNICO que necesita PHPUnit para funcionar
 * - Carga el autoloader para que las funciones helper estén disponibles
 * - Configura lo mínimo necesario para testing
 * 
 * ¿POR QUÉ LO NECESITAMOS?
 * - Sin este archivo, los tests no pueden usar version(), env(), etc.
 * - PHPUnit lo ejecuta ANTES de cualquier test
 * - Es el "puente" entre PHPUnit y nuestro framework
 */

// Cargar autoloader de Composer (esto carga automáticamente core/helpers.php)
require_once __DIR__ . '/../vendor/autoload.php';

// Y eso es todo! Lo mínimo para que funcione.