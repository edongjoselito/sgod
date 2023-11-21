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

            <?php if(isset($acc)){ ?>

            <h1>SCHOOL GOVERNANCE AND<br /> OPERATIONS DIVISION</h1>
            
            <h2><?= $r.$rr; ?> Updates and Presentation of<br /> Accomplishments</h2>
            <h3><?php if(strlen($q)==1){ echo $r.' '. $q.', '.$acc->monthAcc;}else{echo $q.' '. $r.', FY';} ?> <?= $acc->year; ?></h3>
          
        </div>


        <?php if($cat == "updates"){?>

        <?php foreach($update as $row){ ?>
        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $row->section; ?></h1>
        <h2>Updates</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th> 
                    <th>Activity</th> 
                    <th>Performance Indicators</th> 
                    <th>Target</th> 
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $up = $this->SGODModel->get_all_acc_by_section($row->activityCategory, $row->quarter, $row->year, $row->weekAcc, $row->monthAcc, $row->section);
            foreach($up as $row){?>
                    <tr>
                        <td><?= $row->targetDate; ?></td>
                        <td><?= $row->activity; ?></td>
                        <td><?= $row->perIndicators; ?></td>
                        <td><?= $row->target; ?></td>
                        <td><?= $row->remarks; ?></td>
                    </tr> 
                <?php  } ?>                          
            </tbody>
        </table>
        </div>
        <?php } ?>
        <?php }elseif($cat == "accomplishment"){?>

        <?php foreach($accomplish as $row){ ?>
        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $row->section; ?></h1>
        <h2>Accomplishments</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th> 
                    <th>Activity</th> 
                    <th>Performance Indicators</th> 
                    <th>Target</th> 
                    <th>Achieved</th> 
                    <th>% of Accomplishment</th> 
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $comm = $this->SGODModel->get_all_acc_by_section($row->activityCategory, $row->quarter, $row->year, $row->weekAcc, $row->monthAcc, $row->section);
            foreach($comm as $row){?>
                    <tr>
                        <td><?= $row->targetDate; ?></td>
                        <td><?= $row->activity; ?></td>
                        <td><?= $row->perIndicators; ?></td>
                        <td><?= $row->target; ?></td>
                        <td><?= $row->achieved; ?></td>
                        <td><?= $row->percentageAccom; ?></td>
                        <td><?= $row->remarks; ?></td>
                    </tr> 
                <?php  } ?>                            
            </tbody>
        </table>
        </div>
        <?php } ?>
        <?php }else{ ?>

            <?php 
                foreach($sections as $row){?>
         <?php 
            $comm = $this->SGODModel->get_all_acc_by_section('Accomplishment', $row->quarter, $row->year, $row->weekAcc, $acc->monthAcc, $row->section);
            if(!empty($comm)){
        ?>        
        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $row->section; ?></h1>
        <h2>Accomplishments</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th> 
                    <th>Activity</th> 
                    <th>Performance Indicators</th> 
                    <th>Target</th> 
                    <th>Achieved</th> 
                    <th>% of Accomplishment</th> 
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($comm as $row){?>
                    <tr>
                        <td><?= $row->targetDate; ?></td>
                        <td><?= $row->activity; ?></td>
                        <td><?= $row->perIndicators; ?></td>
                        <td><?= $row->target; ?></td>
                        <td><?= $row->achieved; ?></td>
                        <td><?= $row->percentageAccom; ?></td>
                        <td><?= $row->remarks; ?></td>
                    </tr> 
                <?php  }  ?>                            
            </tbody>
        </table>
            
        </div>
        <?php } ?>
        <?php 
           $up = $this->SGODModel->get_all_acc_by_section('Updates', $row->quarter, $row->year, $row->weekAcc, $row->monthAcc, $row->section);
            if(!empty($up)){
        ?> 

        <div class="pagecontent">
        <div class="logoleft">
                <img src="<?= base_url(); ?>assets/images/report/matatag.png" />
                <img src="<?= base_url(); ?>assets/images/report/deped.png" />
                <img src="<?= base_url(); ?>assets/images/report/davor.png" />
            </div>
        <h1><?= $row->section; ?></h1>
        
        <h2>Updates</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th> 
                    <th>Activity</th> 
                    <th>Performance Indicators</th> 
                    <th>Target</th> 
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($up as $row){?>
                    <tr>
                        <td><?= $row->targetDate; ?></td>
                        <td><?= $row->activity; ?></td>
                        <td><?= $row->perIndicators; ?></td>
                        <td><?= $row->target; ?></td>
                        <td><?= $row->remarks; ?></td>
                    </tr> 
                <?php  } ?>                        
            </tbody>
        </table>
        </div>
        <?php } ?>
                


        
        <?php } } ?>

        <?php }else{ ?>
            <h1 class="nr">NO RECORDS FOUND.</h1>

            </div>
        <?php } ?>

        





    </body>
    </hmlm>