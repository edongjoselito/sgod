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
                                        <h4 class="header-title mb-4">Update Accomplishments</h4>
                                        <div class="table-responsive">
                                        
                                        <form class="parsley-examples" method="post" >
                                        <input type="hidden" value="<?php echo $data[0]->id; ?>" name="id">
                                            <div class="row">
                                            
                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Quarter</label>
                                                            <select class="form-control" name="quarter" required>
                                                                <option value="<?php echo $data[0]->quarter; ?>"><?php echo $data[0]->quarter; ?> Quarter</option>
                                                                <?php $arg = array('1st','2nd','3rd','4th'); 
                                                                   foreach($arg as $row){
                                                                    echo "<option value=".$row.">".$row." Quarter</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Year</label>
                                                            <input type="text" name="year" value="<?= $data[0]->year; ?>" required class="form-control" >
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Month</label>
                                                            <select class="form-control" name="monthAcc" required>
                                                                <option value="<?php echo $data[0]->monthAcc; ?>"> <?php echo $data[0]->monthAcc; ?></option>
                                                                <?php 
                                                                for($i=1;$i<13;$i++)
                                                                    echo("<option>".date('F',strtotime('01.'.$i.'.2023'))."</option>");
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Week</label>
                                                            <select class="form-control" name="weekAcc" required>
                                                                <option value="<?php echo $data[0]->weekAcc; ?>">Week <?php echo $data[0]->weekAcc; ?></option>
                                                                <?php 
                                                                        for ($x = 1; $x <=5; $x++){
                                                                            echo '<option value="'.$x.'"> Week '. $x.'</option>';
                                                                    } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                           
    
                                            <div class="row">
                                                <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label >Activity  <span class="text-danger">*</span></label>
                                                             <input type="text" required class="form-control" value="<?php echo $data[0]->activity; ?>"  name="activity">
                                                        </div>
                                                </div>

                                                <!-- <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Particulars </label>
                                                             <input type="text" class="form-control" value="<?php echo $data[0]->particulars; ?>" name="particulars">
                                                        </div>
                                                </div> -->
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                                <label >Activity Category</label>
                                                                    <select class="form-control" name="activityCategory">
                                                                        <option><?php echo $data[0]->activityCategory; ?></option>
                                                                        <option>Accomplishment</option> 
                                                                        <option>Updates</option> 
                                                                    </select>
                                                         </div>
                                                </div>

                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Target Date</label>
                                                             <input type="text" class="form-control" value="<?php echo $data[0]->targetDate; ?>" name="targetDate">
                                                        </div>
                                                </div>


                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Actual Date Conducted </label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->dateConducted; ?>" name="dateConducted" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Venue</label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->venue; ?>" name="venue" >
                                                        </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Performance Indicators </label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->perIndicators; ?>" name="perIndicators" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Target </label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->target; ?>" name="target" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Achieved</label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->achieved; ?>" name="achieved" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Accomplishment Percentage </label>
                                                            <input type="text"  class="form-control" value="<?php echo $data[0]->percentageAccom; ?>" name="percentageAccom" >
                                                        </div>
                                                </div>


                                            </div>

                                           
                                            
                                            <div class="row">
                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Resources Link</label>
                                                            <textarea name="resources" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo $data[0]->resources; ?></textarea>
                                                        </div>
                                                </div>

                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Additional Notes</label>
                                                            <textarea name="notes" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo $data[0]->notes; ?></textarea>
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label >Remarks</label>
                                                            <input type="text"  class="form-control" name="remarks" value="<?php echo $data[0]->remarks; ?>">
                                                        </div>
                                                </div>

                                                
                                            </div>


                                            <div class="form-group text-right mb-0">
                                               <input type="submit" name="update" value="Update" class="btn btn-primary waves-effect waves-light mr-1">
                                                
                                               
                                            </div>

                                        </form>

                                        </div>
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

        
        <!-- Right Sidebar -->
            <?php include('includes/right-sidebar.php'); ?> 
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- <a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">
            <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;Choose Demos
        </a> -->

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

                <!-- Plugin js-->
                <script src="<?= base_url(); ?>assets/libs/parsleyjs/parsley.min.js"></script>

<!-- Validation init js-->
<script src="<?= base_url(); ?>assets/js/pages/form-validation.init.js"></script>

        <!-- Responsive examples -->
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>

        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>

        <!-- Datatables init -->
        <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>


    </body>
</html>