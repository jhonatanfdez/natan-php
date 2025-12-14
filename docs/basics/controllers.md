# Controllers (Controladores)

> Los controladores son el corazón de tu aplicación, organizando la lógica de negocio y coordinando las respuestas.

---

## 📖 Índice

- [Introducción](#introducción)
- [Controladores Web](#controladores-web)
- [Controladores API](#controladores-api)
- [Crear un Controlador](#crear-un-controlador)
- [Métodos de Controlador](#métodos-de-controlador)
- [Dependencias y Servicios](#dependencias-y-servicios)
- [Buenas Prácticas](#buenas-prácticas)
- [Ejemplos Completos](#ejemplos-completos)

---

## Introducción

En NatanPHP, los **controladores** son clases que agrupan la lógica relacionada con el manejo de las peticiones HTTP. Cada controlador puede tener múltiples métodos (acciones) que responden a diferentes rutas.

### ¿Por qué usar Controllers?

```php
// ❌ Sin controlador (todo en routes/web.php)
Router::get('/posts', function() {
    $posts = ['Post 1', 'Post 2', 'Post 3'];
    echo json_encode($posts);
});

Router::get('/posts/{id}', function($id) {
    $post = ['id' => $id, 'title' => 'Mi Post'];
    echo json_encode($post);
});

// ✅ Con controlador (organizado y mantenible)
Router::get('/posts', 'PostsController@index');
Router::get('/posts/{id}', 'PostsController@show');
```

**Ventajas:**
- ✅ Organización del código
- ✅ Reutilización de lógica
- ✅ Fácil testing
- ✅ Separación de responsabilidades
- ✅ Escalabilidad

---

## Controladores Web

Los controladores web heredan de `NatanPHP\App\Web\Controllers\Controller` y están diseñados para aplicaciones web tradicionales con HTML.

### Ubicación

```
app/
└── Web/
    └── Controllers/
        ├── Controller.php        # Clase base
        ├── HomeController.php    # Ejemplo
        └── UsersController.php   # Tu controlador
```

### Clase Base Web Controller

```php
<?php

namespace NatanPHP\App\Web\Controllers;

abstract class Controller
{
    /**
     * Renderizar una vista
     */
    protected function view(string $view, array $data = []): string
    {
        // Implementación de renderizado
    }
    
    /**
     * Crear una respuesta HTTP
     */
    protected function response(string $content, int $status = 200): void
    {
        http_response_code($status);
        echo $content;
    }
    
    /**
     * Redirección HTTP
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
```

### Métodos Disponibles (Web)

| Método | Descripción | Ejemplo |
|--------|-------------|---------|
| `view($view, $data)` | Renderiza una vista PHP | `return $this->view('users/index', compact('users'))` |
| `response($content, $status)` | Envía una respuesta HTTP | `$this->response('Hello', 200)` |
| `redirect($url)` | Redirige a otra URL | `$this->redirect('/home')` |

---

## Controladores API

Los controladores API heredan de `NatanPHP\App\Api\Controllers\ApiController` y están optimizados para APIs REST con respuestas JSON.

### Ubicación

```
app/
└── Api/
    └── Controllers/
        ├── ApiController.php     # Clase base
        ├── HomeController.php    # Ejemplo
        └── UsersController.php   # Tu controlador
```

### Clase Base API Controller

```php
<?php

namespace NatanPHP\App\Api\Controllers;

abstract class ApiController
{
    /**
     * Respuesta JSON exitosa
     */
    protected function successResponse(
        $data = null, 
        string $message = 'OK', 
        int $status = 200
    ): void
    
    /**
     * Respuesta JSON de error
     */
    protected function errorResponse(
        string $message = 'Error', 
        int $status = 400, 
        $errors = null
    ): void
    
    /**
     * Respuesta JSON genérica
     */
    protected function jsonResponse(array $data, int $status = 200): void
    
    /**
     * Respuesta de recurso creado
     */
    protected function createdResponse($data, string $message = 'Created'): void
    
    /**
     * Respuesta sin contenido
     */
    protected function noContentResponse(): void
}
```

### Métodos Disponibles (API)

| Método | Descripción | Status Code | Ejemplo |
|--------|-------------|-------------|---------|
| `successResponse($data, $message, $status)` | Respuesta exitosa | 200 | `$this->successResponse($users, 'Users retrieved')` |
| `errorResponse($message, $status, $errors)` | Respuesta de error | 400-500 | `$this->errorResponse('Not found', 404)` |
| `jsonResponse($data, $status)` | JSON genérico | Personalizado | `$this->jsonResponse(['key' => 'value'], 200)` |
| `createdResponse($data, $message)` | Recurso creado | 201 | `$this->createdResponse($user, 'User created')` |
| `noContentResponse()` | Sin contenido | 204 | `$this->noContentResponse()` |

### Formato de Respuestas API

#### Success Response

```json
{
  "success": true,
  "message": "Users retrieved successfully",
  "data": [
    {"id": 1, "name": "Juan"},
    {"id": 2, "name": "María"}
  ],
  "timestamp": "2024-12-14 10:30:00"
}
```

#### Error Response

```json
{
  "success": false,
  "message": "User not found",
  "errors": null,
  "timestamp": "2024-12-14 10:30:00"
}
```

---

## Crear un Controlador

### 1. Controlador Web Básico

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class PostsController extends Controller
{
    /**
     * Mostrar lista de posts
     */
    public function index(): string
    {
        $posts = [
            ['id' => 1, 'title' => 'Mi Primer Post'],
            ['id' => 2, 'title' => 'Aprendiendo NatanPHP'],
        ];
        
        return $this->view('posts/index', compact('posts'));
    }
    
    /**
     * Mostrar un post específico
     */
    public function show(int $id): string
    {
        $post = [
            'id' => $id,
            'title' => 'Post #' . $id,
            'content' => 'Contenido del post...'
        ];
        
        return $this->view('posts/show', compact('post'));
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create(): string
    {
        return $this->view('posts/create');
    }
    
    /**
     * Guardar nuevo post
     */
    public function store(): void
    {
        // Obtener datos del request
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        
        // Validar y guardar (simulado)
        if (empty($title)) {
            $this->response('Título requerido', 400);
            return;
        }
        
        // Redirigir después de guardar
        $this->redirect('/posts');
    }
}
```

**Registrar en `routes/web.php`:**

```php
use NatanPHP\Core\Router;

Router::get('/posts', 'PostsController@index');
Router::get('/posts/create', 'PostsController@create');
Router::post('/posts', 'PostsController@store');
Router::get('/posts/{id}', 'PostsController@show');
```

---

### 2. Controlador API REST

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class UsersController extends ApiController
{
    /**
     * GET /api/users - Listar todos los usuarios
     */
    public function index(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Juan', 'email' => 'juan@example.com'],
            ['id' => 2, 'name' => 'María', 'email' => 'maria@example.com'],
            ['id' => 3, 'name' => 'Pedro', 'email' => 'pedro@example.com'],
        ];
        
        $this->successResponse($users, 'Users retrieved successfully');
    }
    
    /**
     * GET /api/users/{id} - Obtener un usuario
     */
    public function show(int $id): void
    {
        // Simular búsqueda en base de datos
        $users = [
            1 => ['id' => 1, 'name' => 'Juan', 'email' => 'juan@example.com'],
            2 => ['id' => 2, 'name' => 'María', 'email' => 'maria@example.com'],
        ];
        
        if (!isset($users[$id])) {
            $this->errorResponse('User not found', 404);
            return;
        }
        
        $this->successResponse($users[$id], 'User found');
    }
    
    /**
     * POST /api/users - Crear nuevo usuario
     */
    public function store(): void
    {
        // Obtener datos del body JSON
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos
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
        
        // Simular creación
        $newUser = [
            'id' => 4,
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        
        $this->createdResponse($newUser, 'User created successfully');
    }
    
    /**
     * PUT /api/users/{id} - Actualizar usuario
     */
    public function update(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Simular actualización
        $updatedUser = [
            'id' => $id,
            'name' => $data['name'] ?? 'Updated Name',
            'email' => $data['email'] ?? 'updated@example.com',
        ];
        
        $this->successResponse($updatedUser, 'User updated successfully');
    }
    
    /**
     * DELETE /api/users/{id} - Eliminar usuario
     */
    public function destroy(int $id): void
    {
        // Simular eliminación
        $this->noContentResponse();
    }
}
```

**Registrar en `routes/api.php`:**

```php
use NatanPHP\Core\Router;

// Ruta resource completa
Router::get('/users', 'UsersController@index');
Router::get('/users/{id}', 'UsersController@show');
Router::post('/users', 'UsersController@store');
Router::put('/users/{id}', 'UsersController@update');
Router::delete('/users/{id}', 'UsersController@destroy');
```

---

## Métodos de Controlador

### Convenciones de Nombres (RESTful)

NatanPHP sigue las convenciones REST estándar:

| Método HTTP | Nombre del Método | Ruta | Propósito |
|-------------|-------------------|------|-----------|
| GET | `index()` | `/users` | Listar todos los recursos |
| GET | `show($id)` | `/users/{id}` | Mostrar un recurso específico |
| GET | `create()` | `/users/create` | Mostrar formulario de creación (Web) |
| POST | `store()` | `/users` | Guardar nuevo recurso |
| GET | `edit($id)` | `/users/{id}/edit` | Mostrar formulario de edición (Web) |
| PUT/PATCH | `update($id)` | `/users/{id}` | Actualizar recurso existente |
| DELETE | `destroy($id)` | `/users/{id}` | Eliminar recurso |

### Ejemplo Completo CRUD

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class ProductsController extends ApiController
{
    // GET /api/products
    public function index(): void
    {
        $products = $this->getAllProducts();
        $this->successResponse($products, 'Products retrieved');
    }
    
    // GET /api/products/{id}
    public function show(int $id): void
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            $this->errorResponse('Product not found', 404);
            return;
        }
        
        $this->successResponse($product, 'Product found');
    }
    
    // POST /api/products
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
        $product = $this->createProduct($data);
        $this->createdResponse($product, 'Product created');
    }
    
    // PUT /api/products/{id}
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
        $updated = $this->updateProduct($id, $data);
        $this->successResponse($updated, 'Product updated');
    }
    
    // DELETE /api/products/{id}
    public function destroy(int $id): void
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            $this->errorResponse('Product not found', 404);
            return;
        }
        
        $this->deleteProduct($id);
        $this->noContentResponse();
    }
    
    // Métodos privados auxiliares
    private function getAllProducts(): array
    {
        // Simulación - en producción usarías DB
        return [
            ['id' => 1, 'name' => 'Laptop', 'price' => 999.99],
            ['id' => 2, 'name' => 'Mouse', 'price' => 29.99],
        ];
    }
    
    private function findProduct(int $id): ?array
    {
        $products = $this->getAllProducts();
        foreach ($products as $product) {
            if ($product['id'] === $id) {
                return $product;
            }
        }
        return null;
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
        
        return $errors;
    }
    
    private function createProduct(array $data): array
    {
        // Simulación
        return [
            'id' => rand(100, 999),
            'name' => $data['name'],
            'price' => $data['price'],
        ];
    }
    
    private function updateProduct(int $id, array $data): array
    {
        // Simulación
        return [
            'id' => $id,
            'name' => $data['name'],
            'price' => $data['price'],
        ];
    }
    
    private function deleteProduct(int $id): void
    {
        // Simulación - en producción eliminarías de DB
    }
}
```

---

## Dependencias y Servicios

### Inyección Manual en Constructor

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class OrdersController extends ApiController
{
    private $emailService;
    private $paymentService;
    
    public function __construct()
    {
        // Inyección manual de dependencias
        $this->emailService = new EmailService();
        $this->paymentService = new PaymentService();
    }
    
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Procesar pago
        $payment = $this->paymentService->process($data['amount']);
        
        if (!$payment['success']) {
            $this->errorResponse('Payment failed', 402);
            return;
        }
        
        // Crear orden
        $order = [
            'id' => rand(1000, 9999),
            'amount' => $data['amount'],
            'status' => 'paid',
        ];
        
        // Enviar email
        $this->emailService->send($data['email'], 'Order Confirmation');
        
        $this->createdResponse($order, 'Order created');
    }
}
```

### Acceder a Request Globalmente

```php
<?php

namespace NatanPHP\App\Api\Controllers;

use NatanPHP\Core\Request;

class SearchController extends ApiController
{
    public function search(): void
    {
        // Obtener query string
        $query = $_GET['q'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        
        if (empty($query)) {
            $this->errorResponse('Search query is required', 400);
            return;
        }
        
        // Simular búsqueda
        $results = $this->performSearch($query, $page, $limit);
        
        $this->successResponse([
            'query' => $query,
            'page' => $page,
            'results' => $results,
            'total' => count($results),
        ], 'Search completed');
    }
    
    private function performSearch(string $query, int $page, int $limit): array
    {
        // Simulación
        return [
            ['id' => 1, 'title' => "Result for {$query} #1"],
            ['id' => 2, 'title' => "Result for {$query} #2"],
        ];
    }
}
```

**Uso:**
```bash
GET /api/search?q=laptop&page=1&limit=20
```

---

## Buenas Prácticas

### ✅ DO (Hacer)

#### 1. Mantén los Controladores Delgados

```php
// ✅ BIEN - Delegar lógica a servicios
class UsersController extends ApiController
{
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $userService = new UserService();
        $result = $userService->createUser($data);
        
        if ($result['success']) {
            $this->createdResponse($result['user']);
        } else {
            $this->errorResponse($result['message'], 422, $result['errors']);
        }
    }
}

// ❌ MAL - Demasiada lógica en el controlador
class UsersController extends ApiController
{
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // 50 líneas de validación
        // 30 líneas de procesamiento
        // 20 líneas de guardar en DB
        // 15 líneas de enviar emails
        // ...
    }
}
```

#### 2. Usa Type Hints

```php
// ✅ BIEN
public function show(int $id): void
{
    // ...
}

// ❌ MAL
public function show($id)
{
    // ...
}
```

#### 3. Valida Siempre los Datos

```php
// ✅ BIEN
public function store(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    
    $errors = $this->validate($data);
    if (!empty($errors)) {
        $this->errorResponse('Validation failed', 422, $errors);
        return;
    }
    
    // Procesar...
}
```

#### 4. Respuestas Consistentes

```php
// ✅ BIEN - Usa los métodos de ApiController
$this->successResponse($data, 'Success');
$this->errorResponse('Error', 404);

// ❌ MAL - Respuestas inconsistentes
echo json_encode(['data' => $data]);
echo json_encode(['error' => 'Error', 'code' => 404]);
```

#### 5. Nomenclatura Clara

```php
// ✅ BIEN
class ProductsController extends ApiController
{
    public function index(): void { }
    public function show(int $id): void { }
    public function store(): void { }
}

// ❌ MAL
class ProductsController extends ApiController
{
    public function getAll(): void { }
    public function getOne(int $id): void { }
    public function create(): void { }
}
```

### ❌ DON'T (Evitar)

#### 1. No Mezcles Lógica Web y API

```php
// ❌ MAL
class UsersController extends Controller // ¿Web o API?
{
    public function index()
    {
        $users = $this->getUsers();
        
        if ($this->isApi()) {
            echo json_encode($users);
        } else {
            return $this->view('users/index', compact('users'));
        }
    }
}

// ✅ BIEN - Separa en dos controladores
// app/Web/Controllers/UsersController.php
class UsersController extends Controller
{
    public function index(): string
    {
        $users = $this->getUsers();
        return $this->view('users/index', compact('users'));
    }
}

// app/Api/Controllers/UsersController.php
class UsersController extends ApiController
{
    public function index(): void
    {
        $users = $this->getUsers();
        $this->successResponse($users);
    }
}
```

#### 2. No Hagas Consultas SQL Directas

```php
// ❌ MAL
public function index(): void
{
    $conn = mysqli_connect('localhost', 'user', 'pass', 'db');
    $result = mysqli_query($conn, "SELECT * FROM users");
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    $this->successResponse($users);
}

// ✅ BIEN - Usa servicios/repositorios
public function index(): void
{
    $userRepository = new UserRepository();
    $users = $userRepository->all();
    
    $this->successResponse($users);
}
```

#### 3. No Ignores los Códigos de Estado HTTP

```php
// ❌ MAL - Siempre 200 OK
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->successResponse(null, 'User not found'); // 200 OK ❌
    }
}

// ✅ BIEN - Usa el código apropiado
public function show(int $id): void
{
    $user = $this->findUser($id);
    
    if (!$user) {
        $this->errorResponse('User not found', 404); // 404 Not Found ✅
        return;
    }
    
    $this->successResponse($user);
}
```

---

## Ejemplos Completos

### Ejemplo 1: Blog API Completo

```php
<?php

namespace NatanPHP\App\Api\Controllers;

class PostsController extends ApiController
{
    private $posts = [
        1 => ['id' => 1, 'title' => 'Primer Post', 'content' => 'Contenido...', 'author' => 'Juan'],
        2 => ['id' => 2, 'title' => 'Segundo Post', 'content' => 'Más contenido...', 'author' => 'María'],
    ];
    
    /**
     * GET /api/posts
     * Listar todos los posts con filtros opcionales
     */
    public function index(): void
    {
        $author = $_GET['author'] ?? null;
        $limit = $_GET['limit'] ?? 10;
        
        $posts = $this->posts;
        
        // Filtrar por autor si se proporciona
        if ($author) {
            $posts = array_filter($posts, function($post) use ($author) {
                return $post['author'] === $author;
            });
        }
        
        // Limitar resultados
        $posts = array_slice($posts, 0, $limit);
        
        $this->successResponse(array_values($posts), 'Posts retrieved');
    }
    
    /**
     * GET /api/posts/{id}
     * Obtener un post específico
     */
    public function show(int $id): void
    {
        if (!isset($this->posts[$id])) {
            $this->errorResponse('Post not found', 404);
            return;
        }
        
        $this->successResponse($this->posts[$id], 'Post found');
    }
    
    /**
     * POST /api/posts
     * Crear nuevo post
     */
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar
        $errors = [];
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }
        if (empty($data['content'])) {
            $errors['content'] = 'Content is required';
        }
        if (empty($data['author'])) {
            $errors['author'] = 'Author is required';
        }
        
        if (!empty($errors)) {
            $this->errorResponse('Validation failed', 422, $errors);
            return;
        }
        
        // Crear post
        $newPost = [
            'id' => count($this->posts) + 1,
            'title' => $data['title'],
            'content' => $data['content'],
            'author' => $data['author'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->createdResponse($newPost, 'Post created successfully');
    }
    
    /**
     * PUT /api/posts/{id}
     * Actualizar post existente
     */
    public function update(int $id): void
    {
        if (!isset($this->posts[$id])) {
            $this->errorResponse('Post not found', 404);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $updatedPost = array_merge($this->posts[$id], $data);
        $updatedPost['updated_at'] = date('Y-m-d H:i:s');
        
        $this->successResponse($updatedPost, 'Post updated successfully');
    }
    
    /**
     * DELETE /api/posts/{id}
     * Eliminar post
     */
    public function destroy(int $id): void
    {
        if (!isset($this->posts[$id])) {
            $this->errorResponse('Post not found', 404);
            return;
        }
        
        // Simulación de eliminación
        $this->noContentResponse();
    }
}
```

**Rutas (`routes/api.php`):**

```php
Router::get('/posts', 'PostsController@index');
Router::get('/posts/{id}', 'PostsController@show');
Router::post('/posts', 'PostsController@store');
Router::put('/posts/{id}', 'PostsController@update');
Router::delete('/posts/{id}', 'PostsController@destroy');
```

**Pruebas con cURL:**

```bash
# Listar posts
curl http://localhost:8000/api/posts

# Filtrar por autor
curl http://localhost:8000/api/posts?author=Juan

# Obtener post específico
curl http://localhost:8000/api/posts/1

# Crear post
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -d '{"title":"Nuevo Post","content":"Contenido...","author":"Pedro"}'

# Actualizar post
curl -X PUT http://localhost:8000/api/posts/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Título Actualizado"}'

# Eliminar post
curl -X DELETE http://localhost:8000/api/posts/1
```

---

### Ejemplo 2: Dashboard Web con Vistas

```php
<?php

namespace NatanPHP\App\Web\Controllers;

class DashboardController extends Controller
{
    /**
     * GET /dashboard
     * Página principal del dashboard
     */
    public function index(): string
    {
        $stats = [
            'users' => 1250,
            'posts' => 3420,
            'comments' => 8950,
            'revenue' => 45600.50,
        ];
        
        $recentActivity = [
            ['user' => 'Juan', 'action' => 'Created post', 'time' => '5 min ago'],
            ['user' => 'María', 'action' => 'Commented', 'time' => '12 min ago'],
            ['user' => 'Pedro', 'action' => 'Logged in', 'time' => '1 hour ago'],
        ];
        
        return $this->view('dashboard/index', [
            'stats' => $stats,
            'activity' => $recentActivity,
        ]);
    }
    
    /**
     * GET /dashboard/profile
     * Perfil del usuario
     */
    public function profile(): string
    {
        $user = [
            'name' => 'Jhonatan Fernandez',
            'email' => 'jhonatan@example.com',
            'role' => 'Admin',
            'joined' => '2024-01-15',
        ];
        
        return $this->view('dashboard/profile', compact('user'));
    }
    
    /**
     * POST /dashboard/profile
     * Actualizar perfil
     */
    public function updateProfile(): void
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        // Validar
        if (empty($name) || empty($email)) {
            $this->response('Name and email are required', 400);
            return;
        }
        
        // Simular actualización
        // En producción guardarías en DB
        
        $this->redirect('/dashboard/profile?success=1');
    }
}
```

**Rutas (`routes/web.php`):**

```php
Router::get('/dashboard', 'DashboardController@index');
Router::get('/dashboard/profile', 'DashboardController@profile');
Router::post('/dashboard/profile', 'DashboardController@updateProfile');
```

---

## Resumen

### Controllers Principales

| Tipo | Clase Base | Ubicación | Uso |
|------|-----------|-----------|-----|
| **Web** | `Controller` | `app/Web/Controllers/` | Aplicaciones web con HTML |
| **API** | `ApiController` | `app/Api/Controllers/` | APIs REST con JSON |

### Métodos Comunes

#### Web Controller
- `view($view, $data)` - Renderizar vistas
- `response($content, $status)` - Respuestas HTTP
- `redirect($url)` - Redirecciones

#### API Controller
- `successResponse($data, $message, $status)` - Respuestas exitosas
- `errorResponse($message, $status, $errors)` - Respuestas de error
- `createdResponse($data, $message)` - Recurso creado (201)
- `noContentResponse()` - Sin contenido (204)

### Convenciones RESTful

- `index()` → GET `/resource`
- `show($id)` → GET `/resource/{id}`
- `store()` → POST `/resource`
- `update($id)` → PUT `/resource/{id}`
- `destroy($id)` → DELETE `/resource/{id}`

---

## Próximos Pasos

- 📘 [Requests](basics/requests.md) - Manejo de peticiones HTTP
- 📘 [Routing](basics/routing.md) - Sistema de rutas
- 📘 [Helpers](digging-deeper/helpers.md) - Funciones auxiliares

---

**¿Tienes dudas?** Consulta la [documentación completa](/) o visita el [repositorio en GitHub](https://github.com/jhonatanfdez/natan-php).
