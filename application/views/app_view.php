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
                                        <a data-toggle="modal" class="open-AddBookDialog btn btn-primary" href="#g_app">Generate APP</a>
                                        <a data-toggle="modal" class="open-AddBookDialog btn btn-info" href="#rca">Generate RCA</a>
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
                                                        <th>SCHOOL IMPROVEMENT  PROJECT TITLE</th>
                                                        <th>MATERIALS</th>
                                                        <th>MANAGE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    <?php foreach($data as $row){ ?>
                                                    <tr>
                                                        <td>
                                                            <?php $aip=$this->SGODModel->one_cond_row('sgod_aip','id', $row->aip_id); ?>
                                                            <?= $aip->sip_project; ?> / <?= $aip->strategy; ?>
                                                        </td>
                                                        <td><?= $row->materials; ?>  </td>
                                                        <td>
                                                          <?php if($row->stat == 1){ ?>
                                                            <a data-toggle="modal" data-field="<?= $aip->sip_project; ?>" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-warning" href="#app_update<?= $row->id; ?>">Update APP</a>
                                                        <?php  }else{ ?>
                                                          <a data-toggle="modal" data-field="<?= $aip->sip_project; ?>" data-id="<?= $row->id; ?>" class="open-AddBookDialog btn btn-primary" href="#app">APP</a>
                                                        <?php  } ?>


                                                         <!--  Modal content for the above example -->
                                                        <div id="app_update<?= $row->id; ?>" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                          <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <h5 class="modal-title" id="myLargeModalLabel">UPDATE ANNUAL PROCUREMENT PLAN </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                              </div>
                                                              <div class="modal-body">

                                                                <?php $att = array('class' => 'parsley-examples'); ?>
                                                                <?= form_open('Page/view_app', $att); ?>

                                                                    <input type="hidden" name="id" id="id">
                                                                    <div class="row">
                                                                        <div class="col-lg-3">
                                                                          <div class="form-group">
                                                                          <label >Unit Price</label>
                                                                            <input value="<?= $row->unit_price; ?>" type="text"  class="form-control" name="unit_price" onkeypress="return isNumberKey(event)">
                                                                          </div>
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                          <div class="form-group">
                                                                          <label >Quantity</label>
                                                                            <input value="<?= $row->quantity; ?>" type="text" onkeypress="return isNumberKey(event)" class="form-control" name="quantity" >
                                                                          </div>
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                          <div class="form-group">
                                                                          <label >Unit Measure</label>
                                                                            <input value="<?= $row->unit_measure; ?>" type="text" class="form-control" name="unit_measure" onkeypress="return isNumberKey(event)">
                                                                          </div>
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                          <div class="form-group">
                                                                          <label >Budget Allocated</label>
                                                                            <input value="<?= $row->budget_alloc; ?>" type="text" onkeypress="return isNumberKey(event)" class="form-control" name="budget_alloc" placeholder="Amout">
                                                                          </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-xl-12">
                                                                            <div class="card-box">

                                                                                <ul class="nav nav-tabs">
                                                                                    <li class="nav-item">
                                                                                        <a href="#homeupdate" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                                                                            <span class="d-block d-sm-none"><i class="mdi mdi-home-variant-outline font-18"></i></span>
                                                                                            <span class="d-none d-sm-block">1st Quarter</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                        <a href="#profileupdate" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                                                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline font-18"></i></span>
                                                                                            <span class="d-none d-sm-block">2nd Quarter</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                        <a href="#messagesupdate" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                                                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline font-18"></i></span>
                                                                                            <span class="d-none d-sm-block">3rd Quarter</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                        <a href="#settingsupdate" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                                                            <span class="d-block d-sm-none"><i class="mdi mdi-settings-outline font-18"></i></span>
                                                                                            <span class="d-none d-sm-block">4rd Quarter</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                                <div class="tab-content">
                                                                                    <div class="tab-pane show active" id="homeupdate">
                                                                                      <div class="row">
                                                                                          <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                              <label >January</label>
                                                                                              <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->jan; ?>" name="jan" placeholder="Amount">
                                                                                            </div>
                                                                                          </div>

                                                                                          <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                            <label >Febuary</label>
                                                                                              <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->feb; ?>" name="feb" placeholder="Amount">
                                                                                            </div>
                                                                                          </div>

                                                                                          <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                            <label >March</label>
                                                                                              <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->mar; ?>" name="mar" placeholder="Amount">
                                                                                            </div>
                                                                                          </div>
                                                                                  </div> 
                                                                                    </div>

                                                                                    <div class="tab-pane" id="profileupdate">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >April</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->april; ?>" name="april" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >May</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->may; ?>" name="may" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >June</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->june; ?>" name="june" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                    <div class="tab-pane" id="messagesupdate">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >July</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->july; ?>" name="july" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >August</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->aug; ?>" name="aug" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >September</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->sept; ?>" name="sept" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                        </div>   

                                                                                    </div>
                                                                                    <div class="tab-pane" id="settingsupdate">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >October</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->oct; ?>" name="oct" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >November</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->nov; ?>" name="nov" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                              <div class="form-group">
                                                                                              <label >December</label>
                                                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= $row->dec; ?>" name="dec" placeholder="Amount">
                                                                                              </div>
                                                                                            </div>

                                                                                        </div>

                                                                                      
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                        </div>
                                                                        <!-- end col -->
                                                                                    </div>

                                                                </form>
                                                              </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                          </div>
                                                          <!-- /.modal-dialog -->
                                                        </div>
                                                        <!-- /.modal -->
                                                        
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

                <!--  Modal content for the above example ADD APP -->
                <div id="app" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">ANNUAL PROCUREMENT PLAN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      </div>
                      <div class="modal-body">
                     

                        <?php $att = array('class' => 'parsley-examples','name' => 'abc',); ?>
                        <?= form_open('Page/view_app', $att); ?>

                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-lg-3">
                                  <div class="form-group">
                                  <label >Unit Price</label>
                                    <input type="text" onkeypress="return isNumberKey(event)" id="PPRICE" class="form-control" name="unit_price" placeholder="Amout">
                                  </div>
                                </div>

                                <div class="col-lg-3">
                                  <div class="form-group">
                                  <label >Quantity</label>
                                    <input type="text" onkeypress="return isNumberKey(event)"  onkeyup="multiply()" id="QTY" class="form-control" name="quantity">
                                  </div>
                                </div>

                                <div class="col-lg-3">
                                  <div class="form-group">
                                  <label >Unit Measure</label>
                                    <input type="text" class="form-control" name="unit_measure" >
                                  </div>
                                </div>

                                <div class="col-lg-3">
                                  <div class="form-group">
                                  <label >Budget Allocated</label>
                                    <input type="text" readonly class="form-control" id="TOTAL" name="budget_alloc" placeholder="Amout">
                                  </div>
                                </div>
                            </div>


                            <div class="row">
                              <div class="col-xl-12">
                                  <div class="card-box">

                                      <ul class="nav nav-tabs">
                                          <li class="nav-item">
                                              <a href="#home" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                                  <span class="d-block d-sm-none"><i class="mdi mdi-home-variant-outline font-18"></i></span>
                                                  <span class="d-none d-sm-block">1st Quarter</span>
                                              </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="#profile" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                  <span class="d-block d-sm-none"><i class="mdi mdi-account-outline font-18"></i></span>
                                                  <span class="d-none d-sm-block">2nd Quarter</span>
                                              </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="#messages" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                  <span class="d-block d-sm-none"><i class="mdi mdi-email-outline font-18"></i></span>
                                                  <span class="d-none d-sm-block">3rd Quarter</span>
                                              </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                  <span class="d-block d-sm-none"><i class="mdi mdi-settings-outline font-18"></i></span>
                                                  <span class="d-none d-sm-block">4rd Quarter</span>
                                              </a>
                                          </li>
                                      </ul>
                                      <div class="tab-content">
                                          <div class="tab-pane show active" id="home">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                  <div class="form-group">
                                                    <label >January</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('jan'); ?>" name="jan" placeholder="Amount">
                                                  </div>
                                                </div>

                                                <div class="col-lg-4">
                                                  <div class="form-group">
                                                  <label >Febuary</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('feb'); ?>" name="feb" placeholder="Amount">
                                                  </div>
                                                </div>

                                                <div class="col-lg-4">
                                                  <div class="form-group">
                                                  <label >March</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('mar'); ?>" name="mar" placeholder="Amount">
                                                  </div>
                                                </div>
                                        </div> 
                                          </div>

                                          <div class="tab-pane" id="profile">
                                              <div class="row">
                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >April</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('april'); ?>" name="april" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >May</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('may'); ?>" name="may" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >June</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('june'); ?>" name="june" placeholder="Amount">
                                                    </div>
                                                  </div>

                                              </div>
                                              
                                          </div>
                                          <div class="tab-pane" id="messages">
                                              <div class="row">
                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >July</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('july'); ?>" name="july" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >August</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('aug'); ?>" name="aug" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >September</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('sept'); ?>" name="sept" placeholder="Amount">
                                                    </div>
                                                  </div>

                                              </div>   

                                          </div>
                                          <div class="tab-pane" id="settings">
                                              <div class="row">
                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >October</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('oct'); ?>" name="oct" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >November</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('nov'); ?>" name="nov" placeholder="Amount">
                                                    </div>
                                                  </div>

                                                  <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label >December</label>
                                                      <input type="text" class="form-control" onkeypress="return isNumberKey(event)" value="<?= set_value('dec'); ?>" name="dec" placeholder="Amount">
                                                    </div>
                                                  </div>

                                              </div>

                                             
                                          </div>
                                      </div>
                                  </div>
                                  <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                              </div>
                              <!-- end col -->
                                   
                             

                                                        </div>

                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->



                <div id="g_app" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER ANNUAL IMPLEMENTATION PLAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/generate_app'); ?>
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

                                        <!-- end content -->

                                        <div id="rca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">FILTER RCA</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?= form_open('page/generate_rca'); ?>
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

     <SCRIPT language=Javascript>
        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 
              && (charCode < 48 || charCode > 57))
              return false;

            return true;
        }
      </SCRIPT>

    <script type="text/javascript">
                $(document).on("click", ".open-AddBookDialog", function () {
                    var myBookId = $(this).data('id');
                    $(".modal-body #id").val( myBookId );

                    var fieldId = $(this).data('field');
                    $(".modal-body #field").val( fieldId );
                });

                function multiply(){

                  a=Number(document.abc.QTY.value);

                  b=Number(document.abc.PPRICE.value);

                  c=a*b;

                  document.abc.TOTAL.value=c;
                }
    </script>
       


    </body>
</html>