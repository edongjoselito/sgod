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
         <?= strtoupper($school->schoolName); ?> <?= strtoupper($school->course); ?><br />
         <?= strtoupper($school->sitio); ?>, <?= strtoupper($school->brgy); ?>, <?= strtoupper($school->city); ?>, <?= strtoupper($school->province); ?>
    </p>
    <div class="hr"></div>
    <h1>ANNUAL IMPLEMENTATION PLAN<br />FY <?= $data_row->fy; ?></h1>
                                        
                                        
                                        

    <?php foreach($data as $row){ ?>
    <ul>
        <li>PILLAR : <?= $row->pillar; ?></li>
        <li>DOMAIN : <?= $row->domain; ?></li>	
        <li>STRAND : <?= $row->strand; ?></li>
        <li>PIAs:  <?= $row->pia; ?></li>
    </ul>

    <table>
        <thead>
            <tr>
                <th>SCHOOL IMPROVEMENT PROJECT TITLE</th>
                <th>PROJECT OBJECTIVE</th>
                <th>OUTPUT OF THE YEAR</th>
                <th>STRATEGY ACTIVITY</th>
                <th>PERFORMANCE INDICATORS</th>
                <th>MOV’S</th>
                <th>PERSON/SRESPONSIBLE</th>
                <th>SCHEDULE</th>
                <th>VENUE</th>
                <th>BUDGET PER ACTIVITY</th>
                <th>BUDGET SOURCE</th>
                <th>MATERIALS</th>
            </tr>
        </thead>   
        <tbody >
            <?php 
            $pia = $this->SGODModel->get_all_aip($row->school_id, $row->fy, $row->pia,$row->b_code);
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
                    <td><?= $row->sip_pObjective; ?></td>
                    <td><?= $row->sip_output; ?></td>
                    <td><?= $row->strategy; ?></td>
                    <td><?= $row->pi; ?></td>
                    <td><?= $row->movs; ?></td>
                    <td></td>
                    
                <?php } ?>
                <?php $sp = $row->sip_project; ?>

                <?php $prd = $row->pr; ?>
                <td><?= $row->schedule; ?></td>
                <td><?= $row->venue; ?></td>
                <td><?= $row->budget; ?></td>
                <td><?= $row->budget_source; ?></td>
                <td class="mat"><?= $row->materials; ?></td>
            </tr>
            <?php } ?>
           
        </tbody> 
    </table>
    <?php } ?>







    </body>
    </hmlm>