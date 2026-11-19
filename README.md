# RootCircle

## A Social Networking and Resource-Sharing Platform for Plant Enthusiasts

**CSE470: Software Engineering Project**  
**Section:** 12  
**Faculty:** Soumya Swagata Biswas

### Contributors

| ID | Name |
|---|---|
| 24101655 | Tasik Mohammad Mostafiz |
| 23301585 | Ahmed Abu Bakar |
| 23201377 | Khalid Mumin Chowdhury |
| 24201456 | Zarin Tasnim Sneha |

---

## Tech Stack

- **Framework:** Laravel 12.x
- **Backend:** PHP 8.2+
- **Architecture:** Model-View-Controller (MVC)
- **Database:** MySQL
- **Frontend / Templating:** Laravel Blade
- **UI Framework:** Bootstrap 5
- **Icons:** Bootstrap Icons
- **Image Uploads:** Handled directly in the `public/` directory

---

## Installation & Setup

Follow these steps to run **RootCircle** locally on your computer.

### 1. Prerequisites

Make sure the following are installed:

- PHP 8.2+ with the following extensions enabled:
  - `pdo_mysql`
  - `curl`
  - `mbstring`
  - `fileinfo`
- Composer
- MySQL Server through one of the following:
  - MySQL 8.x
  - XAMPP
  - Laragon
- Git

---

### 2. Clone the Repository

Open your terminal, such as PowerShell, Git Bash, or Command Prompt:

```bash
git clone https://github.com/Ahmedbracu/RootCircle.git
cd RootCircle
```

---

### 3. Install PHP Dependencies

Run:

```bash
composer install
```

---

### 4. Set Up the Environment File

Copy `.env.example` and create your own `.env` file.

**Windows PowerShell / CMD:**

```powershell
copy .env.example .env
```

Open the `.env` file in VS Code or another text editor and configure your MySQL database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rootcircle
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Replace `your_mysql_password` with your actual MySQL password.

---

### 5. Generate the Application Key

Run:

```bash
php artisan key:generate
```

---

### 6. Create the Database & Run Migrations

Make sure your MySQL server is running.

Create a database named `rootcircle` using MySQL Workbench, phpMyAdmin, or the MySQL terminal.

```sql
CREATE DATABASE rootcircle;
```

Then run the Laravel migrations:

```bash
php artisan migrate
```

This will create the required database tables for the application.

---

### 7. Start the Local Development Server

Run:

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

Open the address in your browser.

The application automatically redirects to the login page.

---

## Login

After successfully setting up the application, users can register for an account and log in to access the RootCircle platform.

---
