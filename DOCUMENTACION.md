# Tienda de Muebles Híbrida — Documentación del Proyecto

**Asignatura:** Desarrollo de Aplicaciones Web  
**Alumno:** Saulespin555  
**Fecha:** Mayo 2026  
**Tecnología principal:** PHP 8.3 / Laravel 13  

---

## Tabla de Contenidos

1. [Descripción General](#1-descripción-general)
2. [Arquitectura del Sistema](#2-arquitectura-del-sistema)
3. [Estructura del Proyecto](#3-estructura-del-proyecto)
4. [Módulo: API de Usuarios](#4-módulo-api-de-usuarios-api_usuarios)
5. [Módulo: API de Muebles](#5-módulo-api-de-muebles-api_muebles)
6. [Módulo: Tienda Principal](#6-módulo-tienda-principal-tienda_principal)
7. [Base de Datos](#7-base-de-datos)
8. [Sistema de Autenticación y Permisos](#8-sistema-de-autenticación-y-permisos)
9. [Flujos de Negocio](#9-flujos-de-negocio)
10. [API Reference](#10-api-reference)
11. [Instalación y Configuración](#11-instalación-y-configuración)
12. [Tecnologías Utilizadas](#12-tecnologías-utilizadas)

---

## 1. Descripción General

La **Tienda de Muebles Híbrida** es una aplicación web de comercio electrónico construida con una arquitectura orientada a microservicios. El sistema permite a los usuarios navegar un catálogo de muebles, gestionar un carrito de compras y realizar pedidos en línea.

El término "híbrido" hace referencia a que el sistema combina dos paradigmas:
- **APIs REST independientes** para la lógica de negocio (usuarios y catálogo de productos).
- **Aplicación web tradicional** (server-side rendering con Blade) que actúa como cliente y orquestador de dichas APIs.

### Funcionalidades Principales

| Funcionalidad | Descripción |
|---|---|
| Catálogo de muebles | Navegación con filtros por categoría, precio, color y material |
| Registro y login | Autenticación segura con tokens API |
| Carrito de compras | Añadir, modificar y eliminar productos |
| Proceso de compra | Checkout con datos de envío y método de pago |
| Historial de pedidos | Consulta de pedidos anteriores y su estado |
| Panel de administración | Gestión de productos y usuarios (rol Administrador) |
| Galería de imágenes | Soporte para múltiples imágenes por producto |
| Documentación OpenAPI | Swagger UI integrado en las APIs |

---

## 2. Arquitectura del Sistema

### Visión General

El sistema está compuesto por tres aplicaciones Laravel independientes que se comunican entre sí mediante peticiones HTTP:

```
┌─────────────────────────────────────────────────────────────┐
│                    NAVEGADOR DEL USUARIO                     │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTP (puerto 8000)
┌──────────────────────────▼──────────────────────────────────┐
│              TIENDA PRINCIPAL (Laravel + Blade)              │
│                    tienda_principal/                         │
│                                                             │
│  Controllers: Auth, Muebles, Carrito, Pedido, Admin, Profile │
│  Services:    MueblesService, UsuariosService                │
│  Models:      Carrito, CarritoItem, Pedido, PedidoItem       │
│  DB:          tienda_principal (carritos, pedidos)           │
└──────────┬──────────────────────────────────┬───────────────┘
           │ HTTP (puerto 8001)               │ HTTP (puerto 8002)
┌──────────▼────────────────┐   ┌─────────────▼───────────────┐
│   API USUARIOS (REST)     │   │    API MUEBLES (REST)        │
│     api_usuarios/         │   │      api_muebles/            │
│                           │   │                              │
│  Auth: registro, login    │   │  Productos, Categorías       │
│  Roles: Admin/Gestor/     │   │  Galería de imágenes         │
│         Cliente           │   │  Gestión de stock            │
│  DB: tienda_usuarios      │   │  DB: tienda_muebles          │
└───────────────────────────┘   └──────────────────────────────┘
```

### Decisiones de Diseño

**Separación de responsabilidades:** Cada microservicio tiene un dominio bien definido. La API de Usuarios no conoce los productos y la API de Muebles no conoce los usuarios.

**Snapshots de datos en pedidos:** Al registrar un pedido o un ítem de carrito, se guarda una copia (snapshot) del nombre, precio e imagen del producto en ese momento. Esto garantiza que un cambio futuro en el catálogo no altere los pedidos históricos.

**Autenticación centralizada en api_usuarios:** El token Sanctum emitido por esta API se reutiliza en la Tienda Principal (almacenado en sesión) para autenticar también las llamadas a api_muebles.

**Abilities granulares:** En lugar de una comprobación de rol simple, el sistema usa un sistema de permisos (`abilities`) asociado a cada token, lo que permite control fino sobre qué puede hacer cada usuario.

---

## 3. Estructura del Proyecto

```
03-tienda-muebles-hibrido/
│
├── api_muebles/                    # Microservicio: Catálogo de productos
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── ProductoController.php
│   │   │   ├── CategoriaController.php
│   │   │   └── ImagenController.php
│   │   └── Models/
│   │       ├── Producto.php
│   │       ├── Categoria.php
│   │       ├── Galeria.php
│   │       └── Imagen.php
│   ├── database/migrations/
│   └── routes/api.php
│
├── api_usuarios/                   # Microservicio: Autenticación y usuarios
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── AuthController.php
│   │   │   └── UserController.php
│   │   └── Models/
│   │       ├── User.php
│   │       └── Role.php
│   ├── database/migrations/
│   └── routes/api.php
│
└── tienda_principal/               # Aplicación web cliente
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── AuthController.php
    │   │   ├── MueblesController.php
    │   │   ├── CarritoController.php
    │   │   ├── PedidoController.php
    │   │   ├── ProfileController.php
    │   │   └── AdminController.php
    │   ├── Models/
    │   │   ├── Carrito.php
    │   │   ├── CarritoItem.php
    │   │   ├── Pedido.php
    │   │   └── PedidoItem.php
    │   └── Services/
    │       ├── MueblesService.php
    │       └── UsuariosService.php
    ├── database/migrations/
    ├── resources/views/            # Plantillas Blade
    │   ├── admin/
    │   ├── auth/
    │   ├── carrito/
    │   ├── muebles/
    │   ├── pedidos/
    │   └── profile/
    └── routes/web.php
```

---

## 4. Módulo: API de Usuarios (`api_usuarios`)

### Descripción

Microservicio REST responsable de gestionar la identidad de los usuarios: registro, autenticación y administración de cuentas. Es la única fuente de verdad sobre quién es cada usuario y qué permisos tiene.

### Modelos

#### `Role`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `nombre` | string | Nombre del rol (Administrador, Gestor, Cliente) |

#### `User`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `rol_id` | bigint FK | Referencia a la tabla roles |
| `nombre` | string | Nombre del usuario |
| `apellidos` | string | Apellidos |
| `email` | string unique | Correo electrónico |
| `password` | string | Contraseña hasheada (bcrypt) |
| `intentos_fallidos` | integer | Contador de intentos de login fallidos |
| `bloqueado_hasta` | datetime nullable | Fecha hasta la que la cuenta está bloqueada |

**Relaciones:**
- `User belongsTo Role`
- `User hasMany PersonalAccessToken` (Sanctum)

### Controladores

#### `AuthController`

| Método | Ruta | Descripción | Auth |
|---|---|---|---|
| POST | `/api/v1/registrar` | Registra un nuevo usuario con rol Cliente | No |
| POST | `/api/v1/login` | Inicia sesión, devuelve token y abilities | No |
| GET | `/api/v1/perfil` | Devuelve los datos del usuario autenticado | Sí |
| POST | `/api/v1/logout` | Invalida el token actual | Sí |

**Respuesta de login exitoso:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 5,
    "nombre": "Juan",
    "apellidos": "García",
    "email": "juan@example.com",
    "rol": "Cliente"
  },
  "abilities": ["perfil.ver", "muebles.ver", "carrito.gestionar", "pedidos.crear"]
}
```

#### `UserController` (solo Administrador)

| Método | Ruta | Ability requerida |
|---|---|---|
| GET | `/api/v1/usuarios` | `usuarios.ver` |
| GET | `/api/v1/usuarios/{id}` | `usuarios.ver` |
| POST | `/api/v1/usuarios` | `usuarios.crear` |
| PUT | `/api/v1/usuarios/{id}` | `usuarios.editar` |
| DELETE | `/api/v1/usuarios/{id}` | `usuarios.eliminar` |

---

## 5. Módulo: API de Muebles (`api_muebles`)

### Descripción

Microservicio REST responsable de gestionar el catálogo de productos: muebles, categorías y galería de imágenes. Controla también el stock disponible de cada producto.

### Modelos

#### `Producto`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `nombre` | string | Nombre del mueble |
| `descripcion` | text | Descripción detallada |
| `precio` | decimal(10,2) | Precio en euros |
| `stock` | integer | Unidades disponibles |
| `materiales` | string nullable | Materiales de fabricación |
| `dimensiones` | string nullable | Alto × Ancho × Profundo |
| `color_principal` | string nullable | Color predominante |
| `destacado` | boolean | Aparece en portada |
| `imagen_principal` | string nullable | Ruta a la imagen principal |

**Relaciones:**
- `Producto belongsToMany Categoria` (tabla pivot: `categoria_producto`)
- `Producto hasOne Galeria`

#### `Categoria`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `nombre` | string | Nombre de la categoría |
| `descripcion` | text nullable | Descripción |

**Relaciones:**
- `Categoria belongsToMany Producto`

#### `Galeria`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `producto_id` | bigint FK unique | Relación 1:1 con Producto |

**Relaciones:**
- `Galeria belongsTo Producto`
- `Galeria hasMany Imagen`

#### `Imagen`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `galeria_id` | bigint FK | Referencia a la galería |
| `ruta` | string | Ruta del archivo de imagen |
| `es_principal` | boolean | Indica si es la imagen destacada |
| `orden` | integer | Posición en la galería |

### Controladores

#### `ProductoController`

| Método | Ruta | Descripción | Auth / Ability |
|---|---|---|---|
| GET | `/api/v1/muebles` | Lista productos con filtros | No |
| GET | `/api/v1/muebles/{id}` | Detalle de un producto | No |
| POST | `/api/v1/muebles` | Crear producto | `muebles.crear` |
| PUT | `/api/v1/muebles/{id}` | Actualizar producto | `muebles.editar` |
| DELETE | `/api/v1/muebles/{id}` | Eliminar producto | `muebles.eliminar` |
| PATCH | `/api/v1/muebles/{id}/stock` | Descontar stock | `pedidos.crear` o `muebles.editar` |

**Parámetros de filtrado (GET /muebles):**

| Parámetro | Tipo | Descripción |
|---|---|---|
| `buscar` | string | Búsqueda en nombre y descripción |
| `categoria` | integer | Filtrar por categoría |
| `precio_min` | decimal | Precio mínimo |
| `precio_max` | decimal | Precio máximo |
| `color` | string | Filtrar por color principal |
| `material` | string | Filtrar por material |
| `destacado` | boolean | Solo productos destacados |
| `per_page` | integer | Items por página (paginación) |

#### `CategoriaController`

| Método | Ruta | Descripción | Auth |
|---|---|---|---|
| GET | `/api/v1/categorias` | Lista todas las categorías | No |
| GET | `/api/v1/categorias/{id}` | Detalle de categoría | No |
| POST | `/api/v1/categorias` | Crear categoría | `muebles.crear` |
| PUT | `/api/v1/categorias/{id}` | Actualizar categoría | `muebles.editar` |
| DELETE | `/api/v1/categorias/{id}` | Eliminar categoría | `muebles.eliminar` |

#### `ImagenController`

| Método | Ruta | Descripción | Auth |
|---|---|---|---|
| POST | `/api/v1/muebles/{id}/imagenes` | Subir imagen al producto | `muebles.editar` |
| PUT | `/api/v1/imagenes/{id}/principal` | Establecer como imagen principal | `muebles.editar` |
| DELETE | `/api/v1/imagenes/{id}` | Eliminar imagen | `muebles.editar` |

---

## 6. Módulo: Tienda Principal (`tienda_principal`)

### Descripción

Aplicación web que actúa como capa de presentación y orquestación. Renderiza las vistas con Blade y se comunica con las dos APIs mediante dos servicios dedicados.

### Modelos

#### `Carrito`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `usuario_id` | integer | ID del usuario en api_usuarios |

**Relaciones:**
- `Carrito hasMany CarritoItem`
- Método `total()`: suma de subtotales de todos los ítems

#### `CarritoItem`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `carrito_id` | bigint FK | Referencia al carrito |
| `producto_id` | integer | ID del producto en api_muebles |
| `nombre` | string | Snapshot del nombre al añadir |
| `precio` | decimal(10,2) | Snapshot del precio al añadir |
| `cantidad` | integer | Unidades |
| `imagen` | string nullable | Snapshot de la imagen al añadir |

**Restricción:** `unique(carrito_id, producto_id)` — un producto no puede duplicarse en el carrito.

#### `Pedido`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `usuario_id` | integer | ID del usuario en api_usuarios |
| `estado` | enum | pendiente / pagado / enviado / entregado / cancelado |
| `total` | decimal(10,2) | Importe total del pedido |
| `nombre_cliente` | string | Nombre del destinatario |
| `email_cliente` | string | Email de contacto |
| `direccion_entrega` | text | Dirección de envío |
| `telefono` | string nullable | Teléfono de contacto |
| `metodo_pago` | string | Método de pago seleccionado |

**Relaciones:**
- `Pedido hasMany PedidoItem`

#### `PedidoItem`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint PK | Identificador |
| `pedido_id` | bigint FK | Referencia al pedido |
| `producto_id` | integer | ID del producto en api_muebles |
| `nombre` | string | Snapshot del nombre al comprar |
| `precio` | decimal(10,2) | Snapshot del precio al comprar |
| `cantidad` | integer | Unidades compradas |

### Servicios

Los servicios encapsulan toda la lógica de comunicación HTTP con las APIs externas, utilizando el cliente HTTP de Laravel (`Http::get/post/put/delete`).

#### `MueblesService`

```
URL base: env('API_MUEBLES_URL', 'http://127.0.0.1:8002/api')
```

| Método | Descripción |
|---|---|
| `getAllMuebles($params)` | Lista de productos con filtros opcionales |
| `getMuebleById($id)` | Detalle de un producto |
| `getCategorias()` | Lista de categorías |
| `createMueble($data, $token)` | Crea un producto (requiere token) |
| `updateMueble($id, $data, $token)` | Actualiza un producto (requiere token) |
| `deleteMueble($id, $token)` | Elimina un producto (requiere token) |
| `updateStock($productoId, $cantidad, $token)` | Descuenta stock al completar una compra |

#### `UsuariosService`

```
URL base: env('API_USUARIOS_URL', 'http://127.0.0.1:8001/api')
```

| Método | Descripción |
|---|---|
| `register($data)` | Registra un nuevo usuario |
| `login($credentials)` | Inicia sesión y obtiene token |
| `getPerfil($token)` | Obtiene datos del usuario autenticado |
| `logout($token)` | Cierra sesión e invalida el token |
| `getUsuarios($token)` | Lista todos los usuarios (admin) |
| `getUsuarioById($id, $token)` | Obtiene un usuario por ID (admin) |
| `createUsuario($data, $token)` | Crea un usuario (admin) |
| `updateUsuario($id, $data, $token)` | Actualiza un usuario (admin) |
| `deleteUsuario($id, $token)` | Elimina un usuario (admin) |

### Controladores Web

#### `MueblesController`

| Ruta | Vista | Descripción |
|---|---|---|
| `GET /` | `home` | Portada con productos destacados y recientes |
| `GET /muebles` | `muebles/index` | Catálogo con filtros y paginación |
| `GET /muebles/{id}` | `muebles/show` | Detalle de producto |

#### `CarritoController` *(requiere sesión iniciada)*

| Ruta | Acción |
|---|---|
| `GET /carrito` | Ver carrito actual |
| `POST /carrito/agregar` | Añadir producto (crea snapshot) |
| `PATCH /carrito/{id}` | Cambiar cantidad de un ítem |
| `DELETE /carrito/{id}` | Eliminar un ítem del carrito |
| `DELETE /carrito` | Vaciar todo el carrito |

#### `PedidoController` *(requiere sesión iniciada)*

| Ruta | Acción |
|---|---|
| `GET /checkout` | Formulario de pago y envío |
| `POST /checkout` | Confirmar pedido (crea pedido y descuenta stock) |
| `GET /pedidos` | Historial de pedidos del usuario |
| `GET /pedidos/{id}` | Detalle de un pedido |

#### `AuthController`

| Ruta | Acción |
|---|---|
| `GET /login` | Formulario de inicio de sesión |
| `POST /login` | Procesar login, almacenar token en sesión |
| `GET /registro` | Formulario de registro |
| `POST /registro` | Registrar usuario vía UsuariosService |
| `POST /logout` | Cerrar sesión y eliminar token |

#### `AdminController` *(requiere ability: `admin.panel`)*

| Ruta | Acción |
|---|---|
| `GET /admin/dashboard` | Panel de administración |
| `GET /admin/muebles` | Listado de muebles (admin) |
| `GET /admin/muebles/crear` | Formulario de nuevo mueble |
| `POST /admin/muebles` | Guardar nuevo mueble |
| `GET /admin/muebles/{id}/editar` | Formulario de edición |
| `PUT /admin/muebles/{id}` | Actualizar mueble |
| `DELETE /admin/muebles/{id}` | Eliminar mueble |
| `GET /admin/usuarios` | Listado de usuarios |
| `GET /admin/usuarios/crear` | Formulario de nuevo usuario |
| `POST /admin/usuarios` | Crear usuario |
| `GET /admin/usuarios/{id}/editar` | Formulario de edición de usuario |
| `PUT /admin/usuarios/{id}` | Actualizar usuario |
| `DELETE /admin/usuarios/{id}` | Eliminar usuario |

---

## 7. Base de Datos

El sistema usa **tres bases de datos MySQL independientes**, una por microservicio.

### `tienda_usuarios` — API de Usuarios

```sql
roles
  id             bigint PK
  nombre         varchar
  created_at     timestamp
  updated_at     timestamp

users
  id                 bigint PK
  rol_id             bigint FK → roles.id
  nombre             varchar
  apellidos          varchar
  email              varchar UNIQUE
  email_verified_at  timestamp NULL
  password           varchar
  intentos_fallidos  int DEFAULT 0
  bloqueado_hasta    timestamp NULL
  remember_token     varchar NULL
  created_at         timestamp
  updated_at         timestamp

personal_access_tokens  (Laravel Sanctum)
  id             bigint PK
  tokenable_type varchar
  tokenable_id   bigint
  name           varchar
  token          varchar UNIQUE (hash SHA-256)
  abilities      text NULL
  last_used_at   timestamp NULL
  expires_at     timestamp NULL
  created_at     timestamp
  updated_at     timestamp
```

### `tienda_muebles` — API de Muebles

```sql
categorias
  id          bigint PK
  nombre      varchar
  descripcion text NULL
  created_at  timestamp
  updated_at  timestamp

productos
  id              bigint PK
  nombre          varchar       INDEX
  descripcion     text
  precio          decimal(10,2) INDEX
  stock           int
  materiales      varchar NULL
  dimensiones     varchar NULL
  color_principal varchar NULL  INDEX
  destacado       boolean DEFAULT false  INDEX
  imagen_principal varchar NULL
  created_at      timestamp
  updated_at      timestamp

categoria_producto  (pivot many-to-many)
  categoria_id    bigint FK → categorias.id
  producto_id     bigint FK → productos.id
  PRIMARY KEY (categoria_id, producto_id)

galerias
  id          bigint PK
  producto_id bigint FK UNIQUE → productos.id
  created_at  timestamp
  updated_at  timestamp

imagenes
  id          bigint PK
  galeria_id  bigint FK → galerias.id
  ruta        varchar
  es_principal boolean DEFAULT false
  orden       int DEFAULT 0
  created_at  timestamp
  updated_at  timestamp
```

### `tienda_principal` — Tienda Web

```sql
carritos
  id          bigint PK
  usuario_id  int  INDEX
  created_at  timestamp
  updated_at  timestamp

carrito_items
  id          bigint PK
  carrito_id  bigint FK → carritos.id
  producto_id int
  nombre      varchar
  precio      decimal(10,2)
  cantidad    int
  imagen      varchar NULL
  created_at  timestamp
  updated_at  timestamp
  UNIQUE (carrito_id, producto_id)

pedidos
  id               bigint PK
  usuario_id       int
  estado           enum('pendiente','pagado','enviado','entregado','cancelado')
  total            decimal(10,2)
  nombre_cliente   varchar
  email_cliente    varchar
  direccion_entrega text
  telefono         varchar NULL
  metodo_pago      varchar
  created_at       timestamp
  updated_at       timestamp

pedido_items
  id          bigint PK
  pedido_id   bigint FK → pedidos.id
  producto_id int
  nombre      varchar
  precio      decimal(10,2)
  cantidad    int
  created_at  timestamp
  updated_at  timestamp
```

### Diagrama de Relaciones (Tienda Principal)

```
Carrito 1 ──────────── N CarritoItem
  │
  │ usuario_id (externo → api_usuarios)

Pedido 1 ──────────── N PedidoItem
  │
  │ usuario_id (externo → api_usuarios)

CarritoItem.producto_id ──────► api_muebles (externo)
PedidoItem.producto_id  ──────► api_muebles (externo)
```

---

## 8. Sistema de Autenticación y Permisos

### Flujo de Autenticación

```
Usuario           Tienda Principal         api_usuarios
   │                    │                       │
   │── POST /login ────►│                       │
   │                    │── POST /api/v1/login ─►│
   │                    │◄── token + abilities ──│
   │                    │ (guarda en sesión)     │
   │◄── redirect ───────│                       │
   │                    │                       │
   │── GET /muebles ───►│                       │
   │                    │ (lee token de sesión)  │
   │                    │── GET /api/v1/muebles ─────────► api_muebles
   │                    │◄── lista de muebles ───────────────────────
   │◄── vista HTML ─────│
```

### Roles y Abilities

El sistema define tres roles con permisos predefinidos:

#### Administrador
```
perfil.ver    usuarios.ver    usuarios.crear    usuarios.editar    usuarios.eliminar
muebles.ver   muebles.crear   muebles.editar    muebles.eliminar
admin.panel
```

#### Gestor
```
perfil.ver
muebles.ver   muebles.crear   muebles.editar    muebles.eliminar
```

#### Cliente
```
perfil.ver
muebles.ver
carrito.gestionar
pedidos.crear
```

### Verificación en Endpoints

Los controladores de las APIs verifican los permisos con el método `tokenCan()` de Sanctum:

```php
// Ejemplo en ProductoController.php
if (!$request->user()->tokenCan('muebles.crear')) {
    return response()->json(['error' => 'No autorizado'], 403);
}
```

### Protección de Rutas Web

La Tienda Principal usa un middleware propio que comprueba la sesión:

```php
// routes/web.php
Route::middleware(['auth.session'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/checkout', [PedidoController::class, 'store']);
    // ...
});
```

---

## 9. Flujos de Negocio

### 9.1 Registro de Usuario

```
1. Usuario accede a GET /registro
2. Rellena formulario (nombre, apellidos, email, password)
3. Tienda Principal → POST api_usuarios/api/v1/registrar
4. api_usuarios asigna rol "Cliente", crea el usuario y emite token con abilities de Cliente
5. Tienda Principal guarda token y datos de usuario en sesión
6. Redirige a la portada ya autenticado
```

### 9.2 Proceso de Compra (flujo completo)

```
1. NAVEGAR CATÁLOGO
   Usuario → GET /muebles
   Tienda Principal → MueblesService.getAllMuebles(filtros)
   → Muestra catálogo con paginación

2. VER PRODUCTO
   Usuario → GET /muebles/{id}
   Tienda Principal → MueblesService.getMuebleById(id)
   → Muestra detalle con galería de imágenes

3. AÑADIR AL CARRITO
   Usuario → POST /carrito/agregar {producto_id, cantidad}
   Tienda Principal:
     a. Consulta datos actuales del producto a api_muebles
     b. Crea o actualiza CarritoItem con snapshot {nombre, precio, imagen}
     c. Respeta restricción unique(carrito_id, producto_id)

4. VER CARRITO
   Usuario → GET /carrito
   Tienda Principal lee CarritoItems de BD local
   → Muestra ítems con subtotales y total

5. CHECKOUT
   Usuario → GET /checkout
   Tienda Principal verifica que el carrito no esté vacío
   → Muestra formulario: datos de envío + método de pago

6. CONFIRMAR PEDIDO
   Usuario → POST /checkout {nombre, email, direccion, telefono, metodo_pago}
   Tienda Principal:
     a. Crea registro Pedido con estado "pendiente"
     b. Crea registros PedidoItem (snapshots del carrito)
     c. Para cada ítem: MueblesService.updateStock(id, cantidad, token)
        → api_muebles PATCH /muebles/{id}/stock {cantidad}
        → api_muebles descuenta stock
     d. Vacía el carrito
     e. Redirige a GET /pedidos/{id} (confirmación)
```

### 9.3 Administración de Productos

```
1. Admin accede a /admin/muebles (requiere ability admin.panel)
2. Puede crear, editar o eliminar productos
3. AdminController usa MueblesService con el token del admin
4. El token de admin tiene ability muebles.crear/editar/eliminar
5. api_muebles verifica el ability antes de procesar la acción
```

---

## 10. API Reference

### Endpoints Públicos (sin autenticación)

```http
GET /api/v1/muebles
GET /api/v1/muebles/{id}
GET /api/v1/categorias
GET /api/v1/categorias/{id}
POST /api/v1/registrar
POST /api/v1/login
```

### Endpoints Protegidos — api_usuarios (Bearer Token)

```http
GET    /api/v1/perfil
POST   /api/v1/logout
GET    /api/v1/usuarios          [usuarios.ver]
POST   /api/v1/usuarios          [usuarios.crear]
GET    /api/v1/usuarios/{id}     [usuarios.ver]
PUT    /api/v1/usuarios/{id}     [usuarios.editar]
DELETE /api/v1/usuarios/{id}     [usuarios.eliminar]
```

### Endpoints Protegidos — api_muebles (Bearer Token)

```http
POST   /api/v1/muebles                   [muebles.crear]
PUT    /api/v1/muebles/{id}              [muebles.editar]
DELETE /api/v1/muebles/{id}             [muebles.eliminar]
PATCH  /api/v1/muebles/{id}/stock       [pedidos.crear | muebles.editar]
POST   /api/v1/muebles/{id}/imagenes    [muebles.editar]
PUT    /api/v1/imagenes/{id}/principal  [muebles.editar]
DELETE /api/v1/imagenes/{id}            [muebles.editar]
POST   /api/v1/categorias               [muebles.crear]
PUT    /api/v1/categorias/{id}          [muebles.editar]
DELETE /api/v1/categorias/{id}          [muebles.eliminar]
```

### Formato de Errores

Todas las APIs devuelven errores en JSON con la misma estructura:

```json
{
  "error": "Descripción del error",
  "details": { }
}
```

Códigos HTTP usados: `200`, `201`, `400`, `401`, `403`, `404`, `422`, `500`

### Documentación Swagger

Ambas APIs incluyen documentación interactiva OpenAPI (Swagger UI):

- **api_usuarios:** `http://localhost:8001/api/documentation`
- **api_muebles:** `http://localhost:8002/api/documentation`

---

## 11. Instalación y Configuración

### Requisitos

- PHP 8.3 o superior
- Composer 2.x
- MySQL 8.0 o superior
- Node.js (para assets, opcional)

### Instalación de cada módulo

Los tres módulos siguen el mismo proceso:

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Copiar variables de entorno
cp .env.example .env

# 3. Generar clave de aplicación
php artisan key:generate

# 4. Configurar base de datos en .env
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# 5. Ejecutar migraciones
php artisan migrate

# 6. (Opcional) Ejecutar seeders
php artisan db:seed

# 7. Iniciar servidor de desarrollo
php artisan serve --port=XXXX
```

### Puertos por módulo

| Módulo | Puerto | Variable de entorno |
|---|---|---|
| api_usuarios | 8001 | — |
| api_muebles | 8002 | — |
| tienda_principal | 8000 | `API_USUARIOS_URL`, `API_MUEBLES_URL` |

### Variables de entorno clave — tienda_principal

```env
APP_NAME="Tienda de Muebles"
APP_URL=http://localhost:8000

DB_DATABASE=tienda_principal

API_USUARIOS_URL=http://localhost:8001/api
API_MUEBLES_URL=http://localhost:8002/api

SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Variables de entorno clave — api_usuarios

```env
APP_NAME="API Usuarios"
APP_URL=http://localhost:8001

DB_DATABASE=tienda_usuarios

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

### Variables de entorno clave — api_muebles

```env
APP_NAME="API Muebles"
APP_URL=http://localhost:8002

DB_DATABASE=tienda_muebles

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

### Orden de arranque recomendado

```
1. Arrancar api_usuarios  → php artisan serve --port=8001
2. Arrancar api_muebles   → php artisan serve --port=8002
3. Arrancar tienda        → php artisan serve --port=8000
```

---

## 12. Tecnologías Utilizadas

### Backend

| Tecnología | Versión | Uso |
|---|---|---|
| PHP | 8.3+ | Lenguaje principal |
| Laravel | 13.7 | Framework web (los tres módulos) |
| Laravel Sanctum | 4.0 | Autenticación API con tokens |
| L5-Swagger | 11.0 | Generación de documentación OpenAPI |
| PHPUnit | 12.5 | Framework de testing |
| MySQL | 8.0+ | Base de datos relacional |

### Frontend

| Tecnología | Uso |
|---|---|
| Blade (Laravel) | Motor de plantillas para las vistas HTML |
| Bootstrap / CSS | Estilos y maquetación (según configuración) |

### Herramientas de Desarrollo

| Herramienta | Uso |
|---|---|
| Composer | Gestión de dependencias PHP |
| Laravel Pint | Formateador de código PSR-12 |
| Git | Control de versiones |

### Patrones y Principios Aplicados

| Patrón | Descripción |
|---|---|
| **MVC** | Model-View-Controller en los tres módulos |
| **Service Layer** | Servicios (`MueblesService`, `UsuariosService`) que encapsulan las llamadas HTTP a APIs |
| **RESTful API** | Las dos APIs siguen los principios REST (verbos HTTP, recursos, stateless) |
| **Snapshot Pattern** | Copia de datos de producto en el momento de la compra para preservar la integridad histórica |
| **RBAC** | Role-Based Access Control con abilities granulares por token Sanctum |
| **SOA** | Arquitectura orientada a servicios con tres aplicaciones independientes |

---

*Documentación generada el 13 de mayo de 2026.*
