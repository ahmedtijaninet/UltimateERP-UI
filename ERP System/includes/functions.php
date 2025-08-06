<?php

/**
 * Utility Functions
 *
 * This file contains helper functions used throughout the application.
 */

// Sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

// Redirect to a new page
function redirect($location) {
    header("Location: " . SITE_URL . "/" . $location);
    exit();
}

// Check for CSRF token
function checkCsrfToken($token) {
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        // Handle CSRF attack
        die('Invalid CSRF token');
    }
}

// Generate CSRF token
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Sets a toast message in the session to be displayed on the next page load.
 * @param {string} message The message to display.
 * @param {string} type 'success' or 'error'.
 */
function setToastMessage($message, $type) {
    $_SESSION['toast_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Loads environment variables from a .env file.
 * @param {string} $path The path to the .env file.
 */
function loadDotEnv($path) {
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
