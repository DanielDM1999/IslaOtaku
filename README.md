# Isla Otaku: Información y Reseñas de Anime

**Fecha:** 15/05/2025  
**Autor:** Daniel Delgado Meneses  
**Curso:** Ciclo Superior Desarrollo de Aplicaciones Web (2024-2025)

---

## Introducción

Este proyecto consiste en el desarrollo de una aplicación web para gestionar series de anime, inspirada en la popular plataforma MyAnimeList. La aplicación permite a los usuarios registrarse, acceder a listas personales donde pueden guardar las series según su estado (viendo, abandonado, terminado), valorar animes y escribir reseñas.

La aplicación está diseñada para ofrecer una experiencia sencilla, intuitiva y funcional, desarrollada con HTML, CSS, JavaScript y PHP, aplicando los conocimientos adquiridos durante el Ciclo Formativo de Grado Superior en Desarrollo Web.

---

## Motivación

Mi interés personal en el mundo del anime y manga, junto con mi experiencia como usuario de plataformas como MyAnimeList, me inspiraron a crear esta herramienta, diseñada para ser útil y accesible a los aficionados. Este proyecto me permitió aplicar y consolidar los conocimientos del ciclo formativo, creando una herramienta práctica y enfocada en la comunidad de anime.

---

## Objetivos

En las Islas Canarias, el anime y manga se viven principalmente en eventos y tiendas especializadas, pero no existen plataformas digitales locales que permitan a los fans organizar y compartir su afición. Este proyecto cubre esa necesidad, ofreciendo una herramienta online para gestionar listas, escribir reseñas y conectar con otros aficionados, fortaleciendo la comunidad local y abierta a seguidores de otras regiones.

---

## Aplicaciones similares

- **MyAnimeList (MAL):** Plataforma popular para organizar y puntuar anime y manga, con una amplia base de datos y conexión entre usuarios.  
- **AniList:** Similar a MAL, con diseño moderno y características como modo oscuro.  
- **Kitsu:** Plataforma con enfoque social para seguir amigos y descubrir contenido.  
- **Anime-Planet:** Ofrece recomendaciones, noticias y colaboraciones con servicios de streaming legales.

---

## Metodologías de planificación y control del tiempo

Se utilizó un diagrama de Gantt para organizar las tareas y visualizar su duración, facilitando el seguimiento del progreso del proyecto.

---

## Tecnologías utilizadas

- **MySQL:** Gestión de base de datos.  
- **HTML:** Estructura y presentación de interfaces.  
- **CSS:** Diseño visual y estilos.  
- **JavaScript con AJAX:** Interactividad y navegación dinámica sin recargas completas.  
- **PHP:** Lógica del servidor y gestión de datos.

---

## Herramientas usadas

- **XAMPP:** Entorno local con Apache, MySQL y phpMyAdmin.  
- **Visual Studio Code:** Editor de código.

---

## Estimación de recursos y planificación

- Software: XAMPP, Visual Studio Code, navegadores web.  
- Tiempo: Dos meses y medio.

---

## Diseño visual y prototipos

Se eligieron tonos de azul para transmitir confianza y profesionalismo, y la fuente Poppins por ser moderna y legible, buscando una interfaz clara y agradable para los usuarios.

---

## Análisis

### Esquema Entidad-Relación  
_(Inserta aquí el diagrama correspondiente)_

### Diagrama de Casos de Uso  
_(Inserta aquí el diagrama correspondiente)_

---

## Requisitos

- Registro y gestión de usuarios.  
- Búsqueda de animes por nombre.  
- Añadir animes a listas personales con estados.  
- Escribir y leer reseñas.

---

## Despliegue del aplicativo

El proyecto está publicado en GitHub para facilitar su acceso y revisión:  
[Repositorio IslaOtaku](https://github.com/DanielDM1999/IslaOtaku)

---

## Documentación

### Manual de usuario y configuración

#### Requisitos previos

- Tener instalado XAMPP (Apache y MySQL).  

#### Pasos para la instalación

1. Abrir XAMPP y activar Apache y MySQL.  
2. Copiar la carpeta del proyecto dentro de `htdocs` de XAMPP.  
3. Acceder a phpMyAdmin en `http://localhost/phpmyadmin`.  
4. Importar el archivo SQL `init.sql` que se encuentra en la carpeta `database` para crear la base de datos y datos iniciales.  
5. Abrir el proyecto en el navegador: `http://localhost/IslaOtaku/`.

---

## Estructura del proyecto

### Controladores

- **AnimeController.php:** Maneja información de animes (listas, detalles, búsqueda).  
- **ListController.php:** Gestiona listas personales de animes (agregar, actualizar, filtrar).  
- **ReviewController.php:** Controla reseñas (obtener, contar, agregar, eliminar).  
- **UserController.php:** Gestión de usuarios (registro, login, perfil, listas y reseñas).

### Modelos

- **AnimeModel.php:** Operaciones sobre la base de datos para animes.  
- **ListModel.php:** Gestión de listas de usuarios.  
- **ReviewModel.php:** Operaciones sobre reseñas.  
- **UserModel.php:** Manejo de datos de usuarios.

### Base de datos

- **Config:** `database.php` establece la conexión PDO.  
- **Script:** `init.sql` crea la estructura y carga datos iniciales.

### Traducciones

- `en.php` e `es.php` contienen los textos para soporte multilingüe.

### Archivos públicos

- Carpeta `css`: estilos.  
- Carpeta `js`: scripts JavaScript.  
- Carpeta `images`: imágenes estáticas.

### Archivos subidos

- Carpeta `profilesPictures`: fotos de perfil de usuarios.

### Página principal

- `index.php` gestiona idiomas, carga controladores, acciones y vistas según la URL, y controla autenticación y permisos.

---

## Pruebas realizadas

- Registro de usuario.  
- Búsqueda de series.  
- Gestión y actualización de perfil (foto, nombre).  
- Añadir series a listas personales.  
- Creación, edición y eliminación de reseñas.

---

## Conclusiones

El proyecto permitió crear un sistema funcional basado en una estructura SPA, donde la navegación y el envío de datos se manejan eficientemente con AJAX y parámetros en la URL. Esto ofrece una experiencia fluida sin recargas completas, mejorando la interacción. Aunque se lograron los objetivos, queda margen para mejoras como la incorporación de una sección de administración, pendiente por cambio de fecha de entrega, y futuras funciones como sección de noticias y foro para fortalecer la comunidad.

---

## Webgrafía

- [MyAnimeList](https://myanimelist.net/)  
- [AniList](https://anilist.co/)  
- [Kitsu](https://kitsu.app/explore/anime)  
- [Anime-Planet](https://www.anime-planet.com/)  
- [Wikipedia MyAnimeList](https://es.wikipedia.org/wiki/MyAnimeList)  
- [Diagrama de Gantt - Atlassian](https://www.atlassian.com/es/agile/project-management/gantt-chart)  
- [Coolors](https://coolors.co)  
- [Google Fonts - Poppins](https://fonts.google.com/specimen/Poppins)

---

## Licencia

MIT License
