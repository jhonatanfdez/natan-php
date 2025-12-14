<?php

namespace NatanPHP\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tests Adicionales Exhaustivos para Helpers Ya Testeados Parcialmente
 * 
 * ¿POR QUÉ ESTE ARCHIVO?
 * - FirstTest.php y HelpersTest.php solo tienen tests básicos
 * - Necesitamos cubrir TODOS los casos posibles de cada función
 * - Edge cases, casos extremos, y comportamientos especiales
 * 
 * FUNCIONES EXPANDIDAS AQUÍ:
 * 1. env() - Conversiones de tipos, valores especiales
 * 2. str_slug() - Acentos, caracteres especiales, separadores
 * 3. blank() - Null, arrays, espacios, valores falsy
 * 4. filled() - Complemento de todos los casos de blank()
 * 
 * @package NatanPHP\Tests\Unit
 */
class HelpersExpandedTest extends TestCase
{
    // ==========================================================================
    // TESTS ADICIONALES PARA env() - Variables de Entorno
    // ==========================================================================
    
    /**
     * Test: env() convierte 'true' string a boolean true
     * 
     * EXPLICACIÓN: Las variables de entorno siempre son strings
     * pero env() debe convertir 'true' a boolean true para facilitar uso
     */
    public function testEnvConvertsTrueStringToBoolean(): void
    {
        // Simular variable de entorno con valor 'true'
        $_ENV['TEST_TRUE'] = 'true';
        
        $result = env('TEST_TRUE');
        
        $this->assertIsBool($result, 'env() debería convertir "true" a boolean');
        $this->assertTrue($result, 'env() debería retornar true boolean');
        
        // Limpiar
        unset($_ENV['TEST_TRUE']);
    }
    
    /**
     * Test: env() convierte 'false' string a boolean false
     */
    public function testEnvConvertsFalseStringToBoolean(): void
    {
        $_ENV['TEST_FALSE'] = 'false';
        
        $result = env('TEST_FALSE');
        
        $this->assertIsBool($result, 'env() debería convertir "false" a boolean');
        $this->assertFalse($result, 'env() debería retornar false boolean');
        
        unset($_ENV['TEST_FALSE']);
    }
    
    /**
     * Test: env() convierte '(true)' a boolean true
     * 
     * NOTA: Algunos archivos .env usan paréntesis para valores especiales
     */
    public function testEnvConvertsParenthesisTrueToBoolean(): void
    {
        $_ENV['TEST_TRUE_PARENS'] = '(true)';
        
        $result = env('TEST_TRUE_PARENS');
        
        $this->assertTrue($result, 'env() debería convertir "(true)" a true');
        
        unset($_ENV['TEST_TRUE_PARENS']);
    }
    
    /**
     * Test: env() convierte '(false)' a boolean false
     */
    public function testEnvConvertsParenthesisFalseToBoolean(): void
    {
        $_ENV['TEST_FALSE_PARENS'] = '(false)';
        
        $result = env('TEST_FALSE_PARENS');
        
        $this->assertFalse($result, 'env() debería convertir "(false)" a false');
        
        unset($_ENV['TEST_FALSE_PARENS']);
    }
    
    /**
     * Test: env() convierte 'null' string a null real
     * 
     * EXPLICACIÓN: A veces necesitamos null explícito en configuración
     */
    public function testEnvConvertsNullStringToNull(): void
    {
        $_ENV['TEST_NULL'] = 'null';
        
        $result = env('TEST_NULL');
        
        $this->assertNull($result, 'env() debería convertir "null" a null real');
        
        unset($_ENV['TEST_NULL']);
    }
    
    /**
     * Test: env() convierte '(null)' a null real
     */
    public function testEnvConvertsParenthesisNullToNull(): void
    {
        $_ENV['TEST_NULL_PARENS'] = '(null)';
        
        $result = env('TEST_NULL_PARENS');
        
        $this->assertNull($result, 'env() debería convertir "(null)" a null');
        
        unset($_ENV['TEST_NULL_PARENS']);
    }
    
    /**
     * Test: env() convierte 'empty' a string vacío
     */
    public function testEnvConvertsEmptyToEmptyString(): void
    {
        $_ENV['TEST_EMPTY'] = 'empty';
        
        $result = env('TEST_EMPTY');
        
        $this->assertSame('', $result, 'env() debería convertir "empty" a string vacío');
        
        unset($_ENV['TEST_EMPTY']);
    }
    
    /**
     * Test: env() convierte '(empty)' a string vacío
     */
    public function testEnvConvertsParenthesisEmptyToEmptyString(): void
    {
        $_ENV['TEST_EMPTY_PARENS'] = '(empty)';
        
        $result = env('TEST_EMPTY_PARENS');
        
        $this->assertSame('', $result, 'env() debería convertir "(empty)" a string vacío');
        
        unset($_ENV['TEST_EMPTY_PARENS']);
    }
    
    /**
     * Test: env() retorna valor real de variable de entorno
     * 
     * EXPLICACIÓN: Cuando el valor no es un keyword especial,
     * debe retornarse tal cual
     */
    public function testEnvReturnsActualEnvironmentValue(): void
    {
        $_ENV['TEST_REAL_VALUE'] = 'production';
        
        $result = env('TEST_REAL_VALUE');
        
        $this->assertEquals('production', $result, 'env() debería retornar el valor real');
        
        unset($_ENV['TEST_REAL_VALUE']);
    }
    
    /**
     * Test: env() es case-insensitive para conversiones
     * 
     * EXPLICACIÓN: TRUE, True, true deberían convertirse igual
     */
    public function testEnvIsCaseInsensitiveForConversions(): void
    {
        $_ENV['TEST_TRUE_UPPER'] = 'TRUE';
        $_ENV['TEST_FALSE_MIXED'] = 'False';
        
        $resultTrue = env('TEST_TRUE_UPPER');
        $resultFalse = env('TEST_FALSE_MIXED');
        
        $this->assertTrue($resultTrue, 'env() debería convertir "TRUE" a true');
        $this->assertFalse($resultFalse, 'env() debería convertir "False" a false');
        
        unset($_ENV['TEST_TRUE_UPPER'], $_ENV['TEST_FALSE_MIXED']);
    }
    
    // ==========================================================================
    // TESTS ADICIONALES PARA str_slug() - Conversión a Slug
    // ==========================================================================
    
    /**
     * Test: str_slug() maneja acentos españoles correctamente
     * 
     * EXPLICACIÓN: Los acentos deben convertirse a sus equivalentes sin acento
     */
    public function testStrSlugHandlesSpanishAccents(): void
    {
        $result = str_slug('Artículo con ñ y ü');
        
        $this->assertEquals(
            'articulo-con-n-y-u',
            $result,
            'str_slug() debería convertir acentos españoles correctamente'
        );
    }
    
    /**
     * Test: str_slug() elimina caracteres especiales
     * 
     * EXPLICACIÓN: Símbolos como @#$%^& no deberían aparecer en slugs
     */
    public function testStrSlugRemovesSpecialCharacters(): void
    {
        $result = str_slug('Test @#$ Special %^& Characters!');
        
        // No debería contener caracteres especiales
        $this->assertDoesNotMatchRegularExpression(
            '/[@#$%^&!]/',
            $result,
            'str_slug() debería eliminar caracteres especiales'
        );
    }
    
    /**
     * Test: str_slug() usa separador personalizado
     * 
     * EXPLICACIÓN: A veces necesitamos underscore _ en lugar de guión -
     */
    public function testStrSlugUsesCustomSeparator(): void
    {
        $result = str_slug('Hola Mundo', '_');
        
        $this->assertEquals(
            'hola_mundo',
            $result,
            'str_slug() debería usar separador personalizado'
        );
    }
    
    /**
     * Test: str_slug() maneja espacios múltiples
     * 
     * EXPLICACIÓN: "Hola    Mundo" (espacios múltiples) debe ser "hola-mundo"
     */
    public function testStrSlugHandlesMultipleSpaces(): void
    {
        $result = str_slug('Hola    Mundo    Test');
        
        // No debería tener guiones múltiples
        $this->assertDoesNotMatchRegularExpression(
            '/--+/',
            $result,
            'str_slug() no debería generar separadores múltiples'
        );
        $this->assertEquals('hola-mundo-test', $result);
    }
    
    /**
     * Test: str_slug() limpia separadores al inicio y final
     */
    public function testStrSlugTrimsLeadingAndTrailingSeparators(): void
    {
        $result = str_slug('  Hola Mundo  ');
        
        // No debería empezar ni terminar con guión
        $this->assertStringStartsNotWith('-', $result);
        $this->assertStringEndsNotWith('-', $result);
    }
    
    /**
     * Test: str_slug() convierte todo a minúsculas
     */
    public function testStrSlugConvertsToLowercase(): void
    {
        $result = str_slug('MAYÚSCULAS Y minúsculas');
        
        $this->assertEquals(
            strtolower($result),
            $result,
            'str_slug() debería convertir todo a minúsculas'
        );
    }
    
    /**
     * Test: str_slug() maneja string vacío
     */
    public function testStrSlugHandlesEmptyString(): void
    {
        $result = str_slug('');
        
        $this->assertEquals('', $result, 'str_slug() de string vacío debería ser vacío');
    }
    
    /**
     * Test: str_slug() maneja solo caracteres especiales
     */
    public function testStrSlugHandlesOnlySpecialCharacters(): void
    {
        $result = str_slug('@#$%^&*()');
        
        $this->assertEquals(
            '',
            $result,
            'str_slug() de solo caracteres especiales debería ser vacío'
        );
    }
    
    // ==========================================================================
    // TESTS ADICIONALES PARA blank() - Verificar Vacío
    // ==========================================================================
    
    /**
     * Test: blank() detecta null
     */
    public function testBlankDetectsNull(): void
    {
        $this->assertTrue(blank(null), 'null debería ser blank');
    }
    
    /**
     * Test: blank() detecta string vacío
     */
    public function testBlankDetectsEmptyString(): void
    {
        $this->assertTrue(blank(''), 'String vacío debería ser blank');
    }
    
    /**
     * Test: blank() detecta string con solo espacios
     * 
     * EXPLICACIÓN: '   ' (espacios) se considera vacío después de trim()
     */
    public function testBlankDetectsWhitespaceString(): void
    {
        $this->assertTrue(blank('   '), 'String con solo espacios debería ser blank');
        $this->assertTrue(blank("\t\n"), 'Tabs y newlines deberían ser blank');
    }
    
    /**
     * Test: blank() detecta array vacío
     */
    public function testBlankDetectsEmptyArray(): void
    {
        $this->assertTrue(blank([]), 'Array vacío debería ser blank');
    }
    
    /**
     * Test: blank() tiene comportamiento especial con 0
     * 
     * EXPLICACIÓN:
     * - blank(0) usa empty() → true (el int 0 es considerado blank)
     * - blank('0') usa trim() === '' → false (el string '0' NO es blank porque trim('0') !== '')
     */
    public function testBlankHandlesZeroSpecially(): void
    {
        // Int 0 es considerado blank por empty()
        $this->assertTrue(blank(0), 'El número 0 es considerado blank por empty()');
        
        // String '0' NO es blank porque trim('0') !== ''
        $this->assertFalse(blank('0'), 'El string "0" NO es blank porque tiene contenido después de trim');
    }
    
    /**
     * Test: blank() considera false como blank (comportamiento de empty())
     * 
     * NOTA: blank() usa empty() internamente, y empty(false) es true
     */
    public function testBlankConsidersFalseAsBlank(): void
    {
        $this->assertTrue(blank(false), 'false es considerado blank por empty()');
    }
    
    /**
     * Test: blank() detecta arrays con valores pero empty-evaluable
     */
    public function testBlankWithComplexValues(): void
    {
        // Array con valores debería ser NOT blank
        $this->assertFalse(
            blank(['value']),
            'Array con valores NO debería ser blank'
        );
        
        // String con contenido NO debería ser blank
        $this->assertFalse(
            blank('texto'),
            'String con contenido NO debería ser blank'
        );
    }
    
    // ==========================================================================
    // TESTS ADICIONALES PARA filled() - Verificar con Contenido
    // ==========================================================================
    
    /**
     * Test: filled() es opuesto de blank() con null
     */
    public function testFilledIsOppositeOfBlankWithNull(): void
    {
        $this->assertFalse(filled(null), 'null NO debería estar filled');
        $this->assertTrue(blank(null), 'null SÍ debería estar blank');
    }
    
    /**
     * Test: filled() es opuesto de blank() con string vacío
     */
    public function testFilledIsOppositeOfBlankWithEmptyString(): void
    {
        $this->assertFalse(filled(''), 'String vacío NO debería estar filled');
        $this->assertTrue(blank(''), 'String vacío SÍ debería estar blank');
    }
    
    /**
     * Test: filled() es opuesto de blank() con espacios
     */
    public function testFilledIsOppositeOfBlankWithWhitespace(): void
    {
        $this->assertFalse(filled('   '), 'Espacios NO deberían estar filled');
        $this->assertTrue(blank('   '), 'Espacios SÍ deberían estar blank');
    }
    
    /**
     * Test: filled() es opuesto de blank() con array vacío
     */
    public function testFilledIsOppositeOfBlankWithEmptyArray(): void
    {
        $this->assertFalse(filled([]), 'Array vacío NO debería estar filled');
        $this->assertTrue(blank([]), 'Array vacío SÍ debería estar blank');
    }
    
    /**
     * Test: filled() detecta string con contenido
     */
    public function testFilledDetectsStringWithContent(): void
    {
        $this->assertTrue(filled('texto'), 'String con texto debería estar filled');
    }
    
    /**
     * Test: filled() detecta array con elementos
     */
    public function testFilledDetectsArrayWithElements(): void
    {
        $this->assertTrue(
            filled(['item']),
            'Array con elementos debería estar filled'
        );
    }
    
    /**
     * Test: filled() tiene comportamiento opuesto a blank() con 0
     * 
     * EXPLICACIÓN:
     * - filled(0) = !blank(0) = false (el int 0 NO está filled)
     * - filled('0') = !blank('0') = true (el string '0' SÍ está filled)
     */
    public function testFilledHandlesZeroSpecially(): void
    {
        // Int 0 NO está filled (porque blank(0) es true)
        $this->assertFalse(filled(0), 'El número 0 NO está filled');
        
        // String '0' SÍ está filled (porque blank('0') es false)
        $this->assertTrue(filled('0'), 'El string "0" SÍ está filled');
    }
    
    /**
     * Test: filled() NO considera false como filled
     */
    public function testFilledDoesNotConsiderFalseAsFilled(): void
    {
        $this->assertFalse(filled(false), 'false NO está filled (es blank)');
    }
}
