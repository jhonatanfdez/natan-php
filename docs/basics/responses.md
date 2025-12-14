# Responses (Respuestas)

> Las respuestas son cómo tu aplicación comunica información de vuelta al cliente, ya sea HTML, JSON, archivos o redirects.

---

## 📖 Índice

- [Introducción](#introducción)
- [Respuestas JSON (API)](#respuestas-json-api)
- [Respuestas HTML (Web)](#respuestas-html-web)
- [Redirects](#redirects)
- [Códigos de Estado HTTP](#códigos-de-estado-http)
- [Headers Personalizados](#headers-personalizados)
- [Descargas de Archivos](#descargas-de-archivos)
- [Content Negotiation](#content-negotiation)
- [Ejemplos Completos](#ejemplos-completos)
- [Buenas Prácticas](#buenas-prácticas)

---

## Introducción

En NatanPHP, cada petición HTTP debe devolver una **respuesta**. Las respuestas pueden ser:

- 📄 **HTML** - Páginas web tradicionales
- 📊 **JSON** - Datos para APIs REST
- 🔄 **Redirects** - Redirigir a otra URL
- 📁 **Files** - Descargar archivos
- 🚫 **Errors** - Páginas de error (404, 500, etc.)

### Tipos de Respuestas por Controlador

```
┌─────────────────────────────────────────────┐
│           NatanPHP Framework                │
├─────────────────────────────────────────────┤
│                                             │
│  Web Controller              API Controller │
│  ├── view()                  ├── successResponse() │
│  ├── response()              ├── errorResponse()   │
│  └── redirect()              ├── jsonResponse()    │
│                              ├── createdResponse() │
│                              └── noContentResponse()│
│                                             │
└─────────────────────────────────────────────┘
```

---

## Respuestas JSON (API)

Las respuestas JSON son el estándar para APIs REST. NatanPHP proporciona métodos convenientes en `ApiController`.

### Success Response

```php
protected function successResponse(
    $data = null, 
    string $message = 'OK', 
    int $status = 200
): void
```

**Ejemplo:**

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class UsersController extends ApiController
{
    public function index(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Juan', 'email' => 'juan@example.com'],
            ['id' => 2, 'name' => 'María', 'email' => 'maria@example.com'],
        ];
        
        $this->successResponse($users, 'Users retrieved successfully');
    }
}
```

**Respuesta (200 OK):**

```json
{
  "success": true,
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Juan",
      "email": "juan@example.com"
    },
    {
      "id": 2,
      "name": "María",
      "email": "maria@example.com"
    }
  ],
  "timestamp": "2024-12-14 10:30:00"
}
```

---

### Error Response

```php
protected function errorResponse(
    string $message = 'Error', 
    int $status = 400, 
    $errors = null
): void
```

**Ejemplo:**

```php
public function store(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    
    $errors = [];
    if (empty($data['name'])) {
        $errors['name'] = 'Name is required';
    }
    if (empty($data['email'])) {
        $errors['email'] = 'Email is required';
    }
    
    if (!empty($errors)) {
        $this->errorResponse('Validation failed', 422, $errors);
        return;
    }
    
    // Crear usuario...
}
```

**Respuesta (422 Unprocessable Entity):**

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": "Name is required",
    "email": "Email is required"
  },
  "timestamp": "2024-12-14 10:35:00"
}
```

---

### JSON Response Genérica

```php
protected function jsonResponse(array $data, int $status = 200): void
```

**Ejemplo:**

```php
public function custom(): void
{
    $this->jsonResponse([
        'status' => 'active',
        'version' => '1.0.0',
        'features' => ['routing', 'requests', 'helpers']
    ], 200);
}
```

**Respuesta:**

```json
{
  "status": "active",
  "version": "1.0.0",
  "features": [
    "routing",
    "requests",
    "helpers"
  ]
}
```

---

### Created Response (201)

```php
protected function createdResponse($data, string $message = 'Created'): void
```

**Ejemplo:**

```php
public function store(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    
    $newUser = [
        'id' => 123,
        'name' => $data['name'],
        'email' => $data['email'],
        'created_at' => date('Y-m-d H:i:s'),
    ];
    
    $this->createdResponse($newUser, 'User created successfully');
}
```

**Respuesta (201 Created):**

```json
{
  "success": true,
  "message": "User created successfully",
  "data": {
    "id": 123,
    "name": "Pedro",
    "email": "pedro@example.com",
    "created_at": "2024-12-14 10:40:00"
  },
  "timestamp": "2024-12-14 10:40:00"
}
```

---

### No Content Response (204)

```php
protected function noContentResponse(): void
```

**Ejemplo:**

```php
public function destroy(int $id): void
{
    // Eliminar usuario...
    $this->deleteUser($id);
    
    // Respuesta sin contenido (204)
    $this->noContentResponse();
}
```

**Respuesta (204 No Content):**

```
HTTP/1.1 204 No Content
Content-Type: application/json
```

*Sin body en la respuesta*

---

## Respuestas HTML (Web)

Las respuestas HTML son para aplicaciones web tradicionales. NatanPHP proporciona métodos en `Controller`.

### View Response

```php
protected function view(string $view, array $data = []): string
```

**Ejemplo:**

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class PostsController extends Controller
{
    public function index(): string
    {
        $posts = [
            ['id' => 1, 'title' => 'Mi Primer Post', 'author' => 'Juan'],
            ['id' => 2, 'title' => 'Segundo Post', 'author' => 'María'],
        ];
        
        return $this->view('posts/index', compact('posts'));
    }
    
    public function show(int $id): string
    {
        $post = [
            'id' => $id,
            'title' => 'Post #' . $id,
            'content' => 'Contenido del post...',
            'author' => 'Juan',
        ];
        
        return $this->view('posts/show', compact('post'));
    }
}
```

**Vista (`app/Web/Views/posts/index.php`):**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
</head>
<body>
    <h1>Lista de Posts</h1>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <a href="/posts/<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
                - por <?= htmlspecialchars($post['author']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```

---

### Simple Response

```php
protected function response(string $content, int $status = 200): void
```

**Ejemplo:**

```php
public function health(): void
{
    $this->response('OK', 200);
}

public function error(): void
{
    $this->response('Internal Server Error', 500);
}

public function custom(): void
{
    $html = '<h1>Hello World</h1><p>Custom HTML response</p>';
    $this->response($html, 200);
}
```

---

## Redirects

Los redirects envían al usuario a otra URL.

### Redirect Simple

```php
protected function redirect(string $url): void
```

**Ejemplo:**

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class AuthController extends Controller
{
    public function login(): void
    {
        // Procesar login...
        $authenticated = $this->authenticate();
        
        if ($authenticated) {
            $this->redirect('/dashboard');
        } else {
            $this->redirect('/login?error=1');
        }
    }
    
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
    }
}
```

---

### Redirect con Query String

```php
public function store(): void
{
    // Validar datos
    if ($this->hasErrors()) {
        $this->redirect('/posts/create?error=validation');
        return;
    }
    
    // Guardar post
    $postId = $this->createPost();
    
    // Redirigir con éxito
    $this->redirect("/posts/{$postId}?success=1");
}
```

---

### Redirect Condicional

```php
public function update(int $id): void
{
    $post = $this->findPost($id);
    
    // Verificar permisos
    if (!$this->userOwnsPost($post)) {
        $this->redirect('/posts?error=forbidden');
        return;
    }
    
    // Actualizar post
    $this->updatePost($id, $_POST);
    $this->redirect("/posts/{$id}?success=updated");
}
```

---

### Back Redirect (Volver Atrás)

```php
public function cancel(): void
{
    // Redirigir a la página anterior
    $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
    $this->redirect($referrer);
}
```

---

## Códigos de Estado HTTP

Los códigos de estado HTTP indican el resultado de la petición.

### Códigos Comunes

| Código | Nombre | Descripción | Cuándo Usar |
|--------|--------|-------------|-------------|
| **200** | OK | Éxito | Peticiones GET exitosas |
| **201** | Created | Recurso creado | POST exitoso, recurso nuevo |
| **204** | No Content | Sin contenido | DELETE exitoso, sin respuesta |
| **301** | Moved Permanently | Redirect permanente | URLs cambiadas permanentemente |
| **302** | Found | Redirect temporal | Redirect temporal |
| **400** | Bad Request | Petición inválida | Datos incorrectos del cliente |
| **401** | Unauthorized | No autenticado | Login requerido |
| **403** | Forbidden | Sin permisos | Usuario autenticado sin permisos |
| **404** | Not Found | No encontrado | Recurso no existe |
| **422** | Unprocessable Entity | Validación fallida | Errores de validación |
| **429** | Too Many Requests | Demasiadas peticiones | Rate limiting |
| **500** | Internal Server Error | Error del servidor | Error inesperado |
| **503** | Service Unavailable | Servicio no disponible | Mantenimiento |

---

### Ejemplos por Código

#### 200 OK - Éxito

```php
public function index(): void
{
    $users = $this->getAllUsers();
    $this->successResponse($users, 'Users retrieved', 200);
}
```

#### 201 Created - Recurso Creado

```php
public function store(): void
{
    $user = $this->createUser($_POST);
    $this->createdResponse($user, 'User created');
}
```

#### 204 No Content - Sin Respuesta

```php
public function destroy(int $id): void
{
    $this->deleteUser($id);
    $this->noContentResponse(); // 204
}
```

#### 400 Bad Request - Petición Inválida

```php
public function update(): void
{
    if (empty($_POST)) {
        $this->errorResponse('No data provided', 400);
        return;
    }
    // ...
}
```

#### 401 Unauthorized - No Autenticado

```php
public function profile(): void
{
    if (!isset($_SESSION['user_id'])) {
        $this->errorResponse('Unauthorized', 401);
        return;
    }
    // ...
}
```

#### 403 Forbidden - Sin Permisos

```php
public function delete(int $id): void
{
    if (!$this->isAdmin()) {
        $this->errorResponse('Forbidden', 403);
        return;
    }
    // ...
}
```

#### 404 Not Found - No Encontrado

```php
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->errorResponse('User not found', 404);
        return;
    }
    
    $this->successResponse($user);
}
```

#### 422 Unprocessable Entity - Validación

```php
public function store(): void
{
    $errors = $this->validate($_POST);
    
    if (!empty($errors)) {
        $this->errorResponse('Validation failed', 422, $errors);
        return;
    }
    // ...
}
```

#### 429 Too Many Requests - Rate Limiting

```php
public function login(): void
{
    if ($this->tooManyAttempts()) {
        $this->errorResponse('Too many login attempts', 429);
        return;
    }
    // ...
}
```

#### 500 Internal Server Error

```php
public function process(): void
{
    try {
        $this->complexOperation();
    } catch (\Exception $e) {
        $this->errorResponse('Internal server error', 500);
        return;
    }
}
```

---

## Headers Personalizados

Puedes agregar headers HTTP personalizados a tus respuestas.

### Agregar Headers

```php
public function download(): void
{
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="document.pdf"');
    header('Content-Length: ' . filesize('document.pdf'));
    
    readfile('document.pdf');
}
```

### Headers Comunes

```php
// Content-Type
header('Content-Type: application/json');
header('Content-Type: text/html; charset=UTF-8');
header('Content-Type: application/xml');

// Cache Control
header('Cache-Control: no-cache, must-revalidate');
header('Cache-Control: public, max-age=3600');

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

// Security
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Custom
header('X-API-Version: 1.0');
header('X-Request-ID: ' . uniqid());
```

### Ejemplo Completo con Headers

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class ApiController extends \NatanPHP\App\Api\Controllers\ApiController
{
    public function index(): void
    {
        // Headers de seguridad
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        
        // Headers de API
        header('X-API-Version: 1.0.0');
        header('X-Rate-Limit: 1000');
        
        // Headers de cache
        header('Cache-Control: public, max-age=60');
        
        $data = ['message' => 'Hello API'];
        $this->successResponse($data);
    }
}
```

---

## Descargas de Archivos

### Descargar Archivo

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class DownloadController extends Controller
{
    public function invoice(int $id): void
    {
        $filePath = __DIR__ . "/../../storage/invoices/invoice_{$id}.pdf";
        
        if (!file_exists($filePath)) {
            $this->response('File not found', 404);
            return;
        }
        
        // Headers para descarga
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice_' . $id . '.pdf"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache');
        
        // Enviar archivo
        readfile($filePath);
        exit;
    }
}
```

### Descargar con Nombre Personalizado

```php
public function exportUsers(): void
{
    $csv = $this->generateUsersCsv();
    $filename = 'users_export_' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($csv));
    
    echo $csv;
    exit;
}

private function generateUsersCsv(): string
{
    $users = $this->getAllUsers();
    
    $csv = "ID,Name,Email\n";
    foreach ($users as $user) {
        $csv .= "{$user['id']},{$user['name']},{$user['email']}\n";
    }
    
    return $csv;
}
```

### Ver Archivo en Navegador (Inline)

```php
public function viewInvoice(int $id): void
{
    $filePath = __DIR__ . "/../../storage/invoices/invoice_{$id}.pdf";
    
    if (!file_exists($filePath)) {
        $this->response('File not found', 404);
        return;
    }
    
    // Headers para ver en navegador (no descargar)
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="invoice_' . $id . '.pdf"');
    header('Content-Length: ' . filesize($filePath));
    
    readfile($filePath);
    exit;
}
```

---

## Content Negotiation

Responder según lo que el cliente solicite (JSON vs HTML).

### Detectar Tipo de Respuesta

```php
<?php

namespace NatanPHP\App\Controllers;

class UsersController
{
    public function index()
    {
        $users = $this->getAllUsers();
        
        // Detectar si quiere JSON
        if ($this->wantsJson()) {
            // Respuesta JSON para API
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $users
            ]);
            return;
        }
        
        // Respuesta HTML para Web
        return $this->view('users/index', compact('users'));
    }
    
    private function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accept, 'application/json') !== false;
    }
}
```

### Usando Request Helper

```php
use NatanPHP\Core\Request;

public function show(int $id)
{
    $user = $this->findUser($id);
    
    if (!$user) {
        if (Request::wantsJson()) {
            $this->errorResponse('User not found', 404);
        } else {
            $this->redirect('/users?error=not_found');
        }
        return;
    }
    
    if (Request::wantsJson()) {
        $this->successResponse($user);
    } else {
        return $this->view('users/show', compact('user'));
    }
}
```

---

## Ejemplos Completos

### Ejemplo 1: API REST Completa

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class ProductsController extends ApiController
{
    /**
     * GET /api/products
     * Listar todos los productos
     */
    public function index(): void
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 999.99, 'stock' => 10],
            ['id' => 2, 'name' => 'Mouse', 'price' => 29.99, 'stock' => 50],
            ['id' => 3, 'name' => 'Keyboard', 'price' => 79.99, 'stock' => 25],
        ];
        
        $this->successResponse($products, 'Products retrieved successfully');
    }
    
    /**
     * GET /api/products/{id}
     * Obtener producto específico
     */
    public function show(int $id): void
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            $this->errorResponse('Product not found', 404);
            return;
        }
        
        $this->successResponse($product, 'Product found');
    }
    
    /**
     * POST /api/products
     * Crear nuevo producto
     */
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar
        $errors = $this->validateProduct($data);
        if (!empty($errors)) {
            $this->errorResponse('Validation failed', 422, $errors);
            return;
        }
        
        // Crear
        $product = [
            'id' => rand(100, 999),
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->createdResponse($product, 'Product created successfully');
    }
    
    /**
     * PUT /api/products/{id}
     * Actualizar producto
     */
    public function update(int $id): void
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            $this->errorResponse('Product not found', 404);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar
        $errors = $this->validateProduct($data);
        if (!empty($errors)) {
            $this->errorResponse('Validation failed', 422, $errors);
            return;
        }
        
        // Actualizar
        $updated = array_merge($product, $data);
        $updated['updated_at'] = date('Y-m-d H:i:s');
        
        $this->successResponse($updated, 'Product updated successfully');
    }
    
    /**
     * DELETE /api/products/{id}
     * Eliminar producto
     */
    public function destroy(int $id): void
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            $this->errorResponse('Product not found', 404);
            return;
        }
        
        // Eliminar
        $this->deleteProduct($id);
        
        $this->noContentResponse();
    }
    
    // Métodos auxiliares
    private function findProduct(int $id): ?array
    {
        $products = [
            1 => ['id' => 1, 'name' => 'Laptop', 'price' => 999.99, 'stock' => 10],
            2 => ['id' => 2, 'name' => 'Mouse', 'price' => 29.99, 'stock' => 50],
        ];
        
        return $products[$id] ?? null;
    }
    
    private function validateProduct(array $data): array
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Valid price is required';
        }
        
        if (!isset($data['stock']) || !is_numeric($data['stock'])) {
            $errors['stock'] = 'Valid stock is required';
        }
        
        return $errors;
    }
    
    private function deleteProduct(int $id): void
    {
        // Simulación - en producción eliminarías de DB
    }
}
```

**Pruebas:**

```bash
# Listar productos
curl http://localhost:8000/api/products

# Obtener producto
curl http://localhost:8000/api/products/1

# Crear producto
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Monitor","price":299.99,"stock":15}'

# Actualizar producto
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"name":"Gaming Laptop","price":1299.99,"stock":5}'

# Eliminar producto
curl -X DELETE http://localhost:8000/api/products/1
```

---

### Ejemplo 2: Formulario Web con Validación

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class ContactController extends Controller
{
    /**
     * GET /contact
     * Mostrar formulario de contacto
     */
    public function show(): string
    {
        return $this->view('contact/form');
    }
    
    /**
     * POST /contact
     * Procesar formulario de contacto
     */
    public function submit(): void
    {
        // Validar datos
        $errors = $this->validateContact($_POST);
        
        if (!empty($errors)) {
            // Redirigir con errores
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/contact?error=validation');
            return;
        }
        
        // Enviar email (simulado)
        $this->sendContactEmail($_POST);
        
        // Redirigir con éxito
        $this->redirect('/contact?success=1');
    }
    
    private function validateContact(array $data): array
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'El nombre es requerido';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'El email es requerido';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido';
        }
        
        if (empty($data['message'])) {
            $errors['message'] = 'El mensaje es requerido';
        } elseif (strlen($data['message']) < 10) {
            $errors['message'] = 'El mensaje debe tener al menos 10 caracteres';
        }
        
        return $errors;
    }
    
    private function sendContactEmail(array $data): void
    {
        // Simulación - en producción enviarías email real
        error_log("Contact form submitted: " . json_encode($data));
    }
}
```

**Vista (`app/Web/Views/contact/form.php`):**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Contacto</title>
</head>
<body>
    <h1>Formulario de Contacto</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div style="color: green;">¡Mensaje enviado exitosamente!</div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['errors'])): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    
    <form method="POST" action="/contact">
        <div>
            <label>Nombre:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>">
        </div>
        
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>">
        </div>
        
        <div>
            <label>Mensaje:</label>
            <textarea name="message"><?= htmlspecialchars($_SESSION['old']['message'] ?? '') ?></textarea>
        </div>
        
        <button type="submit">Enviar</button>
    </form>
    
    <?php unset($_SESSION['old']); ?>
</body>
</html>
```

---

## Buenas Prácticas

### ✅ DO (Hacer)

#### 1. Usa el Código de Estado Correcto

```php
// ✅ BIEN
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->errorResponse('User not found', 404); // 404
        return;
    }
    
    $this->successResponse($user); // 200
}

// ❌ MAL - Siempre 200
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->successResponse(null, 'User not found'); // 200 ❌
        return;
    }
    
    $this->successResponse($user);
}
```

#### 2. Respuestas Consistentes en APIs

```php
// ✅ BIEN - Formato consistente
$this->successResponse($data, 'Success');
$this->errorResponse('Error', 400);

// ❌ MAL - Formatos inconsistentes
echo json_encode(['data' => $data]);
echo json_encode(['error' => 'Error', 'code' => 400]);
echo json_encode(['status' => 'ok', 'result' => $data]);
```

#### 3. Escapa HTML en Vistas

```php
// ✅ BIEN
<?= htmlspecialchars($user['name']) ?>

// ❌ MAL - XSS vulnerability
<?= $user['name'] ?>
```

#### 4. Valida Antes de Responder

```php
// ✅ BIEN
public function store(): void
{
    $errors = $this->validate($_POST);
    
    if (!empty($errors)) {
        $this->errorResponse('Validation failed', 422, $errors);
        return;
    }
    
    // Crear recurso...
}
```

#### 5. Usa Headers Apropiados

```php
// ✅ BIEN
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data, JSON_UNESCAPED_UNICODE);

// ✅ BIEN para descargas
header('Content-Disposition: attachment; filename="file.pdf"');
```

### ❌ DON'T (Evitar)

#### 1. No Mezcles Tipos de Respuesta

```php
// ❌ MAL
public function index()
{
    $users = $this->getUsers();
    echo json_encode($users); // JSON
    return $this->view('users/index'); // HTML
}

// ✅ BIEN - Una respuesta
public function index(): void
{
    $users = $this->getUsers();
    $this->successResponse($users);
}
```

#### 2. No Ignores los Errores

```php
// ❌ MAL
public function show(int $id): void
{
    $user = $this->findUser($id);
    $this->successResponse($user); // ¿Y si es null?
}

// ✅ BIEN
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->errorResponse('User not found', 404);
        return;
    }
    
    $this->successResponse($user);
}
```

#### 3. No Expongas Información Sensible

```php
// ❌ MAL
catch (\Exception $e) {
    $this->errorResponse($e->getMessage(), 500); // Stack trace visible
}

// ✅ BIEN
catch (\Exception $e) {
    error_log($e->getMessage()); // Log interno
    $this->errorResponse('Internal server error', 500); // Mensaje genérico
}
```

#### 4. No Olvides exit() Después de Redirect

```php
// ❌ MAL
$this->redirect('/home');
// El código continúa ejecutándose

// ✅ BIEN - redirect() ya hace exit()
protected function redirect(string $url): void
{
    header("Location: {$url}");
    exit; // ✅
}
```

---

## Resumen

### Métodos de Respuesta

#### API Controller

| Método | Código | Uso |
|--------|--------|-----|
| `successResponse($data, $message, $status)` | 200 | Éxito general |
| `errorResponse($message, $status, $errors)` | 400-500 | Errores |
| `jsonResponse($data, $status)` | Custom | JSON personalizado |
| `createdResponse($data, $message)` | 201 | Recurso creado |
| `noContentResponse()` | 204 | Sin contenido |

#### Web Controller

| Método | Uso |
|--------|-----|
| `view($view, $data)` | Renderizar vista HTML |
| `response($content, $status)` | Respuesta simple |
| `redirect($url)` | Redirigir a otra URL |

### Códigos HTTP Principales

- **2xx** - Éxito (200, 201, 204)
- **3xx** - Redirects (301, 302)
- **4xx** - Errores del cliente (400, 401, 403, 404, 422, 429)
- **5xx** - Errores del servidor (500, 503)

---

## Próximos Pasos

- 📘 [Controllers](basics/controllers.md) - Usar respuestas en controllers
- 📘 [Routing](basics/routing.md) - Conectar rutas con respuestas
- 📘 [Requests](basics/requests.md) - Procesar peticiones

---

**¿Tienes dudas?** Consulta la [documentación completa](/) o visita el [repositorio en GitHub](https://github.com/jhonatanfdez/natan-php).
