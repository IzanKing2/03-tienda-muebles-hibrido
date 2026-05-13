---
name: project-overview
description: Arquitectura, servicios y estado de implementación de la tienda de muebles híbrida
metadata:
  type: project
---

Proyecto e-commerce de muebles con arquitectura de microservicios Laravel.

**Why:** Proyecto educativo/práctico con separación de responsabilidades entre autenticación y catálogo.

**How to apply:** Cada cambio en el frontend (tienda_principal) consume las APIs via servicios HTTP, nunca directamente a la BD de los otros servicios.

## Servicios
- `api_muebles` (puerto 8002) — catálogo de productos, categorías, imágenes. DB: `tienda_muebles`
- `api_usuarios` (puerto 8001) — auth, roles, usuarios. DB: `tienda_usuarios`
- `tienda_principal` (puerto 8000) — frontend Blade que consume ambas APIs. DB: `tienda_principal`

## Estado de implementación (2026-05-13)
- Autenticación: completa (registro, login, sesión, roles, abilities)
- Catálogo: completo con filtros, paginación, galería de imágenes
- **Carrito:** implementado (sesión, agregar/actualizar/eliminar/vaciar, vista completa)
- **Panel Admin:** implementado (dashboard, CRUD muebles, CRUD usuarios)
- **Seeder muebles:** 8 categorías, 20 productos variados

## Roles y abilities clave
- Administrador: `admin.panel`, `usuarios.*`, `muebles.*`
- Gestor: `muebles.*` (sin admin.panel)
- Cliente: `carrito.gestionar`, `pedidos.crear`

## Sesión frontend (auth)
- `auth_token` — token Sanctum
- `auth_user` — datos del usuario (incluye `rol`)
- `auth_abilities` — array de strings con las abilities del token

## Credenciales de prueba (seeder api_usuarios)
- admin@tienda.com / password → Administrador
- gestor@tienda.com / password → Gestor
- cliente@tienda.com / password → Cliente
