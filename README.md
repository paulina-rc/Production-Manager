# Production Manager

Web application for recording and reviewing agro-industrial production at an educational institution. Replaces paper record-keeping with role-based access, period reports, and Excel/PDF export.

## Features

- **Authentication and roles** — hashed passwords, three roles, and admin-driven password reset (temporary password shown once, forced change on next login).
- **Productions** — create, edit, view, and search records. Professors can only edit their own.
- **Products and sections** — create, edit, and toggle active status. Never deleted, to preserve traceability.
- **Reports** — weekly, monthly, semester, and yearly, with quantity totals by product and by section. Quantities in different units are never summed together.
- **Export** — any report to Excel or PDF, including totals.
- **Interface** — CSS variable theming, light and dark mode, hover-expanding sidebar, tables that stack as cards on phones, and a 7-day production chart.

## Roles

| | Admin | Professor | Administration |
|---|:---:|:---:|:---:|
| Record productions | Yes | Yes | Yes |
| Edit any production | Yes | Own only | Yes |
| Manage products, sections, users | Yes | No | No |
| Reset passwords | Yes | No | No |
| View and export reports | No | No | Yes |

Permissions are enforced server-side on every page, not by hiding links.

## Tech stack

Plain PHP, PDO, MySQL. HTML, CSS, JavaScript, Chart.js, Font Awesome. Uses `phpoffice/phpspreadsheet` and `dompdf/dompdf`.

## Author

**Paulina Rojas** — [@paulina-rc](https://github.com/paulina-rc)

## License

MIT License. See [LICENSE](LICENSE) for details.
