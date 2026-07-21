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
                        <?= form_open('Brigada/brigada_spc', $att); ?>

                        <input type="hidden" value="<?= $this->session->username; ?>" name="school_id">
                        <input type="hidden" value="<?= $this->session->cur_fy; ?>" name="fy">
                        <input type="hidden" value="<?= date('Y-m-d'); ?>" name="cdate">
                        <input type="hidden" value="<?= $district->id; ?>" name="district">
                        <input type="hidden" value="<?= $this->session->cur_sy; ?>" name="sy">

                        <?php if (!empty($existing)) : ?>
                            <input type="hidden" name="feedback_id" value="<?= $existing[0]->id ?>">
                        <?php endif; ?>


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">

                        <div class="row">
                            <div class="col-lg-12">
                                <div id="accordion" class="mb-3">

                                <?php $count=1; foreach($data as $row){ ?>
                                    <div class="card mb-0">
                                        <div class="card-header" id="headingOne">
                                            <h6 class="m-0">
                                                <a href="#collapseOne<?=$row->id; ?>" class="text-dark" data-toggle="collapse"
                                                        aria-expanded="true"
                                                        aria-controls="collapseOne">
                                                    <?= $count++.'. '. $row->name; ?>
                                                </a>
                                            </h6>
                                        </div>

                                        <div id="collapseOne<?=$row->id; ?>" class="collapse <?php if($row->id == 1){echo 'show';} ?>" aria-labelledby="headingOne" data-parent="#accordion">
                                        <?php 
                                            $smcp_sub = $this->Common->one_cond('brigada_spc_items', 'spc_cat_id', $row->id); 
                                            
                                        ?>
                                            
                                        <div class="card-body">

                                            <table class="table table-bordered mb-0">
                                                                            <thead>
                                                                                <tr class="text-center">
                                                                                    <th rowspan="2" style="vertical-align: middle;">CATEGORY</th>
                                                                                    <th colspan="3">PREPAREDNESS STATUS</th>
                                                                                    <th rowspan="2" style="vertical-align: middle;">REMARKS</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center"><b>Fully<br /> Prepared</b><br /> <small>Fully Prepared -<br /> 100% compliance</small></td>
                                                                                    <td class="text-center"><b>Partially<br /> Prepared</b><br /> <small>Partially Prepared -<br /> does not meet any one<br /> of the requirements/<br />features/characteristics</small></td>
                                                                                    <td class="text-center"><b>Not<br /> Prepared</b><br /><small>NOt Prepared -<br /> does not meet all<br /> requirements</small></td>
                                                                                </tr>
                                                                            </thead>
                                                                            
                                                                            <tbody>
                                                                                <?php $ivy=1;  $r=0; foreach($smcp_sub as $srow){ 
                                                                                        $c = $row->id.''.$ivy++;
                                                                                        $feedback = !empty($existing) ? (array)$existing[0] : [];
                                                                                        $val = isset($feedback['q'.$c]) ? $feedback['q'.$c] : '';
                                                                                        $remarksVal = isset($feedback['r' . $c]) ? $feedback['r' . $c] : '';
                                                                                    ?>
                                                                                <tr <?php echo (++$r%2 ? "" : "class='table-info'"); ?>>
                                                                                    <td><?= $srow->description; ?></td>
                                                                                    <td class="text-center">
                                                                                            <input <?= $val == '0' ? 'checked' : '' ?> type="hidden" name="q<?= $c; ?>" value="0" >
                                                                                            <input <?= $val == '1' ? 'checked' : '' ?> type="radio" name="q<?= $c; ?>" value="1" >
                                                                                    </td>
                                                                                    <td class="text-center"><input <?= $val == '2' ? 'checked' : '' ?> type="radio" name="q<?= $c; ?>" value="2" ></td>
                                                                                    <td class="text-center"><input <?= $val == '3' ? 'checked' : '' ?> type="radio" name="q<?= $c; ?>" value="3" ></td>
                                                                                    <td><textarea class="form-control" name="r<?= $c; ?>" rows="2" id="example-textarea"><?= htmlspecialchars($remarksVal) ?></textarea></td>
                                                                                </tr>
                                                                                <?php } ?>
                                                                                
                                                                                
                                                                                
                                                                                
                                                                            </tbody>
                                                                        </table>

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                
                                  
                                    
                                </div>

                                

                            </div>
                            
                        </div>
                        <!-- end row -->
                         <?php if($this->session->position == 'School'){?>
                        <div class="form-group text-left mb-0">
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                        </div>
                        <?php } ?>

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

                



                <script>
                // Get all the checkboxes with the same name
                const checkboxes = document.querySelectorAll('input[name="option"]');

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        // If a checkbox is checked, uncheck others in the same group
                        checkboxes.forEach((otherCheckbox) => {
                            if (otherCheckbox !== checkbox) {
                                otherCheckbox.checked = false;
                            }
                        });
                    });
                });
            </script>                           





            <?php include('templates/footer.php'); ?>


            