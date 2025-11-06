<?php

namespace NatanPHP\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tests para más funciones helpers del framework
 * 
 * ¿PARA QUÉ SIRVE ESTE ARCHIVO?
 * - Testea las funciones helpers más importantes una por una
 * - Mantiene tests simples y específicos
 * - Si un helper falla, sabemos exactamente cuál
 * 
 * ¿POR QUÉ ESTE ARCHIVO AHORA?
 * - Ya sabemos que version() funciona (FirstTest lo confirmó)
 * - Ahora podemos probar otros helpers con confianza
 * - Cada test es independiente y simple
 */
class HelpersTest extends TestCase
{
    /**
     * Test: Verificar que la función env() existe y funciona básicamente
     */
    public function testEnvFunctionExists(): void
    {
        $this->assertTrue(function_exists('env'), 'La función env() debería existir');
    }
    
    /**
     * Test: Verificar que env() devuelve un default cuando la variable no existe
     */
    public function testEnvReturnsDefault(): void
    {
        $result = env('VARIABLE_QUE_NO_EXISTE', 'valor_default');
        $this->assertEquals('valor_default', $result, 'env() debería devolver el valor default');
    }
    
    /**
     * Test: Verificar que la función str_slug() existe
     */
    public function testStrSlugFunctionExists(): void
    {
        $this->assertTrue(function_exists('str_slug'), 'La función str_slug() debería existir');
    }
    
    /**
     * Test: Verificar que str_slug() hace conversión básica
     */
    public function testStrSlugBasicConversion(): void
    {
        $result = str_slug('Hola Mundo');
        $this->assertEquals('hola-mundo', $result, 'str_slug() debería convertir espacios a guiones');
    }
    
    /**
     * Test: Verificar que blank() existe y funciona básicamente
     */
    public function testBlankFunctionWorks(): void
    {
        $this->assertTrue(function_exists('blank'), 'La función blank() debería existir');
        $this->assertTrue(blank(''), 'String vacío debería ser blank');
        $this->assertFalse(blank('texto'), 'String con contenido no debería ser blank');
    }
    
    /**
     * Test: Verificar que filled() existe y es opuesto de blank()
     */
    public function testFilledFunctionWorks(): void
    {
        $this->assertTrue(function_exists('filled'), 'La función filled() debería existir');
        $this->assertFalse(filled(''), 'String vacío no debería estar filled');
        $this->assertTrue(filled('texto'), 'String con contenido debería estar filled');
    }
}