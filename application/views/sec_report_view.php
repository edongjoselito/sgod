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

    <body class="report">

        <div class="coverpage">
            <div class="logocenter">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
            <h1>SCHOOL GOVERNANCE AND<br /> OPERATIONS DIVISION</h1>
            <?php if(isset($acc)){?>
            <h2>Presentation of Accomplishments<br /> and Weekly Updates</h2>
            <h3><?php if(strlen($q)==1){ echo $r.' '. $q.', '.$acc->monthAcc;}else{echo $q.' '. $r.', FY';} ?> <?= $acc->year; ?></h3>
           
        </div>



        <?php if($cat == "updates"){?>

        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $acc->section; ?></h1>
        <h2>Updates</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Target Date</th>
                    <th>Actual Date</th>
                    <th>Particulars</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($update as $row){?>
                    <tr>
                        <td><?= $row['activity']; ?></td>
                        <td><?= $row['targetDate']; ?></td>
                        <td><?= $row['dateConducted']; ?></td>
                        <td><?= $row['particulars']; ?></td>
                    </tr>
                <?php }  ?>                             
            </tbody>
        </table>
            
        </div>
        <?php }elseif($cat == "accomplishment"){?>


        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $acc->section; ?></h1>
        <h2>Accomplishments</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Target Date</th>
                    <th>Actual Date</th>
                    <th>Particulars</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($accomplish as $row){?>
                    <tr>
                        <td><?= $row['activity']; ?></td>
                        <td><?= $row['targetDate']; ?></td>
                        <td><?= $row['dateConducted']; ?></td>
                        <td><?= $row['particulars']; ?></td>
                    </tr>
                <?php } ?>                              
            </tbody>
        </table>
        </div>
        <?php }else{ ?>
        <?php if(!empty($accomplish)){ ?>
            <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $acc->section; ?></h1>
        <h2>Accomplishments</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Target Date</th>
                    <th>Actual Date</th>
                    <th>Particulars</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($accomplish as $row){?>
                    <tr>
                        <td><?= $row['activity']; ?></td>
                        <td><?= $row['targetDate']; ?></td>
                        <td><?= $row['dateConducted']; ?></td>
                        <td><?= $row['particulars']; ?></td>
                    </tr>
                <?php  } ?>                             
            </tbody>
        </table>
            
        </div>
    <?php } ?>
    <?php if(!empty($update)){ ?>
        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $acc->section; ?></h1>
        <h2>Updates</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Target Date</th>
                    <th>Actual Date</th>
                    <th>Particulars</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($update as $row){?>
                    <tr>
                        <td><?= $row['activity']; ?></td>
                        <td><?= $row['targetDate']; ?></td>
                        <td><?= $row['dateConducted']; ?></td>
                        <td><?= $row['particulars']; ?></td>
                    </tr>
                <?php } ?>                              
            </tbody>
        </table>
        </div>
        <?php } }  ?>

        <?php }else{ ?>
            <h1 class="nr">NO RECORDS FOUND.</h1>

            </div>
        <?php } ?>

        

        





    </body>
    </hmlm>