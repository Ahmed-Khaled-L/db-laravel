#!/bin/bash
set -e

echo "🚀 Starting deployment process..."

# 1. Setup .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from example..."
    cp .env.example .env
fi

# 2. Check if we need to seed (If database doesn't exist yet, we mark it for seeding)
SHOULD_SEED=false
if [ ! -f database/database.sqlite ]; then
    echo "🗄️  Creating database.sqlite file..."
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
    SHOULD_SEED=true
fi

# 3. Generate Application Key if not set
if grep -q "APP_KEY=" .env && [ -z "$(grep "APP_KEY=" .env | cut -d '=' -f2)" ]; then
    echo "🔑 Generating Application Key..."
    php artisan key:generate
fi

# 4. Run Database Migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# 5. Run Seeders (Only if the database was just created)
if [ "$SHOULD_SEED" = true ]; then
    echo "🌱 Seeding database with initial data..."
    php artisan db:seed --force
else
    echo "⏩ Database already exists. Skipping seeders to prevent duplicates."
fi

# 6. Optimize for Production
echo "⚡ Caching configuration for speed..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Setup complete. Starting Server..."

# 7. Execute the main container command (Apache)
exec "$@"
