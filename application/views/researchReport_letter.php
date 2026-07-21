<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Temporary Research Permit</title>

  <style>
    /* =========================
      PRINT: TRUE A4 + NO MARGINS
    ========================== */
    @page { size: A4; margin: 0; }
    html, body { height: 100%; }
    body{
      margin:0;
      background:#fff;
      color:#000;
      font-family: "Times New Roman", Times, serif;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* =========================
      PAGE MARGINS (TIGHTER TO FIT 1 PAGE)
    ========================== */
    .sheet{
      width: 210mm;
      min-height: 297mm;
      margin: 0 auto;
      box-sizing: border-box;
      padding: 5mm 13mm 5mm 13mm;
      display:flex;
      flex-direction:column;
      overflow: hidden;

      /* ✅ required for watermark layering */
      position: relative;
      background: #fff;
    }

    /* =========================
      WATERMARK (TEXT)
      - No extra image needed
      - Print-safe (opacity low)
    ========================== */
    .sheet::before{
      content: "TEMPORARY RESEARCH PERMIT";
      position: absolute;
      left: 50%;
      top: 42%;
      transform: translate(-50%, -50%) rotate(-30deg);
      font-size: 40pt;
      font-weight: 1000;
      letter-spacing: 1px;
      color: rgba(0, 0, 0, 0.14);
      white-space: nowrap;
      z-index: 0;
      pointer-events: none;
      user-select: none;
    }

    /* Ensure all content prints ABOVE watermark */
    .sheet > *{
      position: relative;
      z-index: 1;
    }

    /* =========================
      FONT STACKS
    ========================== */
    .font-oldenglish{
      font-family: "Old English Text MT","OldEnglishTextMT","UnifrakturMaguntia","UnifrakturCook",serif;
    }
    .font-bookman{
      font-family: "Bookman Old Style","Bookman","URW Bookman","Times New Roman",serif;
      font-size: 10pt;
      letter-spacing: 0;
      font-weight: 700;
    }

    /* =========================
      HEADER (SLIGHTLY SMALLER)
    ========================== */
    .header{ text-align:center; margin-top: 0; }
    .seal{
      width: 64px; height: 64px;
      display:block;
      margin: 0 auto 4px auto;
    }

    .hdr-rp{
      font-size: 15pt;
      font-weight: 700;
      color:#000;
      line-height: 1.0;
      margin: 0 0 1px 0;
    }
    .hdr-doe{
      font-size: 11pt;
      font-weight: 700;
      color:#000;
      line-height: 1.0;
      margin: 0;
    }
    .hdr-sub{
      line-height: 1.15;
      margin-top: 1px;
      text-transform: uppercase;
    }

    .header hr{
      border:0;
      border-top: 1px solid #000;
      margin: 6px 0 0 0;
    }

    /* =========================
      OFFICE + LONG RULE
    ========================== */
    .office-row{
      margin-top: 0;
      margin-bottom: 4px;
      display:flex;
      align-items:flex-end;
      gap: 10px;
    }
    .office{
      font-family: Tahoma, Arial, sans-serif;
      font-size: 10pt;
      font-weight: 700;
      white-space: nowrap;
    }
    .office-rule{
      flex: 1;
      border-bottom: 1px solid #000;
      transform: translateY(-2px);
    }

    /* =========================
      META
    ========================== */
    .meta{
      margin-top: 6px;
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      font-size: 10pt;
    }
    .meta .left{ white-space: nowrap; }
    .meta .right{ white-space: nowrap; text-align:right; }
    .meta .right .num{
      font-weight: 900;
      text-decoration: underline;
      text-underline-offset: 3px;
    }

    /* =========================
      TO BLOCK
    ========================== */
    .to-block{
      margin-top: 10px;
      font-size: 10pt;
      line-height: 1.12;
    }
    .to-block p{ margin:0; }
    .to-block .name{ font-weight: 900; }

    /* =========================
      SUBJECT
    ========================== */
    .subject{
      margin-top: 10px;
      font-size: 10pt;
      font-style: italic;
      line-height: 1.12;
    }
    .subject b{ font-weight: 900; }

    /* =========================
      BODY
    ========================== */
    .body{
      margin-top: 10px;
      font-size: 10pt;
      line-height: 1.30;
    }
    .body p{
      margin: 0 0 8px 0;
      text-align: justify;
      text-justify: inter-word;
    }
    .body .salutation b{ font-weight: 900; }

    /* =========================
      SIGNATURE + TITLE
    ========================== */
    .sign-area{
      margin-top: 4px;
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      gap: 10mm;
    }
    .sig-left{ width: 56%; font-size: 10pt; }

    .closing{
      margin-top: 0;
      margin-bottom: 2mm;
    }
    .sig-name{
      font-weight: 900;
      text-transform: uppercase;
      line-height: 1.05;
      margin: 0;
      padding: 0;
      font-size: 10pt;
    }
    .sig-title{
      margin: 0;
      padding: 0;
      line-height: 1.05;
      font-size: 10pt;
    }

    .title-box{
      width: 44%;
      border: 2px solid #cfd6df;
      background:#f8fafc;
      box-sizing: border-box;
      padding: 8px 10px;
      min-height: 22mm;
      font-size: 9.5pt;
      color:#666;
      font-style: italic;
      line-height: 1.20;
      align-self: flex-end;
      transform: translateY(4mm);
    }
    .title-box .lbl{ font-weight: 900; }

    .bottom-rule{
      margin-top: 5mm;
      border-top: 2px solid #000;
      width: 100%;
    }

    /* =========================
      DIGITAL SIGNATURE (ESIG)
    ========================== */
   /* =========================
   DIGITAL SIGNATURE (Clean + Professional)
========================= */
.esig-wrapper{
  position: relative;
  width: 200px;
  margin: 3mm 0 1mm 0;
}

/* Signature image */
.esig-img{
  width: 130px;
  height: auto;
  display: block;
  position: relative;
  z-index: 2; /* always above stamp */
}

/* Horizontal backdrop stamp */
.digital-stamp{
  position: absolute;
  top: 35%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 8pt;
  font-weight: 900;
  letter-spacing: 3px;
  color: rgba(0, 0, 0, 0.20); /* light watermark style */
  z-index: 1;
  white-space: nowrap;
  pointer-events: none;
  user-select: none;
}

    /* =========================
      FOOTER IMAGE
    ========================== */
    .footer img{
      width: 100%;
      height: auto;
      display:block;
      max-height: 32mm;
      object-fit: contain;
    }

    /* screen preview only */
    @media screen{
      body{ background:#f0f0f0; }
      .sheet{
        background:#fff;
        box-shadow: 0 12px 30px rgba(0,0,0,.12);
      }
    }
  </style>
</head>

<body>
<div class="sheet">

<?php
  $h = function($v){ return htmlspecialchars((string)$v, ENT_QUOTES); };

  $controlNo = trim((string)($row->control_no ?? ''));
  if ($controlNo === '') $controlNo = 'N/A';

  $reqDateRaw = trim((string)($row->request_date ?? ''));
  $reqTs = $reqDateRaw !== '' ? strtotime($reqDateRaw) : false;
  $reqDateDisp = $reqTs ? date('d F Y', $reqTs) : date('d F Y');

  $researchTitle = trim((string)($row->research_title ?? ''));
  if ($researchTitle === '') $researchTitle = 'N/A';

  $researcherType = trim((string)($row->researcher_type ?? ''));
  if ($researcherType === '') $researcherType = 'N/A';

  $hei = trim((string)($row->hei_name ?? ''));
  $campus = trim((string)($row->hei_campus_location ?? ''));
  $heiFull = trim($hei . ($campus ? (', ' . $campus) : ''));
  if ($heiFull === '') $heiFull = 'N/A';

  $researcherName = trim((string)($researcherNameLetter ?? ''));
  if ($researcherName === '') $researcherName = trim((string)($row->main_author_name ?? ''));
  if ($researcherName === '') $researcherName = trim((string)($row->created_by_name ?? ''));
  if ($researcherName === '') $researcherName = trim((string)($row->main_author_id ?? ''));
  if ($researcherName === '') $researcherName = trim((string)($row->created_by ?? ''));
  if ($researcherName === '') $researcherName = 'N/A';
?>

  <!-- HEADER -->
  <div class="header">
    <img class="seal" src="<?= base_url('assets/images/ke.png'); ?>" alt="DepEd Seal">

    <div class="hdr-rp font-oldenglish">Republic of the Philippines</div>
    <div class="hdr-doe font-oldenglish">Department of Education</div>

    <div class="hdr-sub font-bookman">REGION XI</div>
    <div class="hdr-sub font-bookman">SCHOOLS DIVISION OF DAVAO ORIENTAL</div>
    <hr>
  </div>

  <br>

  <!-- OFFICE -->
  <div class="office-row">
    <div class="office">Office of the Schools Division Superintendent</div>
  </div>

  <!-- DATE + CONTROL -->
  <div class="meta">
    <div class="left"><?= $h($reqDateDisp); ?></div>
    <div class="right">
      Temporary Research Permit Control No.&nbsp;<span class="num"><?= $h($controlNo); ?></span>
    </div>
  </div>

  <br><br>

  <!-- TO -->
  <div class="to-block">
    <p class="name"><?= $h($researcherName); ?></p>
    <p><?= $h($researcherType); ?></p>
    <p><?= $h($heiFull); ?></p>
  </div>

  <br>

  <!-- SUBJECT -->
  <div class="subject">
    <b>Subject:</b> Temporary Approval of Research Permit Request in the Schools Division of Davao Oriental<br>
    
  </div>

  <br>

  <!-- BODY -->
  <div class="body">
    <p class="salutation">Dear <b>Researcher</b>,</p><br>

    <p>Greetings of Peace and Educational Development!</p><br>

    <p>
      This is to inform you that your online request to conduct pilot testing and data gathering (survey, interviews, and focus group discussions) for your thesis in the Schools
      Division of Davao Oriental is hereby TEMPORARILY APPROVED, subject to verification and validation of the documentary requirements you uploaded through the official online
      application system, and to full compliance with existing DepEd policies and research ethics standards.
    </p>

    <p>
      You may proceed with the research, subject to voluntary participation and strict coordination with concerned school heads to ensure no disruption
      of classes, in accordance with DepEd Order No. 9, s. 2005 dated 02 March 2005. The Division encourages virtual/online data gathering; face-to-face
      activities may be allowed only when essential to the approved design and subject to applicable safety protocols. Please strictly observe research
      ethics, including confidentiality, data privacy, and informed consent/assent.
    </p>

    <p>
      Upon successful verification, validation, and final approval of your online submissions, the Division shall issue and email the final Research
      Permit (signed by the Schools Division Superintendent) in PDF format, bearing the official document tracking code and QR code. Any misrepresentation,
      deficiency, or non-compliance with permit conditions and DepEd requirements shall be sufficient ground for the cancellation/forfeiture of this
      temporary approval and/or any permit subsequently issued.
    </p>

    <p style="margin-top: 20px;">Thank you for choosing the Schools Division of Davao Oriental as your research partner.</p>

    <br><br>

    <!-- SIGNATURE + TITLE -->
    <div class="sign-area">
      <div class="sig-left">

        <div class="closing">Very truly yours,</div>

        <!-- ✅ E-SIGNATURE + NOTE -->
       <div class="esig-wrapper">
    <div class="digital-stamp">DIGITALLY SIGNED</div>
    <img src="<?= base_url('assets/isig/fadul.png'); ?>" class="esig-img" alt="Digital Signature">
</div>

        <p class="sig-name">DR. JOSEPHINE L. FADUL</p>
        <p class="sig-title">Schools Division Superintendent</p>
      </div>

      <div class="title-box">
        <span class="lbl">Research Title:</span>
        <span><?= $h($researchTitle); ?></span>
      </div>
    </div>

    <div class="bottom-rule"></div>
  </div>

  <!-- FOOTER IMAGE -->
  <div class="footer">
    <img src="<?= base_url('assets/images/f.png'); ?>" alt="Footer">
    <b style="margin-left: 300px; font-size: 13px;">“Where the Sunrise Beckons the Sweetest Smile”</b>
  </div>

</div>
</body>
</html>