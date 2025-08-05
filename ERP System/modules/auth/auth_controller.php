<?php

class AuthController {

    private $auth_service;
    private $validator;

    public function __construct() {
        $this->auth_service = new AuthService();
        $this->validator = new Validator();
    }

    public function login() {
        // Display the login form
        require_once 'modules/auth/login_view.php';
    }

    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            checkCsrfToken($_POST['csrf_token']);

            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->auth_service->login($email, $password)) {
                redirect('dashboard');
            } else {
                $_SESSION['error_message'] = 'Invalid email or password.';
                redirect('login');
            }
        } else {
            redirect('login');
        }
    }

    public function register() {
        // Display the registration form
        require_once 'modules/auth/register_view.php';
    }

    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            checkCsrfToken($_POST['csrf_token']);

            $errors = [];
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password']
            ];

            // Validation
            if (!$this->validator->validateRequired($data['username'], 'Username')) $errors = array_merge($errors, $this->validator->getErrors());
            if (!$this->validator->validateEmail($data['email'])) $errors = array_merge($errors, $this->validator->getErrors());
            if (!$this->validator->validatePassword($data['password'])) $errors = array_merge($errors, $this->validator->getErrors());
            if ($data['password'] !== $data['confirm_password']) {
                $errors[] = "Passwords do not match.";
            }

            if (empty($errors)) {
                // Attempt to register
                if ($this->auth_service->register($data)) {
                    $_SESSION['success_message'] = 'Registration successful. Please login.';
                    redirect('login');
                } else {
                    $errors[] = 'Registration failed. The username or email may already be taken.';
                    $page_title = 'Register';
                    require_once 'modules/auth/register_view.php';
                }
            } else {
                // Display errors
                $page_title = 'Register';
                require_once 'modules/auth/register_view.php';
            }
        } else {
            redirect('register');
        }
    }

    public function logout() {
        $this->auth_service->logout();
        redirect('login');
    }
}
