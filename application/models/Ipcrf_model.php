<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipcrf_model extends CI_Model
{
    const STATUS_DRAFT = 'Draft';
    const STATUS_SUBMITTED_RATER = 'Submitted to Rater';
    const STATUS_RETURNED = 'Returned for Revision';
    const STATUS_RATER_APPROVED = 'Rater Approved';
    const STATUS_SUBMITTED_PMT = 'Submitted to PMT';
    const STATUS_PMT_VALIDATED = 'PMT Validated';
    const STATUS_LOCKED = 'Locked';

    public function ensure_schema()
    {
        $statements = array(
            "CREATE TABLE IF NOT EXISTS ipcrf_templates (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(180) NOT NULL,
                year SMALLINT UNSIGNED NOT NULL,
                description TEXT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_by VARCHAR(45) NOT NULL DEFAULT 'system',
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                PRIMARY KEY (id), KEY idx_ipcrf_template_active (is_active, year)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_template_kras (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                template_id INT UNSIGNED NOT NULL,
                title VARCHAR(255) NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                PRIMARY KEY (id), KEY idx_ipcrf_tkra_template (template_id, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_template_objectives (
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
                PRIMARY KEY (id), KEY idx_ipcrf_tobjective_kra (template_kra_id, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_template_competencies (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                template_id INT UNSIGNED NOT NULL,
                category VARCHAR(80) NOT NULL,
                name VARCHAR(180) NOT NULL,
                indicators_json LONGTEXT NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                PRIMARY KEY (id), KEY idx_ipcrf_tcompetency_template (template_id, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_forms (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                template_id INT UNSIGNED NULL,
                employee_id VARCHAR(25) NOT NULL,
                employee_name VARCHAR(180) NOT NULL,
                position VARCHAR(180) NOT NULL DEFAULT '',
                office VARCHAR(180) NOT NULL DEFAULT '',
                rater_id VARCHAR(25) NOT NULL DEFAULT '',
                rater_name VARCHAR(180) NOT NULL DEFAULT '',
                rater_position VARCHAR(180) NOT NULL DEFAULT '',
                approving_authority_id VARCHAR(25) NOT NULL DEFAULT '',
                approving_authority_name VARCHAR(180) NOT NULL DEFAULT '',
                approving_authority_position VARCHAR(180) NOT NULL DEFAULT '',
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
                KEY idx_ipcrf_forms_status (status), KEY idx_ipcrf_forms_rater (rater_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_kras (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                form_id INT UNSIGNED NOT NULL,
                template_kra_id INT UNSIGNED NOT NULL DEFAULT 0,
                title VARCHAR(255) NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                is_deleted TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (id), KEY idx_ipcrf_kra_form (form_id, is_deleted, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_objectives (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_evidence (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                form_id INT UNSIGNED NOT NULL,
                objective_id INT UNSIGNED NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                stored_name VARCHAR(255) NOT NULL,
                mime_type VARCHAR(120) NOT NULL DEFAULT '',
                file_size INT UNSIGNED NOT NULL DEFAULT 0,
                uploaded_by VARCHAR(45) NOT NULL,
                uploaded_at DATETIME NOT NULL,
                PRIMARY KEY (id), KEY idx_ipcrf_evidence_objective (objective_id), KEY idx_ipcrf_evidence_form (form_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_competencies (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                form_id INT UNSIGNED NOT NULL,
                category VARCHAR(80) NOT NULL,
                name VARCHAR(180) NOT NULL,
                indicators_json LONGTEXT NOT NULL,
                rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
                employee_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
                rater_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
                final_rating TINYINT UNSIGNED NOT NULL DEFAULT 0,
                sort_order INT NOT NULL DEFAULT 0,
                is_deleted TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (id), KEY idx_ipcrf_competency_form (form_id, is_deleted, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_development_plans (
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
                PRIMARY KEY (id), KEY idx_ipcrf_development_form (form_id, is_deleted, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_signatures (
                employee_id VARCHAR(25) NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                stored_name VARCHAR(255) NOT NULL,
                mime_type VARCHAR(80) NOT NULL,
                file_size INT UNSIGNED NOT NULL DEFAULT 0,
                uploaded_by VARCHAR(45) NOT NULL,
                uploaded_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                PRIMARY KEY (employee_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ipcrf_workflow_history (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                form_id INT UNSIGNED NOT NULL,
                from_status VARCHAR(45) NOT NULL DEFAULT '',
                to_status VARCHAR(45) NOT NULL,
                remarks TEXT NULL,
                acted_by VARCHAR(45) NOT NULL,
                acted_by_name VARCHAR(180) NOT NULL DEFAULT '',
                acted_at DATETIME NOT NULL,
                PRIMARY KEY (id), KEY idx_ipcrf_workflow_form (form_id, acted_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        foreach ($statements as $statement) {
            $this->db->query($statement);
        }

        // Older installations stored three stage-specific competency ratings. Keep
        // those columns for compatibility, but migrate their latest value into the
        // single rating used by the editor, validation workflow and printed report.
        if (!$this->db->field_exists('rating', 'ipcrf_competencies')) {
            $this->db->query("ALTER TABLE ipcrf_competencies ADD rating TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER indicators_json");
            $this->db->query("UPDATE ipcrf_competencies SET rating = CASE WHEN final_rating BETWEEN 1 AND 5 THEN final_rating WHEN rater_rating BETWEEN 1 AND 5 THEN rater_rating WHEN employee_rating BETWEEN 1 AND 5 THEN employee_rating ELSE 0 END");
        }

        if (!$this->db->field_exists('approving_authority_id', 'ipcrf_forms')) {
            $this->db->query("ALTER TABLE ipcrf_forms ADD approving_authority_id VARCHAR(25) NOT NULL DEFAULT '' AFTER rater_position");
        }
        if (!$this->db->field_exists('approving_authority_name', 'ipcrf_forms')) {
            $this->db->query("ALTER TABLE ipcrf_forms ADD approving_authority_name VARCHAR(180) NOT NULL DEFAULT '' AFTER approving_authority_id");
        }
        if (!$this->db->field_exists('approving_authority_position', 'ipcrf_forms')) {
            $this->db->query("ALTER TABLE ipcrf_forms ADD approving_authority_position VARCHAR(180) NOT NULL DEFAULT '' AFTER approving_authority_name");
        }

        // Links a form KRA back to the template KRA it was tagged from. 0 means the
        // member added the KRA themselves, so the tag sync leaves it alone.
        if (!$this->db->field_exists('template_kra_id', 'ipcrf_kras')) {
            $this->db->query("ALTER TABLE ipcrf_kras ADD template_kra_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER form_id");
        }

        if ((int) $this->db->count_all('ipcrf_templates') === 0) {
            $this->seed_default_template();
        }
    }

    // Seeds an empty shell only: KRAs and objectives are authored by the SGOD
    // Chief in Ipcrf/manage_template, so nothing is pre-set here. Competencies
    // stay seeded — they are the same DepEd list for every member.
    private function seed_default_template()
    {
        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();
        $this->db->insert('ipcrf_templates', array(
            'name' => 'SGOD IPCRF ' . date('Y'),
            'year' => (int) date('Y'),
            'description' => '',
            'is_active' => 1,
            'created_by' => 'system',
            'created_at' => $now,
            'updated_at' => $now
        ));
        $templateId = (int) $this->db->insert_id();

        $competencies = array(
            array('Core Behavioral Competency', 'Self-Management', array('Sets personal goals and direction, needs and development.', 'Understands personal actions and behavior that are clear and purposive and takes into account personal goals and values congruent to those of the organization.', 'Displays emotional maturity and enthusiasm for, and is challenged by, higher goals.', 'Prioritizes work tasks and schedules through Gantt charts, checklists and similar tools to achieve goals.', 'Sets high-quality, challenging and realistic goals for self and others.')),
            array('Core Behavioral Competency', 'Teamwork', array('Willingly does his/her share of responsibility.', 'Promotes collaboration and removes barriers to teamwork and goal accomplishment across the organization.', 'Applies negotiation principles in arriving at win-win agreements.', 'Drives consensus and team ownership of decisions.', 'Works constructively and collaboratively with others and across organizations to accomplish organizational goals and objectives.')),
            array('Core Behavioral Competency', 'Professionalism and Ethics', array('Demonstrates the values and behavior enshrined in the Code of Conduct and Ethical Standards for Public Officials and Employees (RA 6713).', 'Practices ethical and professional behavior and considers the impact of actions and decisions.', 'Maintains a professional image through trustworthiness, regular attendance, punctuality, good grooming and communication.', 'Makes personal sacrifices to meet the organization’s needs.', 'Acts with urgency and responsibility to meet organizational needs, improve systems and help others improve effectiveness.')),
            array('Core Behavioral Competency', 'Result Focus', array('Achieves results with optimal use of time and resources.', 'Avoids rework, mistakes and wastage through effective work methods.', 'Delivers error-free outputs by consistently following standard operating procedures.', 'Expresses a desire to improve and finds more precise ways of meeting goals.', 'Makes specific changes in systems or work methods to improve performance, quality and customer satisfaction.')),
            array('Core Behavioral Competency', 'Service Orientation', array('Can explain and articulate organizational directions, issues and problems.', 'Takes personal responsibility for correcting customer service issues and concerns.', 'Initiates activities that promote advocacy for empowerment.', 'Participates in updating office vision, mission, mandates and strategies based on DepEd directions.', 'Develops and adopts service-improvement programs and simplified procedures that enhance service delivery.')),
            array('Core Behavioral Competency', 'Innovation', array('Examines root causes and suggests effective solutions, new ideas and better ways of doing things.', 'Thinks beyond the box and continuously improves personal productivity to create higher value.', 'Promotes a creative climate and inspires co-workers to develop original ideas or solutions.', 'Translates creative thinking into tangible changes that improve the work unit and organization.', 'Uses ingenious methods and demonstrates resourcefulness to succeed with minimal resources.')),
            array('Core Skill', 'Achievement', array('Enjoys working hard.', 'Is action-oriented and full of energy for challenging tasks.', 'Forwards personal, professional and work-unit needs and interests in an issue.', 'Seizes more opportunities than others.', 'Thinks strategically.')),
            array('Core Skill', 'Accountability', array('Can be counted on to exceed goals successfully.', 'Steadfastly pushes self and others toward results.', 'Gets things done on time with optimum use of resources.', 'Builds team spirit.', 'Transacts with transparency.')),
            array('Core Skill', 'Managing Diversity', array('Respects all classes and kinds of people.', 'Deals effectively with all races, nationalities, cultures, disabilities, ages and sexes.', 'Supports equal and fair treatment and opportunity for all.', 'Applies equal standards and criteria to all classes.', 'Manifests cultural and gender sensitivity when dealing with people.'))
        );

        foreach ($competencies as $index => $competency) {
            $this->db->insert('ipcrf_template_competencies', array(
                'template_id' => $templateId,
                'category' => $competency[0],
                'name' => $competency[1],
                'indicators_json' => json_encode($competency[2]),
                'sort_order' => $index + 1
            ));
        }
        $this->db->trans_complete();
    }

    public function search_employees($query, $limit = 30)
    {
        $this->db->select('IDNumber, FirstName, MiddleName, LastName, NameExtn, empPosition, jobTitle, Department, directHead, directHeadPosition');
        $this->db->from('hris_staff');
        $this->db->where('IDNumber !=', '');
        if ($query !== '') {
            $this->db->group_start();
            $this->db->like('IDNumber', $query);
            $this->db->or_like('FirstName', $query);
            $this->db->or_like('LastName', $query);
            $this->db->or_like("CONCAT(FirstName, ' ', LastName)", $query, 'both', FALSE);
            $this->db->group_end();
        }
        $this->db->order_by('LastName', 'ASC');
        $this->db->order_by('FirstName', 'ASC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result_array();
    }

    public function get_employee($id)
    {
        $this->db->where('IDNumber', trim((string) $id));
        $row = $this->db->get('hris_staff', 1)->row_array();
        if (!$row) {
            return NULL;
        }
        return $this->format_employee($row);
    }

    public function resolve_employee_id($username)
    {
        $username = trim((string) $username);
        if ($username === '') {
            return '';
        }
        if ($this->get_employee($username)) {
            return $username;
        }
        if ($this->db->table_exists('users') && $this->db->field_exists('IDNumber', 'users')) {
            $user = $this->db->select('IDNumber')->where('username', $username)->get('users', 1)->row_array();
            $employeeId = $user ? trim((string) $user['IDNumber']) : '';
            if ($employeeId !== '' && $this->get_employee($employeeId)) {
                return $employeeId;
            }
        }
        return $username;
    }

    public function format_employee($row)
    {
        $middle = trim((string) $row['MiddleName']);
        $middleInitial = $middle === '' ? '' : ' ' . mb_substr($middle, 0, 1) . '.';
        $name = trim($row['FirstName'] . $middleInitial . ' ' . $row['LastName'] . ' ' . $row['NameExtn']);
        return array(
            'id' => trim((string) $row['IDNumber']),
            'name' => preg_replace('/\s+/', ' ', $name),
            'position' => trim((string) ($row['empPosition'] !== '' ? $row['empPosition'] : $row['jobTitle'])),
            'office' => trim((string) $row['Department']),
            'direct_head' => trim((string) $row['directHead']),
            'direct_head_position' => trim((string) $row['directHeadPosition'])
        );
    }

    public function get_templates()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('year', 'DESC');
        $this->db->order_by('name', 'ASC');
        return $this->db->get('ipcrf_templates')->result_array();
    }

    public function get_default_template()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('year', 'DESC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('ipcrf_templates', 1)->row_array();
    }

    public function get_form($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->get('ipcrf_forms', 1)->row_array();
    }

    public function find_period_form($employeeId, $periodStart, $periodEnd)
    {
        $this->db->where('employee_id', $employeeId);
        $this->db->where('period_start', $periodStart);
        $this->db->where('period_end', $periodEnd);
        return $this->db->get('ipcrf_forms', 1)->row_array();
    }

    public function create_form($employee, $data, $actor)
    {
        $existing = $this->find_period_form($employee['id'], $data['period_start'], $data['period_end']);
        if ($existing) {
            return (int) $existing['id'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->insert('ipcrf_forms', array(
            'template_id' => NULL,
            'employee_id' => $employee['id'],
            'employee_name' => $employee['name'],
            'position' => $employee['position'],
            'office' => $employee['office'],
            'rater_id' => trim((string) $data['rater_id']),
            'rater_name' => trim((string) $data['rater_name']),
            'rater_position' => trim((string) $data['rater_position']),
            'approving_authority_id' => trim((string) $data['approving_authority_id']),
            'approving_authority_name' => trim((string) $data['approving_authority_name']),
            'approving_authority_position' => trim((string) $data['approving_authority_position']),
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status' => self::STATUS_DRAFT,
            'created_by' => $actor,
            'updated_by' => $actor,
            'created_at' => $now,
            'updated_at' => $now
        ));
        $id = (int) $this->db->insert_id();
        $this->add_history($id, '', self::STATUS_DRAFT, 'IPCRF created.', $actor, $this->actor_name());
        return $id;
    }

    public function update_personal_form_setup($formId, $employeeId, $data, $actor)
    {
        $formId = (int) $formId;
        $employeeId = trim((string) $employeeId);
        $this->db->trans_begin();

        $form = $this->db->query(
            'SELECT id, employee_id, status FROM ipcrf_forms WHERE id = ? AND employee_id = ? FOR UPDATE',
            array($formId, $employeeId)
        )->row_array();
        if (!$this->can_employee_manage_form($form, $employeeId)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $duplicate = $this->db
            ->where('employee_id', $employeeId)
            ->where('period_start', $data['period_start'])
            ->where('period_end', $data['period_end'])
            ->where('id !=', $formId)
            ->count_all_results('ipcrf_forms') > 0;
        if ($duplicate) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $now = date('Y-m-d H:i:s');
        $this->db->where('id', $formId)->where('employee_id', $employeeId)->update('ipcrf_forms', array(
            'rater_id' => trim((string) $data['rater_id']),
            'rater_name' => trim((string) $data['rater_name']),
            'rater_position' => trim((string) $data['rater_position']),
            'approving_authority_id' => trim((string) $data['approving_authority_id']),
            'approving_authority_name' => trim((string) $data['approving_authority_name']),
            'approving_authority_position' => trim((string) $data['approving_authority_position']),
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status' => self::STATUS_DRAFT,
            'submitted_at' => NULL,
            'validated_at' => NULL,
            'locked_at' => NULL,
            'updated_by' => $actor,
            'updated_at' => $now
        ));
        $this->add_history(
            $formId,
            $form['status'],
            self::STATUS_DRAFT,
            'Rater, approving authority, or performance period updated. Saved as draft.',
            $actor,
            $this->actor_name()
        );

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function personal_forms($employeeId)
    {
        $this->db->select('id, employee_id, employee_name, position, office, period_start, period_end, status, rater_id, rater_name, approving_authority_id, approving_authority_name, approving_authority_position, created_by, updated_at');
        $this->db->from('ipcrf_forms');
        $this->db->where('employee_id', trim((string) $employeeId));
        $this->db->order_by('updated_at', 'DESC');
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }

    public function can_employee_manage_form($form, $employeeId)
    {
        if (!$form || (string) $form['employee_id'] !== trim((string) $employeeId)) {
            return FALSE;
        }

        return in_array($form['status'], array(
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED_RATER,
            self::STATUS_RETURNED
        ), TRUE);
    }

    public function form_evidence_files($formId)
    {
        return $this->db
            ->select('stored_name')
            ->where('form_id', (int) $formId)
            ->get('ipcrf_evidence')
            ->result_array();
    }

    public function delete_personal_form($formId, $employeeId)
    {
        $formId = (int) $formId;
        $employeeId = trim((string) $employeeId);
        $manageableStatuses = array(
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED_RATER,
            self::STATUS_RETURNED
        );

        $this->db->trans_begin();
        $this->db
            ->where('id', $formId)
            ->where('employee_id', $employeeId)
            ->where_in('status', $manageableStatuses)
            ->delete('ipcrf_forms');

        if ($this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return FALSE;
        }

        foreach (array(
            'ipcrf_evidence',
            'ipcrf_objectives',
            'ipcrf_kras',
            'ipcrf_competencies',
            'ipcrf_development_plans',
            'ipcrf_workflow_history'
        ) as $table) {
            $this->db->where('form_id', $formId)->delete($table);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function submitted_rater_forms($raterId)
    {
        return $this->db
            ->select('id, employee_id, employee_name, position, office, period_start, period_end, status, rater_id, rater_name, submitted_at, updated_at')
            ->from('ipcrf_forms')
            ->where('rater_id', trim((string) $raterId))
            ->where('status', self::STATUS_SUBMITTED_RATER)
            ->order_by('submitted_at', 'ASC')
            ->order_by('updated_at', 'ASC')
            ->get()
            ->result_array();
    }

    public function approved_rater_forms($raterId)
    {
        return $this->db
            ->select('id, employee_id, employee_name, position, office, period_start, period_end, status, rater_id, rater_name, submitted_at, validated_at, locked_at, updated_at')
            ->from('ipcrf_forms')
            ->where('rater_id', trim((string) $raterId))
            ->where_in('status', array(
                self::STATUS_RATER_APPROVED,
                self::STATUS_SUBMITTED_PMT,
                self::STATUS_PMT_VALIDATED,
                self::STATUS_LOCKED
            ))
            ->order_by('updated_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_signature($employeeId)
    {
        return $this->db
            ->where('employee_id', trim((string) $employeeId))
            ->get('ipcrf_signatures', 1)
            ->row_array();
    }

    public function save_signature($employeeId, $data)
    {
        $employeeId = trim((string) $employeeId);
        if ($employeeId === '') {
            return FALSE;
        }

        $record = array(
            'employee_id' => $employeeId,
            'original_name' => trim((string) $data['original_name']),
            'stored_name' => trim((string) $data['stored_name']),
            'mime_type' => trim((string) $data['mime_type']),
            'file_size' => (int) $data['file_size'],
            'uploaded_by' => trim((string) $data['uploaded_by']),
            'uploaded_at' => $data['uploaded_at'],
            'updated_at' => $data['updated_at']
        );

        if ($this->get_signature($employeeId)) {
            unset($record['employee_id']);
            return $this->db->where('employee_id', $employeeId)->update('ipcrf_signatures', $record);
        }
        return $this->db->insert('ipcrf_signatures', $record);
    }

    public function delete_signature($employeeId)
    {
        return $this->db
            ->where('employee_id', trim((string) $employeeId))
            ->delete('ipcrf_signatures');
    }

    public function can_view_signature($employeeId, $viewerId)
    {
        $employeeId = trim((string) $employeeId);
        $viewerId = trim((string) $viewerId);
        if ($employeeId === '' || $viewerId === '') {
            return FALSE;
        }
        if ($employeeId === $viewerId || $this->is_admin() || $this->is_pmt()) {
            return TRUE;
        }

        $viewerRatesEmployee = $this->db
            ->where('employee_id', $employeeId)
            ->where('rater_id', $viewerId)
            ->count_all_results('ipcrf_forms') > 0;
        if ($viewerRatesEmployee) {
            return TRUE;
        }
        $viewerApprovesEmployee = $this->db
            ->where('employee_id', $employeeId)
            ->where('approving_authority_id', $viewerId)
            ->count_all_results('ipcrf_forms') > 0;
        if ($viewerApprovesEmployee) {
            return TRUE;
        }
        $employeeReviewsViewer = $this->db
            ->where('employee_id', $viewerId)
            ->where('rater_id', $employeeId)
            ->count_all_results('ipcrf_forms') > 0;
        if ($employeeReviewsViewer) {
            return TRUE;
        }
        return $this->db
            ->where('employee_id', $viewerId)
            ->where('approving_authority_id', $employeeId)
            ->count_all_results('ipcrf_forms') > 0;
    }

    public function load_template_into_form($formId, $templateId, $actor)
    {
        $template = $this->db->where('id', (int) $templateId)->where('is_active', 1)->get('ipcrf_templates', 1)->row_array();
        if (!$template) {
            return FALSE;
        }
        $targetForm = $this->get_form($formId);
        $targetYear = $targetForm ? date('Y', strtotime($targetForm['period_start'])) : (string) $template['year'];

        $this->db->trans_start();
        $this->db->where('form_id', (int) $formId)->update('ipcrf_kras', array('is_deleted' => 1));
        $this->db->where('form_id', (int) $formId)->update('ipcrf_objectives', array('is_deleted' => 1));
        $this->db->where('form_id', (int) $formId)->update('ipcrf_competencies', array('is_deleted' => 1));

        // Only the KRAs the SGOD Chief tagged this member into are copied in — not
        // the whole template. Untagged members start with an empty KRA section and
        // build their own.
        $templateKras = $this->assigned_template_kras($targetForm ? $targetForm['employee_id'] : '', (int) $templateId);
        foreach ($templateKras as $kra) {
            $this->copy_template_kra_into_form($formId, $kra, $kra['sort_order'], (string) $template['year'], $targetYear);
        }

        $competencies = $this->db->where('template_id', (int) $templateId)->order_by('sort_order')->get('ipcrf_template_competencies')->result_array();
        foreach ($competencies as $competency) {
            $this->db->insert('ipcrf_competencies', array(
                'form_id' => $formId, 'category' => $competency['category'], 'name' => $competency['name'],
                'indicators_json' => $competency['indicators_json'], 'rating' => 0,
                'employee_rating' => 0, 'rater_rating' => 0, 'final_rating' => 0,
                'sort_order' => $competency['sort_order'], 'is_deleted' => 0
            ));
        }
        $this->db->where('id', (int) $formId)->update('ipcrf_forms', array('template_id' => (int) $templateId, 'updated_by' => $actor, 'updated_at' => date('Y-m-d H:i:s')));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function decode_json($value, $fallback = array())
    {
        $decoded = json_decode((string) $value, TRUE);
        return is_array($decoded) ? $decoded : $fallback;
    }

    public function get_bundle($formId)
    {
        $form = $this->get_form($formId);
        if (!$form) {
            return NULL;
        }

        $kras = $this->db->where('form_id', (int) $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_kras')->result_array();
        $evidenceRows = $this->db->where('form_id', (int) $formId)->order_by('uploaded_at', 'DESC')->get('ipcrf_evidence')->result_array();
        $evidenceMap = array();
        foreach ($evidenceRows as $evidence) {
            $evidence['url'] = site_url('Ipcrf/download_evidence/' . (int) $formId . '/' . (int) $evidence['id']);
            $evidenceMap[(int) $evidence['objective_id']][] = $evidence;
        }
        foreach ($kras as &$kra) {
            $objectives = $this->db->where('kra_id', $kra['id'])->where('form_id', (int) $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_objectives')->result_array();
            foreach ($objectives as &$objective) {
                $objective['quality'] = $this->decode_json($objective['quality_json']);
                $objective['efficiency'] = $this->decode_json($objective['efficiency_json']);
                $objective['timeliness'] = $this->decode_json($objective['timeliness_json']);
                $objective['evidence'] = isset($evidenceMap[(int) $objective['id']]) ? $evidenceMap[(int) $objective['id']] : array();
                unset($objective['quality_json'], $objective['efficiency_json'], $objective['timeliness_json']);
            }
            unset($objective);
            $kra['objectives'] = $objectives;
        }
        unset($kra);

        $competencies = $this->db->where('form_id', (int) $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_competencies')->result_array();
        foreach ($competencies as &$competency) {
            $competency['indicators'] = $this->decode_json($competency['indicators_json']);
            unset($competency['indicators_json'], $competency['employee_rating'], $competency['rater_rating'], $competency['final_rating']);
        }
        unset($competency);

        $development = $this->db->where('form_id', (int) $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_development_plans')->result_array();
        $history = $this->db->where('form_id', (int) $formId)->order_by('acted_at', 'DESC')->order_by('id', 'DESC')->get('ipcrf_workflow_history')->result_array();

        return array('form' => $form, 'kras' => $kras, 'competencies' => $competencies, 'development' => $development, 'history' => $history);
    }

    private function safe_rating($value)
    {
        $rating = (float) $value;
        return ($rating >= 1 && $rating <= 5) ? $rating : 0;
    }

    private function safe_standard($value)
    {
        $result = array();
        foreach (array('5', '4', '3', '2', '1') as $level) {
            $result[$level] = trim((string) (isset($value[$level]) ? $value[$level] : ''));
        }
        return $result;
    }

    public function save_bundle($formId, $payload, $actor, $scope)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();

        if ($scope === 'full') {
            $header = isset($payload['form']) && is_array($payload['form']) ? $payload['form'] : array();
            $this->db->where('id', (int) $formId)->update('ipcrf_forms', array(
                'rater_id' => trim((string) (isset($header['rater_id']) ? $header['rater_id'] : '')),
                'rater_name' => trim((string) (isset($header['rater_name']) ? $header['rater_name'] : '')),
                'rater_position' => trim((string) (isset($header['rater_position']) ? $header['rater_position'] : '')),
                'approving_authority_id' => trim((string) (isset($header['approving_authority_id']) ? $header['approving_authority_id'] : '')),
                'approving_authority_name' => trim((string) (isset($header['approving_authority_name']) ? $header['approving_authority_name'] : '')),
                'approving_authority_position' => trim((string) (isset($header['approving_authority_position']) ? $header['approving_authority_position'] : '')),
                'period_start' => $this->valid_date(isset($header['period_start']) ? $header['period_start'] : '') ?: date('Y-01-01'),
                'period_end' => $this->valid_date(isset($header['period_end']) ? $header['period_end'] : '') ?: date('Y-12-31'),
                'updated_by' => $actor, 'updated_at' => $now
            ));

            $this->db->where('form_id', (int) $formId)->update('ipcrf_kras', array('is_deleted' => 1));
            $this->db->where('form_id', (int) $formId)->update('ipcrf_objectives', array('is_deleted' => 1));
            $kras = isset($payload['kras']) && is_array($payload['kras']) ? $payload['kras'] : array();
            foreach ($kras as $kraOrder => $kra) {
                $kraId = isset($kra['id']) ? (int) $kra['id'] : 0;
                $kraData = array('form_id' => $formId, 'title' => trim((string) (isset($kra['title']) ? $kra['title'] : 'Untitled KRA')), 'sort_order' => $kraOrder + 1, 'is_deleted' => 0);
                if ($kraId > 0 && $this->row_belongs('ipcrf_kras', $kraId, $formId)) {
                    $this->db->where('id', $kraId)->update('ipcrf_kras', $kraData);
                } else {
                    $this->db->insert('ipcrf_kras', $kraData);
                    $kraId = (int) $this->db->insert_id();
                }
                $objectives = isset($kra['objectives']) && is_array($kra['objectives']) ? $kra['objectives'] : array();
                foreach ($objectives as $objectiveOrder => $objective) {
                    $objectiveId = isset($objective['id']) ? (int) $objective['id'] : 0;
                    $objectiveData = array(
                        'form_id' => $formId, 'kra_id' => $kraId, 'code' => trim((string) (isset($objective['code']) ? $objective['code'] : '')),
                        'objective' => trim((string) (isset($objective['objective']) ? $objective['objective'] : '')),
                        'timeline' => trim((string) (isset($objective['timeline']) ? $objective['timeline'] : '')),
                        'weight' => max(0, min(100, (float) (isset($objective['weight']) ? $objective['weight'] : 0))),
                        'quality_json' => json_encode($this->safe_standard(isset($objective['quality']) ? $objective['quality'] : array())),
                        'efficiency_json' => json_encode($this->safe_standard(isset($objective['efficiency']) ? $objective['efficiency'] : array())),
                        'timeliness_json' => json_encode($this->safe_standard(isset($objective['timeliness']) ? $objective['timeliness'] : array())),
                        'accomplishment' => trim((string) (isset($objective['accomplishment']) ? $objective['accomplishment'] : '')),
                        'quality_rating' => $this->safe_rating(isset($objective['quality_rating']) ? $objective['quality_rating'] : 0),
                        'efficiency_rating' => $this->safe_rating(isset($objective['efficiency_rating']) ? $objective['efficiency_rating'] : 0),
                        'timeliness_rating' => $this->safe_rating(isset($objective['timeliness_rating']) ? $objective['timeliness_rating'] : 0),
                        'sort_order' => $objectiveOrder + 1, 'is_deleted' => 0
                    );
                    if ($objectiveId > 0 && $this->row_belongs('ipcrf_objectives', $objectiveId, $formId)) {
                        // The owner proposes Q/E/T ratings in Draft. They remain provisional until
                        // the assigned rater reviews them and moves the form to Rater Approved.
                        $this->db->where('id', $objectiveId)->update('ipcrf_objectives', $objectiveData);
                    } else {
                        $this->db->insert('ipcrf_objectives', $objectiveData);
                    }
                }
            }

            $this->db->where('form_id', (int) $formId)->update('ipcrf_competencies', array('is_deleted' => 1));
            $competencies = isset($payload['competencies']) && is_array($payload['competencies']) ? $payload['competencies'] : array();
            foreach ($competencies as $order => $competency) {
                $competencyId = isset($competency['id']) ? (int) $competency['id'] : 0;
                $competencyData = array(
                    'form_id' => $formId, 'category' => trim((string) (isset($competency['category']) ? $competency['category'] : 'Core Behavioral Competency')),
                    'name' => trim((string) (isset($competency['name']) ? $competency['name'] : '')),
                    'indicators_json' => json_encode(array_values(array_filter(array_map('trim', (array) (isset($competency['indicators']) ? $competency['indicators'] : array())), 'strlen'))),
                    'rating' => (int) $this->safe_rating(isset($competency['rating']) ? $competency['rating'] : 0),
                    'sort_order' => $order + 1, 'is_deleted' => 0
                );
                if ($competencyId > 0 && $this->row_belongs('ipcrf_competencies', $competencyId, $formId)) {
                    $this->db->where('id', $competencyId)->update('ipcrf_competencies', $competencyData);
                } else {
                    $this->db->insert('ipcrf_competencies', $competencyData);
                }
            }

            $this->save_development_plans($formId, isset($payload['development']) ? $payload['development'] : array());
        } elseif ($scope === 'rater') {
            foreach ((array) (isset($payload['kras']) ? $payload['kras'] : array()) as $kra) {
                foreach ((array) (isset($kra['objectives']) ? $kra['objectives'] : array()) as $objective) {
                    $objectiveId = isset($objective['id']) ? (int) $objective['id'] : 0;
                    if ($objectiveId && $this->row_belongs('ipcrf_objectives', $objectiveId, $formId)) {
                        $this->db->where('id', $objectiveId)->update('ipcrf_objectives', array(
                            'quality_rating' => $this->safe_rating(isset($objective['quality_rating']) ? $objective['quality_rating'] : 0),
                            'efficiency_rating' => $this->safe_rating(isset($objective['efficiency_rating']) ? $objective['efficiency_rating'] : 0),
                            'timeliness_rating' => $this->safe_rating(isset($objective['timeliness_rating']) ? $objective['timeliness_rating'] : 0)
                        ));
                    }
                }
            }
            foreach ((array) (isset($payload['competencies']) ? $payload['competencies'] : array()) as $competency) {
                $competencyId = isset($competency['id']) ? (int) $competency['id'] : 0;
                if ($competencyId && $this->row_belongs('ipcrf_competencies', $competencyId, $formId)) {
                    $this->db->where('id', $competencyId)->update('ipcrf_competencies', array('rating' => (int) $this->safe_rating(isset($competency['rating']) ? $competency['rating'] : 0)));
                }
            }
            // The assigned rater may complete or revise the Development Plan while
            // reviewing the scores, without receiving access to owner-only KRA data.
            $this->save_development_plans($formId, isset($payload['development']) ? $payload['development'] : array());
            $this->db->where('id', (int) $formId)->update('ipcrf_forms', array('updated_by' => $actor, 'updated_at' => $now));
        } elseif ($scope === 'pmt') {
            foreach ((array) (isset($payload['competencies']) ? $payload['competencies'] : array()) as $competency) {
                $competencyId = isset($competency['id']) ? (int) $competency['id'] : 0;
                if ($competencyId && $this->row_belongs('ipcrf_competencies', $competencyId, $formId)) {
                    $this->db->where('id', $competencyId)->update('ipcrf_competencies', array('rating' => (int) $this->safe_rating(isset($competency['rating']) ? $competency['rating'] : 0)));
                }
            }
            $this->db->where('id', (int) $formId)->update('ipcrf_forms', array('updated_by' => $actor, 'updated_at' => $now));
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function save_development_plans($formId, $development)
    {
        $this->db->where('form_id', (int) $formId)->update('ipcrf_development_plans', array('is_deleted' => 1));
        foreach ((array) $development as $order => $plan) {
            $planId = isset($plan['id']) ? (int) $plan['id'] : 0;
            $planData = array(
                'form_id' => $formId,
                'strengths' => trim((string) (isset($plan['strengths']) ? $plan['strengths'] : '')),
                'improvement_needs' => trim((string) (isset($plan['improvement_needs']) ? $plan['improvement_needs'] : '')),
                'learning_objectives' => trim((string) (isset($plan['learning_objectives']) ? $plan['learning_objectives'] : '')),
                'interventions' => trim((string) (isset($plan['interventions']) ? $plan['interventions'] : '')),
                'target_timeline' => trim((string) (isset($plan['target_timeline']) ? $plan['target_timeline'] : '')),
                'responsible_person' => trim((string) (isset($plan['responsible_person']) ? $plan['responsible_person'] : '')),
                'status_remarks' => trim((string) (isset($plan['status_remarks']) ? $plan['status_remarks'] : '')),
                'sort_order' => $order + 1,
                'is_deleted' => 0
            );
            if ($planId > 0 && $this->row_belongs('ipcrf_development_plans', $planId, $formId)) {
                $this->db->where('id', $planId)->update('ipcrf_development_plans', $planData);
            } else {
                $this->db->insert('ipcrf_development_plans', $planData);
            }
        }
    }

    private function row_belongs($table, $id, $formId)
    {
        return $this->db->where('id', (int) $id)->where('form_id', (int) $formId)->count_all_results($table) > 0;
    }

    public function valid_date($value)
    {
        $date = DateTime::createFromFormat('Y-m-d', trim((string) $value));
        return ($date && $date->format('Y-m-d') === $value) ? $value : FALSE;
    }

    public function validation_errors($bundle, $stage = 'current')
    {
        $errors = array();
        foreach ($this->validation_groups($bundle, $stage) as $group) {
            foreach ($group['items'] as $item) {
                $errors[] = $item;
            }
        }
        return $errors;
    }

    /**
     * Same checks as validation_errors(), but split into one bucket per table so
     * the editor can flag exactly the table that is unfinished.
     *
     * Every bucket carries a `started` flag: TRUE once the employee has encoded
     * something into that specific table. The editor shows a bucket only when it
     * has started, so an untouched KRA or competency group stays quiet while a
     * half-finished one right next to it is flagged. Submitting overrides this
     * and shows everything (see the `showAllWarnings` path in ipcrf.js).
     */
    public function validation_groups($bundle, $stage = 'current')
    {
        $groups = array();
        if (!$bundle || empty($bundle['form'])) {
            return array(array(
                'key' => 'employee', 'scope' => 'employee', 'ref' => '',
                'label' => 'Employee Information', 'section' => 'employeeSection',
                'started' => TRUE, 'items' => array('IPCRF record was not found.')
            ));
        }
        $form = $bundle['form'];
        $status = $form['status'];
        $needsEmployee = in_array($stage, array('submit_rater', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_DRAFT, self::STATUS_RETURNED), TRUE));
        $needsRater = in_array($stage, array('rater_approve', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_SUBMITTED_RATER, self::STATUS_RATER_APPROVED), TRUE));
        $needsFinal = in_array($stage, array('pmt_validate', 'lock', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_SUBMITTED_PMT, self::STATUS_PMT_VALIDATED, self::STATUS_LOCKED), TRUE));

        // ---- Employee information -------------------------------------------------
        $employeeItems = array();
        if (trim($form['employee_id']) === '' || trim($form['employee_name']) === '') {
            $employeeItems[] = 'Employee information is incomplete.';
        }
        if (trim($form['rater_name']) === '') {
            $employeeItems[] = 'An assigned rater is required.';
        }
        if (trim((string) (isset($form['approving_authority_name']) ? $form['approving_authority_name'] : '')) === '') {
            $employeeItems[] = 'An approving authority is required.';
        }
        if (!$this->valid_date($form['period_start']) || !$this->valid_date($form['period_end']) || $form['period_start'] > $form['period_end']) {
            $employeeItems[] = 'The performance period is invalid.';
        }
        if ($employeeItems) {
            $groups[] = array(
                'key' => 'employee', 'scope' => 'employee', 'ref' => '',
                'label' => 'Employee Information', 'section' => 'employeeSection',
                'started' => TRUE, 'items' => $employeeItems
            );
        }

        // ---- One bucket per KRA ---------------------------------------------------
        $totalWeight = 0;
        $objectiveCount = 0;
        $anyKraStarted = FALSE;
        foreach ((array) $bundle['kras'] as $kraIndex => $kra) {
            $items = array();
            $started = FALSE;
            $detailMissing = 0;
            $weightMissing = 0;
            $standardsMissing = 0;
            $accomplishmentMissing = 0;
            $ratingMissing = 0;

            if (trim($kra['title']) === '') {
                $items[] = 'This KRA still needs a title.';
            }
            if (!count((array) $kra['objectives'])) {
                $items[] = 'This KRA has no objectives yet.';
            }
            foreach ((array) $kra['objectives'] as $objective) {
                $objectiveCount++;
                $totalWeight += (float) $objective['weight'];
                if ($this->objective_is_started($objective)) {
                    $started = TRUE;
                }
                if (trim($objective['objective']) === '' || trim($objective['timeline']) === '') {
                    $detailMissing++;
                }
                if ((float) $objective['weight'] <= 0) {
                    $weightMissing++;
                }
                // Counted once per objective, not once per blank level, so the
                // number means something to the person reading it.
                foreach (array('quality', 'efficiency', 'timeliness') as $dimension) {
                    $incompleteDimension = FALSE;
                    foreach (array('5', '4', '3', '2', '1') as $level) {
                        if (empty($objective[$dimension][$level])) {
                            $incompleteDimension = TRUE;
                        }
                    }
                    if ($incompleteDimension) {
                        $standardsMissing++;
                        break;
                    }
                }
                if (trim((string) $objective['accomplishment']) === '') {
                    $accomplishmentMissing++;
                }
                if ((float) $objective['quality_rating'] < 1 || (float) $objective['efficiency_rating'] < 1 || (float) $objective['timeliness_rating'] < 1) {
                    $ratingMissing++;
                }
            }
            if ($detailMissing) {
                $items[] = $this->count_label($detailMissing, 'objective still needs', 'objectives still need') . ' an objective or timeline.';
            }
            if ($weightMissing) {
                $items[] = $this->count_label($weightMissing, 'objective still needs', 'objectives still need') . ' a weight.';
            }
            if ($standardsMissing) {
                $items[] = $this->count_label($standardsMissing, 'objective is', 'objectives are') . ' missing Quality, Efficiency or Timeliness standards.';
            }
            if (($needsEmployee || $needsRater) && $accomplishmentMissing) {
                $items[] = $this->count_label($accomplishmentMissing, 'objective still needs', 'objectives still need') . ' an actual result.';
            }
            if ($needsRater && $ratingMissing) {
                $items[] = $this->count_label($ratingMissing, 'objective still needs', 'objectives still need') . ' Q, E and T ratings.';
            }
            if ($started) {
                $anyKraStarted = TRUE;
            }
            if ($items) {
                $title = trim((string) $kra['title']);
                $groups[] = array(
                    'key' => 'kra:' . $kraIndex, 'scope' => 'kra', 'ref' => $kraIndex,
                    'label' => $title !== '' ? $title : 'Untitled KRA',
                    'section' => 'kraSection', 'started' => $started, 'items' => $items
                );
            }
        }

        // Form-wide KRA issues that belong to no single table.
        $sectionItems = array();
        if (empty($bundle['kras'])) {
            $sectionItems[] = 'Add at least one KRA.';
        }
        if ($objectiveCount === 0) {
            $sectionItems[] = 'Add at least one objective.';
        }
        if ($objectiveCount > 0 && abs($totalWeight - 100) > 0.009) {
            $sectionItems[] = 'Objective weights across all KRAs add up to ' . number_format($totalWeight, 2) . '% — they need to total 100%.';
        }
        if ($sectionItems) {
            $groups[] = array(
                'key' => 'kra-section', 'scope' => 'kra-section', 'ref' => '',
                'label' => 'KRAs and Objectives', 'section' => 'kraSection',
                'started' => $anyKraStarted, 'items' => $sectionItems
            );
        }

        // ---- One bucket per competency group --------------------------------------
        $needsCompetencyRating = $needsEmployee || $needsRater || $needsFinal;
        $byCategory = array();
        foreach ((array) $bundle['competencies'] as $competency) {
            $category = trim((string) $competency['category']);
            if ($category === '') {
                $category = 'Other Competency';
            }
            if (!isset($byCategory[$category])) {
                $byCategory[$category] = array();
            }
            $byCategory[$category][] = $competency;
        }
        foreach ($byCategory as $category => $rows) {
            $items = array();
            $started = FALSE;
            $detailMissing = 0;
            $ratingMissing = 0;
            foreach ($rows as $competency) {
                if ((int) $competency['rating'] >= 1) {
                    $started = TRUE;
                }
                if (trim($competency['name']) === '' || empty($competency['indicators'])) {
                    $detailMissing++;
                }
                if ($needsCompetencyRating && (int) $competency['rating'] < 1) {
                    $ratingMissing++;
                }
            }
            if ($detailMissing) {
                $items[] = $this->count_label($detailMissing, 'competency still needs', 'competencies still need') . ' a name or behavioral indicators.';
            }
            if ($ratingMissing) {
                $items[] = $this->count_label($ratingMissing, 'competency still needs', 'competencies still need') . ' a rating.';
            }
            if ($items) {
                $groups[] = array(
                    'key' => 'competency:' . $category, 'scope' => 'competency', 'ref' => $category,
                    'label' => $this->competency_group_label($category),
                    'section' => 'competencySection', 'started' => $started, 'items' => $items
                );
            }
        }
        if (empty($bundle['competencies'])) {
            $groups[] = array(
                'key' => 'competency-section', 'scope' => 'competency-section', 'ref' => '',
                'label' => 'Competency Management', 'section' => 'competencySection',
                'started' => FALSE, 'items' => array('Add at least one competency.')
            );
        }

        // ---- Development plan -----------------------------------------------------
        $developmentItems = array();
        $developmentStarted = FALSE;
        if (empty($bundle['development'])) {
            $developmentItems[] = 'Add at least one development plan entry.';
        } else {
            $developmentMissing = 0;
            foreach ($bundle['development'] as $plan) {
                foreach (array('strengths', 'improvement_needs', 'learning_objectives', 'interventions', 'target_timeline', 'responsible_person', 'status_remarks') as $field) {
                    if (trim((string) $plan[$field]) !== '') {
                        $developmentStarted = TRUE;
                        break;
                    }
                }
                foreach (array('strengths', 'improvement_needs', 'learning_objectives', 'interventions', 'target_timeline', 'responsible_person') as $field) {
                    if (trim((string) $plan[$field]) === '') {
                        $developmentMissing++;
                        break;
                    }
                }
            }
            if ($developmentMissing) {
                $developmentItems[] = $this->count_label($developmentMissing, 'development plan entry has', 'development plan entries have') . ' blank fields.';
            }
        }
        if ($developmentItems) {
            $groups[] = array(
                'key' => 'development', 'scope' => 'development', 'ref' => '',
                'label' => 'Development Plan', 'section' => 'developmentSection',
                'started' => $developmentStarted, 'items' => $developmentItems
            );
        }

        return $groups;
    }

    private function competency_group_label($category)
    {
        $labels = array(
            'Core Behavioral Competency' => 'Core Behavioral Competencies',
            'Core Skill' => 'Core Skills',
            'Leadership Competency' => 'Leadership Competencies'
        );
        return isset($labels[$category]) ? $labels[$category] : $category;
    }

    /**
     * A preset objective arrives with its text, timeline, weight and standards
     * already filled, so only the fields the employee supplies count as a touch.
     */
    private function objective_is_started($objective)
    {
        if (trim((string) $objective['accomplishment']) !== '') {
            return TRUE;
        }
        return (float) $objective['quality_rating'] >= 1
            || (float) $objective['efficiency_rating'] >= 1
            || (float) $objective['timeliness_rating'] >= 1;
    }

    private function count_label($count, $singular, $plural)
    {
        return $count . ' ' . ($count === 1 ? $singular : $plural);
    }



    public function workflow_transition($formId, $toStatus, $remarks, $actor)
    {
        $form = $this->get_form($formId);
        if (!$form) {
            return FALSE;
        }
        $updates = array('status' => $toStatus, 'updated_by' => $actor, 'updated_at' => date('Y-m-d H:i:s'));
        if ($toStatus === self::STATUS_SUBMITTED_RATER) {
            $updates['submitted_at'] = date('Y-m-d H:i:s');
        } elseif ($toStatus === self::STATUS_PMT_VALIDATED) {
            $updates['validated_at'] = date('Y-m-d H:i:s');
        } elseif ($toStatus === self::STATUS_LOCKED) {
            $updates['locked_at'] = date('Y-m-d H:i:s');
        } elseif (in_array($toStatus, array(self::STATUS_DRAFT, self::STATUS_RETURNED), TRUE)) {
            $updates['locked_at'] = NULL;
        }
        $this->db->where('id', (int) $formId)->update('ipcrf_forms', $updates);
        $this->add_history($formId, $form['status'], $toStatus, $remarks, $actor, $this->actor_name());
        return TRUE;
    }

    public function add_history($formId, $fromStatus, $toStatus, $remarks, $actor, $actorName)
    {
        return $this->db->insert('ipcrf_workflow_history', array(
            'form_id' => (int) $formId, 'from_status' => $fromStatus, 'to_status' => $toStatus,
            'remarks' => trim((string) $remarks), 'acted_by' => $actor, 'acted_by_name' => $actorName,
            'acted_at' => date('Y-m-d H:i:s')
        ));
    }

    public function actor_name()
    {
        return trim((string) $this->session->userdata('fName') . ' ' . (string) $this->session->userdata('lName'));
    }

    public function is_admin()
    {
        return in_array((string) $this->session->userdata('section'), array('Super Admin', 'System Administrator'), TRUE);
    }

    public function is_pmt()
    {
        return $this->is_admin() || (string) $this->session->userdata('section') === 'Chief - SGOD';
    }

    public function can_view($form, $actor)
    {
        $approvingAuthorityId = isset($form['approving_authority_id']) ? $form['approving_authority_id'] : '';
        if ($form['employee_id'] === $actor || $form['rater_id'] === $actor || $approvingAuthorityId === $actor || $this->is_admin()) {
            return TRUE;
        }
        return $this->is_pmt() && in_array($form['status'], array(self::STATUS_SUBMITTED_PMT, self::STATUS_PMT_VALIDATED, self::STATUS_LOCKED), TRUE);
    }

    public function edit_scope($form, $actor)
    {
        if ($this->is_admin() && in_array($form['status'], array(self::STATUS_DRAFT, self::STATUS_RETURNED), TRUE)) {
            return 'full';
        }
        if (in_array($form['status'], array(self::STATUS_DRAFT, self::STATUS_RETURNED), TRUE) && $form['employee_id'] === $actor) {
            return 'full';
        }
        if ($form['status'] === self::STATUS_SUBMITTED_RATER && ($form['rater_id'] === $actor || $this->is_admin())) {
            return 'rater';
        }
        if ($form['status'] === self::STATUS_SUBMITTED_PMT && $this->is_pmt()) {
            return 'pmt';
        }
        return 'none';
    }

    public function add_evidence($data)
    {
        $this->db->insert('ipcrf_evidence', $data);
        return (int) $this->db->insert_id();
    }

    public function get_evidence($id)
    {
        return $this->db->where('id', (int) $id)->get('ipcrf_evidence', 1)->row_array();
    }

    public function delete_evidence($id)
    {
        return $this->db->where('id', (int) $id)->delete('ipcrf_evidence');
    }

    public function objective_belongs($objectiveId, $formId)
    {
        return $this->db->where('id', (int) $objectiveId)->where('form_id', (int) $formId)->where('is_deleted', 0)->count_all_results('ipcrf_objectives') > 0;
    }

    /* ==========================================================================
     * IPCR Template management (used by the SGOD Chief "Manage IPCR" workspace).
     * The active template is the master preset every member's IPCRF copies from.
     * ======================================================================== */

    public function get_active_template()
    {
        $template = $this->db->where('is_active', 1)->order_by('year', 'DESC')->get('ipcrf_templates', 1)->row_array();
        if (!$template) {
            $template = $this->get_default_template();
        }
        return $template;
    }

    // Full nested template: template -> kras[ objectives[] ] + competencies[] (grouped-ready).
    public function get_template_bundle($templateId)
    {
        $templateId = (int) $templateId;
        $template = $this->db->where('id', $templateId)->get('ipcrf_templates', 1)->row_array();
        if (!$template) {
            return NULL;
        }

        $kras = $this->db->where('template_id', $templateId)->order_by('sort_order')->get('ipcrf_template_kras')->result_array();
        $totalWeight = 0;
        foreach ($kras as $index => $kra) {
            $objectives = $this->db->where('template_kra_id', (int) $kra['id'])->order_by('sort_order')->get('ipcrf_template_objectives')->result_array();
            foreach ($objectives as &$objective) {
                $objective['quality'] = $this->decode_json($objective['quality_json']);
                $objective['efficiency'] = $this->decode_json($objective['efficiency_json']);
                $objective['timeliness'] = $this->decode_json($objective['timeliness_json']);
                $totalWeight += (float) $objective['weight'];
            }
            unset($objective);
            $kras[$index]['objectives'] = $objectives;
        }

        $competencies = $this->db->where('template_id', $templateId)->order_by('sort_order')->get('ipcrf_template_competencies')->result_array();
        foreach ($competencies as &$competency) {
            $competency['indicators'] = $this->decode_json($competency['indicators_json']);
        }
        unset($competency);

        return array(
            'template' => $template,
            'kras' => $kras,
            'competencies' => $competencies,
            'total_weight' => $totalWeight
        );
    }

    public function update_template_meta($templateId, $data)
    {
        $update = array(
            'name' => trim((string) ($data['name'] ?? '')),
            'year' => (int) ($data['year'] ?? date('Y')),
            'description' => trim((string) ($data['description'] ?? '')),
            'updated_at' => date('Y-m-d H:i:s')
        );
        return $this->db->where('id', (int) $templateId)->update('ipcrf_templates', $update);
    }

    private function next_sort_order($table, $column, $parentId)
    {
        $row = $this->db->select_max('sort_order', 'mx')->where($column, (int) $parentId)->get($table)->row_array();
        return ((int) ($row['mx'] ?? 0)) + 1;
    }

    // ---- KRA ----
    public function add_template_kra($templateId, $title)
    {
        $this->db->insert('ipcrf_template_kras', array(
            'template_id' => (int) $templateId,
            'title' => trim((string) $title),
            'sort_order' => $this->next_sort_order('ipcrf_template_kras', 'template_id', $templateId)
        ));
        $this->touch_template($templateId);
        return (int) $this->db->insert_id();
    }

    public function update_template_kra($kraId, $title)
    {
        return $this->db->where('id', (int) $kraId)->update('ipcrf_template_kras', array('title' => trim((string) $title)));
    }

    public function delete_template_kra($kraId)
    {
        $kraId = (int) $kraId;
        $this->db->where('template_kra_id', $kraId)->delete('ipcrf_template_objectives');
        return $this->db->where('id', $kraId)->delete('ipcrf_template_kras');
    }

    public function template_kra($kraId)
    {
        return $this->db->where('id', (int) $kraId)->get('ipcrf_template_kras', 1)->row_array();
    }

    // ---- Objective ----
    // $scale keys expected: quality, efficiency, timeliness -> each an array('5'=>..,'1'=>..)
    public function save_template_objective($kraId, $objectiveId, $data)
    {
        $payload = array(
            'code' => trim((string) ($data['code'] ?? '')),
            'objective' => trim((string) ($data['objective'] ?? '')),
            'timeline' => trim((string) ($data['timeline'] ?? '')),
            'weight' => (float) ($data['weight'] ?? 0),
            'quality_json' => json_encode($this->normalize_scale($data['quality'] ?? array())),
            'efficiency_json' => json_encode($this->normalize_scale($data['efficiency'] ?? array())),
            'timeliness_json' => json_encode($this->normalize_scale($data['timeliness'] ?? array()))
        );

        if ((int) $objectiveId > 0) {
            // Forms that omit a field (e.g. the KRA workspace, which only edits
            // code/objective) must not blank out the stored value.
            $optional = array('timeline' => 'timeline', 'weight' => 'weight', 'quality' => 'quality_json', 'efficiency' => 'efficiency_json', 'timeliness' => 'timeliness_json');
            foreach ($optional as $key => $column) {
                if (!array_key_exists($key, $data) || $data[$key] === NULL) {
                    unset($payload[$column]);
                }
            }
            $this->db->where('id', (int) $objectiveId)->update('ipcrf_template_objectives', $payload);
            return (int) $objectiveId;
        }

        $payload['template_kra_id'] = (int) $kraId;
        $payload['sort_order'] = $this->next_sort_order('ipcrf_template_objectives', 'template_kra_id', $kraId);
        $this->db->insert('ipcrf_template_objectives', $payload);
        return (int) $this->db->insert_id();
    }

    public function delete_template_objective($objectiveId)
    {
        return $this->db->where('id', (int) $objectiveId)->delete('ipcrf_template_objectives');
    }

    public function template_objective($objectiveId)
    {
        $objective = $this->db->where('id', (int) $objectiveId)->get('ipcrf_template_objectives', 1)->row_array();
        if ($objective) {
            $objective['quality'] = $this->decode_json($objective['quality_json']);
            $objective['efficiency'] = $this->decode_json($objective['efficiency_json']);
            $objective['timeliness'] = $this->decode_json($objective['timeliness_json']);
        }
        return $objective;
    }

    // ---- Competency ----
    public function save_template_competency($templateId, $competencyId, $data)
    {
        $payload = array(
            'category' => trim((string) ($data['category'] ?? '')),
            'name' => trim((string) ($data['name'] ?? '')),
            'indicators_json' => json_encode($this->normalize_indicators($data['indicators'] ?? array()))
        );

        if ((int) $competencyId > 0) {
            $this->db->where('id', (int) $competencyId)->update('ipcrf_template_competencies', $payload);
            return (int) $competencyId;
        }

        $payload['template_id'] = (int) $templateId;
        $payload['sort_order'] = $this->next_sort_order('ipcrf_template_competencies', 'template_id', $templateId);
        $this->db->insert('ipcrf_template_competencies', $payload);
        return (int) $this->db->insert_id();
    }

    public function delete_template_competency($competencyId)
    {
        return $this->db->where('id', (int) $competencyId)->delete('ipcrf_template_competencies');
    }

    public function template_competency($competencyId)
    {
        $competency = $this->db->where('id', (int) $competencyId)->get('ipcrf_template_competencies', 1)->row_array();
        if ($competency) {
            $competency['indicators'] = $this->decode_json($competency['indicators_json']);
        }
        return $competency;
    }

    private function touch_template($templateId)
    {
        $this->db->where('id', (int) $templateId)->update('ipcrf_templates', array('updated_at' => date('Y-m-d H:i:s')));
    }

    // Force a 5..1 keyed scale from posted values, trimming blanks to empty strings.
    private function normalize_scale($scale)
    {
        $scale = is_array($scale) ? $scale : array();
        $out = array();
        foreach (array('5', '4', '3', '2', '1') as $level) {
            $out[$level] = trim((string) ($scale[$level] ?? ''));
        }
        return $out;
    }

    // Keep up to 5 non-null behavioral indicator strings (blanks dropped).
    private function normalize_indicators($indicators)
    {
        $indicators = is_array($indicators) ? $indicators : array();
        $out = array();
        foreach ($indicators as $indicator) {
            $indicator = trim((string) $indicator);
            if ($indicator !== '') {
                $out[] = $indicator;
            }
        }
        return $out;
    }

    /* ==========================================================================
     * KRA tagging — the SGOD Chief assigns members to specific KRAs. Each tagged
     * member then sees those KRAs (with their objectives) on their own side.
     * ======================================================================== */

    private function ensure_assignments_table()
    {
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS ipcrf_kra_assignments (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                template_kra_id INT UNSIGNED NOT NULL,
                member_username VARCHAR(45) NOT NULL,
                assigned_by VARCHAR(45) NOT NULL DEFAULT '',
                sort_order INT NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_kra_member (template_kra_id, member_username),
                KEY idx_member (member_username)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }

    // Members the Chief can tag, drawn from the same department (secGroup).
    public function get_taggable_members($secGroup)
    {
        $this->db->select('username, fName, lName, section');
        $this->db->from('one_sgod_users');
        $this->db->where('secGroup', $secGroup);
        $this->db->where_not_in('section', array('System Administrator', 'Super Admin'));
        $this->db->order_by('section', 'ASC');
        $this->db->order_by('lName', 'ASC');
        return $this->db->get()->result_array();
    }

    // template_kra_id => array of member usernames currently tagged.
    public function get_kra_member_map()
    {
        $this->ensure_assignments_table();
        $rows = $this->db->select('template_kra_id, member_username')->get('ipcrf_kra_assignments')->result_array();
        $map = array();
        foreach ($rows as $row) {
            $map[(int) $row['template_kra_id']][] = $row['member_username'];
        }
        return $map;
    }

    // Replace the tagged members for one KRA. sort_order keeps each member's list ordered.
    public function set_kra_members($kraId, $usernames, $assignedBy)
    {
        $this->ensure_assignments_table();
        $kraId = (int) $kraId;
        $usernames = is_array($usernames) ? array_values(array_unique(array_filter(array_map('trim', $usernames), 'strlen'))) : array();

        // Remove assignments no longer selected.
        if (empty($usernames)) {
            $this->db->where('template_kra_id', $kraId)->delete('ipcrf_kra_assignments');
            return;
        }
        $this->db->where('template_kra_id', $kraId)->where_not_in('member_username', $usernames)->delete('ipcrf_kra_assignments');

        foreach ($usernames as $username) {
            $exists = $this->db->where('template_kra_id', $kraId)->where('member_username', $username)->count_all_results('ipcrf_kra_assignments') > 0;
            if ($exists) {
                continue;
            }
            // Next position within this member's personal KRA list.
            $order = $this->db->select_max('sort_order', 'mx')->where('member_username', $username)->get('ipcrf_kra_assignments')->row_array();
            $this->db->insert('ipcrf_kra_assignments', array(
                'template_kra_id' => $kraId,
                'member_username' => $username,
                'assigned_by' => (string) $assignedBy,
                'sort_order' => ((int) ($order['mx'] ?? 0)) + 1,
                'created_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    /**
     * Template KRAs tagged to the person behind an HRIS employee id.
     *
     * Tags are stored against one_sgod_users.username while forms are keyed by
     * hris_staff.IDNumber, so each tagged username is put through
     * resolve_employee_id() — the same bridge the rest of the module uses — and
     * kept when it lands on this employee.
     */
    public function assigned_template_kras($employeeId, $templateId = 0)
    {
        $this->ensure_assignments_table();
        $employeeId = trim((string) $employeeId);
        if ($employeeId === '') {
            return array();
        }

        $this->db->select('k.id, k.template_id, k.title, a.member_username, a.sort_order');
        $this->db->from('ipcrf_kra_assignments a');
        $this->db->join('ipcrf_template_kras k', 'k.id = a.template_kra_id', 'inner');
        if ((int) $templateId > 0) {
            $this->db->where('k.template_id', (int) $templateId);
        }
        $this->db->order_by('a.sort_order', 'ASC');
        $rows = $this->db->get()->result_array();

        $resolved = array();
        $kras = array();
        foreach ($rows as $row) {
            $username = (string) $row['member_username'];
            if (!array_key_exists($username, $resolved)) {
                $resolved[$username] = $this->resolve_employee_id($username);
            }
            // One person may hold several usernames; de-duplicate by template KRA.
            if ($resolved[$username] === $employeeId) {
                $kras[(int) $row['id']] = $row;
            }
        }
        return array_values($kras);
    }

    private function copy_template_kra_into_form($formId, $templateKra, $sortOrder, $templateYear, $targetYear)
    {
        $this->db->insert('ipcrf_kras', array(
            'form_id' => (int) $formId,
            'template_kra_id' => (int) $templateKra['id'],
            'title' => $templateKra['title'],
            'sort_order' => (int) $sortOrder,
            'is_deleted' => 0
        ));
        $kraId = (int) $this->db->insert_id();

        $objectives = $this->db->where('template_kra_id', (int) $templateKra['id'])->order_by('sort_order')->get('ipcrf_template_objectives')->result_array();
        foreach ($objectives as $objective) {
            $timeline = preg_replace('/\b' . preg_quote((string) $templateYear, '/') . '\b/', $targetYear, (string) $objective['timeline']);
            $this->db->insert('ipcrf_objectives', array(
                'form_id' => (int) $formId, 'kra_id' => $kraId, 'code' => $objective['code'], 'objective' => $objective['objective'],
                'timeline' => $timeline, 'weight' => $objective['weight'], 'quality_json' => $objective['quality_json'],
                'efficiency_json' => $objective['efficiency_json'], 'timeliness_json' => $objective['timeliness_json'],
                'accomplishment' => '', 'quality_rating' => 0, 'efficiency_rating' => 0, 'timeliness_rating' => 0,
                'sort_order' => $objective['sort_order'], 'is_deleted' => 0
            ));
        }
        return $kraId;
    }

    /**
     * Pull any newly tagged KRAs into an existing form, appended after whatever is
     * already there. Idempotent: a template KRA that has ever been copied into this
     * form is skipped, so a member who removed one does not get it back on every
     * page load, and their own KRAs (template_kra_id = 0) are never touched.
     * Returns the number of KRAs added.
     */
    public function sync_assigned_kras($formId, $employeeId)
    {
        $formId = (int) $formId;
        $assigned = $this->assigned_template_kras($employeeId);
        if (empty($assigned)) {
            return 0;
        }

        $seen = array();
        $existing = $this->db->select('template_kra_id')->where('form_id', $formId)->where('template_kra_id >', 0)->get('ipcrf_kras')->result_array();
        foreach ($existing as $row) {
            $seen[(int) $row['template_kra_id']] = TRUE;
        }

        $pending = array();
        foreach ($assigned as $kra) {
            if (!isset($seen[(int) $kra['id']])) {
                $pending[] = $kra;
            }
        }
        if (empty($pending)) {
            return 0;
        }

        $form = $this->get_form($formId);
        $targetYear = $form ? date('Y', strtotime($form['period_start'])) : date('Y');
        $maxRow = $this->db->select_max('sort_order', 'mx')->where('form_id', $formId)->get('ipcrf_kras')->row_array();
        $sortOrder = (int) ($maxRow['mx'] ?? 0);

        $this->db->trans_start();
        foreach ($pending as $kra) {
            $template = $this->db->select('year')->where('id', (int) $kra['template_id'])->get('ipcrf_templates', 1)->row_array();
            $this->copy_template_kra_into_form($formId, $kra, ++$sortOrder, (string) ($template['year'] ?? $targetYear), $targetYear);
        }
        $this->db->trans_complete();

        return $this->db->trans_status() ? count($pending) : 0;
    }

    // The KRAs (with objectives) assigned to one member, in personal order.
    public function get_member_kras($username)
    {
        $this->ensure_assignments_table();
        $username = trim((string) $username);
        if ($username === '') {
            return array();
        }

        $this->db->select('a.id AS assignment_id, a.sort_order, k.id AS kra_id, k.title');
        $this->db->from('ipcrf_kra_assignments a');
        $this->db->join('ipcrf_template_kras k', 'k.id = a.template_kra_id', 'inner');
        $this->db->where('a.member_username', $username);
        $this->db->order_by('a.sort_order', 'ASC');
        $kras = $this->db->get()->result_array();

        foreach ($kras as $index => $kra) {
            $objectives = $this->db->where('template_kra_id', (int) $kra['kra_id'])->order_by('sort_order')->get('ipcrf_template_objectives')->result_array();
            foreach ($objectives as &$objective) {
                $objective['quality'] = $this->decode_json($objective['quality_json']);
                $objective['efficiency'] = $this->decode_json($objective['efficiency_json']);
                $objective['timeliness'] = $this->decode_json($objective['timeliness_json']);
            }
            unset($objective);
            $kras[$index]['objectives'] = $objectives;
        }
        return $kras;
    }
}
