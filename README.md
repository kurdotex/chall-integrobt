# Challenge IntegroBT 


## Arquitectura del Sistema

El proyecto está diseñado siguiendo el principio de **Separation of Concerns (SoC)**:
*   **Backend**: Laravel 12 API operando en contenedores PHP 8.4 + MySQL.
*   **Frontend**: Vue 3 + TypeScript + Tailwind 4, servido dinámicamente.

---

## Guía de Instalación (Docker-Ready)

Esta guía asume que **Docker** y **Docker Compose** se encuentran instalados (En Windows, asegúrate de usar WSL2 para mejor rendimiento).

### 1. Configuración del Entorno
Antes de levantar los contenedores, prepara los archivos de configuración:
```bash
# En la subcarpeta backend
cp backend/.env.example backend/.env

# En la subcarpeta frontend
cp frontend/.env.example frontend/.env
```

### 2. Puesta en Marcha

#### Linux / macOS / Windows (WSL2)
```bash
# 1. Levantar el Backend y Base de Datos
cd backend
docker compose up -d

# 2. Levantar el Frontend
cd ../frontend
docker run -d --name challenge-ui -v $(pwd):/app -w /app -p 5174:5174 node:20-alpine sh -c "npm install && npm run dev -- --host 0.0.0.0 --port 5174"
```

#### Windows (Powershell / CMD)
```powershell
docker run -d --name challenge-ui -v ${PWD}:/app -w /app -p 5174:5174 node:20-alpine sh -c "npm install && npm run dev -- --host 0.0.0.0 --port 5174"
```

---

## Documentación de API (Swagger)
Una vez levantado el proyecto, puedes consultar la documentación interactiva en:
👉 **[http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)**

---

## Decisiones Técnicas y Bonus
*   **Service Layer**: Toda la lógica de negocio (clima, CRUDs) está encapsulada en la capa de `Services` del Backend, manteniendo los controladores limpios.
*   **Caché de Clima**: Se implementó una caché de 30 minutos para las consultas a OpenMeteo, optimizando el tiempo de respuesta y evitando el rate-limit de la API externa.
*   **TypeScript Estricto**: TS para garantizar la integridad de los datos (Interfaces de Sucursal, Empleado y Weather).
*   **UI**: Manejo global de errores mediante interceptores de Axios y modales de confirmación personalizados.

---

## Pruebas Automatizadas
Para ejecutar la suite de tests profesionales:
```bash
docker exec -it backend-laravel.test-1 php artisan test
```

---

## Licencia
Este proyecto está bajo la licencia **MIT**.

---
*Desarrollado para el Challenge IntegroBT.*# chall-integrobt
