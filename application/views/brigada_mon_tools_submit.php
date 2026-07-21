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
                                    <h2 class="text-center"><?= $title; ?></h2>
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

                        <?php $att = array('class' => 'parsley-examples'); ?>
                        <?= form_open('Brigada/brigada_mon_tools_submit', $att); ?>

                        <input type="hidden" name="school_id" value="<?= $this->uri->segment(3); ?>">
                        <input type="hidden" name="district" value="<?= $this->uri->segment(4); ?>">
                        <input type="hidden" name="fy" value="<?= date('Y'); ?>">
                        <input type="hidden" name="encode" value="0">



                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <h4>PART A: IMPLEMENTATION INDICATORS</h4>
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Indicators</th>
                                                    <th>Yes</th>
                                                    <th>No</th>
                                                    <th>Remarks / Observations</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($indi_type as $row){
                                                    $indi = $this->Common->one_cond('brigada_imp_indicators','type',$row->type);
                                                ?>
                                                <tr>
                                                    <td style="background:#44536a; color:#fff" colspan="4"><?= $row->type; ?></td>
                                                </tr>
                                                <?php 
                                                    $c=1; foreach($indi as $irow){
                                                    $remarks = 'r'.$irow->id;
                                                    $q = 'q'.$irow->id;
                                                ?>
                                                <tr>
                                                    <td><?= $irow->description; ?></td>
                                                    <td class="text-center"><input type="radio" name="q<?= $irow->id; ?>" <?php if(!empty($exist)){if($exist->$q == 1){echo " checked ";}} ?> value="1" ></td>
                                                    <td class="text-center"><input  type="radio" name="q<?= $irow->id; ?>" <?php if(!empty($exist)){if($exist->$q == 2){echo " checked ";}} ?> value="2" ></td>
                                                    <td><textarea class="form-control" name="r<?= $irow->id; ?>" rows="1" id="example-textarea"><?php if(!empty($exist)){echo $exist->$remarks;} ?></textarea></td>
                                                </tr>
                                                <?php } } ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <h4>PART B: STAKEHOLDER ENGAGEMENT</h4>
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Stakeholder Type</th>
                                                    <th class="text-center">Engaged (✔)</th>
                                                    <th class="text-center">Type of Support Provided </th>
                                                </tr>
                                            </thead>
                                            <?php
                                                
                                                foreach ($en as $row) {
                                                    $e = 'e'.$row->id;
                                                    $s = 's'.$row->id;
                                                  
                                            ?>
                                                <tr>
                                                    <td><?= $row->description; ?></td>
                                                    <td class="text-center"><input type="checkbox" name="e<?= $row->id; ?>" <?php if(!empty($exist)){if($exist->$e == 1){echo " checked ";}} ?> value="1" ></td>
                                                    <td><textarea class="form-control" name="s<?= $row->id; ?>" rows="1" id="example-textarea"><?php if(!empty($exist)){echo $exist->$s;} ?></textarea></td>
                                                </tr>
                                                <?php } ?>
                                            <tbody>
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <h4>PART C: MONITORING TEAM'S COMMENTS / RECOMMENDATIONS </h4>
                                        <cite>(Use this section to summarize observations, commendations, and suggested areas for improvement.) </cite>
                                        <textarea class="form-control" name="comment" rows="10" id="example-textarea"><?php if(!empty($exist)){echo $exist->comment;} ?></textarea>
                                        <br />
                                        
                                        
                                        <div class="form-group text-left mb-0">
                                            <?php if(empty($exist)){?>
                                            <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                                            <?php }else{?>
                                                <input type="hidden" value="<?= $exist->id; ?>" name="id">
                                                <input type="submit" name="update" value="Update" class="btn btn-primary waves-effect waves-light mr-1">
                                            <?php } ?>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                    </form>

                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->


            <?php include('templates/footer.php'); ?>


            