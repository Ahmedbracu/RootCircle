RootCircle
A Social Networking and Resource-Sharing Platform for Plant Enthusiasts
CSE470: Software Engineering Project
Section: 12
Faculty: Soumya Swagata Biswas

Contributers:
24101655	Tasik Mohammad Mostafiz
23301585	Ahmed Abu Bakar
23201377	Khalid Mumin Chowdhury
24201456	Zarin Tasnim Sneha

Tech Stack
Framework: Laravel 12.x (PHP 8.2+)
Architecture: Model-View-Controller (MVC)
Database: MySQL
Frontend / Templating: Laravel Blade, Bootstrap 5, Bootstrap Icons
Image Uploads: Handled directly in public/ directory

Follow these steps to run RootCircle locally on your computer:

1. Prerequisites
Ensure you have the following installed:
PHP 8.2+ (with pdo_mysql, curl, mbstring, fileinfo extensions enabled)
Composer (getcomposer.org
)
MySQL Server (via MySQL 8.x, XAMPP, or Laragon)
Git

2. Clone the Repository
Open your terminal (PowerShell, Git Bash, or Command Prompt):
bash
git clone https://github.com/Ahmedbracu/RootCircle.git
cd RootCircle

3. Install PHP Dependencies
bash
composer install

4. Set Up Environment File (.env)
Copy .env.example to create your own .env file:
Windows (PowerShell / CMD):
powershell
copy .env.example .env
Open .env in VS Code or Notepad and verify your MySQL database credentials:
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rootcircle
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

5. Generate Application Key
bash
php artisan key:generate

6. Create the Database & Run Migrations
Make sure your MySQL server is running, then create the database:
You can create the database named rootcircle in MySQL Workbench, phpMyAdmin, or terminal:
sql
CREATE DATABASE rootcircle;
Run the migrations to create all tables:
bash
php artisan migrate

7. Start the Local Server
bash
php artisan serve

Open your browser and visit:
http://127.0.0.1:8000
(automatically redirects to login)
