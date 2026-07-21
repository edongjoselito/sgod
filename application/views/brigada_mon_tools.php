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
                                        <h4 class="header-title mb-4">School List</h4><br />

                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <!-- <table class="table mb-0"> -->
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>District</th>
                                                    <!-- <th>Submitted</th>
                                                    <th>Not Submitted</th> -->
                                                    <th>Action</th>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $c=1;
                                                    foreach($district as $row){
                                                    $ds = $this->Common->one_cond_count_row('schools','district',$row->discription);
                                                    //$ss = $this->Common->one_cond_count_row('sbm','district_id',$row->id);
                                                ?>
                                               <tr>
                                                <td><?= $c++; ?></td>
                                                <td><?= $row->discription; ?></td>
                                                <!-- <td class="text-center"><a href="<?= base_url(); ?>/Page/sbm_district_list_admin/<?= $row->id; ?>"><span class='badge badge-success badge-pill'><?= $ds->num_rows(); ?></span></a></td>
                                                <td class="text-center"><a href="<?= base_url(); ?>/Page/sbm_district_list_admin_not/<?= $row->id; ?>"><span class='badge badge-danger badge-pill'></span></a></td> -->
                                                <td>
                                                    <a href="<?= base_url(); ?>Brigada/brigada_mon_tool_schools/<?= $row->id; ?>" class="btn btn-sm btn-success">View</a>
                                                </td>
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

                



                                        





            <?php include('templates/footer.php'); ?>


            