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
                                        <h4 class="header-title mb-4">Add New Accomplishment</h4>
                                        
                                        
                                        
                                        <form class="parsley-examples" method="post" action="<?= base_url(); ?>Page/addAccomplishments">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Quarter <span class="text-danger">*</span></label>
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
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Year <span class="text-danger">*</span></label>
                                                            <input type="text" name="year" required class="form-control" >
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Month <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="monthAcc" required>
                                                                <option></option>
                                                                <?php 
                                                                for($i=1;$i<13;$i++)
                                                                    echo("<option>".date('F',strtotime('01.'.$i.'.2023'))."</option>");
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Week <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="weekAcc" required>
                                                                <option></option>
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
                                                            <label >Activity <span class="text-danger">*</span></label>
                                                             <input type="text" required class="form-control" name="activity">
                                                        </div>
                                                </div>

                                                <!-- <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Particulars</label>
                                                             <input type="text"  class="form-control" name="particulars">
                                                        </div>
                                                </div> -->
                                            </div>

                                          
                                            <div class="row">
                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                                <label >Activity Category <span class="text-danger">*</span></label>
                                                                    <select class="form-control" required name="activityCategory">
                                                                        <option></option>
                                                                        <option>Accomplishment</option> 
                                                                        <option>Updates</option> 
                                                                    </select>
                                                         </div>
                                                </div>

                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Target Date</label>
                                                             <input type="text" class="form-control" name="targetDate">
                                                        </div>
                                                </div>


                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >Actual Date Conducted </label>
                                                            <input type="text"  class="form-control" name="dateConducted" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label >Venue </label>
                                                            <input type="text"  class="form-control" name="venue" >
                                                        </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Performance Indicators </label>
                                                            <input type="text"  class="form-control" name="perIndicators" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Target </label>
                                                            <input type="text"  class="form-control" name="target" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Achieved</label>
                                                            <input type="text"  class="form-control" name="achieved" >
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Accomplishment Percentage </label>
                                                            <input type="text"  class="form-control" name="percentageAccom" >
                                                        </div>
                                                </div>


                                            </div>
                                        

                                            <div class="row">
                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                        <label >Resources Link </label>
                                                        <textarea name="resources" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                                        </div>          
                                                </div>

                                                <div class="col-lg-6">
                                                        <div class="form-group">
                                                        <label >Additional Notes </label>
                                                        <textarea name="notes" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                                </div>          
                                                </div>

                                            </div>  
                                            
                                            <div class="row">
                                                <div class="col-lg-12">
                                                        <div class="form-group">
                                                        <label >Remarks </label>
                                                        <input type="text"  class="form-control" name="remarks" >
                                                        </div>          
                                                </div> 

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

                <div id="addBookDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Attach MOVs</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open_multipart('page/multiple_files'); ?>
                                                        <div class="form-group col-md-12">
                                                            <label >attachment</label>
                                                            <input type="file" multiple="multiple" name="image_name[]" class="form-control" />
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" id="id" value="">
                                                            <input type="hidden" name="field" id="field" value="">
                                                        </div>    

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>


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


        <!-- Modal -->
        <!-- <div id="custom-modal" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.modal.close();">
                <span>&times;</span><span class="sr-only">Close</span>
            </button>

            

            <h4 class="custom-modal-title">Modal title</h4>
            <div class="custom-modal-text">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </div>
        </div> -->


         <!--  Modal for Adding New Accomplishments -->
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myLargeModalLabel">Add New Accomplishment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                               </div>
                             
                               <?php if($this->session->flashdata('success')) : ?>

                                        <?= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>'
                                                .$this->session->flashdata('success'). 
                                            '</div>'; 
                                        ?>
                                        <?php endif; ?>

                               <div class="modal-body">
                               <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="parsley-examples" method="post" action="<?= base_url(); ?>Page/addAccomplishments">
                                            <div class="form-group">
                                                <label >Quarter<span class="text-danger">*</span></label>
                                                <select class="form-control" name="quarter" required>
                                                       <option></option>
                                                       <option value="1st Quarter">1st Quarter</option> 
                                                       <option value="2nd Quarter">2nd Quarter</option>
                                                       <option value="3rd Quarter">3rd Quarter</option>
                                                       <option value="4th Quarter">4th Quarter</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label >Year<span class="text-danger">*</span></label>
                                                <input type="text" name="year" required class="form-control" >
                                            </div>
                                            <div class="form-group">
                                                <label >Activity Title<span class="text-danger">*</span></label>
                                                <input type="text" required class="form-control" name="activity">
                                            </div>
                                            <div class="form-group">
                                                <label >Activity Category <span class="text-danger">*</span></label>
                                                <select class="form-control" name="activityCategory">
                                                       <option></option>
                                                       <option>Activity</option> 
                                                       <option>Updates</option> 
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label >Venue <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="venue" >
                                            </div>

                                            <div class="form-group">
                                                <label >Date Conducted <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="dateConducted" >
                                            </div>

                                            <div class="form-group text-right mb-0">
                                               <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                
                                               
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                           </div>   
                            <!-- end row -->

                           </div>
                        </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
             <!-- /.modal -->


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