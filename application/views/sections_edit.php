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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Edit Section</h4>
                                   
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="">
                                                <form class="parsley-examples" method="post" >
                                            
                                            <div class="form-group">
                                                <label >Section<span class="text-danger">*</span></label>
                                                <input type="text" name="sectionName" required class="form-control" value="<?= $data->sectionName; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label >Section Head</label>
                                                <input type="text"  class="form-control" name="sectionHead" value="<?= $data->sectionHead; ?>">
                                            </div>
                                           

                                            <div class="form-group">
                                                <label >Position</label>
                                                <input type="text"  class="form-control" name="sectionHeadPosition" value="<?= $data->sectionHeadPosition; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Section Group <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="secGroup" value="<?= $data->secGroup; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Members <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="member" value="<?= $data->member; ?>">
                                                <input type="hidden" required  class="form-control" name="id" value="<?= $data->id; ?>">
                                            </div>

                                           
                                            <div class="form-group text-right mb-0">
                                               <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                
                                               
                                            </div>

                                        </form>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- end row -->

                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->

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

     <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>


    </body>
</html>