

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
                                    <!-- <a href="#" class="btn btn-primary waves-effect waves-light openModalBtn"data-toggle="modal" data-target="#myModal">Add New</a> -->
                                    <div class="clearfix"></div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                    <h4 class="header-title mb-4"><?= $title; ?></h4>

                                    

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

                                         <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <?php foreach($brigada as $row) { ?>
                                                    <th style="width:120px;"><?= date('M d, Y', strtotime($row->c_date)); ?></th>
                                                    <?php } ?>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                                <tr>
                                                    <th style="width:200px;">Resource Generated</th>
                                                    <?php $rg=0; foreach ($brigada as $row) { ?>
                                                    <td><?= $row->amount; ?></td>
                                                    <?php $rg += $row->amount; } ?>
                                                    <th><?= $rg; ?></th>
                                                </tr>
                                                
                                                <tr>
                                                    <th style="width:160px;">No. of Volunteers</th>
                                                    <?php $nv=0; foreach ($brigada as $row) { ?>
                                                    <td><?= $row->quantity_of_conftribution; ?></td>
                                                    <?php $nv += $row->quantity_of_conftribution; } ?>
                                                    <th><?= $nv; ?></th>
                                                </tr>
                                              
                                            <tbody>
                                                
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
                 

                
                                        
                            

   

 