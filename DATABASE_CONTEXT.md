# DATABASE_CONTEXT.md

# Base de Datos

Nombre:

production_manager

Motor:

MySQL

Acceso mediante:

PDO

---

# Tabla: roles

Propósito:

Definir permisos generales del sistema.

Campos conocidos:

* id (PK)
* role_name

Ejemplos:

1 = admin
2 = profesor

Relación:

roles.id → users.role_id

---

# Tabla: users

Propósito:

Usuarios que acceden al sistema.

Campos conocidos:

* id
* full_name
* email
* password
* role_id
* active
* last_login
* created_at
* updated_at

Relaciones:

users.role_id → roles.id

---

# Tabla: products

Propósito:

Productos agroindustriales que pueden ser producidos.

Ejemplos:

* Helado de piña
* Yogurt natural
* Dulce de leche

Campos esperados:

* id
* name
* description
* active
* created_at
* updated_at

---

# Tabla: sections

Propósito:

Secciones académicas.

Ejemplos:

* 7-1
* 7-2
* Procesamiento

Campos esperados:

* id
* name
* active
* created_at
* updated_at

---

# Tabla: productions

Propósito:

Registro principal del sistema.

Cada fila representa una producción realizada.

Campos identificados:

* id
* production_date
* product_id
* raw_materials
* processed_by
* section_id
* quantity
* unit
* custom_unit
* created_by
* created_at
* updated_at
* deleted_at

---

# Relaciones

product_id
→ products.id

processed_by
→ users.id

created_by
→ users.id

section_id
→ sections.id

---

# Soft Delete

La tabla productions utiliza:

deleted_at

Cuando:

deleted_at IS NULL

La producción se considera activa.

---

# Reglas de Producción

## Procesado Por

Usuario que realizó la producción.

Se almacena en:

processed_by

---

## Registrado Por

Usuario que registró la producción.

Se almacena en:

created_by

Normalmente corresponde al usuario autenticado.

---

## Unidad

Opciones estándar:

* Units
* Kilograms
* Grams
* Liters
* Milliliters

Si se selecciona:

Other

Debe utilizarse:

custom_unit

---

# Consultas Frecuentes

Producciones activas:

WHERE deleted_at IS NULL

---

Producciones por sección:

WHERE section_id = ?

---

Producciones por producto:

WHERE product_id = ?

---

Producciones por usuario:

WHERE created_by = ?

---

# Pendientes de Base de Datos

Evaluar agregar a users:

* theme
* language

Para soportar:

* Modo oscuro.
* Cambio de idioma.

---

# Nota Importante

Si en el futuro se comparte un schema.sql actualizado, este documento debe actualizarse para reflejar exactamente la estructura real de la base de datos.

La versión actual representa las tablas y relaciones conocidas durante el desarrollo.
