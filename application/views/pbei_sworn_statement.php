<?php
$swornDate = isset($swornDate) ? $swornDate : date('j F Y');
$schoolAddress = isset($schoolAddress) && trim((string) $schoolAddress) !== '' ? $schoolAddress : 'School address not recorded';
$affiantName = isset($affiantName) && trim((string) $affiantName) !== '' ? $affiantName : 'Name not recorded';
$affiantDesignation = isset($affiantDesignation) && trim((string) $affiantDesignation) !== '' ? $affiantDesignation : 'Designation not recorded';
$schoolName = isset($schoolName) && trim((string) $schoolName) !== '' ? $schoolName : 'School name not recorded';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sworn Statement</title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f5f7; color: #15191d; font-family: Arial, Helvetica, sans-serif; }
        .statement { width: min(850px, calc(100% - 120px)); margin: 40px auto; padding: 62px; background: #fff; box-shadow: 0 8px 28px rgba(0,0,0,.1); }
        h1, h2 { color: #111; font-family: Arial, Helvetica, sans-serif; letter-spacing: .035em; }
        h1 { margin: 0 0 46px; text-align: center; font-size: 1.85rem; font-weight: 800; }
        h2 { margin: 0 0 38px; text-align: center; font-size: 1.28rem; font-weight: 800; }
        p { margin: 0; font-size: 1.08rem; line-height: 1.7; text-align: justify; }
        .attested { margin-top: 56px; font-size: 1.08rem; }
        .affiant { margin-top: 76px; font-size: 1.05rem; line-height: 1.55; }
        .affiant strong { font-weight: 800; letter-spacing: .025em; }
        .notarial { margin-top: 78px; font-size: 1.05rem; line-height: 1.65; text-align: justify; }
        .notary-public { margin-top: 112px; margin-left: 62%; font-size: 1rem; font-weight: 700; letter-spacing: .04em; white-space: nowrap; }
        .doc-details { margin-top: 165px; font-size: 1rem; line-height: 1.45; }
        .print-actions { width: min(900px, calc(100% - 40px)); margin: 24px auto 0; font-family: Arial, Helvetica, sans-serif; }
        @media print { @page { margin: 0.75in 1in; } body { background: #fff !important; color: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; } .print-actions { display: none; } .statement { width: 100%; margin: 0; padding: 0; box-shadow: none; } h1 { font-size: 17pt; } h2 { font-size: 13pt; } p, .attested, .affiant, .notarial, .notary-public, .doc-details { font-size: 11.5pt; } }
    </style>
</head>
<body>
    <div class="print-actions"><button type="button" class="btn btn-primary" onclick="window.print()">Print</button><button type="button" class="btn btn-light ml-2" onclick="window.close()">Close</button></div>
    <main class="statement">
        <h1>SWORN STATEMENT</h1>
        <h2>CERTIFICATION OF AUTHENTICITY AND VERACITY</h2>
        <p>I hereby certify that all information above are true and correct, and of my personal knowledge and/or records, and that the documents submitted herewith are original and/or certified true copies thereof. I authorize the Department of Education, or its authorized representative to verify or validate the contents stated therein. I agree that any misrepresentation made in this document and its attachments shall cause the filing of an administrative and/or criminal case/s against me.</p>
        <div class="attested">Attested:</div>
        <div class="affiant"><strong><?= htmlspecialchars($affiantName, ENT_QUOTES, 'UTF-8'); ?></strong><br><em><?= htmlspecialchars($affiantDesignation, ENT_QUOTES, 'UTF-8'); ?></em><br><em><?= htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8'); ?></em><br><em>Affiant</em></div>
        <div class="notarial"><strong>SUBSCRIBED AND SWORN</strong> to before me this <?= htmlspecialchars($swornDate, ENT_QUOTES, 'UTF-8'); ?> at <?= htmlspecialchars($schoolAddress, ENT_QUOTES, 'UTF-8'); ?>, Philippines. The affiant exhibited to me his/her competent proof of identity.</div>
        <div class="notary-public">NOTARY PUBLIC</div>
        <div class="doc-details">Doc. No. _____<br>Page No. _____<br>Book No. _____<br>Series of <?= date('Y'); ?>.</div>
    </main>
</body>
</html>
