# Warzone Ticketing System - Docker Setup

This document provides instructions for running the Warzone Ticketing System using Docker and Docker Compose.

## Prerequisites

- Docker (version 20.10 or higher)
- Docker Compose (version 2.0 or higher)
- Git

## Quick Start

1. **Clone the repository** (if not already done):
   ```bash
   git clone <repository-url>
   cd warzone-ticketing-system
   ```

2. **Create environment file**:
   ```bash
   cp .env.example .env
   ```

3. **Build and start all services**:
   ```bash
   docker-compose up --build -d
   ```

4. **Access the application**:
   - Web Interface: http://localhost
   - Database: localhost:5432
   - Redis: localhost:6379

## Services Overview

The Docker setup includes the following services:

### 1. PostgreSQL Database (`postgres`)
- **Image**: postgres:15-alpine
- **Port**: 5432
- **Database**: warzone_ticketing
- **Username**: warzone_user
- **Password**: warzone_password
- **Data Volume**: `postgres_data`

### 2. Redis Cache (`redis`)
- **Image**: redis:7-alpine
- **Port**: 6379
- **Data Volume**: `redis_data`

### 3. PHP-FPM Application (`app`)
- **Base Image**: php:8.2-fpm-alpine
- **Extensions**: PostgreSQL, Redis, GD, ZIP, etc.
- **Dependencies**: Composer, Node.js
- **Port**: 9000 (internal)

### 4. Nginx Web Server (`nginx`)
- **Image**: nginx:alpine
- **Ports**: 80 (HTTP), 443 (HTTPS)
- **Static Files**: Served directly by Nginx
- **PHP Files**: Proxied to PHP-FPM

### 5. Node.js Build (`node`)
- **Image**: node:18-alpine
- **Purpose**: Build frontend assets with Vite
- **Dependencies**: npm packages

## Environment Variables

Key environment variables in `.env`:

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=warzone_ticketing
DB_USERNAME=warzone_user
DB_PASSWORD=warzone_password

# Redis
REDIS_HOST=redis
REDIS_PORT=6379

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## Docker Commands

### Basic Operations

```bash
# Start all services
docker-compose up -d

# Start with build
docker-compose up --build -d

# Stop all services
docker-compose down

# Stop and remove volumes
docker-compose down -v

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f app
```

### Development Commands

```bash
# Access PHP container
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear

# Access database
docker-compose exec postgres psql -U warzone_user -d warzone_ticketing

# Access Redis
docker-compose exec redis redis-cli

# Rebuild specific service
docker-compose build app
docker-compose up -d app
```

### Database Operations

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback

# Seed database
docker-compose exec app php artisan db:seed

# Fresh migration with seeding
docker-compose exec app php artisan migrate:fresh --seed
```

### Frontend Development

```bash
# Install npm dependencies
docker-compose exec node npm install

# Build assets
docker-compose exec node npm run build

# Watch for changes (development)
docker-compose exec node npm run dev
```

## File Structure

```
warzone-ticketing-system/
├── docker-compose.yaml          # Main Docker Compose configuration
├── docker-entrypoint.sh         # Application startup script
├── .env.example                 # Environment variables template
├── php/
│   ├── Dockerfile              # PHP-FPM container definition
│   ├── php.ini                 # PHP configuration
│   └── startup.sh              # PHP startup script
├── nginx/
│   ├── Dockerfile              # Nginx container definition
│   ├── nginx.conf              # Main Nginx configuration
│   ├── default.conf            # Site configuration
│   └── ssl.conf                # SSL configuration
└── database/
    └── init/
        └── 01-init.sql         # Database initialization script
```

## Troubleshooting

### Common Issues

1. **Permission Issues**:
   ```bash
   # Fix storage permissions
   docker-compose exec app chown -R www-data:www-data /var/www/html/storage
   docker-compose exec app chmod -R 775 /var/www/html/storage
   ```

2. **Database Connection Issues**:
   ```bash
   # Check if database is ready
   docker-compose exec postgres pg_isready -U warzone_user -d warzone_ticketing
   ```

3. **Cache Issues**:
   ```bash
   # Clear all caches
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan view:clear
   ```

4. **Asset Issues**:
   ```bash
   # Rebuild assets
   docker-compose exec node npm run build
   ```

### Logs and Debugging

```bash
# View all logs
docker-compose logs

# View specific service logs
docker-compose logs app
docker-compose logs nginx
docker-compose logs postgres

# Follow logs in real-time
docker-compose logs -f app
```

### Health Checks

The services include health checks:

```bash
# Check service health
docker-compose ps

# Manual health check
curl http://localhost/health
```

## Production Considerations

1. **Security**:
   - Change default passwords
   - Use environment-specific configurations
   - Enable SSL/TLS
   - Configure firewall rules

2. **Performance**:
   - Use production-optimized images
   - Configure resource limits
   - Enable OPcache
   - Use Redis for sessions and cache

3. **Monitoring**:
   - Set up log aggregation
   - Monitor resource usage
   - Configure alerts

4. **Backup**:
   - Regular database backups
   - Volume snapshots
   - Configuration backups

## Support

For issues related to Docker setup, please check:
1. Docker and Docker Compose versions
2. Available system resources
3. Port conflicts
4. Log files for specific errors

For application-specific issues, refer to the main project documentation.
