<?php

/**
 * Authentication Service
 *
 * Handles user authentication logic (login, logout, registration).
 * This is a service class, not a controller.
 */
class AuthService {
    private $db;

    public function __construct() {
        $this->db = new Database;
        session_start();
    }

    // Register a new user
    public function register($data) {
        // Input validation should be done here
        // ...

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insert user into database
        $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', ROLE_EMPLOYEE); // Default role

        return $this->db->execute();
    }

    // Log in a user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($row && password_verify($password, $row['password'])) {
            $this->createUserSession($row);
            return true;
        } else {
            return false;
        }
    }

    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
    }

    // Log out a user
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role_id']);
        session_destroy();
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
