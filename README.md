# AppSalon MVC - PHP

AppSalon es una aplicación web desarrollada en PHP que simula la gestión de un salón de belleza. Implementa el patrón de arquitectura MVC (Modelo-Vista-Controlador) y utiliza tecnologías modernas como SASS, Gulp y JavaScript para ofrecer una experiencia de usuario optimizada.

## 🚀 Características

- **Gestión de usuarios**: Registro, autenticación y recuperación de contraseñas.
- **Panel de administración**: CRUD de servicios y citas.
- **Sistema de reservas**: Agendamiento y gestión de citas.
- **Diseño responsive**: Adaptado para dispositivos móviles y de escritorio.
- **Automatización de tareas**: Compilación de SASS y minificación de archivos con Gulp.

## 🛠️ Tecnologías utilizadas

- **Backend**: PHP
- **Frontend**: SCSS, JavaScript
- **Herramientas**: Gulp, Composer, NPM
- **Base de datos**: MySQL

## 📁 Estructura del proyecto

- `classes/`: Clases auxiliares y utilidades.
- `controllers/`: Controladores que manejan la lógica de negocio.
- `models/`: Modelos que interactúan con la base de datos.
- `views/`: Vistas y plantillas HTML.
- `public/`: Archivos públicos accesibles desde el navegador.
- `src/`: Recursos como SASS y JavaScript.
- `includes/`: Archivos compartidos y configuraciones.
- `Router.php`: Sistema de enrutamiento personalizado.

## ⚙️ Instalación

Sigue los siguientes pasos para instalar y ejecutar el proyecto en tu entorno local:

1. Clona el repositorio:

   ```bash
   git clone https://github.com/Julian-Sandoval-x/appSalon-mvc-php.git
   cd appSalon-mvc-php

   ```

2. Instala las dependencias de PHP

   ```bash
   composer install
   ```

3. Instala las dependencias de JS

   ```bash
   npm install
   ```

4. Compila SASS y JS con gulp

   ```bash
   npm run dev
   ```

5. Arranca el servidor en local
   ```bash
   php -S localhost:3000
   ```

## Licencia

Este proyecto está bajo la licencia MIT.

## Autor

Desarrollado por [Julian Sandoval](https://github.com/Julian-Sandoval-x)
Estudiante de Ingeniería en Sistemas Computacionales
Apasionado por el desarrollo backend y en constante aprendizaje.
