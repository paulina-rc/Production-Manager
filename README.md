# Production Manager

Production Manager is a web-based application for managing and monitoring agro-industrial production processes at an educational institution. It replaces manual record-keeping with a digital platform that improves organization, accessibility, and data traceability.

The **Productions** module is the core of the system and takes priority over all others.

## Features

- **Authentication & roles**: login, password recovery via single-use tokens, and role-based access (Admin, Professor, Administration).
- **Product & section management**: create, edit, and toggle active status.
- **User management**: admin-controlled only (no public registration).
- **Productions**: create, edit, view, and search records, with permission rules (professors can only edit their own records).
- **Reports**: weekly, monthly, semester, and yearly, accessible from a central reports index.
- **Excel/PDF export**: available only to Admin and Administration roles.
- **Modern UI**: CSS variable-based design system, layered shadows, fixed top header, and a 7-day production trend chart.
- **Light/dark mode**: persistent toggle via `localStorage`, applied across all views.
- **Branding**: institutional logo in sidebar, favicon, and login screen.
- **Spanish UI**: all interface text standardized to Spanish.

## Roles

| Role | role_id | Access |
|---|---|---|
| Admin | 1 | Full system access |
| Professor | 2 | Dashboard, Productions (own records), Reports, Profile |
| Administration | 3 | Dashboard, Reports (with Excel/PDF export), Profile |

## Tech Stack

**Backend:** Plain PHP, PDO, MySQL
**Frontend:** HTML, CSS (variable-based theming), JavaScript, Chart.js, Font Awesome
**PHP libraries:** `phpmailer/phpmailer`, `phpoffice/phpspreadsheet`, `dompdf/dompdf`
**Dev environment:** XAMPP, phpMyAdmin, Git, GitHub

## Project Structure

```text
production-manager/
│
├── assets/            # CSS (theme system), images, JS (theme.js)
├── auth/              # Login, logout, password recovery
├── config/            # DB connection, sessions, permissions, mail, export
├── dashboard/
├── database/          # schema.sql, seed.sql
├── includes/          # header, footer, sidebar
├── productions/       # Core module
├── products/
├── reports/           # Weekly, monthly, semester, yearly + export
├── sections/
├── users/
│
└── README.md
```

## Installation

```bash
git clone https://github.com/paulina-rc/Production-Manager.git
composer install
cp .env.example .env
```

Create a MySQL database, import `database/schema.sql` and `database/seed.sql`, set your credentials in `.env`, then start Apache and MySQL via XAMPP and visit:

```text
http://localhost/production-manager
```

## Current Status

Core modules are fully functional: authentication, users, products, sections, productions, and reports, along with Excel/PDF export, role-based permissions, and a redesigned UI with light/dark mode support.

## Author

**Paulina Rojas** — [@paulina-rc](https://github.com/paulina-rc)

## License

MIT License. See [LICENSE](LICENSE) for details.
