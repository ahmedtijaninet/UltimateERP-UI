<?php

/**
 * Global Configuration
 *
 * This file contains global settings for the application.
 */

// Site Details
define('SITE_NAME', 'ERP System');
define('SITE_URL', 'http://localhost/erp-system');

// Security
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_SECRET', 'your-secret-key-here'); // Change this to a random secret key

// Debugging
define('DEBUG_MODE', true);

// Other settings
date_default_timezone_set('UTC');
