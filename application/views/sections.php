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
                                    <!-- <h4 class="page-title">Sections</h4> -->
                                    <!-- <a href="#" class="btn btn-info"> + Add New Section</a> -->
                                    <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">+ Add New Section</button>
                                    <!-- <a target="_blank" href="sec_filter/1/1" class="btn btn-primary">Quarterly Report</a>
                                    <a target="_blank" href="sec_filter/2/1" class="btn btn-secondary">Weekly Report</a> -->

                                    <a target="_blank" href="sec_filterv2/1/1" class="btn btn-success">Quarterly Report</a>
                                    <a target="_blank" href="sec_filterv2/2/1" class="btn btn-primary">Weekly Report</a>
                                    <a target="_blank" href="sfy/1" class="btn btn-success">Yearly Report</a>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target=".sc">Submission Checking</button>

                                    

                                    <div class="page-title-right">
                                        <ol class="breadcrumb p-0 m-0">
                                            <!-- <li class="breadcrumb-item">SGOD Management System v1.0</li> -->
                                        </ol>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <?php if($this->session->flashdata('success')) : ?>

                                <?= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>'
                                        .$this->session->flashdata('success'). 
                                    '</div>'; 
                                ?>
                                <?php endif; ?>

                                <?php if($this->session->flashdata('danger')) : ?>
                                <?= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>'
                                        .$this->session->flashdata('danger'). 
                                    '</div>'; 
                                ?>
                                <?php endif;  ?>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4">Sections</h4>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Section</th>
                                                        <th>Section Head</th>
                                                        <th>Position</th>
                                                        <th>Members</th>
                                                        <th style="text-align:center">Manage</th>
                                                        <!-- <th style="text-align:center">Reports</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php foreach($data as $row) { ?>
                                                        <tr>
                                                            <td><?= $row->sectionName; ?></td>
                                                            <td><?= $row->sectionHead; ?></td>
                                                            <td><?= $row->sectionHeadPosition; ?></td>
                                                            <td><?= $row->member; ?></td>
                                                            <td style="text-align:center">
                                                            <a href="<?= base_url(); ?>page/sections_edit/<?= $row->id; ?>"  class="text-success mr-1 tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <a href="delete_sec?id=<?= $row->id; ?>" class="text-danger tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt "></i></a> 
                                                        </tr>
                                                        
                                                    <?php }  ?>
                                                </tbody>
                                            </table>
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

        
        <!-- Vendor js -->
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>


  <!--  Modal for Adding New Section -->
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myLargeModalLabel">Add New Section</h5>
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
                                        <form class="parsley-examples" method="post" >
                                            
                                            <div class="form-group">
                                                <label >Section<span class="text-danger">*</span></label>
                                                <input type="text" name="sectionName" required class="form-control" >
                                            </div>
                                            <div class="form-group">
                                                <label >Section Head<span class="text-danger">*</span></label>
                                                <input type="text" required class="form-control" name="sectionHead">
                                            </div>
                                           

                                            <div class="form-group">
                                                <label >Position <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="sectionHeadPosition" >
                                            </div>

                                            <div class="form-group">
                                                <label>Section Group <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="secGroup" >
                                            </div>

                                            <div class="form-group">
                                                <label>Members <span class="text-danger">*</span></label>
                                                <input type="text" required  class="form-control" name="member" >
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

             <!--  Modal for filter New accomplishment and updates -->
  <div class="modal fade sc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myLargeModalLabel">Filter</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                               
                               </div>
                          

                               <div class="modal-body">
                               <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="parsley-examples" action="submission" method="post" >
                                            
                                            <div class="form-group">
                                                <label >Quarter <span class="text-danger">*</span></label>
                                                <select class="form-control" name="quarter" required>
                                                    <?php $quater = array("1st", "2nd", "3rd", "4th"); ?>
                                                    <option></option>
                                                    <?php 
                                                    foreach($quater as $row){
                                                            echo '<option value="'.$row.'">'.$row.' Quarter</option>';
                                                    }
                                                    ?>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label >Year <span class="text-danger">*</span></label>
                                                <input type="text" required class="form-control" name="year">
                                            </div>

                                            <div class="form-group">
                                                <label >Month <span class="text-danger">*</span></label>
                                                <select class="form-control" name="month" required>
                                                    <option></option>
                                                    <?php
                                                        for($m=1; $m<=12; ++$m){  ?>
                                                        <option value="<?= date('F', mktime(0, 0, 0, $m, 1)); ?>"><?= date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                                                        <?php  } ?>
                                                </select>
                                            </div>
                                           

                                            <div class="form-group">
                                                <label >Week <span class="text-danger">*</span></label>
                                                <select class="form-control" name="weekAcc" required>
                                                    <option></option>
                                                    <?php 
                                                    for ($x = 1; $x <=5; $x++){
                                                        echo '<option value="'.$x.'"> Week '. $x.'</option>';
                                                }
                                                ?>
                                                </select>
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