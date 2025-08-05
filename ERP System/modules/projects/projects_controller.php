<?php

require_once 'modules/projects/models/ProjectsModel.php';

class ProjectsController {

    private $projects_model;

    public function __construct() {
        $this->projects_model = new ProjectsModel();
    }

    /**
     * Main entry point for the projects module.
     * Displays the projects dashboard.
     */
    public function index() {
        $page_title = 'Projects Dashboard';
        require_once 'modules/projects/projects_dashboard_view.php';
    }

    // == PROJECT METHODS ==

    public function projects() {
        $page_title = 'Projects';
        $projects = $this->projects_model->getProjects();
        require_once 'modules/projects/projects_view.php';
    }

    public function add_project() {
        $page_title = 'Add New Project';
        $customers = $this->projects_model->getCustomers();
        $users = $this->projects_model->getUsers();
        require_once 'modules/projects/add_project_view.php';
    }

    public function process_add_project() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'customer_id' => !empty($_POST['customer_id']) ? $_POST['customer_id'] : null,
                'manager_id' => !empty($_POST['manager_id']) ? $_POST['manager_id'] : null,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'budget' => !empty($_POST['budget']) ? $_POST['budget'] : null
            ];

            if (empty($data['name'])) {
                $_SESSION['error_message'] = 'Project Name is required.';
                redirect('projects/add_project');
                return;
            }

            if ($this->projects_model->addProject($data)) {
                $_SESSION['success_message'] = 'Project added successfully.';
                redirect('projects/projects');
            } else {
                $_SESSION['error_message'] = 'Failed to add project.';
                redirect('projects/add_project');
            }
        } else {
            redirect('projects/add_project');
        }
    }

    public function view_project($id) {
        $page_title = 'View Project';
        $project = $this->projects_model->getProjectById($id);
        if (!$project) {
            redirect('projects/projects');
            return;
        }
        $tasks = $this->projects_model->getTasksForProject($id);
        $users = $this->projects_model->getUsers(); // For assignee dropdown
        require_once 'modules/projects/view_project_view.php';
    }

    // == TASK METHODS ==

    public function process_add_task() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $project_id = $_POST['project_id'];
            $data = [
                'project_id' => $project_id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'due_date' => !empty($_POST['due_date']) ? $_POST['due_date'] : null,
                'assignee_id' => !empty($_POST['assignee_id']) ? $_POST['assignee_id'] : null
            ];

            if (empty($data['title'])) {
                $_SESSION['error_message'] = 'Task Title is required.';
                redirect('projects/view_project/' . $project_id);
                return;
            }

            if ($this->projects_model->addTask($data)) {
                $_SESSION['success_message'] = 'Task added successfully.';
            } else {
                $_SESSION['error_message'] = 'Failed to add task.';
            }
            redirect('projects/view_project/' . $project_id);
        } else {
            redirect('projects/projects');
        }
    }
}
