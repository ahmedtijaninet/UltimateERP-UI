<?php

require_once 'includes/BaseModel.php';

class ProjectsModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // == PROJECT METHODS ==
    public function getProjects() {
        $this->db->query("
            SELECT p.*, c.name as customer_name, u.username as manager_name
            FROM projects p
            LEFT JOIN customers c ON p.customer_id = c.id
            LEFT JOIN users u ON p.manager_id = u.id
            ORDER BY p.start_date DESC
        ");
        return $this->db->resultSet();
    }

    public function getProjectById($id) {
        $this->db->query("
            SELECT p.*, c.name as customer_name, u.username as manager_name
            FROM projects p
            LEFT JOIN customers c ON p.customer_id = c.id
            LEFT JOIN users u ON p.manager_id = u.id
            WHERE p.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addProject($data) {
        $this->db->query("
            INSERT INTO projects (name, description, customer_id, start_date, end_date, budget, manager_id)
            VALUES (:name, :description, :customer_id, :start_date, :end_date, :budget, :manager_id)
        ");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':budget', $data['budget']);
        $this->db->bind(':manager_id', $data['manager_id']);

        return $this->db->execute();
    }

    public function updateProject($data) {
        $this->db->query("
            UPDATE projects
            SET name = :name, description = :description, customer_id = :customer_id, start_date = :start_date, end_date = :end_date, budget = :budget, manager_id = :manager_id, status = :status
            WHERE id = :id
        ");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':budget', $data['budget']);
        $this->db->bind(':manager_id', $data['manager_id']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function deleteProject($id) {
        // Note: This will also delete all tasks for this project due to the ON DELETE CASCADE constraint.
        $this->db->query("DELETE FROM projects WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == HELPERS ==
    public function getUsers() {
        $this->db->query("SELECT id, username FROM users WHERE is_active = 1 ORDER BY username ASC");
        return $this->db->resultSet();
    }

    public function getCustomers() {
        $this->db->query("SELECT id, name FROM customers ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // == TASK METHODS ==
    public function getTasksForProject($project_id) {
        $this->db->query("
            SELECT t.*, u.username as assignee_name
            FROM project_tasks t
            LEFT JOIN users u ON t.assignee_id = u.id
            WHERE t.project_id = :project_id
            ORDER BY t.due_date ASC
        ");
        $this->db->bind(':project_id', $project_id);
        return $this->db->resultSet();
    }

    public function addTask($data) {
        $this->db->query("
            INSERT INTO project_tasks (project_id, title, description, due_date, assignee_id)
            VALUES (:project_id, :title, :description, :due_date, :assignee_id)
        ");
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':assignee_id', $data['assignee_id']);

        return $this->db->execute();
    }

    public function getTaskById($id) {
        $this->db->query("SELECT * FROM project_tasks WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateTask($data) {
        $this->db->query("
            UPDATE project_tasks
            SET title = :title, description = :description, due_date = :due_date, assignee_id = :assignee_id, status = :status
            WHERE id = :id
        ");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':assignee_id', $data['assignee_id']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function deleteTask($id) {
        $this->db->query("DELETE FROM project_tasks WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
