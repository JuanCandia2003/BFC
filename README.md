# Ballet Folclórico de Cochabamba (BFC)

Sistema de gestión integral para el Ballet Folclórico de Cochabamba.

## Descripción

Plataforma web para la administración de:
- Bailarines y registro de miembros
- Catálogo de vestuarios
- Gestión de préstamos de vestuario
- Control de funciones y presentaciones
- Portal público de presentación institucional

## Tech Stack

- **Backend:** PHP 8.2
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Base de Datos:** MySQL 8.0
- **Contenedores:** Docker & Docker Compose

## Estructura del Proyecto

```
BFC/
├── bootstrap.php           # Punto de entrada de la aplicación
├── config/
│   └── config.php          # Configuración centralizada
├── public/                 # Archivos accesibles desde el navegador
│   ├── index.php           # Página principal
│   ├── admin.php           # Panel de administración
│   ├── login.php           # Inicio de sesión
│   ├── bailarin.php        # Portal de bailarines
│   ├── css/                # Estilos CSS
│   └── js/                 # JavaScript
├── src/
│   ├── Controllers/        # Lógica de negocio (MVC)
│   │   ├── Controller.php
│   │   ├── BailarinController.php
│   │   ├── PrestamoController.php
│   │   └── VestuarioController.php
│   ├── Database/           # Conexión a la base de datos
│   │   └── Connection.php  # Singleton PDO
│   ├── Helpers/            # Utilidades y helpers
│   │   ├── Auth.php       # Autenticación
│   │   ├── Database.php   # Query Builder
│   │   ├── Uploader.php   # Subida de archivos
│   │   └── functions.php  # Funciones globales
│   └── Views/              # Vistas del sistema
│       └── Admin/          # Vistas del admin
├── assets/                # Imágenes y archivos subidos
├── docker/                # Configuración de Docker
│   └── apache-config.conf # Configuración de Apache
├── includes/              # Compatibilidad legacy
│   └── db.php
├── mysql/                 # Scripts de base de datos
└── docker-compose.yml     # Servicios Docker
```

## Instalación y Ejecución

### Requisitos

- Docker y Docker Compose instalados

### 1. Iniciar los contenedores

```bash
docker-compose up -d
```

### 2. Acceder a los servicios

- **Aplicación**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
  - Usuario: `root`
  - Contraseña: `root`

### 3. Importar la base de datos

1. Accede a phpMyAdmin (http://localhost:8081)
2. Crea la base de datos `BFC`
3. Importa el archivo `mysql/bfc (1).sql`

### 4. Credenciales por defecto

**Admin:**
- Usuario: `admin`
- Contraseña: `admin123`

**Bailarín:**
- Email: `juan@bfc.com`
- Contraseña: `bailarin123`

## Uso

### Panel de Administración

Accede a `http://localhost:8080/admin.php` e inicia sesión con las credenciales de admin.

**Características:**
- Gestión de bailarines (agregar, editar, eliminar, resetear contraseña)
- Gestión de vestuarios (catálogo con imágenes)
- Gestión de funciones/eventos
- Aprobación y rechazo de préstamos de vestuarios
- Dashboard con estadísticas

### Portal de Bailarines

Accede a `http://localhost:8080/login.php` e inicia sesión como bailarín.

**Características:**
- Solicitar préstamos de vestuarios
- Ver historial de préstamos
- Actualizar perfil

## Comandos Docker útiles

```bash
# Iniciar servicios
docker-compose up -d

# Ver logs en tiempo real
docker-compose logs -f

# Detener servicios
docker-compose down

# Reiniciar un servicio específico
docker-compose restart web

# Ver contenedores activos
docker-compose ps
```

## Configuración

### Base de datos

La configuración está en `config/config.php`:

```php
define('DB_HOST', 'db');        // Host (nombre del servicio en docker-compose)
define('DB_NAME', 'BFC');       // Nombre de la base de datos
define('DB_USER', 'usuario');   // Usuario
define('DB_PASS', 'password'); // Contraseña
```

### Variables de entorno

Para producción, crea un archivo `.env` en la raíz del proyecto basado en `config/.env.example`.

## Arquitectura

El proyecto sigue una arquitectura **MVC simplificada**:

- **Models**: No implementados (usa consultas directas PDO)
- **Views**: Located in `src/Views/Admin/` y `public/`
- **Controllers**: Located in `src/Controllers/`

### Flujo de una petición

```
1. Usuario accede a public/admin.php
2. bootstrap.php carga config, DB connection y helpers
3. El archivo PHP procesa la lógica
4. Require las vistas de src/Views/Admin/
5. La vista se renderiza con los datos
```

## Seguridad

- La carpeta `public/` es el **document root** de Apache
- Archivos sensibles (`config/`, `src/`, `bootstrap.php`) no son accesibles desde el navegador
- Las contraseñas se hashean con `password_hash()` de PHP
- Sesiones seguras con `HttpOnly` y `SameSite`

## Troubleshooting

### Error de conexión a la base de datos

Asegúrate de que:
1. El contenedor `db` está corriendo: `docker-compose ps`
2. La base de datos está creada en phpMyAdmin
3. Las credenciales en `config/config.php` coinciden con `docker-compose.yml`

### Cambios no se reflejan

Limpia la caché del navegador o usa Ctrl+Shift+R

## Licencia

Privado - Ballet Folclórico de Cochabamba
