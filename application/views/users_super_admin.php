<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title" id="myLargeModalLabel">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal">+ Add Admin</button>
                                    </h4>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <?php if($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4"><i class="mdi mdi-account-group-outline"></i> Admin Accounts</h4>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Identifier</th>
                                                        <th>Status</th>
                                                        <th style="text-align:center">Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data as $row){ ?>
                                                        <tr>
                                                            <td><?= $row->lName.', '.$row->fName; ?></td>
                                                            <td><?= $row->username; ?></td>
                                                            <td><?= $row->secGroup; ?> Admin</td>
                                                            <td><?= $row->acctStat; ?></td>
                                                            <td style="text-align:center">
                                                                <?php if($row->acctStat == 'Active'){ ?>
                                                                    <a href="<?= base_url(); ?>Page/deactivate_user?username=<?= $row->username; ?>&status=Inactive" onclick="return confirm('Are you sure you want to deactivate this user?')"><button type="button" class="btn btn-warning btn-sm">Deactivate</button></a>
                                                                <?php } else { ?>
                                                                    <a href="<?= base_url(); ?>Page/deactivate_user?username=<?= $row->username; ?>&status=Active" onclick="return confirm('Are you sure you want to activate this user?')"><button type="button" class="btn btn-success btn-sm">Activate</button></a>
                                                                <?php } ?>
                                                                <a href="<?= base_url(); ?>Page/reset_password?username=<?= $row->username; ?>" onclick="return confirm('Are you sure you want to reset password to default (123456)?')"><button type="button" class="btn btn-info btn-sm">Reset Password</button></a>
                                                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#changePasswordModal<?= $row->username; ?>">Change Password</button>
                                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $row->username; ?>">Edit</button>
                                                                <a href="delete_account?id=<?= $row->username; ?>" onclick="return confirm('Are you sure you want to delete this account?')"><button type="button" class="btn btn-danger btn-sm">Delete</button></a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Add Admin Account</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form method="post">
                                    <div class="form-group col-md-12">
                                        <label>First Name</label>
                                        <input type="text" name="fName" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Last Name</label>
                                        <input type="text" name="lName" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>E-mail/Username</label>
                                        <input type="text" name="email" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Identifier <span class="text-danger">*</span></label>
                                        <select class="form-control" name="secGroup" required>
                                            <option value=""></option>
                                            <?php foreach($adminGroups as $group){ ?>
                                                <option value="<?= $group; ?>"><?= $group; ?> Admin</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="Submit" >
                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <?php foreach($data as $row){ ?>
                    <div id="editModal<?= $row->username; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Admin Account</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="<?= base_url(); ?>Page/update_user">
                                        <div class="form-group col-md-12">
                                            <label>First Name</label>
                                            <input type="text" name="fName" class="form-control" value="<?= $row->fName; ?>" required />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Last Name</label>
                                            <input type="text" name="lName" class="form-control" value="<?= $row->lName; ?>" required />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>E-mail/Username</label>
                                            <input type="text" name="email" class="form-control" value="<?= $row->email; ?>" required />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Password (leave blank to keep current)</label>
                                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Identifier <span class="text-danger">*</span></label>
                                            <select class="form-control" name="secGroup" required>
                                                <?php foreach($adminGroups as $group){
                                                    $selected = ($group == $row->secGroup) ? 'selected' : '';
                                                    echo "<option value='$group' $selected>$group Admin</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="section" value="System Administrator" />
                                        <input type="hidden" name="username" value="<?= $row->username; ?>" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <input type="submit" name="submit" class="btn btn-primary" value="Update" >
                                </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php foreach($data as $row){ ?>
                    <div id="changePasswordModal<?= $row->username; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel<?= $row->username; ?>" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="changePasswordModalLabel<?= $row->username; ?>">Change Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="<?= base_url(); ?>Page/change_user_password">
                                        <div class="form-group col-md-12">
                                            <label>User</label>
                                            <input type="text" class="form-control" value="<?= $row->lName.', '.$row->fName; ?>" readonly />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>New Password</label>
                                            <input type="password" name="new_password" class="form-control" minlength="8" required />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" minlength="8" required />
                                        </div>
                                        <input type="hidden" name="username" value="<?= $row->username; ?>" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-primary" value="Change Password" >
                                </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>
    </body>
</html>
