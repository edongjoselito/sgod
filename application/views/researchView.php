<?php include('templates/head.php'); ?>
<?php include('templates/header.php'); ?>

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-md-12">
          <div class="page-title-box">
            <h4 class="page-title">
              <a href="<?= base_url('Research/create'); ?>">
                <button type="button" class="btn btn-info waves-effect waves-light">
                  <i class="fas fa-user-plus mr-1"></i> <span>Add New</span>
                </button>
              </a>
            </h4>
            <div class="page-title-right">
              <ol class="breadcrumb p-0 m-0"></ol>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="row">
        <div class="col-lg-12 col-sm-6">
          <div class="card">
            <div class="card-header bg-info py-3 text-white">
              <div class="card-widgets">
                <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-toggle="collapse" href="#cardCollpase3" role="button" aria-expanded="true" aria-controls="cardCollpase3">
                  <i class="mdi mdi-minus"></i>
                </a>
              </div>
            </div>

            <div id="cardCollpase3" class="collapse show">
              <div class="card-body">

                <?php if ($this->session->flashdata('success')): ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= $this->session->flashdata('success'); ?>
                  </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('danger')): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= $this->session->flashdata('danger'); ?>
                  </div>
                <?php endif; ?>

                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width:100%;">
                  <thead>
                    <tr>
                      <th style="width:160px">Control No.</th>
                      <th style="width:110px">Date</th>
                      <th style="width:260px">Requested By</th>
                      <th style="width:140px">Status</th>
                      <th style="text-align:center; width:320px">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($data as $row): ?>
                      <?php
                        $requestedBy = !empty($row->created_by_name) ? $row->created_by_name : $row->created_by;
                      ?>
                      <tr>
                        <td><b><?= htmlspecialchars($row->control_no); ?></b></td>
                        <td><?= htmlspecialchars($row->request_date); ?></td>
                        <td><?= htmlspecialchars($requestedBy); ?></td>
                        <td><?= htmlspecialchars($row->status); ?></td>

                        <td style="text-align:center">
                          <a href="<?= base_url('Research/report/'.$row->id); ?>" target="_blank" class="text-info mr-3">
                            <i class="mdi mdi-file-document-box-check-outline"></i> Temp. Permit
                          </a>

                          <a href="<?= base_url('Research/edit/'.$row->id); ?>" class="text-warning mr-3">
                            <i class="mdi mdi-pencil"></i> Edit
                          </a>

                          <a href="<?= base_url('Research/delete/'.$row->id); ?>" class="text-danger"
                             onclick="return confirm('Are you sure you want to delete this record?')">
                            <i class="mdi mdi-delete"></i> Delete
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

<?php include('templates/footer.php'); ?>
