<?php

/**
 * Validator Class
 *
 * Handles input validation.
 */
class Validator {
    private $errors = [];

    // Validate email
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format.";
            return false;
        }
        return true;
    }

    // Validate required field
    public function validateRequired($value, $field_name) {
        if (empty($value)) {
            $this->errors[] = "{$field_name} is required.";
            return false;
        }
        return true;
    }

    // Validate password strength
    public function validatePassword($password) {
        if (strlen($password) < 8) {
            $this->errors[] = "Password must be at least 8 characters long.";
            return false;
        }
        return true;
    }

    // Get validation errors
    public function getErrors() {
        return $this->errors;
    }

    // Check if validation passed
    public function isValid() {
        return empty($this->errors);
    }
}
