-- SGOD IPCRF Performance Management Module
-- Compatible with the existing depedmis_hris MariaDB database.
-- The application seeds the supplied SGOD 2025 preset on first use.

CREATE TABLE IF NOT EXISTS ipcrf_templates (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(180) NOT NULL,
    year SMALLINT UNSIGNED NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_by VARCHAR(45) NOT NULL DEFAULT 'system',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_ipcrf_template_active (is_active, year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_template_kras (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    template_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_tkra_template (template_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_template_objectives (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    template_kra_id INT UNSIGNED NOT NULL,
    code VARCHAR(30) NOT NULL DEFAULT '',
    objective TEXT NOT NULL,
    timeline VARCHAR(180) NOT NULL DEFAULT '',
    weight DECIMAL(6,2) NOT NULL DEFAULT 0,
    quality_json LONGTEXT NOT NULL,
    efficiency_json LONGTEXT NOT NULL,
    timeliness_json LONGTEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_tobjective_kra (template_kra_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_template_competencies (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    template_id INT UNSIGNED NOT NULL,
    category VARCHAR(80) NOT NULL,
    name VARCHAR(180) NOT NULL,
    indicators_json LONGTEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_tcompetency_template (template_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_forms (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    template_id INT UNSIGNED NULL,
    employee_id VARCHAR(25) NOT NULL,
    employee_name VARCHAR(180) NOT NULL,
    position VARCHAR(180) NOT NULL DEFAULT '',
    office VARCHAR(180) NOT NULL DEFAULT '',
    rater_id VARCHAR(25) NOT NULL DEFAULT '',
    rater_name VARCHAR(180) NOT NULL DEFAULT '',
    rater_position VARCHAR(180) NOT NULL DEFAULT '',
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    status VARCHAR(45) NOT NULL DEFAULT 'Draft',
    created_by VARCHAR(45) NOT NULL,
    updated_by VARCHAR(45) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    submitted_at DATETIME NULL,
    validated_at DATETIME NULL,
    locked_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_ipcrf_employee_period (employee_id, period_start, period_end),
    KEY idx_ipcrf_forms_status (status),
    KEY idx_ipcrf_forms_rater (rater_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_kras (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_kra_form (form_id, is_deleted, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_objectives (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    kra_id INT UNSIGNED NOT NULL,
    code VARCHAR(30) NOT NULL DEFAULT '',
    objective TEXT NOT NULL,
    timeline VARCHAR(180) NOT NULL DEFAULT '',
    weight DECIMAL(6,2) NOT NULL DEFAULT 0,
    quality_json LONGTEXT NOT NULL,
    efficiency_json LONGTEXT NOT NULL,
    timeliness_json LONGTEXT NOT NULL,
    accomplishment LONGTEXT NULL,
    quality_rating DECIMAL(4,2) NOT NULL DEFAULT 0,
    efficiency_rating DECIMAL(4,2) NOT NULL DEFAULT 0,
    timeliness_rating DECIMAL(4,2) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_objective_form (form_id, is_deleted),
    KEY idx_ipcrf_objective_kra (kra_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_evidence (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    objective_id INT UNSIGNED NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NOT NULL DEFAULT '',
    file_size INT UNSIGNED NOT NULL DEFAULT 0,
    uploaded_by VARCHAR(45) NOT NULL,
    uploaded_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_ipcrf_evidence_objective (objective_id),
    KEY idx_ipcrf_evidence_form (form_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_competencies (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    category VARCHAR(80) NOT NULL,
    name VARCHAR(180) NOT NULL,
    indicators_json LONGTEXT NOT NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
    -- Retained for compatibility with installations created before the single-rating design.
    employee_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
    rater_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
    final_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_competency_form (form_id, is_deleted, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_development_plans (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    strengths TEXT NULL,
    improvement_needs TEXT NULL,
    learning_objectives TEXT NULL,
    interventions TEXT NULL,
    target_timeline VARCHAR(180) NOT NULL DEFAULT '',
    responsible_person VARCHAR(180) NOT NULL DEFAULT '',
    status_remarks TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_ipcrf_development_form (form_id, is_deleted, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ipcrf_workflow_history (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id INT UNSIGNED NOT NULL,
    from_status VARCHAR(45) NOT NULL DEFAULT '',
    to_status VARCHAR(45) NOT NULL,
    remarks TEXT NULL,
    acted_by VARCHAR(45) NOT NULL,
    acted_by_name VARCHAR(180) NOT NULL DEFAULT '',
    acted_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_ipcrf_workflow_form (form_id, acted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
