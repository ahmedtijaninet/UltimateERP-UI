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
