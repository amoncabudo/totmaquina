# TotMaquina - Sistema de Gestión de Mantenimiento Industrial

## 📋 Descripción

TotMaquina es una plataforma web diseñada para la gestión integral del mantenimiento de maquinaria industrial. Facilita el seguimiento, programación y control de mantenimientos preventivos y correctivos, asegurando la máxima eficiencia operativa.

## 🚀 Características Principales

### 👥 Gestión de Usuarios
- Sistema de roles (Administrador, Supervisor, Técnico)
- Autenticación segura
- Perfiles personalizables con avatar
- Recuperación de contraseña

### 🔧 Gestión de Máquinas
- Inventario detallado
- Importación masiva vía CSV
- Generación de códigos QR
- Geolocalización con Leaflet
- Asignación de técnicos mediante drag & drop
- Buscador dinámico con AJAX

### 📅 Mantenimientos
- Programación de mantenimientos preventivos
- Periodicidad configurable
- Seguimiento en tiempo real
- Generación de informes PDF

### ⚠️ Gestión de Incidencias
- Registro y seguimiento
- Priorización automática
- Asignación a técnicos
- Estadísticas y métricas
- Historial completo

## 💻 Tecnologías

### Frontend
- HTML5 + CSS
- JavaScript 
- Tailwind CSS
- Flowbite
- jQuery
- Leaflet para mapas
- Diseño responsive

### Backend
- PHP 
- Framework Emeset
- MariaDB
- Composer

## 🛠️ Instalación

```bash
# Clonar el repositorio
git clone https://github.com/amoncabudo/Tot__Maquina.git

# Instalar dependencias PHP
composer install

# Instalar dependencias JavaScript
npm install```

## 👥 Roles y Permisos

### 👨‍💼 Administrador
- Gestión completa de usuarios
- Creación de técnicos y supervisores
- Acceso a todas las funcionalidades

### 👨‍🔧 Supervisor
- Gestión de máquinas
- Asignación de técnicos
- Programación de mantenimientos
- Seguimiento global

### 🔧 Técnico
- Gestión de máquinas asignadas
- Registro de mantenimientos
- Seguimiento de incidencias
- Acceso al historial

## 📱 Interfaz de Usuario

### Características
- Diseño responsive
- Interfaz intuitiva
- Navegación simplificada

### Secciones Principales
1. Dashboard
2. Gestión de Máquinas
3. Mantenimientos
4. Incidencias
5. Historial
6. Configuración

## 🔒 Seguridad

- HTTPS obligatorio
- Validación de contraseñas
- Gestión de sesiones segura

## 📊 Estadísticas y Reportes

- Tiempo medio de resolución
- Incidencias por máquina
- Mantenimientos preventivos vs correctivos
- Exportación a PDF
- Gráficos interactivos

## 🌐 API Externa

- Integración con Random User Generator
- Creación automática de usuarios de prueba

## 🚀 Despliegue

- Despliegue automatizado con Git hooks
- Servidor Apache + PHP
- Virtualhost configurado
- Certificado SSL/TLS
---
