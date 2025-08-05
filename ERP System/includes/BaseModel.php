<?php

/**
 * Base Model
 *
 * Provides common CRUD operations for models.
 */
abstract class BaseModel {
    protected $db;
    protected $table_name;

    public function __construct() {
        $this->db = new Database;
    }

    // Find all records
    public function findAll() {
        $this->db->query("SELECT * FROM {$this->table_name}");
        return $this->db->resultSet();
    }

    // Find record by ID
    public function findById($id) {
        $this->db->query("SELECT * FROM {$this->table_name} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Delete record by ID
    public function delete($id) {
        $this->db->query("DELETE FROM {$this->table_name} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
