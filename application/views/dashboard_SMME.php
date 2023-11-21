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

                       
                        <!-- end page title -->
                        <br />
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <img src="<?= base_url(); ?>assets/images/me.jpg" class="img-fluid">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                        
                            <div class="col-xl-3 col-sm-6">
                                <div class="card bg-primary">
                                    <div class="card-body widget-style-2">
                                        <div class="text-white media">
                                            <div class="media-body align-self-center">
                                                <h2 class="my-0 text-white"><span data-plugin="counterup"><?php echo $data[0]->schoolCounts; ?></span></h2>
                                                <a href="<?= base_url(); ?>Page/schools?type=Public"><p class="mb-0 text-white">Public Schools</p></a>
                                            </div>
                                            <i class="ion-md-eye"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="card bg-purple">
                                    <div class="card-body widget-style-2">
                                        <div class="text-white media">
                                            <div class="media-body align-self-center">
                                                <h2 class="my-0 text-white"><span data-plugin="counterup"><?php echo $data1[0]->schoolCounts; ?></span></h2>
                                                <a href="<?= base_url(); ?>Page/schools?type=Private"><p class="mb-0 text-white">Private Schools</p></a>
                                            </div>
                                            <i class="ion-md-paper-plane"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="card bg-info">
                                    <div class="card-body widget-style-2">
                                        <div class="text-white media">
                                            <div class="media-body align-self-center">
                                                <h2 class="my-0 text-white"><span data-plugin="counterup"><?php echo $data2[0]->Counts; ?></span></h2>
                                                <a href="<?= base_url(); ?>Page/viewSecAccomplishments"><p class="mb-0 text-white">Accomplishments</p></a>
                                            </div>
                                            <i class="ion-ios-pricetag"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="card bg-success">
                                    <div class="card-body widget-style-2">
                                        <div class="text-white media">
                                            <div class="media-body align-self-center">
                                                <h2 class="my-0 text-white"><span data-plugin="counterup"><?php echo $data3[0]->Counts; ?></span></h2>
                                                <p class="mb-0">Section Users</p>
                                            </div>
                                            <i class="mdi mdi-comment-multiple"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-xl-6">
                                <div class="row">
                                <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tile-stats">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="status">
                                                                <h4 class="mt-2">61.5%</h4>
                                                                <p class="mb-1">Accomplishments: <span style="font-weight: bold">1st Qtr</span</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 mt-3">
                                                            <span class="sparkpie-big"><canvas width="98" height="50"></canvas></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tile-stats">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="status">
                                                                <h4 class="mt-2">61.5%</h4>
                                                                <p class="mb-1">Accomplishments: <span style="font-weight: bold">2nd Qtr</span</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 mt-3">
                                                            <span class="sparkpie-big"><canvas width="98" height="50"></canvas></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tile-stats">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="status">
                                                                <h4 class="mt-2">61.5%</h4>
                                                                <p class="mb-1">Accomplishments: <span style="font-weight: bold">3rd Qtr</span</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 mt-3">
                                                            <span class="sparkpie-big"><canvas width="98" height="50"></canvas></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tile-stats">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="status">
                                                                <h4 class="mt-2">61.5%</h4>
                                                                <p class="mb-1">Accomplishments: <span style="font-weight: bold">4th Qtr</span></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 mt-3">
                                                            <span class="sparkpie-big"><canvas width="98" height="50"></canvas></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->

                        </div> 
                        <!-- end row -->


                        
                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->

                

                <!-- Footer Start -->
                    <?php include('includes/footer.php'); ?>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        
        <!-- Right Sidebar -->
            
        <!-- /Right-bar -->

       

        <!-- Vendor js -->
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

        <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <!-- Chat app -->
        <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>

        <!-- Todo app -->
        <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>

        <!--Morris Chart-->
        <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>

        <!-- Sparkline charts -->
        <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>

        <!-- Dashboard init JS -->
        <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>

        <!-- App js -->
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    </body>
</html>