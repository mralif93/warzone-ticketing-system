-- Initialize Warzone Ticketing System Database
-- This script runs when the PostgreSQL container starts for the first time

-- Create database if it doesn't exist (this is handled by POSTGRES_DB env var)
-- CREATE DATABASE IF NOT EXISTS warzone_ticketing;

-- Create user if it doesn't exist (this is handled by POSTGRES_USER env var)
-- CREATE USER IF NOT EXISTS warzone_user WITH PASSWORD 'warzone_password';

-- Grant privileges
-- GRANT ALL PRIVILEGES ON DATABASE warzone_ticketing TO warzone_user;

-- Set timezone
SET timezone = 'UTC';

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- Log initialization
DO $$
BEGIN
    RAISE NOTICE 'Warzone Ticketing System database initialized successfully';
END $$;
