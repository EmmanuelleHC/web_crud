
# Project Setup Instructions

## Prerequisites
Ensure the following are installed on your system before proceeding:

- **PHP 8.2 or above**
- **MySQL** 
- **Composer**
-  **Laravel 11**

## Installation and Setup

1. **Clone the Repository**  
   Run the following command to clone the repository:
   ```bash
   git clone <repository_url>
   cd <repository_folder>
   ```

2. **Install Dependencies**  
   Install the required PHP dependencies using Composer:
   ```bash
   composer install
   ```

3. **Create a Database**  
   Use the following command to create the database:
   ```bash
   php artisan db:create
   ```

4. **Run Migrations**  
   Apply database migrations to set up the schema:
   ```bash
   php artisan migrate
   ```

5. **Seed the Database**  
   Populate the database with initial data (if applicable):
   ```bash
   php artisan db:seed
   ```

6. **Serve the Application**  
   Start the application using the built-in PHP server:
   ```bash
   php artisan serve
   ```
   By default, the application will be accessible at `http://localhost:8000`.

## Default User Login
A default user is already created for testing purposes. Use the following credentials to log in:

- **Email:** `test@example.com`  
- **Password:** `password`

## Additional Notes
- Ensure your `.env` file is properly configured with your database credentials and other necessary settings **before running the `php artisan db:create` command**.
