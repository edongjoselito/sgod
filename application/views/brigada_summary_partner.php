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
                                        <h4 class="header-title mb-4"><?= date('Y'); ?> <?= $title; ?></h4><br />

                                        <table class="table table-bordered mb-0">

                                            <thead>
                                                <tr>
                                                    <th colspan="2" style="text-align: center; vertical-align: middle;">School</th>
                                                    <th>GOVERNMENT PARTNER</th>
                                                    <th>INTERVENTION</th>
                                                    <th>AMOUNT</th>
                                                    <th>REMARKS</th>
                                                </tr>

                                            </thead>

                                            <tbody>
                                                <?php 
                                                    $c = 1; 
                                                    $lastSchool = '';
                                                    foreach($data as $row) {
                                                        $showSchool = $row->schoolName != $lastSchool;
                                                    ?>
                                                        <tr>
                                                            <td><?= $showSchool ? $c++ : ''; ?></td>
                                                            <td><?= $showSchool ? $row->schoolName : ''; ?></td>
                                                            <td><?= $row->dtype == 1 ? 'GOVERNMENT PARTNER' : 'PRIVATE PARTNER'; ?></td>
                                                            <td><?= $row->intervention; ?></td>
                                                            <td><?= number_format($row->amount); ?></td>
                                                            <td><?= $row->remarks; ?></td>
                                                        </tr>
                                                    <?php 
                                                        if ($showSchool) {
                                                            $lastSchool = $row->schoolName;
                                                        }
                                                    } 
                                                ?>
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


            