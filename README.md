# Rally Fotográfico

<div align="center">
  <img width="250" height="450" alt="rally_fotografico_imagen" src="https://github.com/user-attachments/assets/5ec402e6-338a-4166-a3cb-d94f924ffd0b" />
</div>


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
   - Importa el script de la base de datos (`Backend/consulta_rallyFinal.sql`).
   - Ajusta los parámetros de conexión en [`Backend/conexion.php`](Backend/conexion.php).
4. **Accede a la aplicación** desde tu navegador:
   - Página principal: `http://localhost/Rally_Fotografico/Frontend/index.html`
   - Panel de administración: `http://localhost/Rally_Fotografico/Frontend/admin/configuracion_admin.html`

> **Nota sobre el despliegue:**  
> Por motivos de presupuesto y disponibilidad, el despliegue final se ha realizado en un hosting compartido (ByetHost), integrando frontend y backend en el mismo servidor. La arquitectura del proyecto permite su despliegue en servidores separados si se dispone de los recursos necesarios, bastando con ajustar las rutas de los servicios.

---

## Cumplimiento de requisitos del proyecto

Este proyecto cumple con los requisitos del enunciado del TFG:

- **Gestión de participantes:** Registro, autenticación, edición de perfil y gestión de usuarios.
- **Gestión de rallies:** Creación, edición y eliminación de concursos fotográficos.
- **Subida y validación de fotografías:** Los participantes pueden subir fotos, que deben ser validadas por el administrador (estados: pendiente, admitida, rechazada).
- **Votación pública:** Sistema de votación con limitación por IP, ranking y visualización de resultados en tiempo real.
- **Panel de administración:** Gestión de usuarios, rallies, validación de fotos y estadísticas.
- **Galería pública:** Visualización de fotografías y votación abierta.
- **Validación de formularios:** Tanto en frontend como en backend.
- **Control de acceso:** Roles diferenciados para administrador y participante.
- **Despliegue:** Instrucciones para entorno local y despliegue en hosting web.
- **Control de versiones:** Proyecto gestionado con Git.
- **Documentación:** Documentación técnica en proceso (JSDoc, Doxygen y manuales).

---

## Manual de usuario

1. **Registro:** Accede a la página principal y regístrate como participante.
2. **Login:** Inicia sesión como participante o administrador.
3. **Participante:** Sube tus fotografías, consulta su estado y edita tu perfil.
4. **Administrador:** Accede al panel de administración para validar fotos, gestionar usuarios y ver estadísticas.
5. **Votación:** Desde la galería pública, cualquier usuario puede votar las fotografías admitidas.

---

## Manual de administrador

1. **Acceso:** Inicia sesión como administrador.
2. **Gestión:** Crea, edita o elimina rallies y usuarios.
3. **Validación:** Revisa y valida/rechaza las fotografías subidas por los participantes.
4. **Estadísticas:** Consulta rankings y resultados en tiempo real.

---

## Consideraciones y mejoras futuras

- Separación real de frontend y backend en servidores distintos.
- Mejoras en la seguridad y autenticación (por ejemplo, autenticación multifactor).
- Sistema de notificaciones por email.
- Ampliación de estadísticas y exportación de resultados.
- Internacionalización de la interfaz.
- Mejoras en la experiencia de usuario y accesibilidad.

---

## Autores

- Diego André Cornejo Giraldo

## Licencia

Este proyecto se entrega como parte de un Trabajo de Fin de Grado. Uso académico.
