# Inventory & Custody Management System

A comprehensive web application built with **Laravel 12** designed to streamline the management of organizational assets, inventory, personnel custody, and store auditing. This system offers a centralized dashboard for tracking items, managing master data, and generating detailed custody reports.

---

## 🚀 Features

### 1. Master Data Management

Control the core entities of your organization with full CRUD capabilities:

- **Items**: Maintain a central catalog of all trackable items.
- **Categories**: Organize items with a hierarchical structure supporting composite keys (ID + Type).
- **Departments**: Manage organizational units.
- **Employees**: Detailed staff records for custody assignment.
- **Stores**: Manage warehouse locations and assign specific items to them.
- **Registers**: Manage system registries.

### 2. Custody & Inventory Operations

Track the lifecycle and location of every item:

- **Personnel Custody**: Workflows for assigning items to employees, handling returns, and auditing current holdings.
- **Inventory Custody**: Manage stock levels, audit store inventories, and track movements.
- **Inquiry System**: Dedicated interfaces for quick lookup of inventory status and personnel custody history.

### 3. Asset Management

- **Asset Tracking**: Specialized module for managing long-term fixed assets.
- **Reporting**: Generate status reports for assets.

### 4. Reporting & Auditing

- **Custody Reports**: View detailed snapshots of who holds which items.
- **Audit Logs**: Track changes and verify inventory accuracy.

---

## 🛠️ Tech Stack & Dependencies

This project utilizes a modern stack for performance and developer experience.

### Backend
- **PHP**: `^8.5`
- **Framework**: Laravel `12.0`
- **Database**: MySQL / MariaDB / SQLite (Default)

### Frontend
- **Styling**: Tailwind CSS `^4.0`
- **Vanilla JS**

---

## ⚙️ Installation & Setup

We provide three ways to run this application. Choose the one that fits your needs.

---

### Option 1: Windows "Clone & Run" ⚡ (Recommended)

For Windows users who have cloned the repository, we include a launcher script.

> ⚠️ **Prerequisite**: Docker Desktop must be installed and running.

**Clone the repository:**
```bash
git clone https://github.com/ahmed-khaled-l/db-laravel.git
cd db-laravel
````

**Run the launcher:**

- Double-click `docker_builder.bat`, or
    
- Run the following command:
    

```bash
.\docker_builder.bat
```

**Access the App:**

Once the environment is built, open:

```
http://localhost:8080
```

---

### Option 2: Manual Docker (Mac/Linux) 🐳

If you are on Mac/Linux or prefer running commands manually:

**Clone the repository:**

```bash
git clone https://github.com/ahmed-khaled-l/db-laravel.git
cd db-laravel
```

**Run Docker Compose:**

```bash
docker-compose up -d --build
```

> **Note**: This setup automatically handles database creation, migrations, seeding, and serving.

**Access the App:**

```
http://localhost:8080
```

---

### Option 3: Native Setup (Without Docker)

If you prefer to run the application directly on your machine (requires PHP & Composer installed):

**Install dependencies:**

```bash
composer install
npm install && npm run build
```

**Configure environment:**

```bash
cp .env.example .env
php artisan key:generate
```

**Setup database:**

```bash
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed
```

**Run server:**

```bash
php artisan serve
```

---

## 📂 Project Structure

```
app/Http/Controllers
```

Core logic for Assets, Custody, Reports, and Master Data.

```
routes/web.php
```

Route definitions for the Dashboard, Resources, and Reporting modules.

```
resources/views
```

Blade templates for the user interface.

```
database/migrations
```

Schema definitions for the system tables.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
    
2. Create your feature branch:
    
    ```bash
    git checkout -b feature/AmazingFeature
    ```
    
3. Commit your changes:
    
    ```bash
    git commit -m 'Add some AmazingFeature'
    ```
    
4. Push to the branch:
    
    ```bash
    git push origin feature/AmazingFeature
    ```
    
5. Open a Pull Request
    

---

## 📄 License

The Laravel framework is open-sourced software licensed under the **MIT license**.
