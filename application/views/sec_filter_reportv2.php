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

        <!-- Plugins css-->
        <link href="<?= base_url(); ?>assets/css/renren.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="filter">
        
        <?php if($this->uri->segment(4) == 1){
            echo form_open('Page/report_adminv2');
        }else{
            echo form_open('Page/reportv2');
        }?>

    <?php if($this->uri->segment(5) == 1){ ?>

        <label>Year</label>
        <input type="text" name="year" required class="form-control" > <br />

    <?php }else{ ?>
        <label>Quarter</label>
        <select class="form-control" name="quarter" required>
            <?php $quater = array("1st", "2nd", "3rd", "4th"); ?>
            <option></option>
            <?php 
              foreach($quater as $row){
                    echo '<option value="'.$row.'">'.$row.' Quarter</option>';
              }
            ?>
             
        </select> <br />
        <label>Year</label>
        <input type="text" name="year" required class="form-control" > <br />

        <?php if($this->uri->segment(3) == 2){ ?> 
        <label>Month</label>
        <select class="form-control" name="month" required>
            <option></option>
            <?php
                for($m=1; $m<=12; ++$m){  ?>
                <option value="<?= date('F', mktime(0, 0, 0, $m, 1)); ?>"><?= date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                <?php  } ?>
        </select>

        
        <label>Week</label>
            <select class="form-control" name="weekAcc" required>
                <option></option>
                <?php 
                for ($x = 1; $x <=5; $x++){
                    echo '<option value="'.$x.'"> Week '. $x.'</option>';
              }
            ?>
            </select> <br />
            <?php }else{ ?>
                <input type="hidden" name="month" class="form-control" >
                <input type="hidden" name="weekAcc" class="form-control" >
            <?php } ?>
         <label>Category</label>
        <select class="form-control" name="activityCategory" required>
            <option></option>
            <option value="accomplishment">Accomplishment</option> 
            <option value="updates">Updates</option> 
            <option value="all">All</option> 
        </select>

        
        
        <input type="hidden" name="sec" value="<?= urldecode($this->uri->segment('4')); ?>">

        <?php } ?>

        <input type="submit" value="Submit" name="submit">
                                                        
        </form>

    


    </body>
    </html>