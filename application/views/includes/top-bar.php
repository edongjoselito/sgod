<?php
$topbarFullName = trim($this->session->userdata('fName') . ' ' . $this->session->userdata('lName'));
$topbarProfileRoute = base_url() . 'Page/user_dashboard?open_profile_picture=1';
$topbarSection = trim((string) $this->session->userdata('section'));
$topbarSecGroup = trim((string) $this->session->userdata('secGroup'));
$topbarUsername = trim((string) $this->session->userdata('username'));

if (
    $topbarUsername !== '' &&
    $topbarSecGroup !== '' &&
    !in_array($topbarSection, array('Super Admin', 'System Administrator', 'Chief - SGOD', 'School'), TRUE)
) {
    $topbarSectionHeadRecord = $this->SGODModel->two_cond_row('sgod_sections', 'sectionHead', $topbarUsername, 'secGroup', $topbarSecGroup);
    if ($topbarSectionHeadRecord && trim((string) $topbarSectionHeadRecord->sectionName) === $topbarSection) {
        $topbarProfileRoute = base_url() . 'Page/section_head_dashboard?open_profile_picture=1';
    }
}
?>
<div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="<?= base_url(); ?>upload/profile/<?php echo $this->session->userdata('avatar');?>" alt="user-image" class="rounded-circle">
                            <span class="pro-user-name ml-1">
                            <?= htmlspecialchars($topbarFullName, ENT_QUOTES, 'UTF-8'); ?>   <i class="mdi mdi-chevron-down"></i> 
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0"><?= htmlspecialchars($topbarFullName, ENT_QUOTES, 'UTF-8'); ?></h6>
                                <a href="<?= htmlspecialchars($topbarProfileRoute, ENT_QUOTES, 'UTF-8'); ?>" class="d-inline-block mt-2 js-change-profile-picture" style="font-size: 0.82rem; font-weight: 600;">
                                    Change Profile Picture
                                </a>
                            </div>

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-outline"></i>
                                <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item">
                                <i class="mdi mdi-settings-outline"></i>
                                <span>Settings</span>
                            </a>

                            <a href="<?= base_url(); ?>Page/changepassword" class="dropdown-item notify-item">
                                <i class="mdi mdi-lock-reset"></i>
                                <span>Change Password</span>
                            </a>

                      
                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="<?php echo site_url('login/logout');?>" class="dropdown-item notify-item">
                                <i class="mdi mdi-logout-variant"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect">
                            <i class="mdi mdi-settings-outline noti-icon"></i>
                        </a>
                    </li>


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="#" class="logo text-center logo-dark">
                        <span class="logo-lg">
                            <img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd ONE" height="46">
                            <!-- <span class="logo-lg-text-dark">Velonic</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">V</span> -->
                            <img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd ONE" height="22">
                        </span>
                    </a>

                    <a href="#" class="logo text-center logo-light">
                        <span class="logo-lg">
                            <img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd ONE" height="46">
                            <!-- <span class="logo-lg-text-dark">Velonic</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">V</span> -->
                            <img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd ONE" height="22">
                        </span>
                    </a>
                </div>

                <!-- LOGO -->
  

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
        
                    <li class="d-none d-lg-block">
                        <form class="app-search">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
