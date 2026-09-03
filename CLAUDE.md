# Production Manager

Sistema web de gestión de producciones agroindustriales (CTP AZ).
Contexto completo en PROJECT_CONTEXT.md y DATABASE_CONTEXT.md.

## Stack
PHP plano (sin framework), PDO, MySQL. Front: HTML/CSS/JS, Chart.js, Font Awesome.
Sin build step. Nada de Composer nuevo sin consultar.

## Reglas
- Toda consulta a la BD va con sentencias preparadas de PDO. Nunca concatenar SQL.
- Toda salida a HTML pasa por htmlspecialchars().
- Los permisos se resuelven con las funciones de config/permissions.php.
  Roles: 1 = Admin, 2 = Profesor, 3 = Administración.
- El CSS vive solo en assets/css/style.css y usa las variables ya definidas.
  No agregar estilos inline nuevos.
- Los cambios de esquema NO se aplican a la base: se entregan como archivos
  en database/migrations/ con nombre AAAA-MM-DD-descripcion.sql.
- Español en la interfaz y en los comentarios.

## Estado actual
Trabajando en: estadísticas del dashboard, permisos de exportación,
recuperación de contraseña sin correo, totales por producto.
