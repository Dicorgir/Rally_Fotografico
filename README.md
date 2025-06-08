# Rally Fotográfico

Plataforma web para la gestión y participación en rallies fotográficos, desarrollada como Trabajo de Fin de Grado (TFG). Permite la organización de concursos, subida de fotografías, votaciones, comentarios y administración avanzada.

## Índice

- [Descripción](#descripción)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Instalación y despliegue](#instalación-y-despliegue)
- [Tecnologías utilizadas](#tecnologías-utilizadas)
- [Funcionalidades principales](#funcionalidades-principales)
- [Capturas de pantalla](#capturas-de-pantalla)
- [Autores](#autores)
- [Licencia](#licencia)

---

## Descripción

Rally Fotográfico es una aplicación web que permite a los usuarios participar en concursos de fotografía (rallies), subir sus imágenes, votar las mejores fotos y comentar. Incluye un panel de administración para gestionar usuarios, rallies y validar fotografías.

## Estructura del proyecto

```
Rally_Fotografico/
│
├── Backend/           # Lógica de servidor y acceso a base de datos (PHP)
│   ├── conexion.php
│   ├── crear_rally.php
│   ├── galeria_fotos.php
│   ├── guardar_comentario.php
│   ├── get_rallies.php
│   ├── ...otros scripts
│
├── Frontend/          # Interfaz de usuario (HTML, CSS, JS)
│   ├── index.html
│   ├── galeria.html
│   ├── registro.html
│   ├── login.html
│   ├── admin/
│   │   ├── configuracion_admin.html
│   │   ├── resultados_admin.html
│   │   └── ...
│   ├── participante/
│   │   ├── perfil_participante.html
│   │   └── ver_fotos.html
│   ├── css/
│   ├── js/
│
├── img/               # Imágenes y recursos gráficos
│   ├── logo_rallyFotografico.png
│   └── ...
│
├── docs/              # Documentación generada (JSDoc)
├── docs-doxygen/      # Documentación generada (Doxygen)
├── README.md
├── .gitignore
└── Doxyfile
```

## Instalación y despliegue

1. **Clona el repositorio** en tu entorno local.
2. **Coloca la carpeta** en el directorio de tu servidor web local (por ejemplo, `c:\xampp\htdocs\Rally_Fotografico`).
3. **Configura la base de datos**:
   - Crea una base de datos MySQL.
   - Importa el script de la basde de datos(`Backend/consulta_rallyFinal.sql`).
   - Ajusta los parámetros de conexión en [`Backend/conexion.php`](Backend/conexion.php).
4. **Accede a la aplicación** desde tu navegador:
   - Página principal: `http://localhost/Rally_Fotografico/Frontend/index.html`
   - Panel de administración: `http://localhost/Rally_Fotografico/Frontend/admin/configuracion_admin.html`

## Tecnologías utilizadas

- **Frontend:** HTML5, CSS3, JavaScript, Chart.js, FontAwesome
- **Backend:** PHP 8.0, MySQL
- **Documentación:** JSDoc, Doxygen

## Funcionalidades principales

- Registro y autenticación de usuarios
- Gestión de rallies fotográficos (crear, editar, eliminar)
- Subida y validación de fotografías
- Votación y ranking de fotos
- Comentarios en fotografías
- Panel de administración con estadísticas y gestión de usuarios
- Modo oscuro/claro
- Responsive design

## Capturas de pantalla

> Añade aquí imágenes relevantes del funcionamiento de la aplicación.

## Autores

- Diego André Cornejo Giraldo

## Licencia

Este proyecto se entrega como parte de un Trabajo de Fin de Grado. Uso académico.
