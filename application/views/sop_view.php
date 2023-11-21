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
                                    <a data-toggle="modal" class="open-AddBookDialog btn btn-primary" href="#g_aip">Generate SOP</a>                        
                                    </h4>
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
                                                        <th>Action</th>
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
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data as $row){ ?>
                                                    <tr>
                                                        <td><?= $row->school_id; ?></td> 
                                                        <td>
                                                            <?php 
		                                                        $soppt = $this->SGODModel->two_cond_row('sgod_sop','aip_id',$row->id,'type','1');
                                                                if(!empty($soppt)){ ?>
                                                                    <a href="sop_edit/<?= $soppt->id; ?>" class="btn btn-warning">EDIT PT</a> 
                                                                <?php }else{ ?>
                                                                    <a data-toggle="modal" data-field="1" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-info" href="#sop">PT</a> 
                                                            <?php } ?>

                                                            <?php 
		                                                        $sopft = $this->SGODModel->two_cond_row('sgod_sop','aip_id',$row->id,'type','2');
                                                                if(!empty($sopft)){ ?>
                                                                    <a href="sop_edit/<?= $sopft->id; ?>" class="btn btn-warning">EDIT FT (MOOE)</a> 
                                                                <?php }else{ ?>
                                                                    <a data-toggle="modal" data-field="2" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-primary" href="#sop">FT (MOOE)</a> 
                                                            <?php } ?>

                                                            <?php 
		                                                        $sopfo = $this->SGODModel->two_cond_row('sgod_sop','aip_id',$row->id,'type','3');
                                                                if(!empty($sopfo)){ ?>
                                                                    <a href="sop_edit/<?= $sopfo->id; ?>" class="btn btn-warning">EDIT FT (OTHER SOURCES OF FUND)</a> 
                                                                <?php }else{ ?>
                                                                    <a data-toggle="modal" data-field="3" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-purple" href="#sop">FT (OTHER SOURCES OF FUND)</a>
                                                            <?php } ?>
                                                            
                                                            
                                                            
                                                        </td> 
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
                
                <!-- FINANCIAL TARGET (MOOE) -->
                <div id="sop" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">TARGET</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/sop'); ?>
                                                         <div class="form-group">
                                                            <input type="hidden" name="aip_id" value="" id="id" />
                                                            <input type="hidden"  name="type" value="" id="field" />

                                                            <label>1ST QUARTER</label>
                                                            <input type="text" id="value1" onkeypress="return isNumberKey(event)" oninput="calculateTotal()" name="q1" value="" class="form-control amount" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label>2ND QUARTER</label>
                                                            <input type="text"  id="value2" onkeypress="return isNumberKey(event)" oninput="calculateTotal()"  name="q2" class="form-control amount" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>3RD QUARTER</label>
                                                            <input type="text"  id="value3" onkeypress="return isNumberKey(event)" oninput="calculateTotal()"  name="q3" class="form-control amount" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>4TH QUARTER</label>
                                                            <input type="text"  id="value4" onkeypress="return isNumberKey(event)" oninput="calculateTotal()" name="q4" class="form-control amount" />
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label><a href="javascript:sumInputs()">TOTAL</a></label>
                                                            <input type="text"  name="total" id="total" class="form-control amount" />
                                                        </div>

                                                        
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit"  class="btn btn-primary waves-effect waves-light" value="Submit" />
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                                            </div>
                                    <!-- FINANCIAL TARGET (MOOE) END -->

                                    <div id="g_aip" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER ANNUAL IMPLEMENTATION PLAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/generate_sop'); ?>
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
                                                            <input type="text" list="b_code"  name="b_code" class="form-control" autocomplete="off"/>
                                                            <datalist id="b_code">
                                                                <?php foreach($ssa as $row){ ?>
                                                                <option><?= $row->alloc_batch; ?></option>
                                                                <?php } ?>
                                                            </datalist>
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

        <script type="text/javascript">
                $(document).on("click", ".open-AddBookDialog", function () {
                    var myBookId = $(this).data('id');
                    $(".modal-body #id").val( myBookId );

                    var fieldId = $(this).data('field');
                    $(".modal-body #field").val( fieldId );
                });
            </script>


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

    <script>
        function calculateTotal() {
            var value1 = parseFloat(document.getElementById('value1').value) || 0;
            var value2 = parseFloat(document.getElementById('value2').value) || 0;
            var value3 = parseFloat(document.getElementById('value3').value) || 0;
            var value4 = parseFloat(document.getElementById('value4').value) || 0;
            var total = value1 + value2 + value3 + value4;
            
            document.getElementById('total').value = total;
        }
    </script>

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