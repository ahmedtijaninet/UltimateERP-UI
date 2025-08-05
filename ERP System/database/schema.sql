-- ERP System - Database Schema
-- This schema is based on the comprehensive structure outlined in the README.
-- It is designed to be modular and scalable.

-- Phase 1: Foundation & Core

-- User Management
CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `user_roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `first_name` VARCHAR(100),
  `last_name` VARCHAR(100),
  `role_id` INT,
  `department_id` INT,
  `is_active` BOOLEAN DEFAULT true,
  `last_login` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `user_roles`(`id`),
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `user_sessions` (
  `id` VARCHAR(255) PRIMARY KEY,
  `user_id` INT NOT NULL,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Company Structure
CREATE TABLE `companies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `address` TEXT,
  `phone` VARCHAR(50),
  `email` VARCHAR(255),
  `website` VARCHAR(255),
  `is_default` BOOLEAN DEFAULT false,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `locations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_id` INT,
  `name` VARCHAR(255) NOT NULL,
  `address` TEXT,
  `is_warehouse` BOOLEAN DEFAULT false,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
) ENGINE=InnoDB;

-- Financial Management (Phase 1)
CREATE TABLE `accounts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `account_code` VARCHAR(50) NOT NULL UNIQUE,
  `account_name` VARCHAR(255) NOT NULL,
  `account_type` ENUM('Asset', 'Liability', 'Equity', 'Revenue', 'Expense') NOT NULL,
  `description` TEXT,
  `is_active` BOOLEAN DEFAULT true,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `journal_entries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `entry_date` DATE NOT NULL,
  `description` TEXT,
  `created_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `journal_entry_id` INT,
  `account_id` INT,
  `debit_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `credit_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `description` TEXT,
  `transaction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries`(`id`),
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
) ENGINE=InnoDB;

-- Phase 2: Core Business Operations

-- Purchasing / Procurement
CREATE TABLE `suppliers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `contact_person` VARCHAR(255),
  `email` VARCHAR(255),
  `phone` VARCHAR(50),
  `address` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Inventory Management
CREATE TABLE `item_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `parent_category_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_category_id`) REFERENCES `item_categories`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `inventory_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_code` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `category_id` INT,
  `unit_price` DECIMAL(15, 2),
  `quantity_on_hand` INT DEFAULT 0,
  `reorder_level` INT,
  `supplier_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `item_categories`(`id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`)
) ENGINE=InnoDB;

-- Sales & CRM
CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `contact_person` VARCHAR(255),
  `email` VARCHAR(255),
  `phone` VARCHAR(50),
  `address` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Enhanced Financial Management (Phase 2)
CREATE TABLE `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_number` VARCHAR(100) NOT NULL UNIQUE,
  `customer_id` INT NOT NULL,
  `issue_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `total_amount` DECIMAL(15, 2) NOT NULL,
  `paid_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `status` ENUM('Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled') NOT NULL DEFAULT 'Draft',
  `created_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `invoice_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `item_id` INT,
  `description` TEXT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(15, 2) NOT NULL,
  `total` DECIMAL(15, 2) NOT NULL,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_items`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `payment_date` DATE NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `payment_method` VARCHAR(100),
  `notes` TEXT,
  `received_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`),
  FOREIGN KEY (`received_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Purchasing Module (Phase 2)
CREATE TABLE `purchase_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_number` VARCHAR(100) NOT NULL UNIQUE,
  `supplier_id` INT NOT NULL,
  `order_date` DATE NOT NULL,
  `expected_delivery_date` DATE,
  `total_amount` DECIMAL(15, 2) NOT NULL,
  `status` ENUM('Draft', 'Ordered', 'Partially Received', 'Fully Received', 'Cancelled') NOT NULL DEFAULT 'Draft',
  `created_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`),
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `purchase_order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `description` TEXT,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(15, 2) NOT NULL,
  `total` DECIMAL(15, 2) NOT NULL,
  `quantity_received` INT DEFAULT 0,
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_items`(`id`)
) ENGINE=InnoDB;

-- Sales & CRM Module (Phase 3)
CREATE TABLE `sales_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `so_number` VARCHAR(100) NOT NULL UNIQUE,
  `customer_id` INT NOT NULL,
  `order_date` DATE NOT NULL,
  `status` ENUM('Draft', 'Confirmed', 'Invoiced', 'Shipped', 'Cancelled') NOT NULL DEFAULT 'Draft',
  `total_amount` DECIMAL(15, 2) NOT NULL,
  `created_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `sales_order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `so_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `description` TEXT,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(15, 2) NOT NULL,
  `total` DECIMAL(15, 2) NOT NULL,
  FOREIGN KEY (`so_id`) REFERENCES `sales_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_items`(`id`)
) ENGINE=InnoDB;

-- Advanced Inventory (Phase 3)
CREATE TABLE `inventory_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `transaction_type` ENUM('Purchase', 'Sale', 'Adjustment', 'Initial') NOT NULL,
  `quantity_change` INT NOT NULL,
  `related_document_id` INT, -- e.g., PO id, SO id
  `notes` TEXT,
  `created_by_user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`item_id`) REFERENCES `inventory_items`(`id`),
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Human Resources (HR) Module (Phase 5)
CREATE TABLE `employees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNIQUE,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `job_title` VARCHAR(255),
  `department_id` INT,
  `hire_date` DATE,
  `phone_number` VARCHAR(50),
  `address` TEXT,
  `date_of_birth` DATE,
  `salary` DECIMAL(15, 2),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`)
) ENGINE=InnoDB;

-- Project Management Module (Phase 5)
CREATE TABLE `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `customer_id` INT,
  `start_date` DATE,
  `end_date` DATE,
  `budget` DECIMAL(15, 2),
  `status` ENUM('Not Started', 'In Progress', 'Completed', 'On Hold', 'Cancelled') NOT NULL DEFAULT 'Not Started',
  `manager_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
  FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `project_tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `start_date` DATE,
  `due_date` DATE,
  `status` ENUM('To Do', 'In Progress', 'Done', 'Blocked') NOT NULL DEFAULT 'To Do',
  `assignee_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assignee_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;
