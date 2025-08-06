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

    public function edit_employee($id) {
        $page_title = 'Edit Employee';
        $employee = $this->hr_model->getEmployeeById($id);
        if (!$employee) {
            redirect('hr/employees');
            return;
        }
        $departments = $this->hr_model->getDepartments();
        require_once 'modules/hr/edit_employee_view.php';
    }

    public function process_update_employee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
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
                setToastMessage('First Name and Last Name are required.', 'error');
                redirect('hr/edit_employee/' . $id);
                return;
            }

            if ($this->hr_model->updateEmployee($data)) {
                setToastMessage('Employee updated successfully.', 'success');
                redirect('hr/employees');
            } else {
                setToastMessage('Failed to update employee.', 'error');
                redirect('hr/edit_employee/' . $id);
            }
        } else {
            redirect('hr/employees');
        }
    }

    public function delete_employee($id) {
        if ($this->hr_model->deleteEmployee($id)) {
            setToastMessage('Employee deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete employee.', 'error');
        }
        redirect('hr/employees');
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
                setToastMessage('First Name and Last Name are required.', 'error');
                redirect('hr/add_employee');
                return;
            }

            if ($this->hr_model->addEmployee($data)) {
                setToastMessage('Employee added successfully.', 'success');
                redirect('hr/employees');
            } else {
                setToastMessage('Failed to add employee.', 'error');
                redirect('hr/add_employee');
            }
        } else {
            redirect('hr/add_employee');
        }
    }

}
