# PROJECT_CONTEXT.md

# Production Manager

Sistema de Gestión de Producción Agroindustrial desarrollado para una institución educativa técnica.

---

# Objetivo del Proyecto

Permitir el registro, administración, consulta y generación de reportes de producciones agroindustriales realizadas por estudiantes y profesores.

El sistema debe facilitar el control de:

* Productos elaborados.
* Producciones realizadas.
* Usuarios del sistema.
* Secciones académicas.
* Reportes históricos.

---

# Tecnologías Utilizadas

Backend:

* PHP puro
* PDO

Base de datos:

* MySQL

Servidor local:

* XAMPP

Frontend:

* HTML5
* CSS3
* JavaScript (mínimo)

No se utilizan frameworks PHP.

---

# Filosofía de Desarrollo

Antes de programar:

1. Analizar la arquitectura actual.
2. Revisar qué ya existe.
3. Evitar duplicar funcionalidades.
4. Trabajar por sprints pequeños.
5. Priorizar consistencia.
6. Mantener código simple y entendible.
7. Explicar impacto de cada cambio.

---

# Estructura General

production-manager/

assets/
├── css/
├── img/
└── js/

auth/

config/
├── auth.php
└── database.php

dashboard/

database/

includes/
├── footer.php
├── header.php
├── navbar.php
└── sidebar.php

products/
sections/
users/
productions/
reports/

index.php

---

# Estado Actual

## Sistema de autenticación

Implementado:

* Login
* Logout
* Control de sesión
* Protección de rutas

---

## Dashboard

Implementado.

Contiene:

* Tarjetas de estadísticas.
* Resumen general.
* Accesos rápidos.

Actualmente se encuentra en proceso de rediseño visual.

---

## Usuarios

Implementado:

* Listado
* Creación
* Edición
* Perfil

Perfil actual:

* Información personal.
* Cambio de contraseña (en desarrollo).
* Preparado para idioma y tema.

---

## Productos

Implementado:

* CRUD funcional.
* Estado activo/inactivo.

No se eliminan físicamente cuando sea posible.

---

## Secciones

Implementado:

* CRUD funcional.

Regla de negocio:

Una producción pertenece a una sola sección.

---

## Producciones

Módulo principal del sistema.

Implementado:

* Listado
* Registro
* Edición
* Vista detallada

Este módulo tiene prioridad sobre cualquier otro módulo.

La mayor parte del esfuerzo visual debe enfocarse aquí.

---

# Reglas de Negocio

## Producción

Cada producción registra:

* Fecha de producción.
* Producto.
* Materias primas.
* Procesado por.
* Registrado por.
* Sección.
* Cantidad.
* Unidad.
* Unidad personalizada.
* Fecha de creación.

---

## Usuarios

Existen roles.

Actualmente:

1 = Administrador
2 = Profesor

Posibilidad de expansión futura.

---

## Permisos

Administrador:

* Acceso total.
* Puede modificar cualquier producción.

Profesor:

* Solo debería acceder a:

  * Dashboard
  * Producciones
  * Reportes
  * Perfil

No debería acceder a:

* Usuarios
* Productos
* Secciones

Solo puede editar producciones creadas por él.

---

# Diseño Visual

Objetivo visual:

Panel administrativo moderno.

Inspiración:

Dashboard profesional con:

* Sidebar fija.
* Tarjetas modernas.
* Formularios modernos.
* Tablas modernas.
* Iconos profesionales.
* Diseño limpio.
* Colores verde oscuro y blanco.

---

# Decisiones Visuales Tomadas

No utilizar:

* Emojis.
* Estilos antiguos.
* Tablas sin formato.
* Formularios básicos.

Sí utilizar:

* Font Awesome.
* Cards.
* Grid Layout.
* Sidebar moderna.
* Diseño consistente.

---

# Funcionalidades Futuras

Prioridad alta:

1. Sidebar por roles.
2. Permisos completos.
3. Rediseño total de módulos.

Prioridad media:

4. Tema oscuro.
5. Cambio de idioma.

Prioridad futura:

6. Exportar a Excel.
7. Exportar a Word.
8. Actividad reciente.
9. Dashboard avanzado.

---

# Forma de Trabajo Esperada

Cuando se solicite una modificación:

* Analizar primero.
* Explicar impacto.
* Indicar archivos afectados.
* Preferir archivos completos para reemplazar.
* Mantener consistencia con el diseño general.
