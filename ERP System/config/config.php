<?php

/**
 * Global Configuration
 *
 * This file contains global settings for the application.
 * It uses environment variables for security and flexibility.
 */

// Site Details
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'ERP System');
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost/erp-system');

// Security
define('SESSION_TIMEOUT', $_ENV['SESSION_TIMEOUT'] ?? 3600);
define('CSRF_TOKEN_SECRET', $_ENV['CSRF_TOKEN_SECRET'] ?? 'your-secret-key-here');

// Debugging
define('DEBUG_MODE', ($_ENV['DEBUG_MODE'] ?? 'true') === 'true');

// Other settings
date_default_timezone_set('UTC');
