# Instalación

> Guía completa para instalar y configurar NatanPHP Framework

---

## Requisitos del Sistema

Antes de instalar NatanPHP, asegúrate de que tu sistema cumple con los siguientes requisitos:

### Requisitos Obligatorios

- **PHP:** >= 8.0
- **Composer:** >= 2.0
- **Extensiones PHP:**
  - `mbstring`
  - `json`
  - `fileinfo`

### Requisitos Recomendados

- **Servidor Web:** Apache 2.4+ o Nginx 1.18+
- **Base de Datos:** MySQL 5.7+ / PostgreSQL 11+ / SQLite 3.8+
- **Git:** Para clonar el repositorio

### Verificar Requisitos

Puedes verificar tu versión de PHP con:

```bash
php -v
# PHP 8.0.0 o superior
```

Verificar Composer:

```bash
composer --version
# Composer version 2.0.0 o superior
```

---

## Instalación

### Opción 1: Clonar desde GitHub (Recomendado)

Esta es la forma más rápida de empezar con NatanPHP:

```bash
# 1. Clonar el repositorio
git clone https://github.com/jhonatanfdez/natan-php.git

# 2. Navegar al directorio del proyecto
cd natan-php/docroot

# 3. Instalar dependencias con Composer
composer install

# 4. Copiar el archivo de configuración
cp .env.example .env

# 5. Iniciar el servidor de desarrollo
php natan serve
```

Tu aplicación estará disponible en `http://localhost:8000` 🎉

### Opción 2: Crear Proyecto Nuevo (Futuro)

> 💡 **Próximamente:** Podrás instalar NatanPHP vía Composer:
> ```bash
> composer create-project natanphp/natanphp mi-proyecto
> ```

---

## Configuración del Entorno

### Archivo .env

El archivo `.env` contiene la configuración de tu aplicación. Copia el ejemplo y ajusta según tus necesidades:

```bash
cp .env.example .env
```

**Ejemplo de configuración básica:**

```bash
# Configuración de la Aplicación
APP_NAME=NatanPHP
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos (futuro)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=natanphp
DB_USERNAME=root
DB_PASSWORD=

# Cache (futuro)
CACHE_DRIVER=file
```

### Permisos de Archivos

Si estás en Linux/macOS, asegúrate de que el directorio `storage/` tenga permisos de escritura:

```bash
chmod -R 775 storage/
```

---

## Configuración del Servidor Web

### Servidor de Desarrollo PHP (Recomendado para desarrollo)

NatanPHP incluye un CLI tool que facilita el desarrollo:

```bash
php natan serve
```

**Opciones disponibles:**

```bash
php natan serve              # Puerto 8000 por defecto
php natan serve --port=8080  # Puerto personalizado (futuro)
php natan serve --host=0.0.0.0  # Accesible desde red local (futuro)
```

### Apache

Si prefieres usar Apache, configura un VirtualHost:

```apache
<VirtualHost *:80>
    ServerName natanphp.local
    DocumentRoot /ruta/a/natan-php/docroot/public
    
    <Directory /ruta/a/natan-php/docroot/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/natanphp-error.log
    CustomLog ${APACHE_LOG_DIR}/natanphp-access.log combined
</VirtualHost>
```

**Importante:** El `DocumentRoot` debe apuntar a la carpeta `public/`.

### Nginx

Configuración básica para Nginx:

```nginx
server {
    listen 80;
    server_name natanphp.local;
    root /ruta/a/natan-php/docroot/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Verificar la Instalación

Una vez instalado, verifica que todo funcione correctamente:

### 1. Verificar la versión del framework

```bash
php -r "require 'bootstrap.php'; echo version();"
# Debe mostrar: v0.2.0
```

### 2. Acceder a la página de inicio

Abre tu navegador y ve a:
- **Servidor PHP:** `http://localhost:8000`
- **Apache/Nginx:** `http://natanphp.local`

Deberías ver la página de bienvenida de NatanPHP.

### 3. Ejecutar los tests

```bash
./vendor/bin/phpunit
```

Deberías ver:

```
OK (140 tests, 320 assertions)
```

---

## Solución de Problemas

### Error: "composer: command not found"

**Solución:** Instala Composer desde [getcomposer.org](https://getcomposer.org/download/)

### Error: "PHP version required >= 8.0"

**Solución:** Actualiza PHP a versión 8.0 o superior:

```bash
# Ubuntu/Debian
sudo apt-get install php8.0

# macOS (Homebrew)
brew install php@8.0

# Windows
# Descarga desde php.net
```

### Error: "Port 8000 already in use"

**Solución:** El puerto está ocupado. El comando `natan serve` intentará liberar el puerto automáticamente, o usa otro puerto (futuro):

```bash
php natan serve --port=8080
```

### Error: "Class not found"

**Solución:** Regenera el autoloader de Composer:

```bash
composer dump-autoload
```

### Servidor muestra carpetas en lugar de la app

**Solución:** Asegúrate de que tu `DocumentRoot` apunta a la carpeta `public/`, no a la raíz del proyecto.

---

## Próximos Pasos

¡Felicidades! 🎉 Has instalado NatanPHP exitosamente.

### ¿Qué sigue?

1. **Aprende la estructura:** [Estructura de Directorios](directory-structure.md)
2. **Configura tu app:** [Configuración](configuration.md)
3. **Crea tu primera ruta:** [Routing Básico](basics/routing.md)
4. **Entiende los controllers:** [Controllers](basics/controllers.md)

### Recursos Útiles

- [Guía de Routing](basics/routing.md) - Aprende a crear rutas
- [Clase Request](basics/requests.md) - Maneja peticiones HTTP
- [Helpers](digging-deeper/helpers.md) - Funciones útiles
- [Testing](testing/getting-started.md) - Escribe tests

---

## Ayuda y Soporte

¿Tienes problemas con la instalación?

- [Reportar un problema](https://github.com/jhonatanfdez/natan-php/issues)
- [Ver documentación completa](/)
- [Revisar issues resueltos](https://github.com/jhonatanfdez/natan-php/issues?q=is%3Aissue+is%3Aclosed)

---

> 💡 **Tip:** Para desarrollo rápido, usa `php natan serve` en lugar de configurar Apache o Nginx.

> ⚠️ **Advertencia:** El servidor de desarrollo PHP NO debe usarse en producción. Para producción, usa Apache o Nginx.
