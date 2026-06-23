# DATABASE_CONTEXT.md

# Database

**Name:**
`production_manager`

**Database Engine:**
MySQL

**Access Method:**
PDO

---

# Table: roles

## Purpose

Defines the general permissions and access levels within the system.

## Known Fields

* id (PK)
* role_name

## Examples

* 1 = admin
* 2 = professor

## Relationship

`roles.id` → `users.role_id`

---

# Table: users

## Purpose

System users who can access the application.

## Known Fields

* id
* full_name
* email
* password
* role_id
* active
* last_login
* created_at
* updated_at

## Relationships

`users.role_id` → `roles.id`

---

# Table: products

## Purpose

Agro-industrial products that can be produced and recorded in the system.

## Examples

* Pineapple Ice Cream
* Natural Yogurt
* Dulce de Leche

## Expected Fields

* id
* name
* description
* active
* created_at
* updated_at

---

# Table: sections

## Purpose

Academic sections or groups associated with production activities.

## Examples

* 7-1
* 7-2
* Processing

## Expected Fields

* id
* name
* active
* created_at
* updated_at

---

# Table: productions

## Purpose

Main production records of the system.

Each row represents a completed production process.

## Identified Fields

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

# Relationships

## Product

`product_id` → `products.id`

## Processed By

`processed_by` → `users.id`

## Created By

`created_by` → `users.id`

## Section

`section_id` → `sections.id`

---

# Soft Delete

The `productions` table uses the field:

`deleted_at`

When:

```sql
deleted_at IS NULL
```

the production record is considered active.

---

# Production Rules

## Processed By

Represents the user who carried out the production process.

Stored in:

`processed_by`

---

## Recorded By

Represents the user who registered the production record.

Stored in:

`created_by`

This typically corresponds to the authenticated user.

---

## Unit

Standard options:

* Units
* Kilograms
* Grams
* Liters
* Milliliters

If the selected option is:

`Other`

then the field:

`custom_unit`

must be used.

---

# Common Queries

## Active Productions

```sql
WHERE deleted_at IS NULL
```

## Productions by Section

```sql
WHERE section_id = ?
```

## Productions by Product

```sql
WHERE product_id = ?
```

## Productions by User

```sql
WHERE created_by = ?
```

---

# Pending Database Improvements

Consider adding the following fields to the `users` table:

* theme
* language

To support:

* Dark mode
* Language switching

---

# Important Note

If an updated `schema.sql` file is provided in the future, this document should be updated to accurately reflect the actual database structure.

The current version represents the tables, fields, and relationships known during the development phase of the project.
