# 🪑 Proyecto Tienda de Muebles Híbrida

¡Bienvenido/a al repositorio de la Tienda de Muebles Híbrida! 

Este proyecto es una excelente oportunidad para aprender cómo construir sistemas modernos. Implementa una **arquitectura orientada a servicios** (a menudo un paso previo a los microservicios completos o una arquitectura híbrida). En lugar de tener todo el código mezclado en un solo lugar (monolito), separamos las responsabilidades en distintas aplicaciones.

## 🏗️ Arquitectura del Proyecto

El sistema está dividido en tres proyectos independientes de Laravel:

1. **`api_muebles/`**: Se encarga de gestionar de forma exclusiva el inventario, catálogo y detalles de los muebles.
2. **`api_usuarios/`**: Se enfoca únicamente en el registro, autenticación (login) y perfiles de los usuarios.
3. **`tienda_principal/`**: Actúa como el **frontend o cliente**. Esta es la aplicación que el usuario final realmente ve en su navegador. No guarda datos de productos o usuarios de forma directa, sino que hace peticiones web a `api_muebles` y `api_usuarios` para recopilar la información y mostrarla de forma integrada.

### 🤔 ¿Por qué se hace de esta manera? (Propósito Educativo)

1. **Separación de responsabilidades (Decoupling):** El código es más limpio. Quien programa los muebles no interfiere con el código de usuarios.
2. **Escalabilidad independiente:** Si la tienda recibe muchos visitantes mirando muebles, pero muy pocos se registran, se le puede dar más potencia (memoria/CPU) solo a la `api_muebles` sin gastar recursos extra en la `api_usuarios`.
3. **Tolerancia a fallos:** Si la `api_usuarios` sufre una caída (por ejemplo, mantenimiento), los usuarios aún podrían seguir navegando por el catálogo de muebles a través de la `tienda_principal` (aunque no puedan comprar o iniciar sesión).

## 📁 Estructura del Repositorio

```text
03-tienda-muebles-hibrido/
├── api_muebles/       # API desarrollada en Laravel para gestionar productos
├── api_usuarios/      # API desarrollada en Laravel para gestionar usuarios
├── tienda_principal/  # Aplicación principal en Laravel que consume las dos APIs
└── memory/            # Archivos de documentación y diseño del proyecto
```

## 🛠️ Tecnologías Utilizadas

*   **Lenguaje:** PHP 8.3
*   **Framework:** Laravel 13.x
*   **Gestión de Dependencias:** Composer (PHP) y NPM (JavaScript/CSS)

## 🚀 Requisitos Previos

Antes de ejecutar el proyecto, asegúrate de tener instalado en tu sistema:
*   [PHP](https://www.php.net/) 8.3 o superior.
*   [Composer](https://getcomposer.org/)
*   [Node.js y npm](https://nodejs.org/)
*   Un servidor de bases de datos (SQLite, MySQL o PostgreSQL)

## ⚙️ Instalación y Configuración

Como el proyecto está dividido en tres partes, **debes instalar y configurar cada una de forma independiente**.

### Pasos Generales (A repetir para las 3 carpetas):

1. **Entra al directorio del proyecto:**
   ```bash
   cd api_muebles # (Repetir con api_usuarios y tienda_principal)
   ```

2. **Instala las dependencias necesarias:**
   * Para PHP: `composer install`
   * Para Node (frontend): `npm install`

3. **Configura el entorno de ejecución:**
   * Duplica el archivo de ejemplo para crear tu configuración local:
     * En Windows: `copy .env.example .env`
     * En Linux/Mac: `cp .env.example .env`
   * Genera la clave de seguridad de Laravel:
     ```bash
     php artisan key:generate
     ```
   * En el archivo `.env` recién creado, configura los accesos a tu base de datos si usas MySQL, o déjalo por defecto si usas SQLite.

4. **Configura la base de datos (Migraciones):**
   ```bash
   php artisan migrate
   ```
   *(Nota: Puedes añadir datos de prueba si tienen los seeders configurados usando `php artisan migrate --seed`)*

5. **Levanta los servidores:**
   * Para que cada proyecto se pueda comunicar con los otros en tu ordenador, deben ejecutarse en **puertos diferentes**.
   * Abre 3 terminales distintas (una por proyecto) y ejecuta:
     ```bash
     # En api_muebles
     php artisan serve --port=8001
     
     # En api_usuarios
     php artisan serve --port=8002
     
     # En tienda_principal
     php artisan serve --port=8000
     ```
   * *Recuerda también ejecutar `npm run dev` en la `tienda_principal` para compilar los estilos y el JavaScript.*

## 🔗 Integración

Para que la `tienda_principal` pueda consumir los datos correctamente, asegúrate de abrir su archivo `.env` y definir las rutas a las APIs. Por ejemplo:

```env
API_MUEBLES_URL="http://127.0.0.1:8001"
API_USUARIOS_URL="http://127.0.0.1:8002"
```

---
*Este repositorio fue creado con un enfoque educativo para demostrar la integración de APIs y sistemas desacoplados utilizando Laravel.*
