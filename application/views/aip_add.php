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
                                        
                                        
                                        <?php $att = array('class' => 'parsley-examples'); ?>
                                        <?= form_open('Page/aip_new', $att); ?>
                                            <input type="hidden"  class="form-control" name="school_id" value="<?= $this->session->username; ?>">
                                            <div class="row">
                                                <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label >FY <span class="text-danger">*</span></label>
                                                            <input type="text" required class="form-control" value="<?= date('Y')+1; ?>" name="fy">
                                                        </div>
                                                </div>
                                                <div class="col-lg-2">
                                                <div class="form-group">
                                                            <label >BATCH CODE <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="b_code" required>
                                                                <option></option>
                                                                <?php foreach($ssa as $row){
                                                                    echo "<option value='".$row->alloc_batch."'>".$row->alloc_batch." - ". $row->alloc_group." : PHP ".number_format($row->alloc_amount).' : '.$row->alloc_type."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                                <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label >GROUP <span class="text-danger">*</span></label>
                                                             <select class="form-control" name="group" required>
                                                                <option></option>
                                                                <?php   
                                                                  $group = array(1 => 'Elementary School',2 => 'Junior High School', 3 => 'Senior High School');
                                                                  foreach($group as $row => $val){
                                                                    echo "<option value='".$row."'>".$val."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>

                                                <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label >Pillar <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="pillar" required>
                                                                <option></option>
                                                                <?php   foreach($pillar as $row){
                                                                    echo '<option value="'.$row->pillar.'">'.$row->pillar.'</option>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>

                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>Intermediate Outcome <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="io" required>
                                                                <option></option>
                                                                <?php   foreach($pillar as $row){
                                                                    echo '<optgroup label="'.$row->pillar.'">';
                                                                    $io = $this->SGODModel->one_cond_orderby('sgod_setting_io','pillar_id',$row->id,'id','ASC');
                                                                        foreach($io as $row){
                                                                            echo '<option class="ssigment" value="'.$row->id.'">'.$row->description.'</option>';   
                                                                        }
                                                                    echo '</optgroup>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>



                                                </div>

                                           
                                            <div class="row">
                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >MATATAG <span class="text-danger">*</span></label>
                                                             <select class="form-control" name="matatag" required>
                                                                <option></option>
                                                                <?php   foreach($matatag as $row){
                                                                    echo '<option value="'.$row->id.'">'.$row->matatag.'</option>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>

                                            <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >Domain <span class="text-danger">*</span></label>
                                                            <select class="form-control" name="domain" required>
                                                                <option></option>
                                                                <?php   foreach($domain as $row){
                                                                    echo '<option value="'.$row->domain.'">'.$row->domain.'</option>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >STRAND<span class="text-danger">*</span></label>
                                                             <select class="form-control" name="strand" required>
                                                                <option></option>
                                                                <?php   foreach($domain as $row){
                                                                    echo '<optgroup label="'.$row->domain.'">';
                                                                    $strand = $this->SGODModel->one_cond_orderby('sgod_settings_strand','domain_id',$row->id,'strand','ASC');
                                                                        foreach($strand as $row){
                                                                            echo '<option class="ssigment" value="'.$row->strand.'">'.$row->strand.'</option>';   
                                                                        }
                                                                    echo '</optgroup>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label >PIAs <span class="text-danger">*</span></label>
                                                             <select class="form-control" name="pia" required>
                                                                <option></option>
                                                                <?php   foreach($pias as $row){
                                                                    echo '<option value="'.$row->pias.'">'.$row->pias.'</option>';
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>Category <span class="text-danger">*</span></label>
                                                             <select class="form-control" name="category" required>
                                                                <option></option>
                                                                <?php  
                                                                 $cat = array('MANDATORY BILLS','MINOR REPAIR','TEACHING-LEARNING INSTRUCTION','TRAININGS/SEMINAR/TRAVEL');
                                                                foreach($cat as $row){
                                                                    echo "<option value='".$row."'>".$row."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                                <label >SCHOOL IMPROVEMENT  PROJECT TITLE</label>
                                                                <input required type="text" class="form-control" value=""  name="sip_project">
                                                         </div>
                                                </div>

                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >PROJECT OBJECTIVE</label>
                                                             <input type="text" class="form-control" value=""  name="sip_pObjective">
                                                        </div>
                                                </div>


                                                <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label >OUTPUT FOR THE YEAR</label>
                                                            <input type="text"  class="form-control" value=""  name="sip_output" >
                                                        </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label >STRATEGY ACTIVITIES </label>
                                                        <input type="text"  class="form-control" value="<?= set_value('strategy'); ?>" name="strategy" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label >PERFORMANCE INDICATOR</label>
                                                        <input type="text"  class="form-control" value="<?= set_value('pi'); ?>" name="pi" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label >MOVs</label>
                                                        <input type="text"  class="form-control" value="<?= set_value('movs'); ?>" name="movs" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label >PERSON(S) RESPONSIBLE</label>
                                                        <input type="text"  class="form-control" value="" name="pr" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label >SCHEDULE</label>
                                                        <input type="text"  class="form-control" value="<?= set_value('schedule'); ?>" name="schedule" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label >VENUE</label>
                                                        <input type="text"  class="form-control" value="<?= set_value('venue'); ?>" name="venue" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label >BUDGET PER ACTIVITY</label>
                                                        <input type="text"  class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('budget'); ?>" name="budget" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label >BUDGET SOURCE</label>
                                                        <select class="form-control" name="budget_source" required>
                                                                <option></option>
                                                                <?php   foreach($bs as $row){
                                                                    echo "<option value='".$row->description."'>".$row->description."</option>";
                                                                   }
                                                                ?>
                                                                
                                                            </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label >MATERIALS / TYPE OF EXPENDITURE</label>
                                                <input type="text"  class="form-control" value="<?= set_value('materials'); ?>"  name="materials" >
                                            </div>

                                            

                                            


                                            <div class="form-group text-left mb-0">
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


                function clearInput(target){
                    if (target.value!= ''){
                        target.value= "";
                    }
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