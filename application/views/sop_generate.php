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


    <body class="aip_generate" id="printTable">

    <img class="logo" src="<?= base_url(); ?>assets/images/report/ke.png" alt="">
    <p>
        Republic of the Philippines<br />
        Department of Education<br />
        Region XI<br />
        Division of Davao Oriental<br />
        <?= strtoupper($school->district); ?><br />
         <?= strtoupper($school->schoolName); ?> <?= strtoupper($school->course); ?> SCHOOL<br />
         <?= strtoupper($school->sitio); ?>, <?= strtoupper($school->brgy); ?>, <?= strtoupper($school->city); ?>, <?= strtoupper($school->province); ?>
    </p>
    <div class="hr"></div>
    <h1>SCHOOL OPERATIONAL PLAN<br />FY <?= $data_row->fy; ?></h1>


    <?php foreach($data as $row){ ?>
    <ul>
        <li>PILLAR: <?= $row->pillar; ?></li>
        <li>DOMAIN: <?= $row->domain; ?></li>	
        <li>STRAND: <?= $row->strand; ?></li>
        <li>PIAs:  <?= $row->pia; ?></li>
    </ul>

   

    <table>
        <thead>
            <tr>
                <th rowspan="3">SCHOOL IMPROVEMENT PROJECT TITLE</th>
                <th rowspan="3">PROJECT OBJECTIVE</th>
                <th rowspan="3">OUTPUT OF THE YEAR</th>
                <th rowspan="3">STRATEGY ACTIVITY</th>
                <th rowspan="3">PERFORMANCE INDICATORS</th>
                <th rowspan="3">MOV’S</th>
                <th rowspan="3">PERSON/SRESPONSIBLE</th>
                <th rowspan="3">SCHEDULE</th>
                <th rowspan="3">VENUE</th>
                <th rowspan="3">BUDGET PER ACTIVITY</th>
                <th rowspan="3">BUDGET SOURCE</th>
                <th rowspan="3">MATERIALS</th>
                <th colspan="5">PHYSICAL TARGET</th>
                <th colspan="5">FINANCIAL TARGET (MOOE)</th>
                <th colspan="5">FINANCIAL TARGET (OTHER SOURCES OF FUND)</th>
            </tr>
            <tr>
                <th colspan="4">QUARTER</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="4">QUARTER</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="4">QUARTER</th>
                <th rowspan="2">TOTAL</th>
            </tr>
            <tr>
                <th>1ST</th>
                <th>2ND</th>
                <th>3RD</th>
                <th>4TH</th>
                <th>1ST</th>
                <th>2ND</th>
                <th>3RD</th>
                <th>4TH</th>
                <th>1ST</th>
                <th>2ND</th>
                <th>3RD</th>
                <th>4TH</th>
            </tr>
        </thead>   
        <tbody >
            <?php 
            $pia = $this->SGODModel->get_all_aip($row->school_id, $row->fy, $row->pia, $row->b_code);
            $sp = null; 
            foreach($pia as $row){ ?>
            <tr>
            <?php if($sp !== $row->sip_project) { ?>
                    
                    <td><?= $row->sip_project; ?></td>
                    <td><?= $row->sip_pObjective; ?></td>
                    <td><?= $row->sip_output; ?></td>
                    <td><?= $row->strategy; ?></td>
                    <td><?= $row->pi; ?></td>
                    <td><?= $row->movs; ?></td>
                    <td><?= $row->pr; ?></td>
                    <?php }else{ ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?= $row->strategy; ?></td>
                        <td><?= $row->pi; ?></td>
                        <td><?= $row->movs; ?></td>
                        <td></td>
                        
                    <?php } ?>
                    <?php $sp = $row->sip_project; ?>
                <td><?= $row->schedule; ?></td>
                <td><?= $row->venue; ?></td>
                <td><?= $row->budget; ?></td>
                <td><?= $row->budget_source; ?></td>
                <td><?= $row->materials; ?></td>
                <?php 
                  $pt = $this->SGODModel->two_cond_row('sgod_sop', 'aip_id',$row->id,'type',1);
                  if(!empty($pt)){
                ?>
                <td><?= $pt->q1; ?></td>
                <td><?= $pt->q2; ?></td>
                <td><?= $pt->q3; ?></td>
                <td><?= $pt->q4; ?></td>
                <td><?= $pt->total; ?></td>
                <?php }else{?>
                    <td></td><td></td><td></td><td></td><td></td>
                <?php } ?>

                <?php 
                  $ft = $this->SGODModel->two_cond_row('sgod_sop', 'aip_id',$row->id,'type',2);
                  if(!empty($ft)){
                ?>
                <td><?= $ft->q1; ?></td>
                <td><?= $ft->q2; ?></td>
                <td><?= $ft->q3; ?></td>
                <td><?= $ft->q4; ?></td>
                <td><?= $ft->total; ?></td>
                <?php }else{?>
                    <td></td><td></td><td></td><td></td><td></td>
                <?php } ?>


                <?php 
                  $fto = $this->SGODModel->two_cond_row('sgod_sop', 'aip_id',$row->id,'type',3);
                  if(!empty($fto)){
                ?>
                <td><?= $fto->q1; ?></td>
                <td><?= $fto->q2; ?></td>
                <td><?= $fto->q3; ?></td>
                <td><?= $fto->q4; ?></td>
                <td><?= $fto->total; ?></td>
                <?php }else{?>
                    <td></td><td></td><td></td><td></td><td></td>
                <?php } ?>

            </tr>
            <?php } ?>
           
        </tbody> 
    </table>
    <?php } ?>







    </body>
    </hmlm>