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

        <link href="<?= base_url(); ?>assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">

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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <!-- <h4 class="page-title" id="myLargeModalLabel">                            
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">+ ADD NEW</button>
                                        <a href="acc" class="btn btn-info waves-effect waves-light" target="_blank">REPORTS</a>
                                    </h4> -->
                                    <!-- <div class="page-title-right">
                                        <ol class="breadcrumb p-0 m-0">
                                            <li class="breadcrumb-item">SGOD Management System v1.0</li>
                                        </ol>
                                    </div> -->
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->


                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4"><?= $title; ?></h4>
                                        
                                        
                                        
                                        <form class="parsley-examples" method="post" action="<?= base_url(); ?>Page/addAccomplishments">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Quarter</label>
                                                            <select class="form-control" name="quarter" required>
                                                                <option></option>
                                                                <?php $arg = array('1st','2nd','3rd','4th'); 
                                                                   foreach($arg as $row){
                                                                    echo "<option value=".$row.">".$row." Quarter</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Year</label>
                                                            <input type="text" name="year" required class="form-control" >
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >Month</label>
                                                            <select class="form-control" name="monthAcc" required>
                                                                <option></option>
                                                                <?php 
                                                                for($i=1;$i<13;$i++)
                                                                    echo("<option>".date('F',strtotime('01.'.$i.'.2023'))."</option>");
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >Week</label>
                                                            <select class="form-control" name="weekAcc" required>
                                                                <option></option>
                                                                <?php 
                                                                        for ($x = 1; $x <=4; $x++){
                                                                            echo '<option value="'.$x.'"> Week '. $x.'</option>';
                                                                    } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                           
                                            <div class="row">
                                                <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label >Activity Title</label>
                                                             <input type="text" required class="form-control" name="activity">
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label >Particulars</label>
                                                             <input type="text" required class="form-control" name="particulars">
                                                        </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                                <label >Activity Category</label>
                                                                    <select class="form-control" name="activityCategory">
                                                                        <option></option>
                                                                        <option>Accomplishment</option> 
                                                                        <option>Updates</option> 
                                                                    </select>
                                                         </div>
                                                </div>

                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >Target Date</label>
                                                             <input type="text" class="form-control" name="targetDate">
                                                        </div>
                                                </div>


                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >Actual Date Conducted </label>
                                                            <input type="text"  class="form-control" name="dateConducted" >
                                                        </div>
                                                </div>

                                            </div>
                                        
                                        
                                         

                                            <div class="form-group">
                                                <label >Venue </label>
                                                <input type="text"  class="form-control" name="venue" >
                                            </div>

                                            <div class="form-group">
                                              
                                            </div>

                                            <div class="form-group text-right mb-0">
                                               <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                
                                               
                                            </div>

                                        </form>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <!--- end row -->

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





     <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>
     <script type="text/javascript">
                $(document).on("click", ".open-AddBookDialog", function () {
                    var myBookId = $(this).data('id');
                    $(".modal-body #id").val( myBookId );

                    var fieldId = $(this).data('field');
                    $(".modal-body #field").val( fieldId );
                });
            </script>


    </body>
</html>