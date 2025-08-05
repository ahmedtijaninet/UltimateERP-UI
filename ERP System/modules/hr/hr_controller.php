<?php

require_once 'modules/hr/models/HRModel.php';

class HRController {

    private $hr_model;

    public function __construct() {
        $this->hr_model = new HRModel();
    }

    /**
     * Main entry point for the HR module.
     * Displays the HR dashboard.
     */
    public function index() {
        $page_title = 'HR Dashboard';
        require_once 'modules/hr/hr_dashboard_view.php';
    }

    // == EMPLOYEE METHODS ==

    public function employees() {
        $page_title = 'Employees';
        $employees = $this->hr_model->getEmployees();
        require_once 'modules/hr/employees_view.php';
    }

    public function add_employee() {
        $page_title = 'Add New Employee';
        $departments = $this->hr_model->getDepartments();
        require_once 'modules/hr/add_employee_view.php';
    }

    public function process_add_employee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'job_title' => trim($_POST['job_title']),
                'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : null,
                'hire_date' => !empty($_POST['hire_date']) ? $_POST['hire_date'] : null,
                'phone_number' => trim($_POST['phone_number']),
                'address' => trim($_POST['address']),
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'salary' => !empty($_POST['salary']) ? $_POST['salary'] : null
            ];

            if (empty($data['first_name']) || empty($data['last_name'])) {
                $_SESSION['error_message'] = 'First Name and Last Name are required.';
                redirect('hr/add_employee');
                return;
            }

            if ($this->hr_model->addEmployee($data)) {
                $_SESSION['success_message'] = 'Employee added successfully.';
                redirect('hr/employees');
            } else {
                $_SESSION['error_message'] = 'Failed to add employee.';
                redirect('hr/add_employee');
            }
        } else {
            redirect('hr/add_employee');
        }
    }

}
