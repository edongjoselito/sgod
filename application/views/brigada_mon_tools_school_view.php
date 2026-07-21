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

                        <?php $att = array('class' => 'parsley-examples'); ?>
                        <?= form_open('Brigada/brigada_mon_tools_school_view', $att); ?>

                        <?php 
                             $school = $this->Common->one_cond_row_select('schools','schoolID, district', 'schoolID', $this->session->username);
                             $dis = $this->Common->one_cond_row_select('district','id, discription', 'discription', $school->district);
                        ?>

                        <input type="hidden" name="school_id" value="<?= $this->session->username; ?>">
                        <input type="hidden" name="fy" value="<?= $this->session->cur_fy; ?>">
                        <input type="hidden" name="district" value="<?= $dis->id; ?>">
                        <input type="hidden" name="encode" value="1">
                        <input type="hidden" name="sy" value="<?= $this->session->cur_sy; ?>">



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
                                                <?php if($exist->encode == 1){?>
                                                <input type="hidden" value="<?= $exist->id; ?>" name="id">
                                                <input type="submit" name="update" value="Update" class="btn btn-primary waves-effect waves-light mr-1">
                                                <?php } ?>
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


            