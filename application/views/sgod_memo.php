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
                                    <h4 class="page-title" id="myLargeModalLabel">                            
                                        <!-- <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">+ ADD NEW</button> -->
                                        <a data-toggle="modal"  class="open-AddBookDialog btn btn-primary" href="#addBookDialog">+ ADD NEW</a>
                                        
                                    </h4>
                                    <!-- <div class="page-title-right">
                                        <ol class="breadcrumb p-0 m-0">
                                            <li class="breadcrumb-item">SGOD Management System v1.0</li>
                                        </ol>
                                    </div> -->
                                    <div class="clearfix"></div>
                                    <?php if($this->session->flashdata('success')) : ?>

                                    <?= '<br /><div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>'
                                            .$this->session->flashdata('success'). 
                                        '</div>'; 
                                    ?>
                                    <?php endif; ?>

                                    <?php if($this->session->flashdata('danger')) : ?>
                                    <?= '<br /><div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>'
                                            .$this->session->flashdata('danger'). 
                                        '</div>'; 
                                    ?>
                                    <?php endif;  ?>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->


                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4"><?= $title; ?></h4>
                                        <h6>Last Memo Number: <span class="text-danger"><?= $ln->memoNo; ?></span></h6><br />
                                        <div class="table-responsive">
                                        <table class="table mb-0">

                                        <thead>
                                                <tr>
                                                    <th>Memo Number</th>
                                                    <th>Memo</th>
                                                    <th>Manage</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach($page as $row){ ?>
                                                <tr>
                                                <td><?= $row->memoNo; ?></td>
                                                    <td><?= wordwrap($row->title,50,"<br>\n"); ?></td>
                                                    <td>
                                                    <?php if($row->added_by == $this->session->username){ ?>
                                                    <?php if(empty($row->fileName)) {?>
                                                        <a class="btn btn-warning btn-sm tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Update File Attachment" href="<?= base_url(); ?>page/memo_file_update/<?= $row->id; ?>"><i class="fas fa-file-pdf"></i></a>
                                                    <?php } else { ?>
                                                        <a class="btn btn-warning btn-sm tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Update File Attachment" href="<?= base_url(); ?>page/memo_file_update/<?= $row->id; ?>"><i class="fas fa-file-pdf"></i></a>   
                                                        <a class="btn btn-success btn-sm tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View File Attachment" href="<?= base_url(); ?>upload/memo/<?= $row->fileName; ?>" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                                    <?php } ?> 

                                                        
                                                    <a class="btn btn-primary btn-sm tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" href="<?= base_url(); ?>page/memo_update/<?= $row->id; ?>"><i class="mdi mdi-pencil"></i></a>
                                                    <a onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Delete" href="<?= base_url(); ?>page/memo_delete/<?= $row->id; ?>"><i class="ion ion-ios-trash"></i></a>
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




                
                                                    <!-- Confirmation Modal -->
                                                    <div class="modal fade " id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="confirmationModalLabel">Delete Confirmation</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="text-center">
                                                                                        <div class="circle-with-stroke d-inline-flex justify-content-center align-items-center">
                                                                                            <span class="h1 text-danger">!</span>
                                                                                        </div>
                                                                                        <p class="mt-3">Are you sure you want to delete this data?</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                        <a href="<?= base_url("Page/memo_del/{$row->id}"); ?>" class="btn btn-danger">Delete</a>
                                                                                        
                                                                                    </div>
                                                                                    </div>
                                                                                </div>
                                                                                </div>

                                                                                <style>
                                                                                .circle-with-stroke {
                                                                                    width: 100px;
                                                                                    height: 100px;
                                                                                    border: 4px solid #dc3545;
                                                                                    border-radius: 50%;
                                                                                }
                                                                                </style>

                                                                                






                <div id="addBookDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><?= $m_title; ?></h5>
                                                        
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    
                                                    <div class="modal-header">
                                                    <h6>Last Memo Number: <span class="text-danger"><?= $ln->memoNo; ?></span></h6>
                                                    </div>

                                                    <div class="modal-body">
                                                    <?= form_open_multipart('Page/'.$add_action); ?>
                                                   
                                                    <div class="form-group col-md-12">
                                                            <label >Memo Number</label>
                                                            <input type="text"  required name="memoNo" class="form-control" />
                                                        </div>
                                                   
                                                        <div class="form-group col-md-12">
                                                            <label for="title">Subject</label>
                                                            <textarea required name="title" class="form-control"></textarea>
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label >File</label>
                                                            <input type="file"  name="file" class="form-control" />
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

                                                <!-- Para sa Update Modal ni kol -->

                                        <div id="app_edit" class="modal fade open-AddBookDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><?= $e_title; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <form action="<?= base_url('Page/update_memo'); ?>" method="post" id="updateMemoForm">
                                                        <input type="hidden" id="id" name="id" >
                                                        <div class="form-group col-md-12">
                                                            <label>Memo Number</label>
                                                            <input type="text" id="memoNo" name="memoNo" class="form-control" value=" <?php echo $page[0]->memoNo ?>" />
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label>Title</label>
                                                            <textarea id="title" name="title" class="form-control" value="" ></textarea>
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label>File</label>
                                                            <input type="file" id="title" name="title" class="form-control" value=" <?php echo $page[0]->title ?>" />
                                                        </div>
                                   

                                                        <div class="modal-footer">
                                                            <button type="submit" name="update" class="btn btn-primary waves-effect waves-light">Update</button>
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
        


         


     <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>
     <script type="text/javascript">
                $('body').tooltip({selector: '[data-toggle="tooltip"]'});

                $(document).on("click", ".open-AddBookDialog", function () {
                    var myBookId = $(this).data('id');
                    $(".modal-body #id").val( myBookId );

                    var fieldId = $(this).data('field');
                    $(".modal-body #field").val( fieldId );

                    var tid = $(this).data('title');
                    $(".modal-body #title").val( tid );
                });
            </script>

<script>
var deleteUrl = "";

 function setDeleteUrl(url) {
deleteUrl = url;
}

function deleteData() {
// Proceed with deletion
window.location.href = deleteUrl;
}
</script>


    </body>
</html>