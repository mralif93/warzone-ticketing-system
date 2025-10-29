#!/bin/bash

# ============================================
# Warzone Ticketing System - Cron Setup Script
# ============================================
# This script sets up automatic pending order cancellation
# Run this on your server to configure the cron job
# ============================================

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Print colored message
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_header() {
    echo ""
    echo -e "${BLUE}=============================================="
    echo -e "  Warzone Ticketing System - Cron Setup"
    echo -e "==============================================${NC}"
    echo ""
}

# Detect OS
detect_os() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS=$ID
        VER=$VERSION_ID
    else
        print_error "Cannot detect OS. Exiting."
        exit 1
    fi
}

# Check if running as root
check_root() {
    if [ "$EUID" -eq 0 ]; then 
        print_error "Please do not run this script as root. Use your regular user account."
        exit 1
    fi
}

# Get project path
get_project_path() {
    # Try to detect current directory
    CURRENT_DIR=$(pwd)
    
    if [ -f "artisan" ]; then
        PROJECT_PATH="$CURRENT_DIR"
        print_message "Detected project path: $PROJECT_PATH"
    else
        echo ""
        print_warning "Artisan file not found in current directory."
        read -p "Enter full path to project directory: " PROJECT_PATH
        
        if [ ! -f "$PROJECT_PATH/artisan" ]; then
            print_error "Artisan file not found at: $PROJECT_PATH"
            exit 1
        fi
    fi
}

# Check if Laravel project
check_laravel() {
    if [ ! -f "$PROJECT_PATH/artisan" ]; then
        print_error "This doesn't appear to be a Laravel project (artisan file not found)"
        exit 1
    fi
}

# Test the command
test_command() {
    print_message "Testing the cancellation command..."
    cd "$PROJECT_PATH"
    
    if php artisan orders:cancel-pending; then
        print_message "✅ Command works correctly!"
    else
        print_error "❌ Command failed! Please check your Laravel setup."
        exit 1
    fi
}

# Setup cron job for user
setup_user_cron() {
    print_message "Setting up cron job for user: $(whoami)"
    
    # Check if cron job already exists
    if crontab -l 2>/dev/null | grep -q "orders:cancel-pending"; then
        print_warning "Cron job already exists!"
        read -p "Do you want to replace it? (y/n): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            print_message "Skipping cron job setup."
            return
        fi
    fi
    
    # Create temporary crontab file
    TEMP_CRON=$(mktemp)
    crontab -l 2>/dev/null > "$TEMP_CRON" || true
    
    # Remove old entry if exists
    grep -v "orders:cancel-pending" "$TEMP_CRON" > "${TEMP_CRON}.tmp" || true
    mv "${TEMP_CRON}.tmp" "$TEMP_CRON"
    
    # Add new cron job
    echo "# Warzone Ticketing System - Cancel pending orders every 5 minutes" >> "$TEMP_CRON"
    echo "*/5 * * * * cd $PROJECT_PATH && php artisan orders:cancel-pending >> /dev/null 2>&1" >> "$TEMP_CRON"
    
    # Install new crontab
    crontab "$TEMP_CRON"
    rm "$TEMP_CRON"
    
    print_message "✅ Cron job added successfully!"
}

# Setup cron job for web server user
setup_webserver_cron() {
    # Detect web server user
    if id "www-data" &>/dev/null; then
        WEB_USER="www-data"
    elif id "nginx" &>/dev/null; then
        WEB_USER="nginx"
    elif id "apache" &>/dev/null; then
        WEB_USER="apache"
    else
        print_warning "No web server user detected. Skipping web server cron setup."
        return
    fi
    
    print_message "Web server user detected: $WEB_USER"
    read -p "Do you want to setup cron for web server user? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        return
    fi
    
    # Setup cron for web server user
    sudo crontab -u "$WEB_USER" -l 2>/dev/null > /tmp/cron_webserver || true
    
    # Remove old entry if exists
    grep -v "orders:cancel-pending" /tmp/cron_webserver > /tmp/cron_webserver.tmp || true
    mv /tmp/cron_webserver.tmp /tmp/cron_webserver
    
    # Add new cron job
    echo "# Warzone Ticketing System - Cancel pending orders every 5 minutes" >> /tmp/cron_webserver
    echo "*/5 * * * * cd $PROJECT_PATH && php artisan orders:cancel-pending >> /dev/null 2>&1" >> /tmp/cron_webserver
    
    # Install new crontab
    sudo crontab -u "$WEB_USER" /tmp/cron_webserver
    rm /tmp/cron_webserver
    
    print_message "✅ Cron job added for $WEB_USER!"
}

# Check cron service
check_cron_service() {
    detect_os
    
    if [ "$OS" = "ubuntu" ] || [ "$OS" = "debian" ]; then
        if ! systemctl is-active --quiet cron 2>/dev/null; then
            print_warning "Cron service is not running!"
            echo ""
            read -p "Do you want to start and enable cron service? (y/n): " -n 1 -r
            echo
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                sudo systemctl start cron
                sudo systemctl enable cron
                print_message "✅ Cron service started and enabled!"
            else
                print_warning "Cron service is not running. Cron jobs will not execute."
            fi
        else
            print_message "✅ Cron service is running"
        fi
    elif [ "$OS" = "centos" ] || [ "$OS" = "rhel" ] || [ "$OS" = "fedora" ]; then
        if ! systemctl is-active --quiet crond 2>/dev/null; then
            print_warning "Crond service is not running!"
            echo ""
            read -p "Do you want to start and enable crond service? (y/n): " -n 1 -r
            echo
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                sudo systemctl start crond
                sudo systemctl enable crond
                print_message "✅ Crond service started and enabled!"
            else
                print_warning "Crond service is not running. Cron jobs will not execute."
            fi
        else
            print_message "✅ Crond service is running"
        fi
    else
        print_warning "Unknown OS: $OS. Please manually check cron service."
    fi
}

# Verify cron job
verify_cron_job() {
    print_message "Verifying cron jobs..."
    echo ""
    echo -e "${BLUE}Current cron jobs for $(whoami):${NC}"
    crontab -l | grep -A 1 "orders:cancel-pending" || print_warning "No cron job found for current user"
    echo ""
}

# Show monitoring commands
show_monitoring() {
    echo -e "${BLUE}=============================================="
    echo -e "  Setup Complete! Monitoring Commands"
    echo -e "=============================================="
    echo ""
    echo "To check cron logs:"
    echo "  sudo tail -f /var/log/syslog | grep CRON"
    echo ""
    echo "To check Laravel logs:"
    echo "  tail -f $PROJECT_PATH/storage/logs/laravel.log"
    echo ""
    echo "To test the command manually:"
    echo "  cd $PROJECT_PATH"
    echo "  php artisan orders:cancel-pending"
    echo ""
    echo "To view your cron jobs:"
    echo "  crontab -l"
    echo ""
}

# Main execution
main() {
    print_header
    
    check_root
    detect_os
    get_project_path
    check_laravel
    
    print_message "This script will set up automatic pending order cancellation."
    echo ""
    read -p "Continue? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_message "Setup cancelled."
        exit 0
    fi
    
    test_command
    setup_user_cron
    
    # Ask about web server cron
    echo ""
    read -p "Do you want to setup cron for web server user? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        setup_webserver_cron
    fi
    
    check_cron_service
    verify_cron_job
    show_monitoring
    
    echo ""
    print_message "✅ Setup completed successfully!"
    echo ""
}

# Run main function
main

