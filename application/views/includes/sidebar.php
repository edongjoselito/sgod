<div class="left-side-menu">

    <div class="slimscroll-menu">
        <?php
            $currentSidebarSection = trim((string) $this->session->userdata('section'));
            $currentSidebarSecGroup = trim((string) $this->session->userdata('secGroup'));
            $currentSidebarUsername = trim((string) $this->session->userdata('username'));
            $sectionDashboardRoutes = array(
                'School Management Monitoring and Evaluation' => 'Page/SMME',
                'Planning' => 'Page/planning',
                'Research' => 'Page/research',
                'Disaster Risk Reduction Management (DRRM) Section' => 'Page/DRRM',
                'Human Resource Development Section' => 'Page/HRD',
                'School Health and Nutrition Section' => 'Page/shns',
                'Physical Education and Schools Sports' => 'Page/pess',
                'Social Mobilization and Networking' => 'Page/SMN',
                'Youth Formation Program' => 'Page/yfp',
            );
            $sectionDashboardRoute = $sectionDashboardRoutes[$currentSidebarSection] ?? 'Page/user_dashboard';
            $isSectionHeadDashboardUser = FALSE;

            if (
                $currentSidebarUsername !== '' &&
                $currentSidebarSecGroup !== '' &&
                !in_array($currentSidebarSection, array('Super Admin', 'System Administrator', 'Chief - SGOD', 'School'), TRUE)
            ) {
                $sidebarSectionHeadRecord = $this->SGODModel->two_cond_row('sgod_sections', 'sectionHead', $currentSidebarUsername, 'secGroup', $currentSidebarSecGroup);
                if ($sidebarSectionHeadRecord && trim((string) $sidebarSectionHeadRecord->sectionName) === $currentSidebarSection) {
                    $isSectionHeadDashboardUser = TRUE;
                    $sectionDashboardRoute = 'Page/section_head_dashboard';
                }
            }
        ?>

        <!--- Sidemenu -->
        <!-- Super Admin -->
        <?php if ($this->session->userdata('section') === 'Super Admin'): ?>

            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/super_admin" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/super_admin_users" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Admins </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <!-- System Administrator -->
        <?php elseif ($this->session->userdata('section') === 'System Administrator'): ?>
            <?php
                $adminRoutes = [
                    'CID' => 'Page/cid_admin',
                    'OSDS' => 'Page/osds_admin',
                    'SGOD' => 'Page/admin',
                ];
                $adminDashboardRoute = $adminRoutes[$this->session->userdata('secGroup')] ?? 'Page/admin';
            ?>

            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $adminDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/sections" class="waves-effect">
                            <i class="mdi mdi-grid"></i>
                            <span> Sections </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersList" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

            <!-- Chief - SGOD -->
        <?php elseif ($this->session->userdata('section') === 'Chief - SGOD'): ?>

            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/sgod" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/sections" class="waves-effect">
                            <i class="mdi mdi-grid"></i>
                            <span> Sections </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>



                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>


                    <!-- <li>
            <a href="javascript: void(0);" class="waves-effect">
                <i class="mdi mdi-file-document-box-check"></i>
                <span> Accomplishments </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="#">Grid</a></li>
                
            </ul>
        </li> -->
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span>Settings</span>
                            <span class="menu-arrow"></span>

                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="app_category">Category</a></li>
                            <li><a href="settings_pillar">Pillar</a></li>
                            <li><a href="app_school">School</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="fas fa-scroll"></i>
                            <spa>Implementation Plans</span>
                                <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/generate_aip_filter">Generate AIP</a></li>
                            <li><a href="<?= base_url(); ?>Page/aip_action_list">AIP Action</a></li>
                            <li><a href="#">Generate SOP</a></li>
                            <li><a href="#">Generate AAP</a></li>
                        </ul>
                    </li>


                    <li>
                        <a href="usersList" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->
        <?php elseif ($this->session->userdata('section') === 'School Management Monitoring and Evaluation'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>


                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'School'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/School" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/school_profile/<?= $this->session->username; ?>" class="waves-effect">
                            <i class="fas fa-school"></i>
                            <span> School Profile </span>
                        </a>

                    </li>
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="fas fa-scroll"></i>
                            <spa>Implementation Plan</span>
                                <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/aip_filter">AIP</a></li>
                            <li><a href="<?= base_url(); ?>Page/sop">SOP</a></li>
                            <li><a href="<?= base_url(); ?>Page/view_app">APP</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span>Settings</span>
                            <span class="menu-arrow"></span>

                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/settings_pias">PIAs</a></li>
                            <li><a href="<?= base_url(); ?>Page/settings_bs">Budget Source</a></li>
                        </ul>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->
        <?php elseif ($this->session->userdata('section') === 'Planning'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>

                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Research'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Disaster Risk Reduction Management (DRRM) Section'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Human Resource Development Section'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'School Health and Nutrition Section'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Physical Education and Schools Sports'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Social Mobilization and Networking'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>
                </ul>

            </div>
            <!-- End Sidebar -->

        <?php elseif ($this->session->userdata('section') === 'Youth Formation Program'): ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>

                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                            <i class="mdi mdi-office-building"></i>
                            <span> Schools </span>
                        </a>
                    </li>

                    <li>
                        <a href="usersListv2" class="waves-effect">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Manage Users </span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- End Sidebar -->

        <?php else: ?>
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="<?= base_url(); ?>Ipcrf" class="waves-effect">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span> IPCRF Performance </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?><?= $sectionDashboardRoute; ?>" class="waves-effect">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span> Accomplishments </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url(); ?>Page/addAccomplishments">Add New</a></li>
                            <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                            <i class="ion ion-ios-paper"></i>
                            <span> Memo </span>
                        </a>
                    </li>

                    <?php if ($isSectionHeadDashboardUser): ?>
                        <li>
                            <a href="<?= base_url(); ?>Page/schools" class="waves-effect">
                                <i class="mdi mdi-office-building"></i>
                                <span> Schools </span>
                            </a>
                        </li>

                        <li>
                            <a href="<?= base_url(); ?>Page/usersListv2" class="waves-effect">
                                <i class="mdi mdi-account-multiple-outline"></i>
                                <span> Manage Users </span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
            <!-- End Sidebar -->

        <?php endif; ?>


        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
