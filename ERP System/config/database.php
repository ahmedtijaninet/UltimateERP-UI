<?php

/**
 * Database Configuration
 *
 * This file defines the database connection parameters.
 * It uses environment variables for security and flexibility.
 */

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USERNAME', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'erp_system');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');
