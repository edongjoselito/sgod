
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


                        <div class="row">
                            <div class="col-lg-12">
                                <div id="accordion" class="mb-3">
                                    <?php $i = 0; foreach ($district as $row) { $i++; 
                                    $school = $this->Common->two_cond_select('schools','schoolName,district,schoolID,schoolType','district',$row->discription,'schoolType','Public');
                                    ?>
                                        <div class="card mb-0">
                                            <div class="card-header" id="heading<?= $i; ?>">
                                                <h6 class="m-0">
                                                    <a href="#collapse<?= $i; ?>"
                                                    class="text-dark <?= ($i != 1) ? 'collapsed' : ''; ?>"
                                                    data-toggle="collapse"
                                                    aria-expanded="<?= ($i == 1) ? 'true' : 'false'; ?>"
                                                    aria-controls="collapse<?= $i; ?>">
                                                        <?= $row->discription; ?>
                                                    </a>
                                                </h6>
                                            </div>

                                            <div id="collapse<?= $i; ?>"
                                                class="collapse <?= ($i == 1) ? 'show' : ''; ?>"
                                                aria-labelledby="heading<?= $i; ?>"
                                                data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="table-responsive">
                                                            <table class="table mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>School Name</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $ivy=1; foreach($school as $row){?>
                                                                    <tr>
                                                                        <th scope="row"><?= $ivy++; ?></th>
                                                                        <td><a href="<?= base_url(); ?>Brigada/contribution_report_admin/<?= $row->schoolID; ?>"><?= $row->schoolName; ?></a></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                   
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
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

                



            