<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <!-- System Administrator -->
		<?php if($this->session->userdata('section')==='System Administrator'):?>

        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/admin" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
                    </a>
                </li>
                 <!-- <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-file-document-box-check"></i>
                        <span> Accomplishments </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/viewSecAccomplishments">View</a></li>
                        
                    </ul>
                </li> -->

                <li>
                    <a href="<?= base_url(); ?>Page/sections" class="waves-effect">
                        <i class="mdi mdi-grid"></i>
                        <span> Sections </span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url(); ?>Page/memo" class="waves-effect">
                        <i class="ion ion-ios-paper"></i>
                        <span>  Memo </span>
                    </a>
                </li>

                

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
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
                <!-- <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>Settings</span>
                        <span class="menu-arrow"></span>
                        
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/settings_section">Sections</a></li>
                        <li><a href="<?= base_url(); ?>Page/settings_domain">Domain</a></li>
                        <li><a href="<?= base_url(); ?>Page/settings_strand">Strand</a></li>
                        <li><a href="<?= base_url(); ?>Page/settings_matatag">Matatag</a></li>
                        <li><a href="<?= base_url(); ?>Page/settings_io">Intermediate Outcome</a></li>

                    </ul>
                </li> -->
                <!-- <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="fas fa-scroll"></i>
                        <spa>Implementation Plans</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        
                        <li><a href="<?= base_url(); ?>Page/aip_action_list">Submitted AIP</a></li>
                    </ul>
                </li> -->
                

                <li>
                    <a href="usersList" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

<!-- Chief - SGOD -->
<?php elseif($this->session->userdata('section')==='Chief - SGOD'):?>

<div id="sidebar-menu">
    <ul class="metismenu" id="side-menu">

        <li class="menu-title">Navigation</li>

        <li>
            <a href="<?= base_url(); ?>Page/sgod" class="waves-effect">
                <i class="mdi mdi-view-dashboard"></i>
                <span>  Dashboard  </span>
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
                <span>  Memo  </span>
            </a>
        </li>

        

        <li>
            <a href="javascript: void(0);" class="waves-effect">
                <i class="mdi mdi-office-building"></i>
                <span> Schools </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                
            </ul>
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
                <span>  Manage Users  </span>
            </a>
        </li>

    </ul>

</div>
<!-- End Sidebar -->
        <?php elseif($this->session->userdata('section')==='School Management Monitoring and Evaluation'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/SMME" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
               
                
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
                <!-- End Sidebar -->
               
        <?php elseif($this->session->userdata('section')==='School'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/School" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
                    </a>
                 
                </li>
                <li>
                    <a href="<?= base_url(); ?>Page/school_profile/<?= $this->session->username; ?>" class="waves-effect">
                        <i class="fas fa-school"></i>
                        <span>  School Profile  </span>
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
             <?php elseif($this->session->userdata('section')==='Planning'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/planning" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='Research'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/research" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->
        
        <?php elseif($this->session->userdata('section')==='Disaster Risk Reduction Management (DRRM) Section'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/DRRM" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='Human Resource Development Section'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/HRD" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='School Health and Nutrition Section'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/shns" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='Physical Education and Schools Sports'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/pess" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='Social Mobilization and Networking'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/SMN" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>
            </ul>

        </div>
                <!-- End Sidebar -->

        <?php elseif($this->session->userdata('section')==='Youth Formation Program'):?>
            <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?= base_url(); ?>Page/yfp" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
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
                        <span>  Memo  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-office-building"></i>
                        <span> Schools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?= base_url(); ?>Page/schools?type=Public">Public</a></li>
                        <li><a href="<?= base_url(); ?>Page/schools?type=Private">Private</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="usersListv2" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>  Manage Users  </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->


        <?php endif;?>


        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>