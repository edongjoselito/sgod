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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
              
                var doc = new jsPDF();
                var specialElementHandlers = {
                    '#editor': function (element, renderer) {
                        return true;
                    }
                };

                $('#cmd').click(function () {
                    doc.fromHTML($('#content').html(), 15, 15, {
                        'width': 170,
                            'elementHandlers': specialElementHandlers
                    });
                    doc.save('sample-file.pdf');
                });
        </script>

    </head>


    <body class="aip_generate" id="printTable">



    <img class="logo" src="<?= base_url(); ?>assets/images/report/ke.png" alt="">
    <p>
        Republic of the Philippines<br />
        Department of Education<br />
        Region XI<br />
        School Division of Davao Oriental<br />
        <?= strtoupper($school->district); ?><br />
         <?= strtoupper($school->schoolName); ?> <?= strtoupper($school->course); ?><br />
         <?= strtoupper($school->sitio); ?>, <?= strtoupper($school->brgy); ?>, <?= strtoupper($school->city); ?>, <?= strtoupper($school->province); ?> 
    </p>
    <div class="hr"></div>



    <div class="rca">
    <p>____, <?= $fy; ?></p>
    <p class="name"><b>DR. JOSEPHINE L. FADUL</b><br />Schools Division Superintendent</p>
    <p class="sname"><b>Thru: DENNIS Y. BELARMINO</b><br/>Accountant III</p>
    <p class="maam">Maam:</p>
    <p class="coa">In pursuance to COA Circular No. 97-002 dated February 10, 2007, the undersigned respectfully request the amount of __________________________________________ (__________) as Cash Advance for the Month of _________ the Maintenance & Other Operating Expenses (MOOE) of __________________________________, ____________________________, Davao Oriental for payments of bills/travels/ purchases of various supplies and materials listed below for the Month of ________________.</p>


    <table style="width:100%">
        <tr>
            <th>Quantity</th>
            <th>Unit Of Measure</th>
            <th>Item Description</th>
            <th>Stock No.</th>
            <th>Estimated Unit Cost</th>
            <th>Estimated Cost</th>
        </tr>
        <tr>
            <td colspan="5" class="alignLeft2">I. MANDATORY BILLS</td>
            <td></td>
        </tr>
        <?php 
            $all_total = 0;
            foreach($mb as $row){?>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $mb_total = 0;
            foreach($get_app as $row){?>
            <tr>
                <td><?= $row->quantity; ?></td>
                <td><?= $row->unit_measure; ?></td>
                <td><?= $row->materials; ?></td>
                <td></td>
                <td><?= $row->unit_price; ?></td>
                <td><?php $mb_ups = (int)$row->unit_price*(int)$row->quantity; echo $mb_ups; ?></td>
                <?php $amb = $mb_total+= $mb_ups; ?>
            </tr>
            <?php  } ?>
            <?php $all_total += $amb; ?>
        <?php  }  ?>

        <?php $all_mb = $all_total; ?>

        <tr>
            <td colspan="5" class="alignLeft2">  II. MINOR REPAIR</td>
            <td></td>
        </tr>
        <?php $all_total = 0;
            foreach($mr as $row){?>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $mb_total = 0;
            foreach($get_app as $row){?>
            <tr>
                <td><?= $row->quantity; ?></td>
                <td><?= $row->unit_measure; ?></td>
                <td><?= $row->materials; ?></td>
                <td></td>
                <td><?= $row->unit_price; ?></td>
                <td><?php $mb_ups = (int)$row->unit_price*(int)$row->quantity; echo $mb_ups; ?></td>
                <?php $amb = $mb_total+= $mb_ups; ?>
            </tr>
            <?php  } ?>
            <?php $all_total += $amb; ?>
        <?php  }  ?>

        <?php $all_mr = $all_total; ?>

        <tr>
            <td colspan="5" class="alignLeft2"> III. TEACHING-LEARNING INSTRUCTION</td>
            <td></td>
        </tr>
        <?php $all_total = 0;
            foreach($tli as $row){?>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $mb_total = 0;
            foreach($get_app as $row){?>
            <tr>
                <td><?= $row->quantity; ?></td>
                <td><?= $row->unit_measure; ?></td>
                <td><?= $row->materials; ?></td>
                <td></td>
                <td><?= $row->unit_price; ?></td>
                <td><?php $mb_ups = (int)$row->unit_price*(int)$row->quantity; echo $mb_ups; ?></td>
                <?php $amb = $mb_total+= $mb_ups; ?>
            </tr>
            <?php  } ?>
            <?php $all_total += $amb; ?>
        <?php  }  ?>

        <?php $all_tli = $all_total; ?>
        <tr>
            <td colspan="5" class="alignLeft2">IV.TRAININGS/SEMINAR/TRAVEL</td>
            <td></td>
        </tr>
        <?php $all_total = 0;
            foreach($tst as $row){?>
            <?php 
            $get_app = $this->SGODModel->one_cond('sgod_app', 'aip_id',$row->id);
            $mb_total = 0;
            foreach($get_app as $row){?>
            <tr>
                <td><?= $row->quantity; ?></td>
                <td><?= $row->unit_measure; ?></td>
                <td><?= $row->materials; ?></td>
                <td></td>
                <td><?= $row->unit_price; ?></td>
                <td><?php $mb_ups = (int)$row->unit_price*(int)$row->quantity; echo $mb_ups; ?></td>
                <?php $amb = $mb_total+= $mb_ups; ?>
            </tr>
            <?php  } ?>
            <?php $all_total += $amb; ?>
        <?php  }  ?>

        <?php $all_tst = $all_total;  ?>

        <tr>
            <td colspan="5" class="alignLeft">TOTAL</td>
            <td><?= number_format((int)$all_mb+(int)$all_mr+(int)$all_tli+(int)$all_tst);?></td>
        </tr>
    </table>




    </div>
    </body>
    </hmlm>