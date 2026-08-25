<?php
$sectionName = isset($sectionName) && trim((string) $sectionName) !== '' ? (string) $sectionName : 'Section';
$scope = isset($scope) && $scope === 'personal' ? 'personal' : 'section';
$scopeLabel = $scope === 'personal' ? 'Personal Accomplishments' : 'Section Accomplishments';
$records = isset($records) && is_array($records) ? $records : array();
$reportGroups = isset($reportGroups) && is_array($reportGroups) ? $reportGroups : array();
$additionalPhotoGroups = isset($additionalPhotoGroups) && is_array($additionalPhotoGroups) ? $additionalPhotoGroups : array();
$kraTitles = isset($kraTitles) && is_array($kraTitles) ? $kraTitles : array();
$selectedMonth = isset($selectedMonth) ? (string) $selectedMonth : '';
$selectedYear = isset($selectedYear) ? (string) $selectedYear : '';
$availableYears = isset($availableYears) && is_array($availableYears) ? $availableYears : array();
$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

if (!function_exists('accomplishment_flipbook_escape')) {
    function accomplishment_flipbook_escape($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('accomplishment_flipbook_text')) {
    function accomplishment_flipbook_text($value) {
        return trim(html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8'));
    }
}

if (!function_exists('accomplishment_flipbook_rich_text')) {
    function accomplishment_flipbook_rich_text($value) {
        $value = trim(html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8'));
        if ($value === '') return '';

        // Summernote stores paragraph and list markup. Keep only its text-formatting tags,
        // remove every attribute, and never pass executable markup through to the report.
        $value = preg_replace('~<(script|style)[^>]*>.*?</\\1>~is', '', $value);
        $value = strip_tags($value, '<p><br><strong><b><em><i><u><ul><ol><li><span>');
        $value = preg_replace_callback('~<(/?)(p|br|strong|b|em|i|u|ul|ol|li|span)([^>]*)>~i', function ($match) {
            $closing = $match[1] === '/';
            $tag = strtolower($match[2]);
            if ($tag !== 'span' || $closing) {
                return '<' . ($closing ? '/' : '') . $tag . '>';
            }

            // Some editors encode emphasis as styled spans instead of b/i/u tags.
            // Keep only these three presentation properties.
            $style = '';
            if (preg_match('~style\\s*=\\s*(["\\\'])(.*?)\\1~is', $match[3], $styleMatch)) {
                $encodedStyle = $styleMatch[2];
                if (preg_match('~(?:^|;)\\s*font-weight\\s*:\\s*(bold|[5-9]00)\\s*(?:;|$)~i', $encodedStyle)) $style .= 'font-weight:bold;';
                if (preg_match('~(?:^|;)\\s*font-style\\s*:\\s*italic\\s*(?:;|$)~i', $encodedStyle)) $style .= 'font-style:italic;';
                if (preg_match('~(?:^|;)\\s*text-decoration(?:-line)?\\s*:\\s*[^;]*underline[^;]*~i', $encodedStyle)) $style .= 'text-decoration:underline;';
            }
            return $style === '' ? '<span>' : '<span style="' . $style . '">';
        }, $value);

        if (strip_tags($value) === $value) {
            return nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }

        return $value;
    }
}

if (!function_exists('accomplishment_flipbook_rich_text_linkify')) {
    function accomplishment_flipbook_rich_text_linkify($html) {
        return preg_replace_callback('~(?:https?://|www\\.)[^\\s<]+~i', function ($match) {
            $url = rtrim($match[0], '.,;:!?)]}');
            $suffix = substr($match[0], strlen($url));
            $href = stripos($url, 'www.') === 0 ? 'https://' . $url : $url;
            if (!filter_var($href, FILTER_VALIDATE_URL)) return $match[0];
            return '<a class="record-link-button" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="mdi mdi-open-in-new"></i>Open Link</a>' . $suffix;
        }, $html);
    }
}

if (!function_exists('accomplishment_flipbook_linkify')) {
    function accomplishment_flipbook_linkify($value) {
        $parts = preg_split('~((?:https?://|www\.)[^\s<]+)~i', (string) $value, -1, PREG_SPLIT_DELIM_CAPTURE);
        $html = '';
        foreach ($parts as $index => $part) {
            if ($index % 2 === 0) {
                $html .= htmlspecialchars($part, ENT_QUOTES, 'UTF-8');
                continue;
            }
            $href = stripos($part, 'www.') === 0 ? 'https://' . $part : $part;
            $html .= '<a class="record-link-button" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="mdi mdi-open-in-new"></i>Open Link</a>';
        }
        return $html;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= accomplishment_flipbook_escape($sectionName); ?> Accomplishment Flipbook</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <style>
        :root { --ink: #1e2559; --blue: #3c40c6; --paper: #fffefb; --muted: #69708d; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; color: var(--ink); font-family: Arial, sans-serif; background: radial-gradient(circle at top, #6670e8 0, #252b81 42%, #151841 100%); }
        .flipbook-shell { width: min(96vw, 1500px); min-height: 100vh; margin: 0 auto; padding: 22px 20px 30px; }
        .flipbook-topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; color: #fff; }
        .flipbook-topbar h1 { margin: 0; color: #fff; font-size: clamp(1.2rem, 2.5vw, 1.7rem); font-weight: 700; }
        .flipbook-topbar p { margin: 5px 0 0; color: rgba(255,255,255,.76); font-size: .9rem; }
        .topbar-actions { display: flex; gap: 9px; flex-wrap: wrap; }
        .topbar-actions a, .topbar-actions button { border: 1px solid rgba(255,255,255,.45); border-radius: 9px; padding: 9px 12px; color: #fff; background: rgba(255,255,255,.1); font: inherit; font-size: .88rem; cursor: pointer; text-decoration: none; }
        .topbar-actions a:hover, .topbar-actions button:hover { color: var(--ink); background: #fff; }
        .book-stage { min-height: calc(100vh - 150px); padding: 14px; border-radius: 18px; background: rgba(10,13,50,.26); box-shadow: 0 22px 65px rgba(5,8,35,.4); perspective: 1800px; }
        .book-page { display: none; min-height: calc(100vh - 180px); padding: clamp(28px, 5vw, 58px); overflow: hidden; border-radius: 7px; background: var(--paper); box-shadow: inset 0 0 0 1px rgba(40,46,95,.08), 0 5px 15px rgba(0,0,0,.16); transform-origin: left center; }
        .book-page.is-active { display: block; }
        .book-page.turn-next { animation: turnNext .45s ease; }
        .book-page.turn-prev { animation: turnPrev .45s ease; }
        @keyframes turnNext { from { opacity: .45; transform: rotateY(-35deg); } to { opacity: 1; transform: rotateY(0); } }
        @keyframes turnPrev { from { opacity: .45; transform: rotateY(35deg); } to { opacity: 1; transform: rotateY(0); } }
        .cover-page { flex-direction: column; justify-content: center; text-align: center; color: #fff; background: linear-gradient(135deg, #252b81, #535bd5); }
        .book-page.cover-page.is-active { display: flex; }
        .cover-icon { display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; margin: 0 auto 22px; border: 1px solid rgba(255,255,255,.45); border-radius: 50%; font-size: 2rem; background: rgba(255,255,255,.1); }
        .cover-page h2 { margin: 0; color: #fff; font-size: clamp(2rem, 5vw, 3.4rem); font-weight: 800; letter-spacing: -.04em; }
        .cover-page p { max-width: 520px; margin: 18px auto 0; color: rgba(255,255,255,.8); font-size: 1rem; line-height: 1.65; }
        .cover-page .cover-meta { margin-top: 42px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; font-size: .78rem; }
        .page-kicker { color: var(--blue); font-weight: 800; font-size: .73rem; letter-spacing: .1em; text-transform: uppercase; }
        .record-heading { margin: 12px 0 24px; padding-bottom: 18px; border-bottom: 2px solid #e6e8f5; }
        .record-title { margin: 0 0 12px; font-size: clamp(1.4rem, 3vw, 2rem); line-height: 1.25; }
        .title-meta { display: flex; flex-wrap: wrap; gap: 8px; }
        .title-chip { display: inline-flex; align-items: center; gap: 5px; max-width: 100%; padding: 6px 10px; border: 1px solid #dce1f1; border-radius: 999px; color: #4c5374; background: #f6f7fc; font-size: .78rem; line-height: 1.25; }
        .title-chip i { color: var(--blue); font-size: .95rem; }
        .record-intro { display: grid; grid-template-columns: minmax(0, 1fr) minmax(260px, .8fr); gap: 25px; align-items: start; margin-bottom: 26px; }
        .record-intro--no-photo { display: block; }
        .record-intro .record-section { margin-top: 0; }
        .featured-photo { display: flex; align-items: center; justify-content: center; width: 100%; max-height: min(48vw, 420px); margin: 0; overflow: hidden; border: 0; border-radius: 12px; background: #eef0f8; cursor: zoom-in; }
        .featured-photo img { display: block; width: 100%; height: auto; max-height: min(48vw, 420px); object-fit: contain; }
        .record-media-gallery { min-width: 0; }
        .additional-photo-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(72px, 1fr)); gap: 8px; margin: 9px 0 0; }
        .additional-photo-gallery a { display: block; height: 68px; overflow: hidden; border: 1px solid #dce1f1; border-radius: 8px; background: #eef0f8; }
        .additional-photo-gallery img { display: block; width: 100%; height: 100%; object-fit: cover; }
        .photo-lightbox { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 28px; background: rgba(6,8,28,.9); }
        .photo-lightbox.is-open { display: flex; }
        .photo-lightbox img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 7px; box-shadow: 0 18px 60px rgba(0,0,0,.5); }
        .photo-lightbox button { border: 0; cursor: pointer; }
        .photo-lightbox .lightbox-close { position: absolute; top: 18px; right: 22px; width: 42px; height: 42px; border-radius: 50%; color: #252b81; background: #fff; font-size: 1.5rem; }
        .photo-lightbox .lightbox-nav { position: absolute; top: 50%; width: 48px; height: 48px; border-radius: 50%; color: #252b81; background: #fff; font-size: 1.9rem; line-height: 1; transform: translateY(-50%); }
        .photo-lightbox .lightbox-prev { left: 24px; } .photo-lightbox .lightbox-next { right: 24px; }
        .photo-lightbox .lightbox-nav:disabled { cursor: default; opacity: .35; }
        .lightbox-count { position: absolute; bottom: 22px; padding: 6px 11px; border-radius: 999px; color: #fff; background: rgba(255,255,255,.15); font-size: .8rem; font-weight: 700; }
        .record-meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 26px; }
        .meta-card { min-height: 74px; padding: 13px 15px; border-radius: 10px; background: #f4f5fc; }
        .meta-card span { display: block; margin-bottom: 5px; color: var(--muted); font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .meta-card strong { display: block; color: var(--ink); font-size: .94rem; line-height: 1.4; }
        .record-section { margin-top: 22px; }
        .record-section h3 { margin: 0 0 9px; color: var(--blue); font-size: .83rem; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
        .record-section p { margin: 0; color: #343952; line-height: 1.7; white-space: pre-wrap; }
        .rich-text { color: #343952; line-height: 1.7; }
        .rich-text p { margin: 0 0 1em; white-space: normal; }
        .rich-text p:last-child { margin-bottom: 0; }
        .rich-text ul, .rich-text ol { margin: 0 0 1em; padding-left: 1.4em; }
        .rich-text li { margin: .25em 0; }
        .record-link-button { display: inline-flex; align-items: center; gap: 6px; margin: 3px 4px 3px 0; padding: 7px 10px; border-radius: 8px; color: #fff; background: #3c40c6; font-size: .82rem; font-weight: 700; line-height: 1.25; text-decoration: none; word-break: break-all; }
        .record-link-button:hover { color: #fff; background: #272b8c; }
        .reference-hero { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 22px; margin-top: 24px; padding: 20px 22px; border-radius: 14px; color: #fff; background: linear-gradient(135deg,#272b8c,#565de8); }
        .reference-hero h3 { margin: 0 0 12px; color: rgba(255,255,255,.82); font-size: .75rem; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
        .reference-hero p { margin: 0; color: #fff; line-height: 1.7; white-space: pre-wrap; }
        .reference-hero .rich-text, .reference-hero .rich-text p { color: #fff; }
        .reference-hero .record-link-button { color: #272b8c; background: #fff; }
        .reference-hero .record-link-button:hover { color: #272b8c; background: #e9ebff; }
        @media (max-width: 700px) { .reference-hero { grid-template-columns: 1fr; } }
        .attachment-list { margin: 0; padding-left: 18px; color: #343952; }
        .attachment-list li { margin: 6px 0; }
        .page-footer { position: absolute; right: 28px; bottom: 22px; color: #8c91a9; font-size: .76rem; }
        .book-page { position: relative; padding-bottom: 65px; }
        .flipbook-stage-wrap { position: relative; }
        .flipbook-controls { position: absolute; inset: 0; z-index: 3; margin: 0; color: #fff; pointer-events: none; }
        .flipbook-controls button { position: absolute; top: 50%; width: 48px; height: 48px; border: 0; border-radius: 50%; color: var(--ink); background: #fff; font-size: 1.45rem; cursor: pointer; box-shadow: 0 6px 18px rgba(0,0,0,.24); transform: translateY(-50%); pointer-events: auto; transition: transform .18s ease, box-shadow .18s ease; }
        .flipbook-controls .js-prev { left: 18px; }
        .flipbook-controls .js-next { right: 18px; }
        .flipbook-controls button:hover:not(:disabled) { box-shadow: 0 9px 22px rgba(0,0,0,.3); transform: translateY(-50%) scale(1.07); }
        .flipbook-controls button:disabled { cursor: not-allowed; opacity: .4; }
        .page-count { position: absolute; right: 50%; bottom: 17px; min-width: 115px; padding: 5px 10px; border-radius: 999px; color: #fff; background: rgba(10,13,50,.58); font-size: .78rem; font-weight: 700; text-align: center; transform: translateX(50%); }
        .empty-page { display: flex; align-items: center; justify-content: center; text-align: center; }
        .empty-page h2 { margin: 0 0 8px; font-size: 1.6rem; }
        .empty-page p { margin: 0; color: var(--muted); }
        @media (max-width: 700px) { .flipbook-shell { padding: 18px 12px 30px; } .flipbook-topbar { align-items: flex-start; flex-direction: column; } .book-stage { min-height: 520px; padding: 7px; } .book-page { min-height: 500px; padding: 28px 22px 54px; } .record-intro { grid-template-columns: 1fr; } .featured-photo, .featured-photo img { max-height: min(72vw, 420px); } .record-meta { grid-template-columns: 1fr; gap: 9px; } .flipbook-controls button { width: 40px; height: 40px; font-size: 1.2rem; } .flipbook-controls .js-prev { left: 11px; } .flipbook-controls .js-next { right: 11px; } .page-count { bottom: 11px; } .photo-lightbox { padding: 16px 58px; } .photo-lightbox .lightbox-nav { width: 40px; height: 40px; } .photo-lightbox .lightbox-prev { left: 10px; } .photo-lightbox .lightbox-next { right: 10px; } }
        @media print { body { background: #fff; } .flipbook-shell { width: auto; padding: 0; } .flipbook-topbar, .flipbook-controls { display: none; } .book-stage { padding: 0; background: #fff; box-shadow: none; } .book-page { display: block !important; min-height: auto; margin: 0; border-radius: 0; box-shadow: none; page-break-after: always; } .cover-page { min-height: 100vh; } }
    </style>
</head>
<body>
    <main class="flipbook-shell">
        <header class="flipbook-topbar">
            <div>
                <h1><?= accomplishment_flipbook_escape($sectionName); ?> Accomplishments</h1>
                <p><?= accomplishment_flipbook_escape($scopeLabel); ?> · <?= count($records); ?> record<?= count($records) === 1 ? '' : 's'; ?></p>
            </div>
            <div class="topbar-actions">
                <a href="<?= base_url(); ?>Page/viewSecAccomplishments">Back to list</a>
            </div>
        </header>
        <div class="flipbook-stage-wrap">
        <section class="book-stage" aria-label="Accomplishment flipbook">
            <article class="book-page cover-page is-active">
                <div class="cover-icon"><i class="mdi mdi-book-open-page-variant"></i></div>
                <h2>Accomplishments</h2>
                <p>A page-by-page report of <?= accomplishment_flipbook_escape($scopeLabel); ?> for <?= accomplishment_flipbook_escape($sectionName); ?>.</p>
                <div class="cover-meta">Generated <?= date('F j, Y'); ?></div>
                <span class="page-footer">Cover</span>
            </article>
            <?php if (empty($records)) : ?>
                <article class="book-page empty-page">
                    <div><h2>No records available</h2><p>Add an accomplishment first, then generate the flipbook again.</p></div>
                    <span class="page-footer">1</span>
                </article>
            <?php else : ?>
                <?php foreach ($records as $index => $record) : ?>
                    <?php
                        $attachments = isset($reportGroups[(int) $record->id]) ? $reportGroups[(int) $record->id] : array();
						$additionalPhotos = isset($additionalPhotoGroups[(int) $record->id]) ? $additionalPhotoGroups[(int) $record->id] : array();
                        $featuredPhoto = isset($record->featured_photo) ? trim((string) $record->featured_photo) : '';
                        $kraTitle = isset($kraTitles[(int) $record->kra_id]) ? $kraTitles[(int) $record->kra_id] : '';
                        $resourceText = trim((string) $record->resources);
						$particularsHtml = accomplishment_flipbook_rich_text($record->particulars);
						$notesHtml = accomplishment_flipbook_rich_text_linkify(accomplishment_flipbook_rich_text($record->notes));
                    ?>
                    <article class="book-page">
                        <div class="page-kicker">Accomplishment <?= $index + 1; ?></div>
                        <div class="record-heading">
                            <h2 class="record-title"><?= accomplishment_flipbook_escape(accomplishment_flipbook_text($record->activity) ?: 'Untitled accomplishment'); ?></h2>
                            <div class="title-meta">
                                <span class="title-chip"><i class="mdi mdi-calendar-outline"></i><?= accomplishment_flipbook_escape($record->dateConducted ?: 'Date not specified'); ?></span>
                                <span class="title-chip"><i class="mdi mdi-map-marker-outline"></i><?= accomplishment_flipbook_escape($record->venue ?: 'Venue not specified'); ?></span>
                            </div>
                        </div>
                        <div class="record-intro <?= $featuredPhoto === '' && empty($additionalPhotos) ? 'record-intro--no-photo' : ''; ?>">
                            <?php if ($particularsHtml !== '') : ?>
                                <section class="record-section"><h3>Activity/Accomplishment Details</h3><div class="rich-text"><?= $particularsHtml; ?></div></section>
                            <?php endif; ?>
                            <?php if ($featuredPhoto !== '' || !empty($additionalPhotos)) : ?>
                                <div class="record-media-gallery">
                                    <?php if ($featuredPhoto !== '') : ?><a class="featured-photo js-photo-lightbox" href="<?= base_url(); ?>upload/accomplishment_featured_photos/<?= rawurlencode($featuredPhoto); ?>" aria-label="View full featured photo"><img src="<?= base_url(); ?>upload/accomplishment_featured_photos/<?= rawurlencode($featuredPhoto); ?>" alt="Featured photo for <?= accomplishment_flipbook_escape(accomplishment_flipbook_text($record->activity)); ?>"></a><?php endif; ?>
                                    <?php if (!empty($additionalPhotos)) : ?><div class="additional-photo-gallery"><?php foreach ($additionalPhotos as $photo) : ?><a class="js-photo-lightbox" href="<?= base_url(); ?>upload/accomplishment_additional_photos/<?= rawurlencode($photo->file_name); ?>" aria-label="View additional photo"><img src="<?= base_url(); ?>upload/accomplishment_additional_photos/<?= rawurlencode($photo->file_name); ?>" alt="Additional photo"></a><?php endforeach; ?></div><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($notesHtml !== '' || $resourceText !== '') : ?>
                            <section class="reference-hero">
                                <?php if ($notesHtml !== '') : ?><div class="reference-item"><h3>Additional Notes</h3><div class="rich-text"><?= $notesHtml; ?></div></div><?php endif; ?>
                                <?php if ($resourceText !== '') : ?><div class="reference-item"><h3>Resource Link</h3><p><?= accomplishment_flipbook_linkify($resourceText); ?></p></div><?php endif; ?>
                            </section>
                        <?php endif; ?>
                        <?php if ($kraTitle !== '') : ?>
                            <section class="record-section"><h3>KRA</h3><p><?= accomplishment_flipbook_escape($kraTitle); ?></p></section>
                        <?php endif; ?>
                        <?php if (!empty($attachments)) : ?>
                            <section class="record-section"><h3>Attached Reports</h3><ul class="attachment-list"><?php foreach ($attachments as $attachment) : ?><li><?= accomplishment_flipbook_escape($attachment->document_name ?: $attachment->original_name); ?></li><?php endforeach; ?></ul></section>
                        <?php endif; ?>
                        <span class="page-footer"><?= $index + 2; ?></span>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <nav class="flipbook-controls" aria-label="Flipbook navigation">
            <button type="button" class="js-prev" aria-label="Previous page"><i class="mdi mdi-chevron-left"></i></button>
            <span class="page-count" aria-live="polite"></span>
            <button type="button" class="js-next" aria-label="Next page"><i class="mdi mdi-chevron-right"></i></button>
        </nav>
        </div>
    </main>
    <div class="photo-lightbox" role="dialog" aria-modal="true" aria-label="Full featured photo">
        <button type="button" class="lightbox-close" aria-label="Close full image">&times;</button>
        <button type="button" class="lightbox-nav lightbox-prev" aria-label="Previous image"><i class="mdi mdi-chevron-left"></i></button>
        <img src="" alt="Full featured photo">
        <button type="button" class="lightbox-nav lightbox-next" aria-label="Next image"><i class="mdi mdi-chevron-right"></i></button>
        <span class="lightbox-count" aria-live="polite"></span>
    </div>
    <script>
        (function () {
            var pages = Array.prototype.slice.call(document.querySelectorAll('.book-page'));
            var current = 0;
            var previous = document.querySelector('.js-prev');
            var next = document.querySelector('.js-next');
            var counter = document.querySelector('.page-count');
            function showPage(index, direction) {
                current = Math.max(0, Math.min(index, pages.length - 1));
                pages.forEach(function (page, pageIndex) {
                    page.classList.toggle('is-active', pageIndex === current);
                    page.classList.remove('turn-next', 'turn-prev');
                });
                if (direction) pages[current].classList.add(direction === 'next' ? 'turn-next' : 'turn-prev');
                previous.disabled = current === 0;
                next.disabled = current === pages.length - 1;
                counter.textContent = 'Page ' + (current + 1) + ' of ' + pages.length;
            }
            previous.addEventListener('click', function () { showPage(current - 1, 'prev'); });
            next.addEventListener('click', function () { showPage(current + 1, 'next'); });
            document.addEventListener('keydown', function (event) {
                if (lightbox && lightbox.classList.contains('is-open')) return;
                if (event.key === 'ArrowLeft') previous.click();
                if (event.key === 'ArrowRight') next.click();
            });
            showPage(0);

            var lightbox = document.querySelector('.photo-lightbox');
            var lightboxImage = lightbox.querySelector('img');
            var lightboxPrevious = lightbox.querySelector('.lightbox-prev');
            var lightboxNext = lightbox.querySelector('.lightbox-next');
            var lightboxCount = lightbox.querySelector('.lightbox-count');
            var galleryLinks = [];
            var galleryIndex = 0;
            function showLightboxImage(index) {
                if (!galleryLinks.length) return;
                galleryIndex = (index + galleryLinks.length) % galleryLinks.length;
                var link = galleryLinks[galleryIndex];
                lightboxImage.src = link.href;
                lightboxImage.alt = link.querySelector('img') ? link.querySelector('img').alt : 'Accomplishment photo';
                lightboxPrevious.disabled = galleryLinks.length < 2;
                lightboxNext.disabled = galleryLinks.length < 2;
                lightboxCount.textContent = galleryLinks.length > 1 ? (galleryIndex + 1) + ' of ' + galleryLinks.length : '';
            }
            function closeLightbox() { lightbox.classList.remove('is-open'); lightboxImage.src = ''; galleryLinks = []; }
            document.querySelectorAll('.js-photo-lightbox').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    var gallery = link.closest('.record-media-gallery');
                    galleryLinks = gallery ? Array.prototype.slice.call(gallery.querySelectorAll('.js-photo-lightbox')) : [link];
                    galleryIndex = Math.max(0, galleryLinks.indexOf(link));
                    showLightboxImage(galleryIndex);
                    lightbox.classList.add('is-open');
                });
            });
            lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
            lightboxPrevious.addEventListener('click', function () { showLightboxImage(galleryIndex - 1); });
            lightboxNext.addEventListener('click', function () { showLightboxImage(galleryIndex + 1); });
            lightbox.addEventListener('click', function (event) { if (event.target === lightbox) closeLightbox(); });
            document.addEventListener('keydown', function (event) {
                if (!lightbox.classList.contains('is-open')) return;
                if (event.key === 'Escape') closeLightbox();
                if (event.key === 'ArrowLeft') { event.preventDefault(); showLightboxImage(galleryIndex - 1); }
                if (event.key === 'ArrowRight') { event.preventDefault(); showLightboxImage(galleryIndex + 1); }
            });
        })();
    </script>
</body>
</html>
