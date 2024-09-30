# Laravel Project Manager

This is a Laravel-based project management application. It allows users to register, log in, and create/manage projects. The application uses Docker for containerization, making it easy to set up and run.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started

Follow these steps to get the application up and running using Docker.

### Step 1: Clone the Repository

Clone this repository to your local machine:

```bash
git clone git@github.com:justaydan/TaskManagement.git
cd TaskManagement
```
### Step 2: Set Up Environment Variables

Copy the .env.example file to .env:
```bash
cp .env.example .env
```
Modify the .env file if necessary (e.g., setting up database credentials or environment-specific configurations).

### Step 3: Run Docker Containers
Now, you can build and run the Docker containers. This will set up the web application, database, and any necessary services:
```bash
docker-compose up -d
```
The -d flag ensures that the services run in the background (detached mode).
Docker Compose will set up the web server, PHP, and MySQL containers for you.

### Step 4: Install Dependencies
After the containers are running, you'll need to install the project dependencies. You can do this by running the following command inside the web container:
```bash
docker-compose exec task_management composer install
```

### Step 5: Run Migrations
Run Laravel migrations to set up the database schema:
```bash
docker-compose exec task_management php artisan migrate

```

### Step 6: Access the Application
After the containers are up and running, you can access the application in your browser at:

```bash
http://localhost:8000
```
