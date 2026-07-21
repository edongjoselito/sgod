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
                        <?php 
                        $pre_stat = [
                            1 => 'FullyPrepared - 100% compliance',
                            2 => 'Partially Prepared - does not meet any one of the requirements/features/characteristics',
                            3 => 'Not Prepared - does not meet all requirements'
                        ]; 
                        ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <?php 
                                            $cat = $this->Common->one_cond_row('brigada_spc_category','id',$item->spc_cat_id);
                                            $q = 'q'.$cat->id.$this->uri->segment(4);
                                            $remarks = 'r'.$cat->id.$this->uri->segment(4);
                                            $val = $this->uri->segment(3);
                                            $fb =  $this->Common->two_cond('brigada_spc_feedback','sy',$this->session->cur_sy,$q,$val);
                                        ?>
                                        <h4 class="header-title mb-4"><?= $cat->name; ?><br /><small class="text-success"><?= $item->description; ?></small><br /><small class="text-muted"><?= $pre_stat[$this->uri->segment(3)]?></small></h4>

                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <!-- <table class="table mb-0"> -->
                                            <thead>
                                                <tr>
                                                    <th>School Name</th>
                                                    <th>Remarks</th>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($fb as $row){
                                                    $id = $row->school_id;
                                                    $s = $this->Common->one_cond_row('schools', 'schoolID',$id);
                                                ?>
                                                <tr>
                                                    <td><?= $s->schoolName; ?></td>
                                                    <td><?= $row->{$remarks}; ?></td>
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


            