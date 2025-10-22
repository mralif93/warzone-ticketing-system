#!/bin/bash

# Warzone Ticketing System - Docker Management Scripts

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if Docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        print_error "Docker is not running. Please start Docker and try again."
        exit 1
    fi
}

# Function to setup environment
setup_env() {
    print_status "Setting up environment..."
    
    if [ ! -f ".env" ]; then
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_success "Created .env file from .env.example"
        else
            print_error ".env.example file not found!"
            exit 1
        fi
    else
        print_warning ".env file already exists"
    fi
}

# Function to build and start services
start_services() {
    print_status "Building and starting Docker services..."
    docker-compose up --build -d
    
    print_status "Waiting for services to be ready..."
    sleep 10
    
    print_status "Running Laravel setup commands..."
    docker-compose exec app php artisan key:generate --force
    docker-compose exec app php artisan migrate --force
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    
    print_success "Services started successfully!"
    print_status "Application is available at: http://localhost"
}

# Function to stop services
stop_services() {
    print_status "Stopping Docker services..."
    docker-compose down
    print_success "Services stopped successfully!"
}

# Function to restart services
restart_services() {
    print_status "Restarting Docker services..."
    docker-compose restart
    print_success "Services restarted successfully!"
}

# Function to view logs
view_logs() {
    local service=${1:-""}
    if [ -n "$service" ]; then
        print_status "Viewing logs for $service..."
        docker-compose logs -f "$service"
    else
        print_status "Viewing logs for all services..."
        docker-compose logs -f
    fi
}

# Function to access container shell
shell_access() {
    local service=${1:-"app"}
    print_status "Accessing $service container shell..."
    docker-compose exec "$service" bash
}

# Function to run Laravel commands
artisan() {
    print_status "Running Laravel command: $*"
    docker-compose exec app php artisan "$@"
}

# Function to run Composer commands
composer() {
    print_status "Running Composer command: $*"
    docker-compose exec app composer "$@"
}

# Function to run NPM commands
npm() {
    print_status "Running NPM command: $*"
    docker-compose exec node npm "$@"
}

# Function to show service status
status() {
    print_status "Docker services status:"
    docker-compose ps
}

# Function to clean up
cleanup() {
    print_warning "This will remove all containers, networks, and volumes. Are you sure? (y/N)"
    read -r response
    if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
        print_status "Cleaning up Docker resources..."
        docker-compose down -v --remove-orphans
        docker system prune -f
        print_success "Cleanup completed!"
    else
        print_status "Cleanup cancelled."
    fi
}

# Function to show help
show_help() {
    echo "Warzone Ticketing System - Docker Management Scripts"
    echo ""
    echo "Usage: $0 [COMMAND] [OPTIONS]"
    echo ""
    echo "Commands:"
    echo "  start           Build and start all services"
    echo "  stop            Stop all services"
    echo "  restart         Restart all services"
    echo "  logs [service]  View logs (optionally for specific service)"
    echo "  shell [service] Access container shell (default: app)"
    echo "  artisan [cmd]   Run Laravel artisan command"
    echo "  composer [cmd]  Run Composer command"
    echo "  npm [cmd]       Run NPM command"
    echo "  status          Show services status"
    echo "  cleanup         Remove all containers and volumes"
    echo "  help            Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 start"
    echo "  $0 logs app"
    echo "  $0 shell postgres"
    echo "  $0 artisan migrate"
    echo "  $0 composer install"
    echo "  $0 npm run build"
}

# Main script logic
main() {
    check_docker
    
    case "${1:-help}" in
        "start")
            setup_env
            start_services
            ;;
        "stop")
            stop_services
            ;;
        "restart")
            restart_services
            ;;
        "logs")
            view_logs "$2"
            ;;
        "shell")
            shell_access "$2"
            ;;
        "artisan")
            shift
            artisan "$@"
            ;;
        "composer")
            shift
            composer "$@"
            ;;
        "npm")
            shift
            npm "$@"
            ;;
        "status")
            status
            ;;
        "cleanup")
            cleanup
            ;;
        "help"|*)
            show_help
            ;;
    esac
}

# Run main function with all arguments
main "$@"
