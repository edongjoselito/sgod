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
        <link href="<?= base_url(); ?>assets/css/renren.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <link href="<?= base_url(); ?>assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url(); ?>assets/libs/nestable2/jquery.nestable.min.css" rel="stylesheet" type="text/css" />

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
                                    <h4 class="page-title" id="myLargeModalLabel">    
                                        <a href="<?= base_url(); ?>Page/viewSecAccomplishments" class="btn btn-success waves-effect width-md waves-light">BACK</a>                        
                                        <a data-toggle="modal" data-field="file1" data-id="<?= $this->uri->segment(3); ?>" class="open-AddBookDialog btn btn-info" href="#addBookDialog">Attach MOVs</a>
                                        <a data-toggle="modal" data-field="file1" data-id="<?= $this->uri->segment(3); ?>" class="open-AddBookDialog btn btn-primary" href="#addTrainingResources">Attach Resources</a>
                                    </h4>
                                    <div class="page-title-right">
                                        <!-- <ol class="breadcrumb p-0 m-0">
                                            <li class="breadcrumb-item">SGOD Management System v1.0</li>
                                        </ol> -->
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->


                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4">Attachments</h4>

                                        <div class="right_content">
                                            <h4 class="header-title">Mandatory Attachments</h4>

                                            <div class="custom-dd dd" id="nestable_list_2">
                                                <ol class="dd-list">
                                                    <li class="dd-item" data-id="11">
                                                        <div class="dd-handle">
                                                           Attendance Sheets
                                                        </div>
                                                    </li>
                                                    <li class="dd-item" data-id="12">
                                                        <div class="dd-handle">
                                                            Memo
                                                        </div>
                                                    </li>
                                                    <li class="dd-item" data-id="13">
                                                        <div class="dd-handle">
                                                            Training Design
                                                        </div>
                                                    </li>
                                                   
                                                </ol>
                                            </div>
                                            
                                            <h4 class="header-title">Actual Attachments</h4>
                                            <ul>
                                            <?php foreach($sf as $row){ ?>
                                                <li>
                                                    <a href="<?= base_url(); ?>upload/training_resources/<?= $row->file; ?>" target="_blank"><?= $row->file_title; ?></a>
                                                    <div class="card-widgets"><a href="<?= base_url(); ?>page/delete_file/<?= $row->id; ?>" onclick="return confirm('Are you sure?')"><i class="mdi mdi-close"></i></a>
                                                    </div>
                                                </li>
                                            <?php } ?>    
                                            </ul>
                                        </div>
                                            

                                        <!-- The four columns -->

                                        <div class="row left_content">
                                        <?php foreach($data as $row){ ?>
                                        <div class="column">
                                            <img src="<?= base_url().'upload/tr_images/'.$row->file; ?>" alt="Nature" style="width:100%" onclick="myFunction(this);">
                                            <a class="renDel" href="<?= base_url(); ?>page/delete_attach/<?= $row->id; ?>"><button type="button" class="btn btn-danger btn-sm">Delete</button></a>
                                        </div>
                                        <?php } ?>

                                        <div class="container">
                                        <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>
                                        <img id="expandedImg" style="width:100%">
                                        <div id="imgtext"></div>
                                        </div>
                                        
                                        </div>
              


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

                                        <div id="addTrainingResources" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Attach Training Resources</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open_multipart('page/atr'); ?>
                                                         <div class="form-group col-md-12">
                                                            <label >Training Resources Title</label>
                                                            <input type="text"  name="atr" class="form-control" />
                                                        </div>
                                                    
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


                                        


     <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>
     <script>
        function myFunction(imgs) {
        var expandImg = document.getElementById("expandedImg");
        var imgText = document.getElementById("imgtext");
        expandImg.src = imgs.src;
        imgText.innerHTML = imgs.alt;
        expandImg.parentElement.style.display = "block";
        }

                $(document).on("click", ".open-AddBookDialog", function () {
                    var myBookId = $(this).data('id');
                    $(".modal-body #id").val( myBookId );

                    var fieldId = $(this).data('field');
                    $(".modal-body #field").val( fieldId );
                });
            </script>


    </body>
</html>