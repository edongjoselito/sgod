<?php include('templates/head.php'); ?>
            <?php include('templates/header.php'); ?>

            

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
                                    <div class="page-title-right">
                                        <ol class="breadcrumb p-0 m-0">
                                            <li class="breadcrumb-item"><a href="#" data-toggle="modal" data-target="#myModal">Current School Year : <span class="badge badge-success"><?= $this->session->cur_sy; ?></span></a></li>
                                        </ol>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->


                        
                                        

                        <?php if ($this->session->flashdata('success')) : ?>

                            <?= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>'
                                . $this->session->flashdata('success') .
                                '</div>';
                            ?>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('danger')) : ?>
                            <?= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>'
                                . $this->session->flashdata('danger') .
                                '</div>';
                            ?>
                        <?php endif;  ?>

                        

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <h4 class="header-title mb-4">School List <?php if ($this->session->position === 'socmob') {?> - <?= $district->discription; ?> District<?php } ?></h4><br />

                                        <table class="table mb-0">
                                            <!-- <table class="table mb-0"> -->
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>School Name </th>
                                                    <th class="text-center">Submitted</th>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $c=1;
                                                    foreach($school as $row){
                                                    $spc_submit=$this->Common->two_cond_row('brigada_spc_feedback','school_id',$row->schoolID,'sy',$_SESSION['cur_sy']);
                                                ?>
                                               <tr>
                                                <td><?= $c++; ?></td>
                                                <td><?= htmlspecialchars($row->schoolName, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center">
                                                    <?php if(empty($spc_submit)){?>
                                                    <span class="text-danger">&#10008;</span> 
                                                    <?php }else{ ?>
                                                        <a target="_blank" href="<?= base_url(); ?>Brigada/brigada_spc_district/<?= $row->schoolID; ?>" class="btn btn-success btn-sm">View</a>
                                                    <?php } ?>
                                                </td>
                                               
                                                <!-- <td>
                                                    <a href="<?= base_url(); ?>Page/tapr_form_district/<?= $row->schoolID; ?>" target="_blank" class="btn btn-sm btn-success">View</a>
                                                </td> -->
                                               </tr>
                                               <?php } ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->

                <!-- sample modal content -->
                                        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success">
                                                        <h5 class="modal-title text-white" id="myModalLabel">Change Fiscal Year</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <form action="<?= base_url('Pages/change_sy') ?>" method="post">
                                                        <div class="form-group row">
                                                            <div class="col-lg-12">
                                                                <select name="new_fy" class="form-control" onchange="this.form.submit()">
                                                                <option disabled selected>Change School Year</option>
                                                                <?php
                                                                    $currentYear = date('Y');

                                                                    for ($y = $currentYear - 5; $y <= $currentYear + 4; $y++) :
                                                                        $sy = $y . '-' . ($y + 1);
                                                                    ?>
                                                                        <option value="<?= $sy; ?>" <?= ($this->session->userdata('cur_fy') == $sy) ? 'selected' : ''; ?>>
                                                                            <?= $sy; ?>
                                                                        </option>
                                                                <?php endfor; ?>
                                                            </select>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->

                



                                        





            <?php include('templates/footer.php'); ?>


            