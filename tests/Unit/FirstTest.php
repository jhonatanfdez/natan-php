<?php

namespace NatanPHP\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Primer test súper simple para verificar que todo funciona
 * 
 * ¿PARA QUÉ SIRVE ESTE ARCHIVO?
 * - Es el test MÁS BÁSICO posible
 * - Solo verifica que la función version() existe y funciona
 * - Si este test pasa, sabemos que el framework está cargado correctamente
 * 
 * ¿POR QUÉ EMPEZAMOS CON ESTO?
 * - Si este test falla, hay un problema básico con el setup
 * - Si pasa, podemos agregar más tests con confianza
 * - Es nuestro "Hello World" del testing
 */
class FirstTest extends TestCase
{
    /**
     * Test: Verificar que la función version() existe
     * 
     * Este es el test más básico: ¿está cargado el framework?
     */
    public function testVersionFunctionExists(): void
    {
        $this->assertTrue(function_exists('version'), 'La función version() debería existir');
    }
    
    /**
     * Test: Verificar que version() devuelve algo válido
     * 
     * Si la función existe, ¿devuelve lo que esperamos?
     */
    public function testVersionReturnsValidString(): void
    {
        $version = version();
        $this->assertIsString($version, 'version() debería devolver un string');
        $this->assertStringStartsWith('v', $version, 'La versión debería empezar con "v"');
    }
}