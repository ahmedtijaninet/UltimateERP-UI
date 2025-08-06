<?php

/**
 * Authentication Service
 *
 * Handles user authentication, permissions, and audit logging.
 */
class AuthService {
    private $db;

    public function __construct() {
        $this->db = new Database;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Register a new user
    public function register($data) {
        // ... (same as before)
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', 5); // Default role is 'Employee'
        return $this->db->execute();
    }

    // Log in a user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND is_active = 1');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($row && password_verify($password, $row['password'])) {
            $this->createUserSession($row);
            $this->audit('login', null, null, 'User logged in successfully.');
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
        $this->loadPermissions($user['role_id']);
    }

    // Load user permissions into session
    private function loadPermissions($role_id) {
        $this->db->query("SELECT permission_key FROM role_permissions WHERE role_id = :role_id");
        $this->db->bind(':role_id', $role_id);
        $permissions = $this->db->resultSet();
        $_SESSION['permissions'] = array_column($permissions, 'permission_key');
    }

    // Check if user has a specific permission
    public function hasPermission($permission_key) {
        if (!isset($_SESSION['permissions'])) {
            return false;
        }
        // Admin has all permissions
        if (in_array('manage_users', $_SESSION['permissions'])) {
            return true;
        }
        return in_array($permission_key, $_SESSION['permissions']);
    }

    // Log out a user
    public function logout() {
        $this->audit('logout');
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role_id']);
        unset($_SESSION['permissions']);
        session_destroy();
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Audit user actions
    public function audit($action, $target_type = null, $target_id = null, $details = '') {
        $this->db->query("
            INSERT INTO audit_log (user_id, action, target_type, target_id, details, ip_address)
            VALUES (:user_id, :action, :target_type, :target_id, :details, :ip_address)
        ");
        $this->db->bind(':user_id', $_SESSION['user_id'] ?? null);
        $this->db->bind(':action', $action);
        $this->db->bind(':target_type', $target_type);
        $this->db->bind(':target_id', $target_id);
        $this->db->bind(':details', $details);
        $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN');
        $this->db->execute();
    }
}
