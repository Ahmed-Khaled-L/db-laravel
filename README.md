# Inventory & Custody Management System

A comprehensive web application built with **Laravel 12** designed to streamline the management of organizational assets, inventory, personnel custody, and store auditing. This system offers a centralized dashboard for tracking items, managing master data, and generating detailed custody reports.

## 🚀 Features

### 1. Master Data Management
Control the core entities of your organization with full CRUD capabilities:
* **Items**: Maintain a central catalog of all trackable items.
* **Categories**: Organize items with a hierarchical structure supporting composite keys (ID + Type).
* **Departments**: Manage organizational units.
* **Employees**: detailed staff records for custody assignment.
* **Stores**: Manage warehouse locations and assign specific items to them.
* **Registers**: Manage system registries.

### 2. Custody & Inventory Operations
Track the lifecycle and location of every item:
* **Personnel Custody**: workflows for assigning items to employees, handling returns, and auditing current holdings.
* **Inventory Custody**: Manage stock levels, audit store inventories, and track movements.
* **Inquiry System**: Dedicated interfaces for quick lookup of inventory status and personnel custody history.

### 3. Asset Management
* **Asset Tracking**: Specialized module for managing long-term fixed assets.
* **Reporting**: Generate status reports for assets.

### 4. Reporting & Auditing
* **Custody Reports**: View detailed snapshots of who holds which items.
* **Audit Logs**: Track changes and verify inventory accuracy.

---

## 🛠️ Tech Stack & Dependencies

This project utilizes a modern stack for performance and developer experience:

### Backend
* **PHP**: `^8.5`
* **Framework**: `Laravel 12.0`
* **Database**: MySQL / MariaDB (Standard Laravel support) / Sqlite

### Frontend
* **Styling**: `Tailwind CSS ^4.0`

---

## ⚙️ Installation & Setup

### Prerequisites
Ensure your environment meets the following requirements:
* [PHP](https://www.php.net/) >= 8.5
* [Composer](https://getcomposer.org/)

### Automated Setup (Recommended)
This project includes a custom composer script that handles the entire setup process (dependencies, environment, keys, migrations, and frontend build).

1.  **Clone the repository**
    ```bash
    git clone [https://github.com/your-username/your-repo.git](https://github.com/your-username/your-repo.git)
    cd your-repo
    ```

2.  **Run the setup command**
    ```bash
    composer run setup
    ```
    This single command performs the following:
    1.  `composer install`: Installs PHP dependencies.
    2.  Copies `.env.example` to `.env` (if it doesn't exist).
    3.  `php artisan key:generate`: Generates the application key.
    4.  `php artisan migrate --force`: Runs database migrations.

### Manual Setup
If you prefer to run steps individually:

1.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

2.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: Configure your database credentials in the `.env` file before migrating.*

3.  **Database Migration**
    ```bash
    php artisan migrate
    ```
    
4.  **runs the Laravel server**
    ```bash
    php artisan serve
    ```
    
5.  **Access the Application**
   Open your browser and navigate to: http://localhost:8000

6.  **Database Seeding**
    ```bash
    php artisan db:seed
    ```

## 📂 Project Structure

* **`app/Http/Controllers`**
    Core logic for Assets, Custody, Reports, and Master Data.

* **`routes/web.php`**
    Route definitions for the Dashboard, Resources, and Reporting modules.

* **`resources/views`**
    Blade templates for the user interface.

* **`database/migrations`**
    Schema definitions for the system tables.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1.  Fork the project
2.  Create your feature branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## 📄 License

The Laravel framework is open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
---
