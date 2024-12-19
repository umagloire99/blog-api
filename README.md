
<p align="center">
    <h1 align="center">Banking API</h1>
    <h3 align="center">This is a simple blog API built with Laravel and Docker, providing endpoints for managing blog posts, users, and comments. The application uses MySQL as its database and NGINX as its web server.</h3>
<p align="center">
    API Documentation
    <br />
    <a href="http://blog-api-d168872c4b9a.herokuapp.com/"><strong>Explore API docs »</strong></a>
    <br />
    <br />
    <a href="http://blog-api-d168872c4b9a.herokuapp.com/api/">http://blog-api-d168872c4b9a.herokuapp.com/api/</a>
</p>

## Features

- Acess Role Control.
- User authentication and management
- CRUD operations for blog posts
- Commenting system for posts
- Replying comment
- Dockerized environment for easy setup

---

## Requirements

- [Docker](https://www.docker.com/) (with Docker Compose)
- [Git](https://git-scm.com/)

---

## Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/umagloire99/blog-api.git
cd blog-api
```

### Step 2: Setup Environment Variables

```bash
cp .env.example .env
```

### Step 4: Start Docker Containers

```bash
docker-compose up --build
```

## Usage

Run the migration and seed. In order to generate the default admin user that can add posts and manage users.

```bash
docker exec -it blog-api bash -c "php artisan migrate --seed"
```

- Local: [http://localhost:8080](http://localhost:8080)
- Documentation on production: [https://blog-api-d168872c4b9a.herokuapp.com/](http://blog-api-d168872c4b9a.herokuapp.com/)
- API on production: [https://blog-api-d168872c4b9a.herokuapp.com/api](http://blog-api-d168872c4b9a.herokuapp.com/api)

## Testing

Run this command to test the different endpoints

```bash
docker exec -it blog-api bash -c "php artisan test"
```
