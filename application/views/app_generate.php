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

    
    <div class="at">
    
   
    </div>
   


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
    <h1><?= $title; ?><br />FY <?= $fy; ?> </h1>

    <?php if($aip_sum->budget >= $budget->alloc_amount){echo "<h1 class='exceed'>You have exceeded to the allocated budget.</h1>"; }?> 

 

    <table width="100%">
        <?php 
            $mooe = $ssa->alloc_amount; 
            $mandatory = $mooe*.20;
            $minor = $mooe*.30;
            $tli = $mooe*.25;
            $ac = $mooe*.25;
            $monthly = $mooe/12;
            $quarterly = $monthly*3;
        ?>
        <thead>
            <tr>
            <th colspan="21" class="nobc"></th>
            <th colspan="2">Monthly</th>
            </tr>
            <tr>
                <th colspan="2">Source of Fund:</th>
                <th colspan="8" class="nobc"> MOOE</th>
                <th colspan="8">Mandatory   (20%):</th>
                <th colspan="3" class="nobc"><?= number_format(($mandatory), 2, '.', ','); ?></th>
                <th colspan="2" class="nobc"><?= number_format(($monthly*.20), 2, '.', ','); ?></th>
            </tr>
            <tr>
                <th colspan="2">Annual Amount:</th>
                <th colspan="8" class="nobc"><?= number_format(($mooe), 2, '.', ','); ?></th>
                <th colspan="8">Minor Reapir (30%)</th>
                <th colspan="3" class="nobc"><?= number_format(($minor), 2, '.', ','); ?></th>
                <th colspan="2" class="nobc"><?= number_format(($monthly*.30), 2, '.', ','); ?></th>
            </tr>
            <tr>
                <th colspan="2">Monthly</th>
                <th colspan="8" class="nobc"><?= number_format(($monthly), 2, '.', ','); ?></th>
                <th colspan="8">Teaching-Learning Instructions (25%)</th>
                <th colspan="3" class="nobc"><?= number_format(($tli), 2, '.', ','); ?></th>
                <th colspan="2" class="nobc"><?= number_format(($monthly*.25), 2, '.', ','); ?></th>
            </tr>
            <tr>
                <th colspan="2">Quarterly</th>
                <th colspan="8" class="nobc"><?= number_format(($quarterly), 2, '.', ','); ?></th>
                <th colspan="8">Attendance to & Conduct of Trainings/Seminars/Conferences (25%) </th>
                <th colspan="3" class="nobc"><?= number_format(($ac), 2, '.', ','); ?></th>
                <th colspan="2" class="nobc"><?= number_format(($monthly*.25), 2, '.', ','); ?></th>
            </tr>
            
            
        </thead>   
        <tbody >
            <tr>
                <td rowspan="2">No.</td>
                <td rowspan="2">ITEM & DESCRIPTION</td>
                <td rowspan="2">UNIT PRICE</td>
                <td rowspan="2">Quantity</td>
                <td rowspan="2">Unit Measure</td>
                <td rowspan="2">Budget Allocated</td>
                <td colspan="4">1st Quarter</td>
                <td colspan="4">2nd Quarter</td>
                <td colspan="4">3rd Quarter</td>
                <td colspan="4">4rth Quarter</td>
                <td>Total</td>
            </tr>
            <tr>
                <td>Jan.</td>
                <td>Feb.</td>
                <td>Mar.</td>
                <td>Total</td>
                <td>April</td>
                <td>May</td>
                <td>June</td>
                <td>Total</td>
                <td>July</td>
                <td>Aug.</td>
                <td>sep.</td>
                <td>Total</td>
                <td>oct.</td>
                <td>Nov.</td>
                <td>Dec.</td>
                <td>Total</td>
                <td>Amount</td>
            </tr>
            <tr>
                <td colspan="23" class="bar">I. MANDATORY   ( 20%)</td>
            </tr>
            <?php 
                $aip_by_pillar = $this->SGODModel->aip('sgod_aip',$school_id,$fy,$b_code,'MANDATORY BILLS');
                $otba = 0;
                $otjan = 0;
                $otfeb = 0;
                $otmar = 0;
                $otapril = 0;
                $otmay = 0;
                $otjune = 0;
                $otjuly = 0;
                $otaug = 0;
                $otsept = 0;
                $otoct = 0;
                $otnov = 0;
                $otdec = 0;
                $otfq = 0;
                $otsq = 0;
                $ottq = 0;
                $otfrq = 0;
                foreach($aip_by_pillar as $row){
                    
            ?>
            <tr>
                <td><?= $row->pillar; ?></td>
                <td colspan="8" class="pt"><?= $row->sip_project; ?> / <?= $row->strategy; ?></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $ba = 0;
            $jan = 0;
            $feb = 0;
            $mar = 0;
            $april = 0;
            $may = 0;
            $june = 0;
            $july = 0;
            $aug = 0;
            $sept = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;
            $fq = 0;
            $sq = 0;
            $tq = 0;
            $frq = 0;
            foreach($get_app as $approw){ ?>
                
                <tr>
                    <td></td>
                    <td><?= $approw->materials; ?></td>
                    <td><?= $approw->unit_price; ?></td>
                    <td><?= $approw->quantity; ?></td>
                    <td><?= $approw->unit_measure; ?></td>
                    <td><?= number_format((int)$approw->budget_alloc); ?></td>
                    <td><?= number_format((int)$approw->jan); ?></td>
                    <td><?= number_format((int)$approw->feb); ?></td>
                    <td><?= number_format((int)$approw->mar); ?></td>
                    <td><?php $firstq = (int)$approw->jan+(int)$approw->feb+(int)$approw->mar; echo number_format($firstq); ?></td>
                    <td><?= number_format((int)$approw->april); ?></td>
                    <td><?= number_format((int)$approw->may); ?></td>
                    <td><?= number_format((int)$approw->june); ?></td>
                    <td><?php $secondq = (int)$approw->april+(int)$approw->may+(int)$approw->june; echo number_format($secondq); ?></td></td>
                    <td><?= number_format((int)$approw->july); ?></td>
                    <td><?= number_format((int)$approw->aug); ?></td>
                    <td><?= number_format((int)$approw->sept); ?></td>
                    <td><?php $threeq = (int)$approw->july+(int)$approw->aug+(int)$approw->sept;  echo number_format($threeq); ?></td></td>
                    <td><?= number_format((int)$approw->oct); ?></td>
                    <td><?= number_format((int)$approw->nov); ?></td>
                    <td><?= number_format((int)$approw->dec); ?></td>
                    <td><?php $fourthq = (int)$approw->oct+(int)$approw->nov+(int)$approw->dec; echo number_format($fourthq); ?></td></td>
                    <td><?= number_format((int)$approw->jan+(int)$approw->feb+(int)$approw->mar+(int)$approw->april+(int)$approw->may+(int)$approw->june+(int)$approw->july+(int)$approw->aug+(int)$approw->sept+(int)$approw->oct+(int)$approw->nov+(int)$approw->dec); ?></td>
                    <?php 
                        $ba += (int)$approw->budget_alloc; 
                        $jan += (int)$approw->jan; 
                        $feb += (int)$approw->feb; 
                        $mar += (int)$approw->mar; 
                        $april += (int)$approw->april; 
                        $may += (int)$approw->may; 
                        $june += (int)$approw->june; 
                        $july += (int)$approw->july; 
                        $aug += (int)$approw->aug; 
                        $sept += (int)$approw->sept; 
                        $oct += (int)$approw->oct; 
                        $nov += (int)$approw->nov; 
                        $dec += (int)$approw->dec; 
                        $fq += (int)$firstq;
                        $sq += (int)$secondq;
                        $tq += (int)$threeq;
                        $frq += (int)$fourthq;
                    ?>
                </tr>

            <?php } ?>
        
            <tr>
               <td></td>
               <td class="st">Total</td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"><?= number_format($ba); ?></td>
               <td class="st"><?= number_format($jan); ?></td>
               <td class="st"><?= number_format($feb); ?></td>
               <td class="st"><?= number_format($mar); ?></td>
               <td class="st"><?= number_format($fq); ?></td>
               <td class="st"><?= number_format($april); ?></td>
               <td class="st"><?= number_format($may); ?></td>
               <td class="st"><?= number_format($june); ?></td>
               <td class="st"><?= number_format($sq); ?></td>
               <td class="st"><?= number_format($july); ?></td>
               <td class="st"><?= number_format($aug); ?></td>
               <td class="st"><?= number_format($sept); ?></td>
               <td class="st"><?= number_format($tq); ?></td>
               <td class="st"><?= number_format($oct); ?></td>
               <td class="st"><?= number_format($nov); ?></td>
               <td class="st"><?= number_format($dec); ?></td>
               <td class="st"><?= number_format($frq); ?></td>
               <td class="st"><?= number_format($fq+$sq+$tq+$frq); ?></td>

            </tr>
            <?php
                $otba += (int)$ba;
                $otjan += (int)$jan; 
                $otfeb += (int)$feb; 
                $otmar += (int)$mar; 
                $otapril += (int)$april; 
                $otmay += (int)$may; 
                $otjune += (int)$june; 
                $otjuly += (int)$july; 
                $otaug += (int)$aug; 
                $otsept += (int)$sept; 
                $otoct += (int)$oct; 
                $otnov += (int)$nov; 
                $otdec += (int)$dec; 
                $otfq += (int)$fq;
                $otsq += (int)$sq;
                $ottq += (int)$tq;
                $otfrq += (int)$frq;
             ?>
        
        <?php } ?>

            <tr>
                <td class="t"></td>
                <td class="t">Total Mandatory Bills</td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"><?= number_format($otba); ?></td>
                <td class="t"><?= number_format($otjan); ?></td><?php $mbjan = $otjan; ?>
                <td class="t"><?= number_format($otfeb); ?></td><?php $mbfeb = $otfeb; ?>
                <td class="t"><?= number_format($otmar); ?></td><?php $mbmar = $otmar; ?>
                <td class="t"><?= number_format($otfq); ?></td><?php $mbfq = $otfq; ?>
                <td class="t"><?= number_format($otapril); ?></td><?php $mbapril = $otapril; ?>
                <td class="t"><?= number_format($otmay); ?></td><?php $mbmay = $otmay; ?>
                <td class="t"><?= number_format($otjune); ?></td><?php $mbjune = $otjune; ?>
                <td class="t"><?= number_format($otsq); ?></td><?php $mbsq = $otsq; ?>
                <td class="t"><?= number_format($otjuly); ?></td><?php $mbjuly = $otjuly; ?>
                <td class="t"><?= number_format($otaug); ?></td><?php $mbaug = $otaug; ?>
                <td class="t"><?= number_format($otsept); ?></td><?php $mbsept = $otsept; ?>
                <td class="t"><?= number_format($ottq); ?></td><?php $mbtq = $ottq; ?>
                <td class="t"><?= number_format($otoct); ?></td><?php $mboct = $otoct; ?>
                <td class="t"><?= number_format($otnov); ?></td><?php $mbnov = $otnov; ?>
                <td class="t"><?= number_format($otdec); ?></td><?php $mbdec = $otdec; ?>
                <td class="t"><?= number_format($otfrq); ?></td><?php $mbfrq = $otjan; ?>
                <td class="t"><?= number_format($otfq+$otsq+$ottq+$otfrq); ?></td></td><?php $mbtotal = $otfq+$otsq+$ottq+$otfrq; ?>
            </tr>


            <tr>
                <td colspan="23" class="bar">II. MINOR REPAIR     ( 30% )</td>
            </tr>
            <?php 
                $aip_by_pillar = $this->SGODModel->aip('sgod_aip',$school_id,$fy,$b_code,'MINOR REPAIR');
                $otba = 0;
                $otjan = 0;
                $otfeb = 0;
                $otmar = 0;
                $otapril = 0;
                $otmay = 0;
                $otjune = 0;
                $otjuly = 0;
                $otaug = 0;
                $otsept = 0;
                $otoct = 0;
                $otnov = 0;
                $otdec = 0;
                $otfq = 0;
                $otsq = 0;
                $ottq = 0;
                $otfrq = 0;
                foreach($aip_by_pillar as $row){
                    
            ?>
            <tr>
                <td><?= $row->pillar; ?></td>
                <td colspan="8" class="pt"><?= $row->sip_project; ?> / <?= $row->strategy; ?></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $ba = 0;
            $jan = 0;
            $feb = 0;
            $mar = 0;
            $april = 0;
            $may = 0;
            $june = 0;
            $july = 0;
            $aug = 0;
            $sept = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;
            $fq = 0;
            $sq = 0;
            $tq = 0;
            $frq = 0;
            foreach($get_app as $approw){ ?>
                
                <tr>
                    <td></td>
                    <td><?= $approw->materials; ?></td>
                    <td><?= $approw->unit_price; ?></td>
                    <td><?= $approw->quantity; ?></td>
                    <td><?= $approw->unit_measure; ?></td>
                    <td><?= number_format((int)$approw->budget_alloc); ?></td>
                    <td><?= number_format((int)$approw->jan); ?></td>
                    <td><?= number_format((int)$approw->feb); ?></td>
                    <td><?= number_format((int)$approw->mar); ?></td>
                    <td><?php $firstq = (int)$approw->jan+(int)$approw->feb+(int)$approw->mar; echo number_format($firstq); ?></td>
                    <td><?= number_format((int)$approw->april); ?></td>
                    <td><?= number_format((int)$approw->may); ?></td>
                    <td><?= number_format((int)$approw->june); ?></td>
                    <td><?php $secondq = (int)$approw->april+(int)$approw->may+(int)$approw->june; echo number_format($secondq); ?></td></td>
                    <td><?= number_format((int)$approw->july); ?></td>
                    <td><?= number_format((int)$approw->aug); ?></td>
                    <td><?= number_format((int)$approw->sept); ?></td>
                    <td><?php $threeq = (int)$approw->july+(int)$approw->aug+(int)$approw->sept;  echo number_format($threeq); ?></td></td>
                    <td><?= number_format((int)$approw->oct); ?></td>
                    <td><?= number_format((int)$approw->nov); ?></td>
                    <td><?= number_format((int)$approw->dec); ?></td>
                    <td><?php $fourthq = (int)$approw->oct+(int)$approw->nov+(int)$approw->dec; echo number_format($fourthq); ?></td></td>
                    <td><?= number_format((int)$approw->jan+(int)$approw->feb+(int)$approw->mar+(int)$approw->april+(int)$approw->may+(int)$approw->june+(int)$approw->july+(int)$approw->aug+(int)$approw->sept+(int)$approw->oct+(int)$approw->nov+(int)$approw->dec); ?></td>
                    <?php 
                        $ba += (int)$approw->budget_alloc; 
                        $jan += (int)$approw->jan; 
                        $feb += (int)$approw->feb; 
                        $mar += (int)$approw->mar; 
                        $april += (int)$approw->april; 
                        $may += (int)$approw->may; 
                        $june += (int)$approw->june; 
                        $july += (int)$approw->july; 
                        $aug += (int)$approw->aug; 
                        $sept += (int)$approw->sept; 
                        $oct += (int)$approw->oct; 
                        $nov += (int)$approw->nov; 
                        $dec += (int)$approw->dec; 
                        $fq += (int)$firstq;
                        $sq += (int)$secondq;
                        $tq += (int)$threeq;
                        $frq += (int)$fourthq;
                    ?>
                </tr>

            <?php } ?>
        
            <tr>
               <td></td>
               <td class="st">Total</td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"><?= number_format($ba); ?></td>
               <td class="st"><?= number_format($jan); ?></td>
               <td class="st"><?= number_format($feb); ?></td>
               <td class="st"><?= number_format($mar); ?></td>
               <td class="st"><?= number_format($fq); ?></td>
               <td class="st"><?= number_format($april); ?></td>
               <td class="st"><?= number_format($may); ?></td>
               <td class="st"><?= number_format($june); ?></td>
               <td class="st"><?= number_format($sq); ?></td>
               <td class="st"><?= number_format($july); ?></td>
               <td class="st"><?= number_format($aug); ?></td>
               <td class="st"><?= number_format($sept); ?></td>
               <td class="st"><?= number_format($tq); ?></td>
               <td class="st"><?= number_format($oct); ?></td>
               <td class="st"><?= number_format($nov); ?></td>
               <td class="st"><?= number_format($dec); ?></td>
               <td class="st"><?= number_format($frq); ?></td>
               <td class="st"><?= number_format($fq+$sq+$tq+$frq); ?></td>

            </tr>
            <?php
                $otba += (int)$ba;
                $otjan += (int)$jan; 
                $otfeb += (int)$feb; 
                $otmar += (int)$mar; 
                $otapril += (int)$april; 
                $otmay += (int)$may; 
                $otjune += (int)$june; 
                $otjuly += (int)$july; 
                $otaug += (int)$aug; 
                $otsept += (int)$sept; 
                $otoct += (int)$oct; 
                $otnov += (int)$nov; 
                $otdec += (int)$dec; 
                $otfq += (int)$fq;
                $otsq += (int)$sq;
                $ottq += (int)$tq;
                $otfrq += (int)$frq;
             ?>
        
        <?php } ?>

            <tr>
                <td class="t"></td>
                <td class="t">Total Minor Repair </td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"><?= number_format($otba); ?></td>
                <td class="t"><?= number_format($otjan); ?></td><?php $mrjan = $otjan; ?>
                <td class="t"><?= number_format($otfeb); ?></td><?php $mrfeb = $otfeb; ?>
                <td class="t"><?= number_format($otmar); ?></td><?php $mrmar = $otmar; ?>
                <td class="t"><?= number_format($otfq); ?></td><?php $mrfq = $otfq; ?>
                <td class="t"><?= number_format($otapril); ?></td><?php $mrapril = $otapril; ?>
                <td class="t"><?= number_format($otmay); ?></td><?php $mrmay = $otmay; ?>
                <td class="t"><?= number_format($otjune); ?></td><?php $mrjune = $otjune; ?>
                <td class="t"><?= number_format($otsq); ?></td><?php $mrsq = $otsq; ?>
                <td class="t"><?= number_format($otjuly); ?></td><?php $mrjuly = $otjuly; ?>
                <td class="t"><?= number_format($otaug); ?></td><?php $mraug = $otaug; ?>
                <td class="t"><?= number_format($otsept); ?></td><?php $mrsept = $otsept; ?>
                <td class="t"><?= number_format($ottq); ?></td><?php $mrtq = $ottq; ?>
                <td class="t"><?= number_format($otoct); ?></td><?php $mroct = $otoct; ?>
                <td class="t"><?= number_format($otnov); ?></td><?php $mrnov = $otnov; ?>
                <td class="t"><?= number_format($otdec); ?></td><?php $mrdec = $otdec; ?>
                <td class="t"><?= number_format($otfrq); ?></td><?php $mrfrq = $otjan; ?>
                <td class="t"><?= number_format($otfq+$otsq+$ottq+$otfrq); ?></td></td><?php $mrtotal = $otfq+$otsq+$ottq+$otfrq; ?>
            </tr>

            <tr>
                <td colspan="23" class="bar">III. TEACHING-LEARNING INSTRUCTION   (25% )</td>
            </tr>
            <?php 
                $aip_by_pillar = $this->SGODModel->aip('sgod_aip',$school_id,$fy,$b_code,'TEACHING-LEARNING INSTRUCTION');
                $otba = 0;
                $otjan = 0;
                $otfeb = 0;
                $otmar = 0;
                $otapril = 0;
                $otmay = 0;
                $otjune = 0;
                $otjuly = 0;
                $otaug = 0;
                $otsept = 0;
                $otoct = 0;
                $otnov = 0;
                $otdec = 0;
                $otfq = 0;
                $otsq = 0;
                $ottq = 0;
                $otfrq = 0;
                foreach($aip_by_pillar as $row){
                    
            ?>
            <tr>
                <td><?= $row->pillar; ?></td>
                <td colspan="8" class="pt"><?= $row->sip_project; ?> / <?= $row->strategy; ?></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $ba = 0;
            $jan = 0;
            $feb = 0;
            $mar = 0;
            $april = 0;
            $may = 0;
            $june = 0;
            $july = 0;
            $aug = 0;
            $sept = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;
            $fq = 0;
            $sq = 0;
            $tq = 0;
            $frq = 0;
            foreach($get_app as $approw){ ?>
                
                <tr>
                    <td></td>
                    <td><?= $approw->materials; ?></td>
                    <td><?= $approw->unit_price; ?></td>
                    <td><?= $approw->quantity; ?></td>
                    <td><?= $approw->unit_measure; ?></td>
                    <td><?= number_format((int)$approw->budget_alloc); ?></td>
                    <td><?= number_format((int)$approw->jan); ?></td>
                    <td><?= number_format((int)$approw->feb); ?></td>
                    <td><?= number_format((int)$approw->mar); ?></td>
                    <td><?php $firstq = (int)$approw->jan+(int)$approw->feb+(int)$approw->mar; echo number_format($firstq); ?></td>
                    <td><?= number_format((int)$approw->april); ?></td>
                    <td><?= number_format((int)$approw->may); ?></td>
                    <td><?= number_format((int)$approw->june); ?></td>
                    <td><?php $secondq = (int)$approw->april+(int)$approw->may+(int)$approw->june; echo number_format($secondq); ?></td></td>
                    <td><?= number_format((int)$approw->july); ?></td>
                    <td><?= number_format((int)$approw->aug); ?></td>
                    <td><?= number_format((int)$approw->sept); ?></td>
                    <td><?php $threeq = (int)$approw->july+(int)$approw->aug+(int)$approw->sept;  echo number_format($threeq); ?></td></td>
                    <td><?= number_format((int)$approw->oct); ?></td>
                    <td><?= number_format((int)$approw->nov); ?></td>
                    <td><?= number_format((int)$approw->dec); ?></td>
                    <td><?php $fourthq = (int)$approw->oct+(int)$approw->nov+(int)$approw->dec; echo number_format($fourthq); ?></td></td>
                    <td><?= number_format((int)$approw->jan+(int)$approw->feb+(int)$approw->mar+(int)$approw->april+(int)$approw->may+(int)$approw->june+(int)$approw->july+(int)$approw->aug+(int)$approw->sept+(int)$approw->oct+(int)$approw->nov+(int)$approw->dec); ?></td>
                    <?php 
                        $ba += (int)$approw->budget_alloc; 
                        $jan += (int)$approw->jan; 
                        $feb += (int)$approw->feb; 
                        $mar += (int)$approw->mar; 
                        $april += (int)$approw->april; 
                        $may += (int)$approw->may; 
                        $june += (int)$approw->june; 
                        $july += (int)$approw->july; 
                        $aug += (int)$approw->aug; 
                        $sept += (int)$approw->sept; 
                        $oct += (int)$approw->oct; 
                        $nov += (int)$approw->nov; 
                        $dec += (int)$approw->dec; 
                        $fq += (int)$firstq;
                        $sq += (int)$secondq;
                        $tq += (int)$threeq;
                        $frq += (int)$fourthq;
                    ?>
                </tr>

            <?php } ?>
        
            <tr>
               <td></td>
               <td class="st">Total</td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"><?= number_format($ba); ?></td>
               <td class="st"><?= number_format($jan); ?></td>
               <td class="st"><?= number_format($feb); ?></td>
               <td class="st"><?= number_format($mar); ?></td>
               <td class="st"><?= number_format($fq); ?></td>
               <td class="st"><?= number_format($april); ?></td>
               <td class="st"><?= number_format($may); ?></td>
               <td class="st"><?= number_format($june); ?></td>
               <td class="st"><?= number_format($sq); ?></td>
               <td class="st"><?= number_format($july); ?></td>
               <td class="st"><?= number_format($aug); ?></td>
               <td class="st"><?= number_format($sept); ?></td>
               <td class="st"><?= number_format($tq); ?></td>
               <td class="st"><?= number_format($oct); ?></td>
               <td class="st"><?= number_format($nov); ?></td>
               <td class="st"><?= number_format($dec); ?></td>
               <td class="st"><?= number_format($frq); ?></td>
               <td class="st"><?= number_format($fq+$sq+$tq+$frq); ?></td>

            </tr>
            <?php
                $otba += (int)$ba;
                $otjan += (int)$jan; 
                $otfeb += (int)$feb; 
                $otmar += (int)$mar; 
                $otapril += (int)$april; 
                $otmay += (int)$may; 
                $otjune += (int)$june; 
                $otjuly += (int)$july; 
                $otaug += (int)$aug; 
                $otsept += (int)$sept; 
                $otoct += (int)$oct; 
                $otnov += (int)$nov; 
                $otdec += (int)$dec; 
                $otfq += (int)$fq;
                $otsq += (int)$sq;
                $ottq += (int)$tq;
                $otfrq += (int)$frq;
             ?>
        
        <?php } ?>

            <tr>
                <td class="t"></td>
                <td class="t">Total Teaching-learning Instruction </td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"><?= number_format($otba); ?></td>
                <td class="t"><?= number_format($otjan); ?></td><?php $tijan = $otjan; ?>
                <td class="t"><?= number_format($otfeb); ?></td><?php $tifeb = $otfeb; ?>
                <td class="t"><?= number_format($otmar); ?></td><?php $timar = $otmar; ?>
                <td class="t"><?= number_format($otfq); ?></td><?php $tifq = $otfq; ?>
                <td class="t"><?= number_format($otapril); ?></td><?php $tiapril = $otapril; ?>
                <td class="t"><?= number_format($otmay); ?></td><?php $timay = $otmay; ?>
                <td class="t"><?= number_format($otjune); ?></td><?php $tijune = $otjune; ?>
                <td class="t"><?= number_format($otsq); ?></td><?php $tisq = $otsq; ?>
                <td class="t"><?= number_format($otjuly); ?></td><?php $tijuly = $otjuly; ?>
                <td class="t"><?= number_format($otaug); ?></td><?php $tiaug = $otaug; ?>
                <td class="t"><?= number_format($otsept); ?></td><?php $tisept = $otsept; ?>
                <td class="t"><?= number_format($ottq); ?></td><?php $titq = $ottq; ?>
                <td class="t"><?= number_format($otoct); ?></td><?php $tioct = $otoct; ?>
                <td class="t"><?= number_format($otnov); ?></td><?php $tinov = $otnov; ?>
                <td class="t"><?= number_format($otdec); ?></td><?php $tidec = $otdec; ?>
                <td class="t"><?= number_format($otfrq); ?></td><?php $tifrq = $otjan; ?>
                <td class="t"><?= number_format($otfq+$otsq+$ottq+$otfrq); ?></td></td><?php $titotal = $otfq+$otsq+$ottq+$otfrq; ?>
                
            </tr>

            <tr>
                <td colspan="23" class="bar">IV. Attendance to & Conduct of Trainings/Seminars/Conferences (25%)  </td>
            </tr>
            <?php 
                $aip_by_pillar = $this->SGODModel->aip('sgod_aip',$school_id,$fy,$b_code,'TRAININGS/SEMINAR/TRAVEL');
                $otba = 0;
                $otjan = 0;
                $otfeb = 0;
                $otmar = 0;
                $otapril = 0;
                $otmay = 0;
                $otjune = 0;
                $otjuly = 0;
                $otaug = 0;
                $otsept = 0;
                $otoct = 0;
                $otnov = 0;
                $otdec = 0;
                $otfq = 0;
                $otsq = 0;
                $ottq = 0;
                $otfrq = 0;
                foreach($aip_by_pillar as $row){
                    
            ?>
            <tr>
                <td><?= $row->pillar; ?></td>
                <td colspan="8" class="pt"><?= $row->sip_project; ?> / <?= $row->strategy; ?></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $ba = 0;
            $jan = 0;
            $feb = 0;
            $mar = 0;
            $april = 0;
            $may = 0;
            $june = 0;
            $july = 0;
            $aug = 0;
            $sept = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;
            $fq = 0;
            $sq = 0;
            $tq = 0;
            $frq = 0;
            foreach($get_app as $approw){ ?>
                
                <tr>
                    <td></td>
                    <td><?= $approw->materials; ?></td>
                    <td><?= $approw->unit_price; ?></td>
                    <td><?= $approw->quantity; ?></td>
                    <td><?= $approw->unit_measure; ?></td>
                    <td><?= number_format((int)$approw->budget_alloc); ?></td>
                    <td><?= number_format((int)$approw->jan); ?></td>
                    <td><?= number_format((int)$approw->feb); ?></td>
                    <td><?= number_format((int)$approw->mar); ?></td>
                    <td><?php $firstq = (int)$approw->jan+(int)$approw->feb+(int)$approw->mar; echo number_format($firstq); ?></td>
                    <td><?= number_format((int)$approw->april); ?></td>
                    <td><?= number_format((int)$approw->may); ?></td>
                    <td><?= number_format((int)$approw->june); ?></td>
                    <td><?php $secondq = (int)$approw->april+(int)$approw->may+(int)$approw->june; echo number_format($secondq); ?></td></td>
                    <td><?= number_format((int)$approw->july); ?></td>
                    <td><?= number_format((int)$approw->aug); ?></td>
                    <td><?= number_format((int)$approw->sept); ?></td>
                    <td><?php $threeq = (int)$approw->july+(int)$approw->aug+(int)$approw->sept;  echo number_format($threeq); ?></td></td>
                    <td><?= number_format((int)$approw->oct); ?></td>
                    <td><?= number_format((int)$approw->nov); ?></td>
                    <td><?= number_format((int)$approw->dec); ?></td>
                    <td><?php $fourthq = (int)$approw->oct+(int)$approw->nov+(int)$approw->dec; echo number_format($fourthq); ?></td></td>
                    <td><?= number_format((int)$approw->jan+(int)$approw->feb+(int)$approw->mar+(int)$approw->april+(int)$approw->may+(int)$approw->june+(int)$approw->july+(int)$approw->aug+(int)$approw->sept+(int)$approw->oct+(int)$approw->nov+(int)$approw->dec); ?></td>
                    <?php 
                        $ba += (int)$approw->budget_alloc; 
                        $jan += (int)$approw->jan; 
                        $feb += (int)$approw->feb; 
                        $mar += (int)$approw->mar; 
                        $april += (int)$approw->april; 
                        $may += (int)$approw->may; 
                        $june += (int)$approw->june; 
                        $july += (int)$approw->july; 
                        $aug += (int)$approw->aug; 
                        $sept += (int)$approw->sept; 
                        $oct += (int)$approw->oct; 
                        $nov += (int)$approw->nov; 
                        $dec += (int)$approw->dec; 
                        $fq += (int)$firstq;
                        $sq += (int)$secondq;
                        $tq += (int)$threeq;
                        $frq += (int)$fourthq;
                    ?>
                </tr>

            <?php } ?>
        
            <tr>
               <td></td>
               <td class="st">Total</td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"></td>
               <td class="st"><?= number_format($ba); ?></td>
               <td class="st"><?= number_format($jan); ?></td>
               <td class="st"><?= number_format($feb); ?></td>
               <td class="st"><?= number_format($mar); ?></td>
               <td class="st"><?= number_format($fq); ?></td>
               <td class="st"><?= number_format($april); ?></td>
               <td class="st"><?= number_format($may); ?></td>
               <td class="st"><?= number_format($june); ?></td>
               <td class="st"><?= number_format($sq); ?></td>
               <td class="st"><?= number_format($july); ?></td>
               <td class="st"><?= number_format($aug); ?></td>
               <td class="st"><?= number_format($sept); ?></td>
               <td class="st"><?= number_format($tq); ?></td>
               <td class="st"><?= number_format($oct); ?></td>
               <td class="st"><?= number_format($nov); ?></td>
               <td class="st"><?= number_format($dec); ?></td>
               <td class="st"><?= number_format($frq); ?></td>
               <td class="st"><?= number_format($fq+$sq+$tq+$frq); ?></td>

            </tr>
            <?php
                $otba += (int)$ba;
                $otjan += (int)$jan; 
                $otfeb += (int)$feb; 
                $otmar += (int)$mar; 
                $otapril += (int)$april; 
                $otmay += (int)$may; 
                $otjune += (int)$june; 
                $otjuly += (int)$july; 
                $otaug += (int)$aug; 
                $otsept += (int)$sept; 
                $otoct += (int)$oct; 
                $otnov += (int)$nov; 
                $otdec += (int)$dec; 
                $otfq += (int)$fq;
                $otsq += (int)$sq;
                $ottq += (int)$tq;
                $otfrq += (int)$frq;
             ?>
        
        <?php } ?>

            <tr>
                <td class="t"></td>
                <td class="t">Monthly Cash Allocation</td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"></td>
                <td class="t"><?= number_format($otba); ?></td>
                <td class="t"><?= number_format($otjan); ?></td><?php $majan = $otjan; ?>
                <td class="t"><?= number_format($otfeb); ?></td><?php $mafeb = $otfeb; ?>
                <td class="t"><?= number_format($otmar); ?></td><?php $mamar = $otmar; ?>
                <td class="t"><?= number_format($otfq); ?></td><?php $mafq = $otfq; ?>
                <td class="t"><?= number_format($otapril); ?></td><?php $maapril = $otapril; ?>
                <td class="t"><?= number_format($otmay); ?></td><?php $mamay = $otmay; ?>
                <td class="t"><?= number_format($otjune); ?></td><?php $majune = $otjune; ?>
                <td class="t"><?= number_format($otsq); ?></td><?php $masq = $otsq; ?>
                <td class="t"><?= number_format($otjuly); ?></td><?php $majuly = $otjuly; ?>
                <td class="t"><?= number_format($otaug); ?></td><?php $maaug = $otaug; ?>
                <td class="t"><?= number_format($otsept); ?></td><?php $masept = $otsept; ?>
                <td class="t"><?= number_format($ottq); ?></td><?php $matq = $ottq; ?>
                <td class="t"><?= number_format($otoct); ?></td><?php $maoct = $otoct; ?>
                <td class="t"><?= number_format($otnov); ?></td><?php $manov = $otnov; ?>
                <td class="t"><?= number_format($otdec); ?></td><?php $madec = $otdec; ?>
                <td class="t"><?= number_format($otfrq); ?></td><?php $mafrq = $otjan; ?>
                <td class="t"><?= number_format($otfq+$otsq+$ottq+$otfrq); ?></td></td><?php $matotal = $otfq+$otsq+$ottq+$otfrq; ?>
            </tr>
            <tr>
                <td></td>
                <td class="ot" style="text-align:left; font-weight:bold" colspan="5">Monthly Cash Allocation</td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($mafeb+$tifeb+$mrfeb+$mbfeb); ?></td>
                <td class="ot"><?= number_format($mamar+$timar+$mrmar+$mbmar); ?></td>
                <td class="ot"><?= number_format($mafq+$tifq+$mrfq+$mbfq); ?></td>
                <td class="ot"><?= number_format($maapril+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
                <td class="ot"><?= number_format($majan+$tijan+$mrjan+$mbjan); ?></td>
            </tr>


        </tbody> 
    </table>
        







    </body>
    </hmlm>