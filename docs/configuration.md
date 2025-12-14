# Configuración

> Gestiona la configuración de tu aplicación NatanPHP de forma segura y organizada

---

## Introducción

NatanPHP utiliza un sistema de configuración simple y efectivo basado en **variables de entorno** (`.env`). Este enfoque te permite mantener configuraciones sensibles fuera del código y adaptar tu aplicación a diferentes entornos sin cambiar una línea de código.

### Filosofía de Configuración

- 🔐 **Seguridad primero** - Credenciales fuera del repositorio
- 🌍 **Multi-entorno** - Desarrollo, staging, producción
- 📝 **Archivo .env** - Configuración centralizada
- 🎯 **Variables de entorno** - Estándar de la industria
- 🔧 **Fácil de cambiar** - Sin modificar código

---

## Tabla de Contenidos

- [Archivo .env](#archivo-env)
- [Variables de Entorno](#variables-de-entorno)
- [Acceder a la Configuración](#acceder-a-la-configuración)
- [Entornos](#entornos)
- [Mejores Prácticas](#mejores-prácticas)

---

## Archivo .env

### Ubicación

El archivo `.env` se encuentra en la raíz del proyecto:

```
NatanPHP-Framework/
├── docroot/
│   ├── .env          ← Archivo de configuración
│   ├── .env.example  ← Plantilla de ejemplo
│   ├── core/
│   ├── app/
│   └── public/
```

### Crear tu .env

**Primera vez:**

```bash
# Copiar desde el ejemplo
cp .env.example .env

# Editar con tus valores
nano .env
# o
code .env
```

### Estructura del .env

```bash
# .env - Configuración de NatanPHP

# =============================================================================
# APLICACIÓN
# =============================================================================
APP_NAME="NatanPHP Framework"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8080

# =============================================================================
# BASE DE DATOS
# =============================================================================
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=natan_db
DB_USERNAME=root
DB_PASSWORD=

# =============================================================================
# SESIONES
# =============================================================================
SESSION_DRIVER=file
SESSION_LIFETIME=120

# =============================================================================
# CACHE
# =============================================================================
CACHE_DRIVER=file
CACHE_TTL=3600

# =============================================================================
# CORREO ELECTRÓNICO
# =============================================================================
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@natanphp.com
MAIL_FROM_NAME="${APP_NAME}"

# =============================================================================
# SERVICIOS EXTERNOS
# =============================================================================
STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=

MAILGUN_DOMAIN=
MAILGUN_SECRET=

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

# =============================================================================
# DESARROLLO
# =============================================================================
LOG_CHANNEL=single
LOG_LEVEL=debug
```

---

## Variables de Entorno

### Variables de Aplicación

#### APP_NAME
**Tipo:** `string`  
**Descripción:** Nombre de tu aplicación  
**Ejemplo:**
```bash
APP_NAME="Mi Aplicación Genial"
```

#### APP_ENV
**Tipo:** `string`  
**Valores:** `development`, `staging`, `production`  
**Descripción:** Entorno de ejecución  
**Ejemplo:**
```bash
APP_ENV=production
```

#### APP_DEBUG
**Tipo:** `boolean`  
**Valores:** `true`, `false`  
**Descripción:** Activar modo debug (mostrar errores detallados)  
**Ejemplo:**
```bash
APP_DEBUG=false  # Siempre false en producción
```

#### APP_URL
**Tipo:** `string`  
**Descripción:** URL base de tu aplicación  
**Ejemplo:**
```bash
# Desarrollo
APP_URL=http://localhost:8080

# DDEV
APP_URL=https://natanphp-framework.ddev.site

# Producción
APP_URL=https://miapp.com
```

---

### Variables de Base de Datos

#### DB_CONNECTION
**Tipo:** `string`  
**Valores:** `mysql`, `pgsql`, `sqlite`  
**Descripción:** Tipo de base de datos  
**Ejemplo:**
```bash
DB_CONNECTION=mysql
```

#### DB_HOST
**Tipo:** `string`  
**Descripción:** Host del servidor de BD  
**Ejemplo:**
```bash
# Local
DB_HOST=localhost

# Docker
DB_HOST=mysql

# Producción
DB_HOST=db.produccion.com
```

#### DB_PORT
**Tipo:** `integer`  
**Descripción:** Puerto de la base de datos  
**Ejemplo:**
```bash
DB_PORT=3306  # MySQL
DB_PORT=5432  # PostgreSQL
```

#### DB_DATABASE
**Tipo:** `string`  
**Descripción:** Nombre de la base de datos  
**Ejemplo:**
```bash
DB_DATABASE=natan_db
```

#### DB_USERNAME
**Tipo:** `string`  
**Descripción:** Usuario de la base de datos  
**Ejemplo:**
```bash
DB_USERNAME=root
```

#### DB_PASSWORD
**Tipo:** `string`  
**Descripción:** Contraseña de la base de datos  
**Ejemplo:**
```bash
DB_PASSWORD=secreto123

# Vacío en desarrollo local
DB_PASSWORD=
```

---

### Variables de Servicios Externos

#### Stripe (Pagos)
```bash
STRIPE_PUBLIC_KEY=pk_test_123456789
STRIPE_SECRET_KEY=sk_test_987654321
```

#### Mailgun (Email)
```bash
MAILGUN_DOMAIN=mg.miapp.com
MAILGUN_SECRET=key-abc123xyz
```

#### AWS (Cloud Storage)
```bash
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=mi-bucket-produccion
```

---

## Acceder a la Configuración

### Helper env()

Usa el helper `env()` para acceder a variables de entorno:

```php
// Obtener valor simple
$appName = env('APP_NAME');

// Con valor por defecto
$debugMode = env('APP_DEBUG', false);
$apiKey = env('STRIPE_SECRET_KEY', 'clave-default');

// Conexión a base de datos
$dbHost = env('DB_HOST', 'localhost');
$dbName = env('DB_DATABASE', 'mi_base_datos');
$dbUser = env('DB_USER', 'root');
$dbPass = env('DB_PASSWORD', '');
```

### Conversión Automática de Tipos

El helper `env()` convierte automáticamente ciertos valores:

```bash
# En .env
APP_DEBUG=true
CACHE_ENABLED=false
MAX_CONNECTIONS=100
API_KEY=null
SECRET=empty
```

```php
// En PHP
env('APP_DEBUG');        // boolean: true
env('CACHE_ENABLED');    // boolean: false
env('MAX_CONNECTIONS');  // string: "100" (no se convierte a int)
env('API_KEY');          // null
env('SECRET');           // string: ""
```

**Valores especiales:**
- `"true"` o `"(true)"` → `true` (boolean)
- `"false"` o `"(false)"` → `false` (boolean)
- `"null"` o `"(null)"` → `null`
- `"empty"` o `"(empty)"` → `""` (string vacío)

---

## Entornos

### Desarrollo (Development)

Configuración para tu máquina local:

```bash
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_HOST=localhost
DB_DATABASE=natan_dev
DB_USERNAME=root
DB_PASSWORD=

LOG_LEVEL=debug
```

**Características:**
- ✅ Debug activado (errores detallados)
- ✅ Base de datos local
- ✅ Logs completos
- ✅ Sin optimizaciones

---

### Staging (Pruebas)

Configuración para servidor de pruebas:

```bash
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.miapp.com

DB_HOST=staging-db.miapp.com
DB_DATABASE=natan_staging
DB_USERNAME=staging_user
DB_PASSWORD=password_staging

LOG_LEVEL=info
```

**Características:**
- ✅ Similar a producción
- ✅ Debug activado para pruebas
- ✅ Base de datos de pruebas
- ✅ Logs informativos

---

### Producción (Production)

Configuración para servidor en vivo:

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miapp.com

DB_HOST=prod-db.miapp.com
DB_DATABASE=natan_production
DB_USERNAME=prod_user
DB_PASSWORD=SuperSecurePassword123!

# Servicios reales
STRIPE_SECRET_KEY=sk_live_RealKey123
MAILGUN_SECRET=key-RealSecret456

LOG_LEVEL=error
```

**Características:**
- ⚠️ Debug DESACTIVADO (no mostrar errores)
- 🔐 Credenciales reales y seguras
- 📊 Solo logs de errores
- ⚡ Optimizaciones activadas

> ⚠️ **CRÍTICO:** En producción, `APP_DEBUG` debe ser **siempre `false`**

---

## Mejores Prácticas

### ✅ Hacer

**1. Mantén .env fuera del repositorio**

```bash
# .gitignore
.env
.env.local
.env.*.local
```

**2. Usa .env.example como plantilla**

```bash
# .env.example (SÍ incluir en git)
APP_NAME="NatanPHP Framework"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_HOST=localhost
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña

STRIPE_SECRET_KEY=tu_clave_aqui
```

**3. Documenta cada variable**

```bash
# .env.example
# Nombre de la aplicación (aparece en emails, títulos, etc.)
APP_NAME="NatanPHP Framework"

# Entorno: development, staging, production
APP_ENV=development

# Clave secreta de Stripe (obtener desde dashboard.stripe.com)
STRIPE_SECRET_KEY=sk_test_123456789
```

**4. Usa valores por defecto seguros**

```php
// ✅ Bueno - Siempre con default
$debug = env('APP_DEBUG', false);  // Default: false (seguro)
$dbHost = env('DB_HOST', 'localhost');

// ❌ Evitar - Sin default
$debug = env('APP_DEBUG');  // Puede ser null
```

**5. Valida configuraciones críticas**

```php
// Al iniciar la aplicación
$requiredVars = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];

foreach ($requiredVars as $var) {
    if (!env($var)) {
        die("Error: La variable {$var} no está configurada en .env");
    }
}
```

---

### ❌ No Hacer

**1. NO hagas commit del .env**

```bash
# ❌ NUNCA
git add .env
git commit -m "Agregando configuración"

# ✅ Verificar que esté en .gitignore
cat .gitignore | grep .env
```

**2. NO pongas credenciales en el código**

```php
// ❌ MAL - Hardcoded
$dbPassword = 'password123';
$stripeKey = 'sk_live_abc123';

// ✅ BIEN - Desde .env
$dbPassword = env('DB_PASSWORD');
$stripeKey = env('STRIPE_SECRET_KEY');
```

**3. NO uses debug en producción**

```bash
# ❌ PELIGROSO en producción
APP_DEBUG=true

# ✅ CORRECTO en producción
APP_DEBUG=false
```

**4. NO compartas el .env entre entornos**

```bash
# ❌ MAL - Usar mismo .env en dev y prod
# Cada entorno debe tener su propio .env

# ✅ BIEN - .env diferente por entorno
# dev:  .env con DB local, debug true
# prod: .env con DB remota, debug false
```

---

## Ejemplo Completo

### Estructura de Archivos

```
proyecto/
├── .env                    ← Tu configuración (NO en git)
├── .env.example            ← Plantilla (SÍ en git)
├── .env.testing            ← Para tests
├── .gitignore              ← Ignora .env
└── docroot/
    └── core/
        └── helpers.php     ← Helper env()
```

### .env de Desarrollo

```bash
# .env (local)
APP_NAME="Mi Blog"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=blog_dev
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=log  # Solo guardar en logs, no enviar
```

### .env de Producción

```bash
# .env (servidor)
APP_NAME="Mi Blog"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miblog.com

DB_HOST=mysql.produccion.com
DB_PORT=3306
DB_DATABASE=blog_prod
DB_USERNAME=blog_user
DB_PASSWORD=SuperSecurePassword!2024

MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.abc123xyz

STRIPE_SECRET_KEY=sk_live_RealProductionKey123
```

### Usar en tu Código

```php
// config/database.php
class Database
{
    private $pdo;
    
    public function __construct()
    {
        $host = env('DB_HOST', 'localhost');
        $db = env('DB_DATABASE', 'natan_db');
        $user = env('DB_USERNAME', 'root');
        $pass = env('DB_PASSWORD', '');
        $port = env('DB_PORT', 3306);
        
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        
        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // En desarrollo, mostrar error
            if (env('APP_DEBUG', false)) {
                die('Error de conexión: ' . $e->getMessage());
            }
            
            // En producción, mensaje genérico
            die('Error de conexión a la base de datos');
        }
    }
}
```

---

## Configuración Futura

En versiones futuras de NatanPHP, el sistema de configuración se expandirá:

### Archivos de Configuración (Planeado)

```
docroot/
└── config/
    ├── app.php         # Configuración de aplicación
    ├── database.php    # Configuración de BD
    ├── cache.php       # Configuración de cache
    ├── mail.php        # Configuración de email
    └── services.php    # APIs externas
```

### Helper config() Mejorado (Futuro)

```php
// Acceso con notación de puntos
$appName = config('app.name');
$dbHost = config('database.connections.mysql.host');
$cacheDriver = config('cache.default');

// Configuración en arrays
$mailConfig = config('mail');
/*
[
    'driver' => 'smtp',
    'host' => 'smtp.mailtrap.io',
    'port' => 2525,
    ...
]
*/
```

---

## Siguientes Pasos

Ahora que entiendes la configuración, continúa con:

- [📁 Directory Structure](./directory-structure.md) - Estructura de carpetas del framework
- [🛠️ Helpers](./digging-deeper/helpers.md) - Helper `env()` en detalle
- [🚀 Installation](./installation.md) - Configurar .env durante instalación

---

## Ayuda y Soporte

¿Problemas con la configuración?

- [Ver .env.example](https://github.com/jhonatanfdez/natan-php/blob/main/docroot/.env.example)
- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Guía de instalación](./installation.md#configuración-inicial)

---

> 🔐 **Seguridad:** Nunca compartas tu archivo `.env` públicamente. Contiene credenciales sensibles.

> 📝 **Tip:** Usa contraseñas generadas aleatoriamente para producción, no contraseñas simples.

> 🌍 **Multi-entorno:** Crea un .env diferente para cada servidor (dev, staging, prod).
