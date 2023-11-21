<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">

        <!-- Plugins css-->
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

         <!-- third party css -->
         <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" /> 
    </head>

    <body>

        <!-- Begin page -->
        <div id="wrapper">
      
            <!-- Topbar Start -->
                <?php include('includes/top-bar.php'); ?>
            <!-- end Topbar --> 

<!-- ========== Left Sidebar Start ========== -->

<?php include('includes/sidebar.php') ?>

<!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                    <div class="row">
                            <div class="col-sm-12">
                                <div class="profile-bg-picture" style="background-image:url('<?= base_url(); ?>assets/images/mis.jpg')">
                                    <span class="picture-bg-overlay"></span>
                                    <!-- overlay -->
                                </div>
                                <!-- meta -->
                                <div class="profile-user-box">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- <div class="profile-user-img"><img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-lg rounded-circle"></div> -->
                                            <div class="">
                                                <h4 class="mt-5 font-18 ellipsis"><span style="text-transform: uppercase"><?php echo $data[0]->schoolName; ?></span></h4>
                                                <p class="font-13"> <?php echo $data[0]->brgy.', '.$data[0]->city.', '.$data[0]->province; ?></p>
                                                <!-- <p class="text-muted mb-0"><small>California, United States</small></p> -->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <!-- <button type="button" class="btn btn-success waves-effect waves-light">
                                                    <i class="mdi mdi-account-settings-variant mr-1"></i> Edit Profile
                                                </button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ meta -->
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <div class="card p-0">
                                    <div class="card-body p-0">
                                        <ul class=" nav nav-tabs tabs-bordered nav-justified">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#aboutme">About</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#enrolment-data">Enrolment Data</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#indicator">Performance Indicator</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nut-status">Nutritional Status</a></li>
                                        </ul>

                                        <div class="tab-content m-0 p-4">

                                            <div id="aboutme" class="tab-pane active">
                                                <div class="profile-desk">
                                                    <h5 class="text-uppercase font-weight-bold">SCHOOL DETAILS</h5>
                                                    <!-- <div class="designation mb-4"><?php echo $data[0]->schoolName; ?></div> -->
                                                    <!-- <p class="text-muted">
                                                        I have 10 years of experience designing for the web, and specialize in the areas of user interface design, interaction design, visual design and prototyping. I’ve worked with notable startups including Pearl Street Software.
                                                    </p>
                                                    <a class="btn btn-primary mt-4" href="#"> <i class="fa fa-check"></i> Following</a> -->

                                                    <!-- <h5 class="mt-4">Contact Information</h5> -->
                                                    <table class="table table-condensed mb-0">
                                                        
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Station Code</th>
                                                                    <td>
                                                                        <?php echo $data[0]->stationCode; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">School ID</th>
                                                                    <td>
                                                                          <?php echo $data[0]->schoolID; ?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">District</th>
                                                                    <td><?php echo $data[0]->district; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Year Established</th>
                                                                    <td><?php echo $data[0]->yearEstab; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">School Email</th>
                                                                    <td><?php echo $data[0]->schoolEmail; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">School Head</th>
                                                                    <td><?php echo $data[0]->adminFName.' '.$data[0]->adminMName.' '.$data[0]->adminLName; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">School Head Designation</th>
                                                                    <td><?php echo $data[0]->adminDesignation; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">School Head Contact Nos.</th>
                                                                    <td><?php echo $data[0]->adminMobile.', '.$data[0]->adminTel; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">School Type</th>
                                                                    <td><?php echo $data[0]->schoolType; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">Permit No.</th>
                                                                    <td><?php echo $data[0]->permitNo; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">Recognition No.</th>
                                                                    <td><?php echo $data[0]->recogNo; ?></td>
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- end profile-desk -->
                                                </div> <!-- about-me -->

                                                <!-- Activities -->
                                                <div id="enrolment-data" class="tab-pane">
                                                    <!-- <div class="timeline-2">
                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>

                                                <!-- Performance Indicator -->
                                                <div id="indicator" class="tab-pane">
                                                    <!-- <div class="timeline-2">
                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ml-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>

                                                <!-- settings -->
                                                <div id="personnel" class="tab-pane">
                                                    <!-- <div class="user-profile-content">
                                                        <form>
                                                            <div class="form-group">
                                                                <label for="FullName">Full Name</label>
                                                                <input type="text" value="John Doe" id="FullName" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Email">Email</label>
                                                                <input type="email" value="first.last@example.com" id="Email" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Username">Username</label>
                                                                <input type="text" value="john" id="Username" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Password">Password</label>
                                                                <input type="password" placeholder="6 - 15 Characters" id="Password" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="RePassword">Re-Password</label>
                                                                <input type="password" placeholder="6 - 15 Characters" id="RePassword" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="AboutMe">About Me</label>
                                                                <textarea style="height: 125px;" id="AboutMe" class="form-control">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</textarea>
                                                            </div>
                                                            <button class="btn btn-primary" type="submit">Save</button>
                                                        </form>
                                                    </div> -->
                                                </div>

                                                <!-- profile -->
                                                <div id="nut-status" class="tab-pane">
                                                    <div class="row m-t-10">
                                                        <div class="col-md-12">
                                                            <div class="portlet"><!-- /primary heading -->
                                                                <div id="portlet2" class="panel-collapse collapse show">
                                                                    <div class="portlet-body">
                                                                        <div class="table-responsive">
                                                                            <!-- <table class="table mb-0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Project Name</th>
                                                                                        <th>Start Date</th>
                                                                                        <th>Due Date</th>
                                                                                        <th>Status</th>
                                                                                        <th>Assign</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>1</td>
                                                                                        <td>Velonic Admin</td>
                                                                                        <td>01/01/2015</td>
                                                                                        <td>07/05/2015</td>
                                                                                        <td><span class="badge badge-info">Work in Progress</span></td>
                                                                                        <td>Coderthemes</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>2</td>
                                                                                        <td>Velonic Frontend</td>
                                                                                        <td>01/01/2015</td>
                                                                                        <td>07/05/2015</td>
                                                                                        <td><span class="badge badge-success">Pending</span></td>
                                                                                        <td>Coderthemes</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>3</td>
                                                                                        <td>Velonic Admin</td>
                                                                                        <td>01/01/2015</td>
                                                                                        <td>07/05/2015</td>
                                                                                        <td><span class="badge badge-pink">Done</span></td>
                                                                                        <td>Coderthemes</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>4</td>
                                                                                        <td>Velonic Frontend</td>
                                                                                        <td>01/01/2015</td>
                                                                                        <td>07/05/2015</td>
                                                                                        <td><span class="badge badge-purple">Work in Progress</span></td>
                                                                                        <td>Coderthemes</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>5</td>
                                                                                        <td>Velonic Admin</td>
                                                                                        <td>01/01/2015</td>
                                                                                        <td>07/05/2015</td>
                                                                                        <td><span class="badge badge-warning">Coming soon</span></td>
                                                                                        <td>Coderthemes</td>
                                                                                    </tr>

                                                                                </tbody>
                                                                            </table> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- /Portlet -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> 
                                    </div>
                                </div>
                            <!-- end page title -->

                        </div>
                        <!-- end row -->

                    </div>
                    <!-- end container-fluid -->


                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->


            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

             <!-- Footer Start -->
             <?php include('includes/footer.php'); ?>
            <!-- end Footer -->

        </div>
        <!-- END wrapper -->

        
      

        <!-- Vendor js -->
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

        <!-- Required datatable js -->
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>

        <!-- Responsive examples -->
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>

        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>

        <!-- Datatables init -->
        <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

    </body>
</html>