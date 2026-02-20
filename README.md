# Steganography Laravel Gateway

A Laravel-based gateway for the [Steganography API microservice](https://github.com/Emad88/steganography-slim-api).

The gateway provides:

- User authentication
- Image upload & storage (S3-compatible via MinIO)
- Dashboard UI
- Proxy endpoints for encoding/decoding messages in images

The actual steganography algorithms run in the external microservice.


## Tech stack

- **Laravel 12** - web gateway & API proxy
- **MySQL** - application database
- **MinIO** - S3-compatible object storage
- **Tailwind CSS** - UI styling
- **Laravel Sail** - local Docker environment

## Prerequisites

- Docker
- Docker Compose
- The Steganography API microservice running locally

Start the microservice first: https://github.com/Emad88/steganography-slim-api

Default expected URL: `http://localhost:8080`


## Running locally

Clone the repository:

```
git clone https://github.com/Emad88/steganography-laravel-gateway
cd steganography-laravel-gateway/app
```

Copy environment file:

```
cp .env.example .env
```

Start Sail:

```
./vendor/bin/sail up -d
```

Run migrations:

```
./vendor/bin/sail artisan migrate
```

## Accessing services

| Service | URL | Credentials |
|--------|-----|------------|
| Laravel Gateway | http://localhost | create an account |
| phpMyAdmin | http://localhost:8081 | `sail` / `password` |
| MinIO Console | http://localhost:9101 | `sail` / `password` |
| Steganography API | http://localhost:8080 | — |


## Adding new Steganography APIs

After adding a new encoding endpoint in the Steganography API microservice, register it in:

```
return [
    'steganography' => [
        'base_url' => env('STEGANOGRAPHY_API_BASE_URL', 'http://localhost:8080'),
        'encodings' => ['alpha', 'bit', 'new'],
    ],
];
```

Each encoding name corresponds to an API path:

```
POST /{encoding}/encode
POST /{encoding}/decode
```

## Linting & formatting

This project uses **Laravel Pint** for PHP code style.

Run the linter:

```
./vendor/bin/sail pint
```