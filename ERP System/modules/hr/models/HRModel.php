<?php

require_once 'includes/BaseModel.php';

class HRModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // == EMPLOYEE METHODS ==
    public function getEmployees() {
        $this->db->query("
            SELECT e.*, d.name as department_name, u.username
            FROM employees e
            LEFT JOIN departments d ON e.department_id = d.id
            LEFT JOIN users u ON e.user_id = u.id
            ORDER BY e.last_name, e.first_name ASC
        ");
        return $this->db->resultSet();
    }

    public function getEmployeeById($id) {
        $this->db->query("SELECT * FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addEmployee($data) {
        $this->db->query("
            INSERT INTO employees (first_name, last_name, job_title, department_id, hire_date, phone_number, address, date_of_birth, salary)
            VALUES (:first_name, :last_name, :job_title, :department_id, :hire_date, :phone_number, :address, :date_of_birth, :salary)
        ");
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':job_title', $data['job_title']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':hire_date', $data['hire_date']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':salary', $data['salary']);

        return $this->db->execute();
    }

    public function updateEmployee($data) {
        $this->db->query("
            UPDATE employees
            SET first_name = :first_name, last_name = :last_name, job_title = :job_title, department_id = :department_id, hire_date = :hire_date, phone_number = :phone_number, address = :address, date_of_birth = :date_of_birth, salary = :salary
            WHERE id = :id
        ");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':job_title', $data['job_title']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':hire_date', $data['hire_date']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':salary', $data['salary']);

        return $this->db->execute();
    }

    public function deleteEmployee($id) {
        $this->db->query("DELETE FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == HELPERS ==
    public function getDepartments() {
        $this->db->query("SELECT id, name FROM departments ORDER BY name ASC");
        return $this->db->resultSet();
    }
}
