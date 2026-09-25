# Taskday - Personal Task Manager

Project Code: WST21-PM-2026-SF  
Student Name: Ezekiel P. Sarigumba
Course & Year: BSIT-2nd Year
Database Used: SQLite

Taskday is a simple Laravel personal task manager for capturing, organizing, and completing everyday work. It uses the Laravel flow of routes, controllers, models, migrations, and Blade views.

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status
- Optional descriptions and due dates
- Task summary dashboard
- Responsive layout for desktop and mobile

## Setup

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Install frontend dependencies:

   ```bash
   npm install
   ```

3. Create the environment file and generate the application key:

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. Create the local SQLite database file and run the migrations:

   ```bash
   type nul > database\database.sqlite
   php artisan migrate
   ```

5. Start the application and Vite:

   ```bash
   php artisan serve
   npm run dev
   ```

Open `http://127.0.0.1:8000` in a browser.
