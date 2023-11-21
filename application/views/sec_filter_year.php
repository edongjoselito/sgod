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

        
        <?php if($this->uri->segment(3) == 1){
            echo form_open('Page/report_sfy');
        }else{
            echo form_open('Page/reportv2');
        }?>
        
        <label>Year</label>
        <input type="text" name="year" required class="form-control" > <br /><br />

        <select class="form-control" name="activityCategory" required>
            <option></option>
            <option value="accomplishment">Accomplishment</option> 
            <option value="updates">Updates</option> 
            <option value="all">All</option> 
        </select>

        <input type="submit" value="Submit" name="submit">
                                                        
        </form>

    


    </body>
    </html>