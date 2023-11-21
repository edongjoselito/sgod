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
                                        <a href="<?= $b_link; ?>"><button type="button" class="btn btn-primary waves-effect waves-light" ><?= $b_label; ?></button></a>
                                        <a data-toggle="modal" data-field="" data-id="" class="open-AddBookDialog btn btn-success" href="#addBookDialog">Filter AIP</a>
                                        <a data-toggle="modal" class="open-AddBookDialog btn btn-primary" href="#g_aip">Generate AIP</a>
                                        <a class="btn btn-info waves-effect waves-light" href="<?= base_url(); ?>Page/aip_action_list">AIP Status</a>
                                        <!-- <a data-toggle="modal" data-field="" data-id="" class="open-AddBookDialog btn btn-success" href="#addBookDialog">Upload AIP</a> -->
                                        
                                        <a data-toggle="modal" class="open-AddBookDialog btn btn-warning" href="#sfe">Submit for Evaluation</a>
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
                                                        <th>FISCAL YEAR</th>
                                                        <th>PILLAR</th>
                                                        <th>DOMAIN</th>
                                                        <th>STRAND</th>
                                                        <th>PIA's</th>
                                                        <th>SCHOOL IMPROVEMENT PROJECT TITLE</th>
                                                        <th>PROJECT OBJECTIVE</th>
                                                        <th>OUTPUT FOR THE YEAR</th>
                                                        <th>STRATEGY ACTIVITIES</th>
                                                        <th>PERFORMANCE INDICATORS</th>
                                                        <th>MOVs</th>
                                                        <th>PERSON(S) RESPONSIBLE</th>
                                                        <th>SCHEDULE</th>
                                                        <th>VENUE</th>
                                                        <th>BUDGET PER ACTIVITY</th>
                                                        <th>BUDGET SOURCE</th>
                                                        <th>MATERIALS</th>
                                                        <th>BATCH CODE</th>
                                                        <th>MANAGE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data as $row){ ?>
                                                    <tr>
                                                        <td><?= $row->school_id; ?></td> 
                                                        <td><?= $row->fy; ?></td> 
                                                        <td><?= $row->pillar; ?></td> 
                                                        <td><?= $row->domain; ?></td>
                                                        <td><?= $row->strand; ?></td> 
                                                        <td><?= $row->pia; ?></td> 
                                                        <td><?= $row->sip_project; ?></td>
                                                        <td><?= $row->sip_pObjective; ?></td> 
                                                        <td><?= $row->sip_output; ?></td>
                                                        <td><?= $row->strategy; ?></td>
                                                        <td><?= $row->pi; ?></td>
                                                        <td><?= $row->movs; ?></td>
                                                        <td><?= $row->pr; ?></td>
                                                        <td><?= $row->schedule; ?></td>
                                                        <td><?= $row->venue; ?></td>
                                                        <td><?= $row->budget; ?></td>
                                                        <td><?= $row->budget_source; ?></td>
                                                        <td><?= $row->materials; ?></td>
                                                        <td><?= $row->b_code; ?></td>
                                                        <td>
                                                            <?php 
                                                              $check = $this->SGODModel->three_cond_row('sgod_aip_submit','school_id',$row->school_id,'fy',$row->fy,'b_code',$row->b_code);
                                                              if(!empty($check)){
                                                              if($check->status == 0){
                                                            ?>
                                                            <a class="btn btn-success sm" href="aip_edit/<?= $row->id; ?>">EDIT</a>  &nbsp;
                                                            <a class="btn btn-danger sm" href="aip_delete/<?= $row->id; ?>">DELETE</a>
                                                            <?php }}else{ ?>
                                                                <a class="btn btn-success sm" href="aip_edit/<?= $row->id; ?>">EDIT</a>  &nbsp;
                                                                <a class="btn btn-danger sm" href="aip_delete/<?= $row->id; ?>">DELETE</a>
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
                                                            <input type="hidden" name="school_id" <?php if($this->session->section === 'School'){echo " readonly ";} ?> value="<?php if($this->session->section === 'School'){echo $this->session->username;} ?>" required class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>YEAR</label>
                                                            <input type="text"  name="fy" class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>BATCH CODE</label>
                                                            <select class="form-control" name="b_code" required>
                                                                <option></option>
                                                                <?php foreach($ssa as $row){
                                                                    echo "<option value='".$row->alloc_batch."'>".$row->alloc_batch." - ". $row->alloc_group." : PHP ".number_format($row->alloc_amount)."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label >Pillar </label>
                                                            <select class="form-control" name="pillar">
                                                                <option></option>
                                                                <?php   foreach($pillar as $row){
                                                                    echo "<option value='{$row->pillar}'>".$row->pillar."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >Domain</label>
                                                            <select class="form-control" name="domain">
                                                                <option></option>
                                                                <?php   foreach($domain as $row){
                                                                    echo "<option value='{$row->domain}'>".$row->domain."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >STRAND </label>
                                                            <select class="form-control" name="strand">
                                                                <option></option>
                                                                <?php   foreach($strand as $row){
                                                                    echo "<option value='{$row->strand}'>".$row->strand."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label >PIAs </label>
                                                            <select class="form-control" name="pias">
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

                                        <!-- end content -->

                <div id="sfe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER ANNUAL IMPLEMENTATION PLAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/submit_aip'); ?>
                                                         <div class="form-group">
                                                            <label>School ID</label>
                                                            <input type="text" name="school_id" <?php if($this->session->section != 'System Administrator'){echo " readonly ";} ?> value="<?php if($this->session->section === 'School'){echo $this->session->username;} ?>" required class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>YEAR</label>
                                                            <input type="text"  name="fy" class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>BATCH CODE</label>
                                                            <select class="form-control" name="b_code" required>
                                                                <option></option>
                                                                <?php foreach($ssa as $row){
                                                                    echo "<option value='".$row->alloc_batch."'>".$row->alloc_batch." - ". $row->alloc_group." : PHP ".number_format($row->alloc_amount)."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
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
                                         </div>


                                        

                                        <div id="g_aip" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER ANNUAL IMPLEMENTATION PLAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/generate_aip'); ?>
                                                         <div class="form-group">
                                                            <label>School ID</label>
                                                            <input type="text" name="school_id" <?php if($this->session->section != 'System Administrator'){echo " readonly ";} ?> value="<?php if($this->session->section === 'School'){echo $this->session->username;} ?>" required class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>YEAR</label>
                                                            <input type="text"  name="fy" class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>BATCH CODE</label>
                                                            <select class="form-control" name="b_code" required>
                                                                <option></option>
                                                                <?php foreach($ssa as $row){
                                                                    echo "<option value='".$row->alloc_batch."'>".$row->alloc_batch." - ". $row->alloc_group." : PHP ".number_format($row->alloc_amount)."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>

                                                        
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="Generate" />
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div> 
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
     
     <SCRIPT language=Javascript>
        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 
              && (charCode < 48 || charCode > 57))
              return false;

            return true;
        }
      </SCRIPT>


    </body>
</html>