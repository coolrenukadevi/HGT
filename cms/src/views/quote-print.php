<?php
/**
 * Branded A4 quotation + itinerary (CRM CUSTOM level). Standalone page: the browser's "Save as PDF" makes the file.
 * Vars: $e, $pkg, $row, $q, $res, $by, $terms, $co.
 * One total cost only — never a per-person, hotel or transport break-up (owner rule 2026-10-04).
 */
$r = $res['r'];
$dmy = function ($d, $f = 'd M Y') { return $d ? date($f, strtotime($d)) : ''; };
$dates = $q['travel_from'] ? $dmy($q['travel_from']) . ($q['travel_to'] ? ' – ' . $dmy($q['travel_to']) : '') : 'To be confirmed';
$ref = quote_ref($row);
$facts = array_filter(array(
    'Destination' => $q['destination'],
    'Travel dates' => $dates,
    'Duration' => $q['duration'],
    'Travellers' => $q['travellers'],
    'Meal plan' => $r['meals'],
    'Hotel category' => $r['hotel'],
    'Transport' => $r['transfers'],
    'Starts' => $r['start'],
    'Ends' => $r['end'],
), function ($v) { return $v !== '' && $v !== null; });
$days = $q['days'];
$dayDate = function ($i) use ($q) { return $q['travel_from'] ? strtoupper(date('d M', strtotime($q['travel_from'] . ' +' . $i . ' days'))) : ''; };
$amount = $q['total_amount'] !== '' ? '₹ ' . $q['total_amount'] . (preg_match('/[\.]/', $q['total_amount']) ? '' : '/-') : '';
?><!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($ref . ' · ' . $q['title'] . ' · Holiday Guru Travel') ?></title>
<style>
@font-face { font-family: "Plus Jakarta Sans"; font-weight: 700; font-display: block; src: url("/assets/fonts/hg/plus-jakarta-sans-latin-700-normal.woff2") format("woff2"); }
@font-face { font-family: "Plus Jakarta Sans"; font-weight: 800; font-display: block; src: url("/assets/fonts/hg/plus-jakarta-sans-latin-800-normal.woff2") format("woff2"); }
@font-face { font-family: "Inter"; font-weight: 400; font-display: block; src: url("/assets/fonts/hg/inter-latin-400-normal.woff2") format("woff2"); }
@font-face { font-family: "Inter"; font-weight: 600; font-display: block; src: url("/assets/fonts/hg/inter-latin-600-normal.woff2") format("woff2"); }
:root { --navy: #0A163D; --navy-2: #1A2A66; --orange: #FE7F16; --orange-text: #A84B00; --orange-50: #FFF1E5; --blue: #1689D8; --blue-50: #E8F3FC; --line: #E3E8F0; --text: #1A1F36; --muted: #5B6478; --surface: #F5F8FC; }
@page { size: A4; margin: 0; }
* { box-sizing: border-box; }
html, body { margin: 0; background: #DDE3EC; color: var(--text); font: 400 10.5pt/1.55 "Inter", "Segoe UI", Arial, sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
h1, h2, h3, .brand { font-family: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif; color: var(--navy); }
.sheet { width: 210mm; margin: 12mm auto; background: #fff; box-shadow: 0 6px 30px rgba(10,22,61,.18); }
.doc { width: 100%; border-collapse: collapse; }
.doc > thead td, .doc > tfoot td { padding: 0; }
.doc > tbody > tr > td { padding: 0 16mm; vertical-align: top; }
/* Running header / footer (repeat on every printed page through thead/tfoot) */
.hd { display: flex; align-items: center; justify-content: space-between; gap: 10mm; padding: 9mm 16mm 5mm; border-bottom: 3px solid var(--navy); position: relative; }
.hd::after { content: ""; position: absolute; left: 16mm; bottom: -3px; width: 42mm; height: 3px; background: var(--orange); }
.hd img { width: 52mm; height: auto; display: block; }
.hd address { font-style: normal; text-align: right; font-size: 8.6pt; line-height: 1.5; color: var(--muted); }
.hd address b { display: block; font: 800 10.5pt/1.3 "Plus Jakarta Sans", Arial, sans-serif; color: var(--navy); letter-spacing: .04em; }
.ft { margin-top: 8mm; }
.ft-space { height: 0; }
.keep { break-inside: avoid; }
h2.pb { break-before: page; margin-top: 4mm; }
.ft__tag { padding: 0 16mm 2.5mm; font: 600 9pt/1.4 "Plus Jakarta Sans", Arial, sans-serif; color: var(--orange-text); }
.ft__bar { display: flex; justify-content: space-between; gap: 6mm; padding: 3.5mm 16mm; background: var(--navy); color: #fff; font-size: 8.6pt; border-top: 2.5mm solid var(--orange); }
.ft__bar span { white-space: nowrap; }
.ft__bar b { color: #FFC78F; font-weight: 600; }
/* Content */
.title { display: flex; justify-content: space-between; align-items: flex-end; gap: 8mm; margin: 7mm 0 5mm; }
.eyebrow { margin: 0 0 1mm; font: 700 8.4pt/1.2 "Plus Jakarta Sans", Arial, sans-serif; letter-spacing: .14em; text-transform: uppercase; color: var(--orange-text); }
h1 { margin: 0; font-size: 19pt; line-height: 1.2; font-weight: 800; }
.ref { text-align: right; font-size: 8.6pt; color: var(--muted); white-space: nowrap; }
.ref b { color: var(--navy); font-weight: 600; }
.greet p { margin: 0 0 2.5mm; }
.greet .hi { font-weight: 600; color: var(--navy); }
h2 { display: flex; align-items: center; gap: 3mm; margin: 7mm 0 3mm; font-size: 13pt; font-weight: 800; break-after: avoid; }
h2::before { content: ""; width: 4px; height: 1.1em; border-radius: 2px; background: var(--orange); }
.facts { display: grid; grid-template-columns: repeat(3, 1fr); border: 1px solid var(--line); border-radius: 3mm; overflow: hidden; }
.facts div { padding: 2.6mm 3.5mm; border-right: 1px solid var(--line); border-bottom: 1px solid var(--line); background: #fff; }
.facts div:nth-child(3n) { border-right: 0; }
.facts dt { font-size: 7.6pt; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); }
.facts dd { margin: .5mm 0 0; font-weight: 600; color: var(--navy); }
table.grid { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid var(--line); border-radius: 3mm; overflow: hidden; }
table.grid th { background: var(--navy); color: #fff; text-align: left; font: 700 8.6pt/1.3 "Plus Jakarta Sans", Arial, sans-serif; letter-spacing: .06em; text-transform: uppercase; padding: 2.6mm 3.5mm; }
table.grid td { padding: 2.4mm 3.5mm; border-top: 1px solid var(--line); }
table.grid tr:nth-child(even) td { background: var(--surface); }
table.grid td.n { width: 18mm; text-align: center; font-variant-numeric: tabular-nums; }
table.grid tr { break-inside: avoid; }
.cost { display: flex; align-items: center; justify-content: space-between; gap: 8mm; margin-top: 5mm; padding: 5mm 6mm; border-radius: 3mm; background: linear-gradient(100deg, var(--navy) 0%, var(--navy-2) 100%); color: #fff; break-inside: avoid; }
.cost__k { font: 700 9pt/1.3 "Plus Jakarta Sans", Arial, sans-serif; letter-spacing: .1em; text-transform: uppercase; color: #FFC78F; }
.cost__l { margin-top: 1mm; font-size: 9.6pt; color: #D7DEEE; }
.cost__v { text-align: right; font: 800 20pt/1.1 "Plus Jakarta Sans", Arial, sans-serif; color: #fff; white-space: nowrap; }
.cost__v small { display: block; margin-top: 1mm; font: 600 9pt/1.3 "Inter", Arial, sans-serif; color: #FFC78F; }
.valid { margin: 2mm 0 0; font-size: 8.6pt; color: var(--muted); }
.two { display: grid; grid-template-columns: 1fr 1fr; gap: 6mm; }
.box { border: 1px solid var(--line); border-radius: 3mm; padding: 3.5mm 4.5mm; break-inside: avoid; }
.box h3 { margin: 0 0 2mm; font-size: 10.5pt; font-weight: 800; }
.box--in { border-top: 3px solid var(--blue); }
.box--out { border-top: 3px solid var(--orange); }
ul.ck { margin: 0; padding: 0; list-style: none; }
ul.ck li { position: relative; padding-left: 5mm; margin: 0 0 1.3mm; }
ul.ck li::before { content: ""; position: absolute; left: 0; top: .55em; width: 2mm; height: 2mm; border-radius: 50%; background: var(--blue); }
.box--out ul.ck li::before { background: var(--orange); border-radius: 0; height: .5mm; top: .75em; }
.itin-meta { margin: 0 0 3mm; color: var(--muted); }
.itin-meta b { color: var(--navy); }
.day { margin: 0 0 4mm; border: 1px solid var(--line); border-radius: 3mm; overflow: hidden; break-inside: avoid; }
.day__h { display: flex; align-items: center; gap: 3.5mm; padding: 2.6mm 4mm; background: var(--blue-50); border-bottom: 1px solid var(--line); }
.day__n { flex: none; display: grid; place-items: center; min-width: 15mm; padding: 1mm 2mm; border-radius: 2mm; background: var(--navy); color: #fff; font: 800 8.6pt/1.2 "Plus Jakarta Sans", Arial, sans-serif; letter-spacing: .06em; text-align: center; }
.day__n small { display: block; font-weight: 700; font-size: 7pt; color: #FFC78F; letter-spacing: .04em; }
.day__t { margin: 0; font-size: 10.8pt; font-weight: 800; color: var(--navy); }
.day__b { padding: 3mm 4mm 3.2mm; }
.day__b p { margin: 0 0 2mm; white-space: pre-line; }
.day__b ul { margin: 0 0 2mm; padding-left: 5mm; }
.day__stay { margin: 0; font-weight: 600; color: var(--orange-text); }
.notes { padding: 3.5mm 4.5mm; border-left: 3px solid var(--orange); background: var(--orange-50); border-radius: 0 3mm 3mm 0; white-space: pre-line; break-inside: avoid; }
.terms { columns: 2; column-gap: 7mm; font-size: 8.5pt; line-height: 1.45; }
.terms section { break-inside: avoid; margin: 0 0 4mm; }
.terms h3 { margin: 0 0 1.5mm; font-size: 9.6pt; font-weight: 800; }
.terms ul { margin: 0; padding-left: 4mm; }
.terms li { margin: 0 0 .9mm; }
.sign { display: flex; justify-content: space-between; align-items: flex-end; gap: 8mm; margin-top: 8mm; padding-top: 5mm; border-top: 1px solid var(--line); break-inside: avoid; }
.sign p { margin: 0; }
.sign .thanks { font: 800 12pt/1.3 "Plus Jakarta Sans", Arial, sans-serif; color: var(--navy); }
.sign .who { font-weight: 600; color: var(--orange-text); }
.sign .gst { text-align: right; font-size: 8.6pt; color: var(--muted); }
.toolbar { position: sticky; top: 0; z-index: 2; display: flex; justify-content: center; gap: 10px; padding: 10px; background: var(--navy); }
.toolbar a, .toolbar button { font: 600 14px/1 "Inter", Arial, sans-serif; padding: 10px 16px; border-radius: 8px; border: 0; cursor: pointer; text-decoration: none; }
.toolbar button { background: var(--orange); color: #1A1F36; }
.toolbar a { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.4); }
@media print {
  html, body { background: #fff; }
  .sheet { margin: 0; box-shadow: none; width: auto; }
  .toolbar { display: none; }
  /* Footer repeats at the bottom of every printed page; the tfoot spacer keeps content clear of it. */
  .ft { position: fixed; left: 0; right: 0; bottom: 0; margin: 0; background: #fff; }
  .ft-space { height: 30mm; }
}
@media screen and (max-width: 820px) { .sheet { width: auto; margin: 0; } .doc > tbody > tr > td { padding: 0 16px; } .hd, .ft__bar, .ft__tag { padding-left: 16px; padding-right: 16px; } .facts { grid-template-columns: 1fr 1fr; } .two, .terms { display: block; columns: 1; } }
</style>
</head>
<body>
<div class="toolbar"><button type="button" onclick="window.print()">Download PDF</button><a href="/enquiries/<?= (int) $e['enquiry_pk'] ?>/quotation">Back to editor</a></div>
<div class="sheet">
<table class="doc" role="presentation">
<thead><tr><td>
    <header class="hd">
        <img src="/assets/brand/holiday-guru-travel-logo-480.webp" width="480" height="240" alt="Holiday Guru Travel">
        <address><b><?= e(strtoupper($co['legal'])) ?></b><?= e($co['addr1']) ?>, <?= e($co['addr2']) ?><br>Phone: <?= e($co['phone']) ?><?= $co['whatsapp'] ? ' · WhatsApp: ' . e($co['whatsapp']) : '' ?><br>Email: <?= e(strtolower($co['email'])) ?> · <?= e($co['site']) ?></address>
    </header>
</td></tr></thead>
<tfoot><tr><td><div class="ft-space" aria-hidden="true"></div></td></tr></tfoot>
<tbody><tr><td>

    <div class="title">
        <div>
            <p class="eyebrow">Tour quotation &amp; itinerary</p>
            <h1><?= e($q['title']) ?></h1>
        </div>
        <p class="ref">Ref <b><?= e($ref) ?></b><br>Enquiry no. <b><?= e(enquiry_no($e['enquiry_pk'])) ?></b><br>Date <b><?= e($dmy($row['updated_at'])) ?></b><?php if ($e['package_id']) { ?><br>Package ID <b><?= e($e['package_id']) ?></b><?php } ?></p>
    </div>

    <div class="greet">
        <p class="hi"><?= e($q['salutation'] ?: 'Dear Guest,') ?></p>
        <p>Greetings from Holiday Guru Travel! Thank you for your enquiry. As requested, please find below the tour plan and cost for your trip. We would be happy to adjust anything to suit you.</p>
    </div>

    <?php if ($facts) { ?>
    <h2>Trip summary</h2>
    <dl class="facts"><?php foreach ($facts as $k => $v) { ?><div><dt><?= e($k) ?></dt><dd><?= e($v) ?></dd></div><?php } ?></dl>
    <?php } ?>

    <?php if ($q['hotels']) { ?>
    <h2>Hotel details</h2>
    <table class="grid">
        <thead><tr><th scope="col">Destination</th><th scope="col" class="n">Nights</th><th scope="col"><?= e($r['hotel'] ?: 'Hotel') ?></th></tr></thead>
        <tbody><?php foreach ($q['hotels'] as $h) { ?><tr><td><?= e($h['place']) ?></td><td class="n"><?= str_pad((string) (int) $h['nights'], 2, '0', STR_PAD_LEFT) ?></td><td><?= e($h['hotel']) ?></td></tr><?php } ?></tbody>
    </table>
    <?php } ?>

    <?php if ($amount !== '') { ?>
    <div class="cost">
        <div><div class="cost__k">Package cost</div><div class="cost__l"><?= e($q['total_label'] ?: 'Total package cost') ?></div></div>
        <div class="cost__v"><?= e($amount) ?><?php if ($q['tax_note'] !== '') { ?><small><?= e($q['tax_note']) ?></small><?php } ?></div>
    </div>
    <?php } else { ?>
    <div class="cost"><div><div class="cost__k">Package cost</div><div class="cost__l">Shared by your travel expert with this itinerary.</div></div></div>
    <?php } ?>
    <?php if ($q['valid_until']) { ?><p class="valid">Rooms are limited at this price; if availability runs out the cost may change. This quotation is valid until <b><?= e($dmy($q['valid_until'])) ?></b> — please confirm in time.</p><?php } ?>

    <h2>Inclusions &amp; exclusions</h2>
    <div class="two">
        <div class="box box--in"><h3>Included</h3><ul class="ck"><?php foreach ($r['inclusions'] as $i) { ?><li><?= e($i) ?></li><?php } ?></ul></div>
        <div class="box box--out"><h3>Not included</h3><ul class="ck"><?php foreach ($r['exclusions'] as $i) { ?><li><?= e($i) ?></li><?php } ?></ul></div>
    </div>

    <?php if ($days) { ?>
    <div class="keep">
    <h2>Day-wise itinerary</h2>
    <p class="itin-meta"><b><?= e($q['title']) ?></b><?= $q['duration'] ? ' — ' . e($q['duration']) : '' ?><?= $q['travel_from'] ? ' · ' . e($dates) : '' ?><?= $r['start'] ? ' · Arrival: ' . e($r['start']) : '' ?><?= $r['end'] ? ' · Departure: ' . e($r['end']) : '' ?></p>
    <?php foreach ($days as $i => $d) { $sights = quote_lines($d['sightseeing']); ?>
    <?= $i === 1 ? '</div>' : '' ?>
    <article class="day">
        <div class="day__h"><span class="day__n">DAY <?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?><?php if ($dayDate($i)) { ?><small><?= e($dayDate($i)) ?></small><?php } ?></span><h3 class="day__t"><?= e($d['title']) ?></h3></div>
        <div class="day__b">
            <?php if ($d['text'] !== '') { ?><p><?= e($d['text']) ?></p><?php } ?>
            <?php if ($sights) { ?><ul><?php foreach ($sights as $s) { ?><li><?= e($s) ?></li><?php } ?></ul><?php } ?>
            <?php if ($d['overnight'] !== '') { ?><p class="day__stay">Overnight stay: <?= e($d['overnight']) ?></p><?php } ?>
        </div>
    </article>
    <?php } ?>
    <?= count($days) === 1 ? '</div>' : '' ?>
    <?php } ?>

    <?php if (trim($r['special_notes']) !== '') { ?>
    <h2>Special notes</h2>
    <div class="notes"><?= e(trim($r['special_notes'])) ?></div>
    <?php } ?>

    <div class="sign">
        <div>
            <p>We are happy to help — call or email us for any clarification.</p>
            <p class="thanks">Thanks &amp; regards,</p>
            <p class="who"><?= e($by ? $by['name'] : $co['legal']) ?></p>
            <p>Sales Team, <?= e($co['legal']) ?></p>
        </div>
        <p class="gst"><?= e($co['legal']) ?><?php if ($co['gstin']) { ?><br>GSTIN <?= e($co['gstin']) ?><?php } ?></p>
    </div>
    <h2 class="pb">Terms &amp; conditions</h2>
    <p class="itin-meta">These terms form part of quotation <b><?= e($ref) ?></b>.</p>
    <div class="terms">
        <?php foreach ($terms as $head => $items) { ?><section><h3><?= e($head) ?></h3><ul><?php foreach ($items as $t) { ?><li><?= e($t) ?></li><?php } ?></ul></section><?php } ?>
    </div>

</td></tr></tbody>
</table>
<footer class="ft">
    <p class="ft__tag">See you soon on your next trip to another fascinating destination…</p>
    <div class="ft__bar"><span><b>Call</b> <?= e($co['phone']) ?><?= $co['whatsapp'] ? ' · ' . e($co['whatsapp']) : '' ?></span><span><b>Web</b> <?= e($co['site']) ?></span><span><b>Email</b> <?= e(strtolower($co['email'])) ?></span></div>
</footer>
</div>
</body>
</html>
