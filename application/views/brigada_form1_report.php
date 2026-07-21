<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/hris.ico">
    <title>Document</title>
    <style>
        .wrap{
            width:80%;
            margin:auto;
            font-family: 'Bookman Old Style', serif;
        }
        .hwrap{
            text-align:center;
        }
        .logo{
            width:70px;
            height:70px;
        }
        .rp{
            font-family: "Old English Text MT"; 
            font-size: 12pt;
        }
            .r{
            font-family: "Trajan Pro";
            font-size: 9pt;
        }
            .de{
            font-family: "Old English Text MT"; 
            font-size: 16pt;
        }
        .title{
            text-align:center;
            font-size:25px;
        }
        .subtitle{
            text-align:center;
            font-size:15px;
            margin-bottom:50px;
        }
        .brigada{
            width: 100%;
            margin-bottom:50px;
        }
        .brigada,
        .brigada td,
        .brigada th{
            border:1px solid #222;
            border-collapse: collapse;
            text-align:left;
            padding:5px 15px;
        }
        .brigada th{
            text-align:center;
        }
        

        .footer{
            border-top:1px solid #222;
            margin-top:70px;
        }

        .footer .fr{
            padding-top:10px;
        }

        .footer .fr img{
            float:left;
            margin-right:30px;
            margin-left:15%;
        }

        .footer .fr p{
            padding:0 !important;
            margin:0 !important;
        }


        .ldiv{
            float:left;
            width:60%;
        }
        .rdiv{
            float:right;
            width:40%;
        }

        .pb{
            float:left;
            margin-right:80px;
            padding-top:20px;
            margin-top:80px;
            border-top:1px solid #222;
        }

        .pbd{
            float:left;
            padding-top:20px;
            margin-top:80px;
            border-top:1px solid #222;
        }

        .text-center{
            text-align:center !important;
        }



        .blocker{clear:both !important}

         @media print {
            @page {
            margin: 4mm 12mm 12mm 12mm;
            }
            .wrap{
                width:100%;
                margin:0 !important;
                font-size:13px;
            }
            .title{
                font-size:25px;
            }
            .footer .fr img{
              margin-left:3%;  
            }
            .tl{
                width:30%;
            }
            .notes{
                margin-bottom:75px;
            }
            .renguapo{
                font-size:10px;
                font-weight:normal;
            }

            .qr{
                width:50px;
                top:-15px;
            }

            .footer{
            margin-top:40px;
        }

         }
        


    </style>
</head>
<body>
   

     

<div class="wrap">

    <div class="hwrap">
        <img class="logo" src="<?= base_url(); ?>assets/images/report/ke.png" alt="">
        <p>
        <span class="rp">Republic of the Philippines</span><br />
            <span class="de">Department of Education</span><br />
            <span class="r">Region XI</span><br />
            <span class="r">School Division of Davao Oriental</span><br />
        </p>
    </div>


    <hr />

    <h1 class="title">BRIGADA ESKWELA</h1>
    <h2 class="subtitle">PHYSICAL FACILITIES AND MAINTENANCE NEEDS ASSESSMENT FORM</h2>

    <table class="brigada">
        <tr>
            <th rowspan="2">FACILITIES</th>
            <th colspan="2">CONDITION<br />(Check One)</th>
            <th>Remarks</th>
            <th rowspan="2">Nature of<br />improvement<br />Needed<br />(e.g. repair, <br />repainting, <br />replacement, etc)</th>
            <th rowspan="2">Material Resources<br /> Needed (Indicate<br /> kind and quality)</th>
            <th rowspan="2">Manpower<br /> Needed(Indicate<br /> quantity and nature of<br /> labor services needed)</th>
        </tr>
        <tr>
            <th>Satisfactory</th>
            <th>Unsatisfactory</th>
            <th>If Unsatisfactory,<br /> describe<br /> the problem</th>
        </tr>
        <?php foreach($facilities as $row){ 
            $f = $this->Common->two_cond_row('brigada_facility_inspection','facility_id',$row->id,'user_id',$this->session->username);
            ?>
        <tr>
            <td><?= $row->name; ?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '&#10003;' : '';?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '' : '&#10003;';?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '' : $f->remarks;?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '' : $f->improvement_needed;?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '' : $f->material_resources;?></td>
            <td class="text-center"><?= $f->is_satisfactory ? '' : $f->manpower_needed;?></td>
        </tr>
        <?php } ?>
    </table>
    <div class="blocker"></div>


    <div class="ldiv">
        <p>Prepared by:</p>
        <div class="blocker"></div>
        <p class="pb">School Physical Facilities Coordinator</p>
        <p class="pbd">Date of Inspection</p>

    </div>

    <div class="rdiv">
         <p>Noted:</p>
         <p class="pb">ASP Division Coordinator/ BE coordinator</p>
    </div>

    
    <div class="blocker"></div>
    <!-- <div class="footer">
        <div class="fr">
            <img src="<?= base_url(); ?>assets/images/logo3.jpg" alt="">
            <p><b>Address:</b> Government Center, Dahican, Mati City</p>
            <p><b>Contact No.:</b> (087) 388-3372</p>
            <p><b>Email Address:</b> davao.oriental@deped.gov.ph</p>
            <p><b>Official Website:</b> <a href="https://depeddavor.com/">https://depeddavor.com/</a></p>
            <p class="italic-quote">“Where the Sunrise Beckons the Sweetest Smile”</p>
        </div>

        <div class="blocker"></div>
    </div> -->

  </div>
    
</body>
</html>