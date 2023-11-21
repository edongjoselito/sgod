<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/renren.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="presentation">
        <div class="">
            <?= form_open(''); ?>
            <select name="year">
                <option>2023</option>
            </select>
            </form>
            
        </div>
        
        
        <div class="section">
            <img src="<?= base_url().'assets/images/pre-logo-m.png'?>" />
            <h1><?= $section; ?></h1>
        </div>

        <div class="activity">
            <?php foreach($acc as $row){ ?>

                <div class="act">
                    <h1><?= $row['activity']; ?></h1>
                    <?php 
		            $image=$this->SGODModel->get_all_data_where_single('sgod_acc_image','acc_id',$row['id']);
                    foreach($image as $img){ ?>
                        <img src="<?= base_url().'upload/'. $img['file']; ?>" />          
                    <?php } ?>

                </div>
           <?php } ?>
        </div>

        




    </body>
</html>