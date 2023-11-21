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
                                    <h4 class="page-title" id="myLargeModalLabel">                            
                                        <!-- <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">+ ADD NEW</button> -->
                                    

                                        <!-- <a data-toggle="modal" data-field="" data-id="" class="open-AddBookDialog btn btn-success" href="#addBookDialog">Upload AIP</a> -->
                                    </h4>
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
                                        <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>SCHOOL ID</th>
                                                        <th>SCHOOL NAME</th>
                                                        <th>YEAR</th>
                                                        <th>REMARKS</th>
                                                        <th>MANAGE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data as $row){ 
                                                        $school=$this->SGODModel->get_data_by_id('schools', 'schoolId',$row->school_id);
                                                        ?>
                                                    <tr>
                                                        <td><?= $row->school_id; ?></td>
                                                        <td><?= $school->schoolName; ?></td>
                                                        <td><?= $row->fy; ?></td> 
                                                        <td><?= $row->remarks; ?></td> 
                                                        <td>
                                                        <?php if($this->session->section == "System Administrator"){ ?>
                                                            <a href="<?= base_url(); ?>Page/aip_track/<?= $row->id; ?>" class="btn btn-warning">View Status</a> &nbsp;
                                                            <?php if($row->status != 1){ ?>
                                                                <a href="aip_admin/<?= $row->school_id.'/'.$row->fy.'/'.$row->b_code.'/'.$row->id; ?>" class="btn btn-success">Evaluate AIP</a> &nbsp;
                                                                <a href="generate_sop_admin/<?= $row->school_id.'/'.$row->fy.'/'.$row->b_code.'/'.$row->id; ?>" class="btn btn-primary">Evaluate SOP</a> &nbsp;
                                                                <a href="generate_app_admin/<?= $row->school_id.'/'.$row->fy.'/'.$row->b_code.'/'.$row->id; ?>" class="btn btn-info">Evaluate APP</a> &nbsp;
                                                                <a href="generate_rca_admin/<?= $row->school_id.'/'.$row->fy.'/'.$row->b_code.'/'.$row->id; ?>" class="btn btn-success">Evaluate RCA</a> &nbsp;
                                                                <a href="<?= base_url(); ?>Page/approved_aip/<?= $row->id; ?>" class="btn btn-warning">Approved</a>&nbsp;
                                                                <a data-toggle="modal" data-field="" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-success" href="#comment">Comment/Remarks</a>
                                                            <?php } else { ?> 
                                                                <a onclick="return confirm('Are you sure?')" href="<?= base_url(); ?>Page/open_aip/<?= $row->id; ?>" class="btn btn-primary">Open</a>
                                                                <a data-toggle="modal" data-field="" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-success" href="#comment">Comment/Remarks</a>
                                                            <?php } ?>
                                                            
                                                        <?php }else{ ?>
                                                            <a href="<?= base_url(); ?>Page/aip_track/<?= $row->id; ?>" class="btn btn-primary">View Status</a>&nbsp;
                                                            <a data-toggle="modal" data-field="" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-success" href="#comment">Comment/Remarks</a>
                                                        <?php } ?>
                                                        </td> 
                                                    </tr>
                                                    <?php } ?>
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

                <div id="addBookDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER ANNUAL IMPLEMENTATION PLAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('Page/aip_filterd'); ?>
                                                         <div class="form-group">
                                                            <label>School ID</label>
                                                            <input type="text" name="school_id" <?php if($this->session->acctLevel === 'School'){echo " readonly ";} ?> value="<?php if($this->session->acctLevel === 'School'){echo $this->session->username;} ?>" required class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>FY</label>
                                                            <input type="text"  name="fy" class="form-control" />
                                                        </div>

                                                        <div class="form-group">
                                                            <label >Pillar <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="pillar" required>
                                                                <option></option>
                                                                <?php   foreach($pillar as $row){
                                                                    echo "<option value='{$row->pillar}'>".$row->pillar."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >Domain <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="domain" required>
                                                                <option></option>
                                                                <?php   foreach($domain as $row){
                                                                    echo "<option value='{$row->domain}'>".$row->domain."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >STRAND <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="strand" required>
                                                                <option></option>
                                                                <?php   foreach($strand as $row){
                                                                    echo "<option value='{$row->strand}'>".$row->strand."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >PIAs <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="pias" required>
                                                                <option></option>
                                                                <?php   foreach($pias as $row){
                                                                    echo "<option value='{$row->pias}'>".$row->pias."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" id="id" value="">
                                                            <input type="hidden" name="field" id="field" value="">
                                                        </div>    

                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="Submit" />
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>


                                        <div id="comment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Comments/Remarks</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('Page/remarks_aip'); ?>
                                                        <input type="hidden" name="id" id="id">
                                                        <div class="form-group">
                                                            <label>Comment/Remarks</label>
                                                            <textarea name="remarks" id="" class="form-control"></textarea>
                                                        </div>

                                                           

                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="Submit" />
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