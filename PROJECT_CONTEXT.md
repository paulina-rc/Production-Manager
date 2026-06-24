# PROJECT_CONTEXT.md

# Production Manager

Production Manager is an Agro-Industrial Production Management System developed for a technical educational institution.

---

# Project Objective

The purpose of the system is to allow the registration, management, tracking, and reporting of agro-industrial production activities carried out by students and teachers.

The system is designed to facilitate the management of:

* Produced products
* Production records
* System users
* Academic sections
* Historical reports

---

# Technology Stack

## Backend

* Pure PHP
* PDO

## Database

* MySQL

## Local Development Environment

* XAMPP

## Frontend

* HTML5
* CSS3
* JavaScript (minimal usage)

No PHP frameworks are used in this project.

---

# Development Philosophy

Before implementing any feature:

1. Analyze the current architecture.
2. Review existing functionality.
3. Avoid duplicate implementations.
4. Work in small and manageable sprints.
5. Prioritize consistency.
6. Keep the code simple and maintainable.
7. Explain the impact of every change.

---

# Project Structure

```text
production-manager/

├── assets/
│   ├── css/
│   ├── img/
│   └── js/
│
├── auth/
│
├── config/
│   ├── auth.php
│   └── database.php
│
├── dashboard/
│
├── database/
│
├── includes/
│   ├── footer.php
│   ├── header.php
│   ├── navbar.php
│   └── sidebar.php
│
├── products/
├── sections/
├── users/
├── productions/
├── reports/
│
└── index.php
```

---

# Current Status

## Authentication System

Implemented:

* Login
* Logout
* Session management
* Route protection

---

## Dashboard

Implemented.

Includes:

* Statistics cards
* General overview
* Quick access shortcuts

The dashboard is currently undergoing a visual redesign.

---

## Users

Implemented:

* User listing
* User creation
* User editing
* User profile

Current profile features:

* Personal information
* Password change functionality (in development)
* Prepared for language and theme preferences

---

## Products

Implemented:

* Fully functional CRUD operations
* Active / inactive status management

Products should not be physically deleted whenever possible.

---

## Sections

Implemented:

* Fully functional CRUD operations

Business rule:

A production record belongs to a single academic section.

---

## Productions

This is the core module of the system.

Implemented:

* Production listing
* Production registration
* Production editing
* Detailed production view

This module has priority over all other modules.

Most visual and usability improvements should focus on this area.

---

# Business Rules

## Production Record

Each production entry stores:

* Production date
* Product
* Raw materials
* Processed by
* Recorded by
* Section
* Quantity
* Unit
* Custom unit
* Creation date

---

## Users

The system currently supports role-based access.

Current roles:

* 1 = Administrator
* 2 = Teacher

The architecture should allow future role expansion.

---

## Permissions

### Administrator

* Full system access
* Can modify any production record

### Teacher

Should only have access to:

* Dashboard
* Productions
* Reports
* Profile

Should not have access to:

* Users
* Products
* Sections

Teachers may only edit production records created by themselves.

---

# Visual Design

## Design Goal

Create a modern administrative dashboard experience.

## Inspiration

A professional dashboard featuring:

* Fixed sidebar
* Modern cards
* Modern forms
* Modern tables
* Professional icons
* Clean layout
* Dark green and white color palette

---

# Visual Design Decisions

### Avoid

* Emojis
* Outdated design styles
* Unformatted tables
* Basic forms

### Use

* Font Awesome
* Cards
* Grid layouts
* Modern sidebar navigation
* Consistent design patterns

---

# Future Features

## High Priority

1. Role-based sidebar navigation
2. Complete permission system
3. Full visual redesign of all modules

## Medium Priority

4. Dark mode
5. Language switching

## Future Roadmap

6. Export to Excel
7. Export to Word
8. Recent activity tracking
9. Advanced dashboard analytics

---

# Expected Workflow

When a modification is requested:

* Analyze the requirement first.
* Explain the expected impact.
* Identify the affected files.
* Prefer providing complete replacement files when possible.
* Maintain consistency with the overall project design and architecture.

```
```
