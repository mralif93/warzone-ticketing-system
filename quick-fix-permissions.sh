#!/bin/bash

# Quick Permission Fix for Laravel Server
# Run this ON YOUR SERVER as root

echo "==================================="
echo "Fixing Laravel Storage Permissions"
echo "==================================="
echo ""

# Navigate to project directory
cd /home/webwar/warzone-ticketing-system || {
    echo "❌ Error: Could not find project directory"
    exit 1
}

echo "📍 Working directory: $(pwd)"
echo ""

# Fix the log file ownership (CRITICAL)
echo "Step 1: Fixing laravel.log ownership..."
chown webwar:webwar storage/logs/laravel.log
chmod 664 storage/logs/laravel.log
echo "✓ Log file ownership fixed"
echo ""

# Fix all storage directories
echo "Step 2: Fixing storage permissions..."
chmod -R 775 storage
chown -R webwar:webwar storage
echo "✓ Storage permissions fixed"
echo ""

# Fix bootstrap/cache
echo "Step 3: Fixing bootstrap/cache permissions..."
chmod -R 775 bootstrap/cache
chown -R webwar:webwar bootstrap/cache
echo "✓ Bootstrap cache permissions fixed"
echo ""

# Clear Laravel caches
echo "Step 4: Clearing Laravel caches..."
sudo -u webwar php artisan config:clear
sudo -u webwar php artisan cache:clear
sudo -u webwar php artisan route:clear
sudo -u webwar php artisan view:clear
echo "✓ Caches cleared"
echo ""

# Verify
echo "Step 5: Verifying fix..."
echo ""
echo "📁 Current permissions:"
ls -lh storage/logs/laravel.log
echo ""

# Test write access
echo "Step 6: Testing write access..."
TEST_FILE="storage/logs/fix-test-$$.txt"
if sudo -u webwar touch "$TEST_FILE" 2>/dev/null; then
    rm -f "$TEST_FILE"
    echo "✅ SUCCESS: Laravel can now write to storage/logs/"
else
    echo "❌ FAILED: Still cannot write to storage/logs/"
fi

echo ""
echo "==================================="
echo "Fix complete!"
echo "==================================="
