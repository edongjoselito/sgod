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

        if ((int) $this->db->count_all('ipcrf_templates') === 0) {
            $this->seed_default_template();
        }
    }

    private function rating_scale($five, $four, $three, $two, $one)
    {
        return array('5' => $five, '4' => $four, '3' => $three, '2' => $two, '1' => $one);
    }

    private function percentage_scale($label)
    {
        return $this->rating_scale(
            $label . ' for 100% of the target',
            $label . ' for 90%–99% of the target',
            $label . ' for 80%–89% of the target',
            $label . ' for 70%–79% of the target',
            $label . ' for 69% and below of the target'
        );
    }

    private function error_scale()
    {
        return $this->rating_scale('Completed with no error', 'Completed with 1 error', 'Completed with 2 errors', 'Completed with 3 errors', 'Completed with 4 or more errors');
    }

    private function quarter_scale($last = 'No update provided')
    {
        return $this->rating_scale('Provided every quarter', 'Provided for 3 quarters', 'Provided for 2 quarters', 'Provided for 1 quarter', $last);
    }

    private function period_scale()
    {
        return $this->rating_scale('Completed within the specified period', 'Completed 1 month after the specified period', 'Completed 2 months after the specified period', 'Completed 3 months after the specified period', 'Completed 4 months or more after the specified period');
    }

    private function seed_default_template()
    {
        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();
        $this->db->insert('ipcrf_templates', array(
            'name' => 'SGOD IPCRF 2025 Preset',
            'year' => 2025,
            'description' => 'Master preset transcribed from the supplied six-page SGOD IPCRF 2025 form.',
            'is_active' => 1,
            'created_by' => 'system',
            'created_at' => $now,
            'updated_at' => $now
        ));
        $templateId = (int) $this->db->insert_id();

        $period = 'January to December 2025';
        $campaignQ = $this->rating_scale('Implemented at least 5 advocacy campaigns', 'Implemented 4 advocacy campaigns', 'Implemented 3 advocacy campaigns', 'Implemented 2 advocacy campaigns', 'Implemented 1 advocacy campaign');
        $campaignE = $this->rating_scale('Gathered pledges/resources after 5 advocacy campaigns', 'Gathered pledges/resources after 4 advocacy campaigns', 'Gathered pledges/resources after 3 advocacy campaigns', 'Gathered pledges/resources after 2 advocacy campaigns', 'Gathered pledges/resources after 1 advocacy campaign');
        $partnershipQ = $this->rating_scale('Established at least 50 partnership engagements', 'Established 40–49 partnership engagements', 'Established 30–39 partnership engagements', 'Established 20–29 partnership engagements', 'Established fewer than 20 partnership engagements');
        $partnershipE = $this->rating_scale('Partnerships generated from 100% of districts', 'Partnerships generated from 90%–99% of districts', 'Partnerships generated from 80%–89% of districts', 'Partnerships generated from 70%–79% of districts', 'Partnerships generated from 69% and below of districts');
        $proposalQ = $this->rating_scale('Prepared partnership proposals for at least 5 activities', 'Prepared proposals for 4 activities', 'Prepared proposals for 3 activities', 'Prepared proposals for 2 activities', 'Prepared proposal for 1 activity');
        $approvalE = $this->percentage_scale('Proposals approved');
        $programQ = $this->rating_scale('Monitored, analyzed and provided recommendations for at least 5 special programs/projects', 'Completed for 4 programs/projects', 'Completed for 3 programs/projects', 'Completed for 2 programs/projects', 'Completed for 1 program/project');
        $programE = $this->percentage_scale('Districts covered');
        $timely = $this->period_scale();

        $kras = array(
            array('title' => 'I – RESOURCING', 'objectives' => array(
                array('1.1', 'Prepare and implement advocacy campaign programs to increase awareness of stakeholders and gather resource support for basic education.', 10, $campaignQ, $campaignE, $timely),
                array('1.2', 'Establish and/or strengthen linkages, engagements and partnerships with stakeholders to ensure continuous support for basic education.', 5, $partnershipQ, $partnershipE, $timely),
                array('1.3', 'Monitor progress and outcome of projects and partnerships to identify areas for continuous improvement and sustaining partnerships, and submit report to the SDS.', 5, $programQ, $programE, $timely),
                array('1.4', 'Prepare final draft of partnership proposals for recommendation to the SDS.', 10, $proposalQ, $approvalE, $timely),
                array('1.5', 'Prepare final draft of Memorandum of Agreement/Understanding for recommendation to the SDS.', 5, $this->rating_scale('Prepared at least 5 MOUs/MOAs', 'Prepared 4 MOUs/MOAs', 'Prepared 3 MOUs/MOAs', 'Prepared 2 MOUs/MOAs', 'Prepared 1 MOU/MOA'), $approvalE, $timely),
                array('1.6', 'Accept donations (e.g. equipment and tools) from program/project partners for proper utilization.', 5, $this->rating_scale('Accepted 100% of donations', 'Accepted 90%–99% of donations', 'Accepted 80%–89% of donations', 'Accepted 70%–79% of donations', 'Accepted less than 70% of donations'), $this->percentage_scale('Donations with proof of acceptance'), $timely)
            )),
            array('title' => 'II – SUSTAINED PARTNERSHIP', 'objectives' => array(
                array('2.1', 'Finalize write-up and provide to stakeholders the status and progress of programs and projects to provide feedback and generate continuous support.', 10, $this->percentage_scale('Program/project updates provided'), $this->error_scale(), $this->quarter_scale()),
                array('2.2', 'Prepare and submit report on programs supported by stakeholders to management as feedback on progress, status and resource requirements.', 10, $this->percentage_scale('Program/project reports prepared'), $this->error_scale(), $this->quarter_scale()),
                array('2.3', 'Prepare and provide final report of accomplishments of programs supported by stakeholders to provide feedback and generate continuous support.', 5, $this->percentage_scale('Final accomplishment reports prepared and provided'), $this->error_scale(), $this->quarter_scale()),
                array('2.4', 'Monitor and ensure implementation of policies, standards and guidelines for outcomes-focused resource mobilization to maintain integrity and credibility of the schools division to its partners.', 10, $this->percentage_scale('School partnership standards monitored'), $this->error_scale(), $this->quarter_scale()),
                array('2.5', 'Design approach and method for monitoring implementation of programs and projects focused on resource mobilization and submit report to management.', 5, $this->percentage_scale('District monitoring method designed'), $this->error_scale(), $this->quarter_scale('No monitoring list provided')),
                array('2.6', 'Capacitate schools in forging partnership linkages.', 5, $this->percentage_scale('Schools capacitated'), $this->percentage_scale('Schools generating partnerships after capacity building'), $timely)
            )),
            array('title' => 'III – RESEARCH AND DEVELOPMENT', 'objectives' => array(
                array('3.1', 'Conduct action research on factors contributing to successful participation and provision of resources for school governance.', 5, $this->rating_scale('Research completed with all required parts', 'Research lacks 1 required part', 'Research lacks 2 required parts', 'Research lacks 3 required parts', 'Research lacks 4 or more required parts'), $this->error_scale(), $timely)
            )),
            array('title' => 'IV – TECHNICAL ASSISTANCE', 'objectives' => array(
                array('4.1', 'Provide technical assistance to schools and learning centers by responding to identified needs in relation to social mobilization, governance and operations.', 5, $this->rating_scale('Provided technical assistance for at least 5 special programs/projects', 'Provided assistance for 4 programs/projects', 'Provided assistance for 3 programs/projects', 'Provided assistance for 2 programs/projects', 'Provided assistance for 1 program/project'), $programE, $timely)
            )),
            array('title' => 'V – PLUS FACTOR', 'objectives' => array(
                array('5.1', 'Conceptualize, develop and deploy innovative digital systems to address operational and governance requirements of different sections beyond Social Mobilization.', 5, $this->rating_scale('Innovation fully addresses the identified requirements', 'Innovation addresses most requirements', 'Innovation addresses the essential requirements', 'Innovation partially addresses requirements', 'Innovation does not yet address the essential requirements'), $this->rating_scale('Deployed with optimal use of available resources', 'Deployed with minor resource issues', 'Deployed with acceptable resource use', 'Deployed with significant resource issues', 'Not successfully deployed'), $timely)
            ))
        );

        foreach ($kras as $kraOrder => $kra) {
            $this->db->insert('ipcrf_template_kras', array('template_id' => $templateId, 'title' => $kra['title'], 'sort_order' => $kraOrder + 1));
            $kraId = (int) $this->db->insert_id();
            foreach ($kra['objectives'] as $objectiveOrder => $objective) {
                $this->db->insert('ipcrf_template_objectives', array(
                    'template_kra_id' => $kraId,
                    'code' => $objective[0],
                    'objective' => $objective[1],
                    'timeline' => $period,
                    'weight' => $objective[2],
                    'quality_json' => json_encode($objective[3]),
                    'efficiency_json' => json_encode($objective[4]),
                    'timeliness_json' => json_encode($objective[5]),
                    'sort_order' => $objectiveOrder + 1
                ));
            }
        }

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

        $templateKras = $this->db->where('template_id', (int) $templateId)->order_by('sort_order')->get('ipcrf_template_kras')->result_array();
        foreach ($templateKras as $kra) {
            $this->db->insert('ipcrf_kras', array('form_id' => $formId, 'title' => $kra['title'], 'sort_order' => $kra['sort_order'], 'is_deleted' => 0));
            $kraId = (int) $this->db->insert_id();
            $objectives = $this->db->where('template_kra_id', $kra['id'])->order_by('sort_order')->get('ipcrf_template_objectives')->result_array();
            foreach ($objectives as $objective) {
                $timeline = preg_replace('/\b' . preg_quote((string) $template['year'], '/') . '\b/', $targetYear, $objective['timeline']);
                $this->db->insert('ipcrf_objectives', array(
                    'form_id' => $formId, 'kra_id' => $kraId, 'code' => $objective['code'], 'objective' => $objective['objective'],
                    'timeline' => $timeline, 'weight' => $objective['weight'], 'quality_json' => $objective['quality_json'],
                    'efficiency_json' => $objective['efficiency_json'], 'timeliness_json' => $objective['timeliness_json'],
                    'accomplishment' => '', 'quality_rating' => 0, 'efficiency_rating' => 0, 'timeliness_rating' => 0,
                    'sort_order' => $objective['sort_order'], 'is_deleted' => 0
                ));
            }
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
        if (!$bundle || empty($bundle['form'])) {
            return array('IPCRF record was not found.');
        }
        $form = $bundle['form'];
        if (trim($form['employee_id']) === '' || trim($form['employee_name']) === '') {
            $errors[] = 'Employee information is incomplete.';
        }
        if (trim($form['rater_name']) === '') {
            $errors[] = 'An assigned rater is required.';
        }
        if (trim((string) (isset($form['approving_authority_name']) ? $form['approving_authority_name'] : '')) === '') {
            $errors[] = 'An approving authority is required.';
        }
        if (!$this->valid_date($form['period_start']) || !$this->valid_date($form['period_end']) || $form['period_start'] > $form['period_end']) {
            $errors[] = 'The performance period is invalid.';
        }
        if (empty($bundle['kras'])) {
            $errors[] = 'At least one KRA is required.';
        }

        $weight = 0;
        $objectiveCount = 0;
        $structuralMissing = 0;
        $accomplishmentMissing = 0;
        $ratingMissing = 0;
        foreach ($bundle['kras'] as $kra) {
            if (trim($kra['title']) === '') {
                $structuralMissing++;
            }
            foreach ($kra['objectives'] as $objective) {
                $objectiveCount++;
                $weight += (float) $objective['weight'];
                if (trim($objective['objective']) === '' || trim($objective['timeline']) === '' || (float) $objective['weight'] <= 0) {
                    $structuralMissing++;
                }
                foreach (array('quality', 'efficiency', 'timeliness') as $dimension) {
                    foreach (array('5', '4', '3', '2', '1') as $level) {
                        if (empty($objective[$dimension][$level])) {
                            $structuralMissing++;
                        }
                    }
                }
                if (trim((string) $objective['accomplishment']) === '') {
                    $accomplishmentMissing++;
                }
                if ((float) $objective['quality_rating'] < 1 || (float) $objective['efficiency_rating'] < 1 || (float) $objective['timeliness_rating'] < 1) {
                    $ratingMissing++;
                }
            }
        }
        if ($objectiveCount === 0) {
            $errors[] = 'At least one objective is required.';
        }
        if (abs($weight - 100) > 0.009) {
            $errors[] = 'Total objective weight must be exactly 100%; current total is ' . number_format($weight, 2) . '%.';
        }
        if ($structuralMissing) {
            $errors[] = $structuralMissing . ' required KRA/objective/indicator field(s) are incomplete.';
        }

        $status = $form['status'];
        $needsEmployee = in_array($stage, array('submit_rater', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_DRAFT, self::STATUS_RETURNED), TRUE));
        $needsRater = in_array($stage, array('rater_approve', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_SUBMITTED_RATER, self::STATUS_RATER_APPROVED), TRUE));
        $needsFinal = in_array($stage, array('pmt_validate', 'lock', 'all'), TRUE) || ($stage === 'current' && in_array($status, array(self::STATUS_SUBMITTED_PMT, self::STATUS_PMT_VALIDATED, self::STATUS_LOCKED), TRUE));
        if (($needsEmployee || $needsRater) && $accomplishmentMissing) {
            $errors[] = $accomplishmentMissing . ' objective accomplishment(s) are blank.';
        }
        if ($needsRater && $ratingMissing) {
            $errors[] = $ratingMissing . ' objective(s) do not have complete Quality, Efficiency and Timeliness ratings.';
        }
        if (empty($bundle['competencies'])) {
            $errors[] = 'At least one competency is required.';
        }
        $needsCompetencyRating = $needsEmployee || $needsRater || $needsFinal;
        $competencyMissing = 0;
        foreach ($bundle['competencies'] as $competency) {
            if (trim($competency['name']) === '' || empty($competency['indicators'])) {
                $competencyMissing++;
            }
            if ($needsCompetencyRating && (int) $competency['rating'] < 1) {
                $competencyMissing++;
            }
        }
        if ($competencyMissing) {
            $errors[] = $competencyMissing . ' competency name, indicator, or rating field(s) are incomplete.';
        }
        if (empty($bundle['development'])) {
            $errors[] = 'At least one Development Plan entry is required.';
        } else {
            $developmentMissing = 0;
            foreach ($bundle['development'] as $plan) {
                foreach (array('strengths', 'improvement_needs', 'learning_objectives', 'interventions', 'target_timeline', 'responsible_person') as $field) {
                    if (trim((string) $plan[$field]) === '') {
                        $developmentMissing++;
                    }
                }
            }
            if ($developmentMissing) {
                $errors[] = $developmentMissing . ' Development Plan field(s) are incomplete.';
            }
        }
        return $errors;
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
}
