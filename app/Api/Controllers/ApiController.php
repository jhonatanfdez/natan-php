<?php

namespace NatanPHP\App\Api\Controllers;

/**
 * ApiController - Controlador base para toda la aplicación API REST
 * 
 * Proporciona funcionalidades comunes para todos los controladores API como
 * respuestas JSON estandarizadas, manejo de errores, códigos de estado HTTP
 * y métodos utilitarios para APIs REST.
 * 
 * Características principales:
 * - Respuestas JSON consistentes y estandarizadas
 * - Manejo automático de códigos de estado HTTP
 * - Métodos para success, error y paginación
 * - Headers HTTP apropiados para APIs
 * - Formato de error estructurado para debugging
 * 
 * Ejemplo de uso:
 * ```php
 * class UsuariosController extends ApiController {
 *     public function index() {
 *         $usuarios = ['Juan', 'María'];
 *         return $this->successResponse($usuarios, 'Usuarios obtenidos');
 *     }
 * }
 * ```
 * 
 * @package NatanPHP\App\Api\Controllers
 * @author Natan PHP Framework
 */
abstract class ApiController
{
    /**
     * Respuesta JSON exitosa
     * 
     * @param mixed $data Datos a retornar
     * @param string $message Mensaje opcional
     * @param int $status Código de estado HTTP
     * @return void
     */
    protected function successResponse($data = null, string $message = 'OK', int $status = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        
        $this->jsonResponse($response, $status);
    }
    
    /**
     * Respuesta JSON de error
     * 
     * @param string $message Mensaje de error
     * @param int $status Código de estado HTTP
     * @param mixed $errors Errores específicos
     * @return void
     */
    protected function errorResponse(string $message = 'Error', int $status = 400, $errors = null): void
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        
        $this->jsonResponse($response, $status);
    }
    
    /**
     * Respuesta JSON genérica
     * 
     * @param array $data Datos a retornar
     * @param int $status Código de estado HTTP
     * @return void
     */
    protected function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Respuesta para recursos no encontrados
     * 
     * @param string $resource Nombre del recurso
     * @return void
     */
    protected function notFoundResponse(string $resource = 'Recurso'): void
    {
        $this->errorResponse("{$resource} no encontrado", 404);
    }
    
    /**
     * Respuesta para validación fallida
     * 
     * @param array $errors Errores de validación
     * @return void
     */
    protected function validationErrorResponse(array $errors): void
    {
        $this->errorResponse('Datos de entrada inválidos', 422, $errors);
    }
}