<!doctype html>
<html lang="en">
   <head>
		<meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="LR Hub - DepEd Region XI" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

<body class="authentication-page">

        <div class="account-pages my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-header p-4 bg-primary">
                                <!-- <h4 class="text-white text-center mb-0 mt-0">DepEd Payroll System</h4> -->
                                <h4 class="text-white text-center mb-0 mt-0"><img  width="100%" src="<?= base_url(); ?>assets/images/logo-light.png" alt=""></h4>
                                
                            </div>
                            <div class="card-body">
                                <!-- <?php echo $this->session->flashdata('msg'); ?> -->

                                <?php if($this->session->flashdata('msg')) : ?>
                                        <?= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>'
                                                .$this->session->flashdata('msg'). 
                                            '</div>'; 
                                        ?>
                                        <?php endif;  ?>

							<form action="<?php echo site_url('Login/auth');?>" method="post" class="mt-4">

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Username/Email</label>
                                        <input class="form-control" name="username" id="emailaddress" required="">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password :</label>
                                        <input class="form-control" type="password" required="" name="password">
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="checkbox checkbox-success">
                                            <input id="remember" type="checkbox" checked="">
                                            <label for="remember">
                                                Remember me
                                            </label>
                                                <a href="#" data-toggle="modal" data-target="#forgotModal" class="text-muted float-right">Forgot your password?</a>
                                        </div>
                                    </div>

                                    <div class="form-group row text-center mt-4 mb-4">
                                        <div class="col-12">
                                           	<button type="submit" class="btn btn-md btn-block btn-primary waves-effect waves-light" type="submit">Sign In</button>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group row mb-0">
                                        <div class="col-sm-12 text-center">
                                            <p class="text-muted mb-0">Don't have an account? <a href="<?= base_url(); ?>Login/registration" class="text-dark m-l-5"><b>Sign Up</b></a></p>
                                        </div>
                                    </div> -->
                                  
                                </form>

                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <!-- end row -->

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
        </div>

        <!-- Vendor js -->
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    </body>
<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" style="color:black">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="forgotModalLabel">Forgot Password</h4>
            </div>
            <div class="modal-body">         
				 <form id="resetPassword" name="resetPassword" method="post" action="<?php echo base_url();?>login/forgot_pass" onsubmit ='return validate()'>
						<div class="input-group mb-3">
						  <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required >
						  <div class="input-group-append">
							<div class="input-group-text">
							  <span class="fas fa-envelope"></span>
							</div>
						  </div>
						</div>
						<div class="row">
						  <div class="col-12">
							<input type = "submit" value="Request a New Password" class="btn btn-primary btn-block name="forgot_pass">
						  </div>
						  <!-- /.col -->
						</div>
				</form> 
                       
                            
                </div>  
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            
            </div>
            </div>
        </div>
        </div>
        <!-- End Forgot Password Modal -->
</html>