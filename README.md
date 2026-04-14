# Java CRM

**Java CRM** is a CRM (Customer Relationship Management) application built on top of Laravel and Vue.js framework.

## Features

-   Leads Management
-   Quotes Management
-   Email Integration (IMAP)
-   Activities Scheduling
-   Contacts & Organizations
-   Products Management
-   Workflow Automation
-   Role-Based Access Control
-   Dashboard with Analytics
-   Web Forms

## Requirements

-   **PHP**: 8.2 or higher
-   **MySQL**: 8.0 or higher
-   **Node.js**: 18 or higher
-   **Composer**: 2.x

## Installation

1. Clone the repository:

```bash
git clone <your-repo-url>
cd java-crm
```

2. Install dependencies:

```bash
composer install
```

3. Copy environment file:

```bash
cp .env.example .env
```

4. Configure your `.env` file with database credentials.

5. Run the installer:

```bash
php artisan java-crm:install
```

6. Start the development server:

```bash
php artisan serve
```

7. Access the application at `http://localhost:8000/admin/login`

## License

[MIT License](LICENSE)
