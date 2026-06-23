# Production Manager

Production Manager is a web-based application designed to manage and monitor production processes. The project was developed to centralize information related to products and production records.

The system aims to replace manual record-keeping processes with a digital platform that improves organization, accessibility, and data traceability. 

## Project Overview

The primary goal of Production Manager is to provide an integrated solution for managing agro-industrial production activities. The platform allows institutions to maintain accurate records of production operations while ensuring accountability through user identification and activity tracking.

## Features

The application currently includes user authentication and authorization, product management, section management, production tracking, and reporting capabilities.

Users can create and manage production records, associate products with specific sections, and maintain a complete history of production activities. The system also includes password recovery functionality and role-based permissions to ensure secure access to sensitive information.

Reporting tools allow users to generate monthly, semester-based, and annual production reports, providing valuable insights into operational performance over time.

## Technology Stack

The backend of the application is developed using PHP and MySQL, following a structured architecture that emphasizes maintainability and scalability.

The frontend is built with HTML, CSS, and JavaScript, providing a responsive and user-friendly interface.

Development and deployment are supported by tools such as XAMPP, phpMyAdmin, Git, and GitHub.

## Project Structure

```text
production-manager/
│
├── assets/
│   ├── css/
│   ├── img/
│   └── js/
│
├── auth/
├── config/
├── dashboard/
├── database/
├── includes/
├── productions/
├── products/
├── reports/
├── sections/
├── users/
│
└── README.md
```

## Installation

To run the project locally, clone the repository and place it inside the `htdocs` directory of your XAMPP installation.

Create a MySQL database and import the `schema.sql` file included in the project. Once the database has been configured, start Apache and MySQL from the XAMPP Control Panel and access the application through your browser.

```bash
git clone https://github.com/your-username/production-manager.git
```

The application will be available at:

```text
http://localhost/production-manager
```

## Current Status

Production Manager is currently under active development. Core modules such as user management, product management, section administration, production tracking, and reporting are already functional.

Ongoing work focuses on interface improvements, dashboard enhancements, report exports, and additional administrative features aimed at improving usability and system efficiency.

## Author

**Paulina Rojas** — [@paulina-rc](https://github.com/paulina-rc)
