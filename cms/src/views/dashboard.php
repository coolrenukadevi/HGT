<?php
$total = count($rows);
ob_start(); ?>
<?php
// KPI tiles: real numbers only. The change line compares like with like (this week vs the week before).
$tile = function ($label, $value, $icon, $tone, $sub) {
    return '<div class="cms-stat cms-stat--' . $tone . '"><div><span class="cms-stat__label">' . e($label) . '</span><strong class="cms-stat__value">' . $value . '</strong><p class="cms-stat__sub">' . $sub . '</p></div><span class="cms-stat__icon" aria-hidden="true">' . icon($icon) . '</span></div>';
};
$change = function ($now, $before) {
    if ($before === 0) return $now ? '<span class="cms-delta cms-delta--up">' . icon('up') . 'New</span> none the week before' : 'none this week or last';
    $pct = (int) round(100 * ($now - $before) / $before);
    if ($pct === 0) return '<span class="cms-delta">±0%</span> same as last week';
    return '<span class="cms-delta cms-delta--' . ($pct > 0 ? 'up' : 'down') . '">' . icon($pct > 0 ? 'up' : 'down') . ($pct > 0 ? '+' : '−') . abs($pct) . '%</span> since last week';
};
?>
<div class="cms-kpis cms-kpis--stat">
    <?= $tile('Active packages', $total, 'box', 'blue', (int) ($by['published'] ?? 0) . ' published · ' . (int) ($by['draft'] ?? 0) . ' draft · ' . (int) ($by['in_review'] ?? 0) . ' in review') ?>
    <?= $week !== null
        ? $tile('Enquiries this week', $week, 'inbox', 'orange', $change($week, $prevWeek))
        : $tile('Offer Zone', $offersLive . ' <small>/ ' . (int) cms_config('offer_zone_limit') . '</small>', 'tag', 'orange', 'live offers (internal limit)') ?>
    <?= $tile('Ready to publish', $ready, 'check', 'green', 'of ' . $total . ' pass every mandatory check') ?>
    <?= $tile('With a current rate', $rated, 'rupee', 'yellow', ($total - $rated) . ' show “Price on request”' . ($week !== null ? ' · ' . $offersLive . ' live offers' : '')) ?>
</div>
<p class="cms-note"><?= icon('info') ?>Counts on this dashboard are internal. The public website never shows package totals.</p>

<div class="cms-dashgrid">
<div class="cms-dashgrid__main">
<div class="cms-overview">
<?php
if ($enqDays) {
    echo card('Enquiries overview', chart_line($enqDays, 'enquiries', 'Enquiries per day, last 30 days'), array('sub' => $change($week, $prevWeek) . ' · last 30 days, test enquiries excluded', 'actions' => '<a class="cms-btn cms-btn--ghost cms-btn--sm" href="/enquiries">All enquiries</a>', 'class' => 'cms-overview__chart'));
}
?>
</div>

<div class="cms-grid2">
<?php
echo card('Packages by destination', chart_hbars($dest, 'packages', 'Active packages per destination'), array('sub' => 'Active packages (not archived). Click a destination to see its packages.'));
echo card('Package readiness', chart_hbars($readiness, 'packages', 'Packages by number of missing mandatory items'), array('sub' => 'How many mandatory items each active package is missing.', 'actions' => '<a class="cms-btn cms-btn--ghost cms-btn--sm" href="/packages?check=fail">Fix gaps</a>'));
?>
</div>

<div class="cms-grid2">
<?php
$b = '';
if ($gapCount) {
    $b .= '<ul class="cms-bars">';
    $max = max(array_map(function ($g) { return $g[1]; }, $gapCount));
    foreach (array_slice($gapCount, 0, 8, true) as $k => $g) {
        $b .= '<li><span class="cms-bars__l">' . e(preg_replace('/ — .*/', '', $g[0])) . '</span><span class="cms-bars__bar"><span style="width:' . round(100 * $g[1] / $max) . '%"></span></span><span class="cms-bars__n">' . $g[1] . '</span></li>';
    }
    $b .= '</ul>';
} else $b = '<p class="cms-muted">Every active package passes the mandatory checks.</p>';
echo card('Publication gaps', $b, array('sub' => 'Packages missing a mandatory item. Fix these before the next publish.', 'actions' => '<a class="cms-btn cms-btn--ghost cms-btn--sm" href="/packages?check=fail">View packages</a>'));

$b = '<dl class="cms-dl">'
   . '<div><dt>Proposed (pending owner approval)</dt><dd>' . (int) ($idBy['proposed'] ?? 0) . '</dd></div>'
   . '<div><dt>Approved</dt><dd>' . (int) ($idBy['approved'] ?? 0) . '</dd></div>'
   . '<div><dt>Not yet numbered</dt><dd>' . (int) ($idBy['pending'] ?? 0) . '</dd></div></dl>'
   . '<p class="cms-hint">Assignment is ' . (cms_config('package_id_assignment') ? '<strong>on</strong>' : '<strong>off</strong> until the owner approves the Package ID mapping') . '. Offer Codes (OF-…) use their own sequence.</p>';
echo card('Package ID', $b, array('sub' => 'The only package identifier — itinerary, CRM, quotations and payments.'));
?>
</div>

<div class="cms-grid2">
<?php
$b = '';
if ($review) {
    $b .= '<ul class="cms-list">';
    foreach ($review as $p) $b .= '<li><a href="/packages/' . (int) $p['package_pk'] . '?tab=advanced">' . e($p['name']) . '</a> ' . status_pill($p['status']) . '</li>';
    $b .= '</ul>';
} else $b = '<p class="cms-muted">Nothing is waiting for review.</p>';
echo card('Review queue', $b, array('sub' => 'Content → SEO/AEO → Pricing → Approval → Publish'));

$b = '';
if ($expiring) {
    $b .= '<ul class="cms-list">';
    foreach ($expiring as $r) $b .= '<li><a href="/packages/' . (int) $r['package_pk'] . '?tab=pricing">' . e($r['name']) . '</a> <span class="cms-muted">v' . (int) $r['version'] . ' · ends ' . e(dmy($r['valid_until'])) . '</span></li>';
    $b .= '</ul>';
} else $b = '<p class="cms-muted">No approved rate ends in the next 14 days.</p>';
echo card('Rates ending soon', $b, array('sub' => 'After the end date the website shows “Price on request”.'));
?>
</div>

<div class="cms-grid1">
<?php
if (hg_can(role(), 'enquiries')) {
    $b = '';
    if ($enq) {
        $b .= '<ul class="cms-list">';
        foreach ($enq as $x) $b .= '<li><a href="/enquiries/' . (int) $x['enquiry_pk'] . '">' . e($x['name']) . '</a> <span class="cms-muted">' . e($x['package_name'] ?: $x['enquiry_type']) . '</span>' . ($x['is_test'] ? ' <span class="cms-pill cms-pill--test">Test</span>' : '') . '</li>';
        $b .= '</ul>';
    } else $b = '<p class="cms-muted">No enquiries recorded in the CMS yet. Website enquiries still arrive by email until the intake is connected.</p>';
    echo card('Latest enquiries', $b, array('actions' => '<a class="cms-btn cms-btn--ghost cms-btn--sm" href="/enquiries">All enquiries</a>'));
}
?>
</div>
</div>
<aside class="cms-dashgrid__rail" aria-label="Calendar, trips and activity">
<?php
$last = site_last_sync();
$b = '<p class="cms-sync"><span class="cms-sync__dot" aria-hidden="true"></span>' . ($last ? 'Last updated ' . e(dmy($last['at'])) . ', ' . e(substr($last['at'], 11, 5)) . ' UTC' : 'No changes sent yet') . '</p>'
   . '<p class="cms-hint">Publishing, pausing or archiving a package, approving a rate and saving offers update the website automatically. Every update is backed up first.</p>'
   . (hg_can(role(), 'packages', 'manage') ? '<form method="post" action="/site-sync">' . csrf_field() . '<button class="cms-btn cms-btn--ghost cms-btn--sm" type="submit">' . icon('upload') . 'Sync website now</button></form>' : '')
   . (hg_may(role(), 'publish') ? '<form method="post" action="/site-resync" class="cms-mt-s">' . csrf_field() . '<button class="cms-btn cms-btn--ghost cms-btn--sm" type="submit" data-cms-confirm="Copy the website’s current itineraries, inclusions, hotel category and meal plans into the CMS? Packages with unpublished CMS edits are skipped.">' . icon('restore') . 'Re-sync from website</button></form><p class="cms-hint">Use after package data was improved on the website directly, so a CMS publish never writes older data back.</p>' : '');
echo card('Website', $b, array('class' => 'cms-rail-card', 'actions' => '<a class="cms-link" href="' . e(rtrim(cms_config('site_url'), '/')) . '/" target="_blank" rel="noopener">Open site</a>'));

// Calendar: weeks start on Sunday, as in the owner's reference.
$first = strtotime($cal . '-01'); $days = (int) date('t', $first); $lead = (int) date('w', $first);
$prev = date('Y-m', strtotime('-1 month', $first)); $next = date('Y-m', strtotime('+1 month', $first));
$tripTotal = array_sum($tripDays);
?>
<section class="cms-cal" aria-labelledby="cal-title">
    <header class="cms-cal__head">
        <a class="cms-cal__nav" href="/?cal=<?= e($prev) ?>" aria-label="Previous month"><?= icon('left') ?></a>
        <h2 id="cal-title"><?= e(strtoupper(date('F Y', $first))) ?></h2>
        <a class="cms-cal__nav" href="/?cal=<?= e($next) ?>" aria-label="Next month"><?= icon('right') ?></a>
    </header>
    <table class="cms-cal__grid">
        <caption class="cms-sr"><?= e(date('F Y', $first)) ?><?= hg_can(role(), 'enquiries') ? ': highlighted days have customer travel dates' : '' ?></caption>
        <thead><tr><?php foreach (array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat') as $w) { ?><th scope="col" abbr="<?= $w ?>"><?= strtoupper($w) ?></th><?php } ?></tr></thead>
        <tbody><tr>
        <?php for ($i = 0; $i < $lead; $i++) echo '<td></td>';
        for ($d = 1; $d <= $days; $d++) {
            $date = $cal . '-' . sprintf('%02d', $d); $n = isset($tripDays[$date]) ? $tripDays[$date] : 0;
            $cls = ($date === today() ? ' is-today' : '') . ($n ? ' has-trip' : '');
            $label = date('j F', strtotime($date)) . ($date === today() ? ', today' : '') . ($n ? ', ' . $n . ' trip' . ($n === 1 ? '' : 's') . ' starting' : '');
            echo '<td><span class="cms-cal__d' . $cls . '" aria-label="' . e($label) . '"' . ($n ? ' title="' . e($n . ' trip' . ($n === 1 ? '' : 's') . ' starting') . '"' : '') . '>' . $d . '</span></td>';
            if (($lead + $d) % 7 === 0 && $d < $days) echo '</tr><tr>';
        }
        for ($i = ($lead + $days) % 7; $i && $i < 7; $i++) echo '<td></td>'; ?>
        </tr></tbody>
    </table>
    <?php if (hg_can(role(), 'enquiries')) { ?><p class="cms-cal__foot"><span class="cms-cal__key" aria-hidden="true"></span><?= $tripTotal ? $tripTotal . ' trip' . ($tripTotal === 1 ? '' : 's') . ' starting this month' : 'No customer trips this month' ?></p><?php } ?>
</section>

<?php if (hg_can(role(), 'enquiries')) {
    $b = '';
    if ($trips) {
        $b .= '<ul class="cms-trips">';
        foreach ($trips as $t) {
            $b .= '<li><a class="cms-trips__a" href="/enquiries/' . $t['pk'] . '">'
                . ($t['img'] ? '<img src="' . e($t['img']) . '" alt="" width="72" height="56" loading="lazy" decoding="async">' : '<span class="cms-trips__ph" aria-hidden="true">' . icon('route') . '</span>')
                . '<span class="cms-trips__t"><strong>' . e($t['title']) . '</strong><span class="cms-trips__m"><span>' . icon('calendar') . e(date('d M Y', strtotime($t['date']))) . '</span>'
                . ($t['pax'] ? '<span>' . icon('user') . $t['pax'] . '<span class="cms-sr"> travellers</span></span>' : '') . '</span><span class="cms-trips__who">' . e($t['who']) . '</span></span></a></li>';
        }
        $b .= '</ul>';
    } else $b = '<p class="cms-muted">No upcoming trips yet. Travel dates from enquiries appear here.</p>';
    echo card('Upcoming trips', $b, array('actions' => '<a class="cms-link" href="/enquiries">View all</a>', 'class' => 'cms-rail-card'));
} ?>
<?php if ($spot) { ?>
<section class="cms-spot" aria-label="Destination spotlight">
    <div class="cms-spot__track" tabindex="0" aria-label="Destinations with the most packages; scroll or use the arrows">
        <?php foreach ($spot as $i => $d) { ?>
        <a class="cms-spot__slide" href="/packages?dest=<?= e(rawurlencode($d['key'])) ?>" aria-label="<?= e($d['name'] . ': ' . $d['n'] . ' packages') ?>">
            <img src="<?= e($d['img']) ?>" alt="" loading="<?= $i ? 'lazy' : 'eager' ?>" decoding="async">
            <span class="cms-spot__cap"><span class="cms-spot__eyebrow">Destination spotlight · <?= $i + 1 ?>/<?= count($spot) ?></span><strong><?= e($d['name']) ?></strong><span><?= (int) $d['n'] ?> active package<?= $d['n'] === 1 ? '' : 's' ?></span></span>
        </a>
        <?php } ?>
    </div>
    <div class="cms-spot__nav">
        <button type="button" class="cms-spot__btn" data-cms-spot="-1" aria-label="Previous destination"><?= icon('left') ?></button>
        <button type="button" class="cms-spot__btn" data-cms-spot="1" aria-label="Next destination"><?= icon('right') ?></button>
    </div>
</section>
<?php } ?>

<?php
$ico = function ($action) {
    foreach (array('rate' => 'rupee', 'price' => 'rupee', 'offer' => 'tag', 'media' => 'image', 'image' => 'image', 'enquir' => 'inbox', 'user' => 'user', 'publish' => 'send', 'import' => 'upload') as $k => $i) if (stripos($action, $k) !== false) return $i;
    return 'history';
};
$b = '<ul class="cms-act">';
foreach ($activity as $a) $b .= '<li><span class="cms-act__i" aria-hidden="true">' . icon($ico($a['action'])) . '</span><span><span class="cms-act__what"><strong>' . e($a['uname'] ?: 'System') . '</strong> ' . e(lcfirst($a['action'])) . ($a['package_pk'] ? ' · <a href="/packages/' . (int) $a['package_pk'] . '">package ' . ($a['package_id'] ? e($a['package_id']) : '#' . (int) $a['package_pk']) . '</a>' : '') . ($a['field'] ? ' · ' . e($a['field']) : '') . '</span><span class="cms-act__t">' . e(dmy($a['at'])) . ', ' . e(substr($a['at'], 11, 5)) . '</span></span></li>';
$b .= '</ul>';
if (!$activity) $b = '<p class="cms-muted">No activity yet.</p>';
echo card('Recent activity', $b, array('actions' => hg_can(role(), 'activity') ? '<a class="cms-link" href="/activity">View all</a>' : '', 'class' => 'cms-rail-card'));
?>
</aside>
</div>
<?php
$content = ob_get_clean();
$right = hg_can(role(), 'packages', 'edit') ? '<a class="cms-btn cms-btn--primary" href="/packages/new">' . icon('plus') . 'Add New Package</a>' : '';
cms_render('layout', array('title' => 'Dashboard', 'active' => 'dashboard', 'bodyClass' => 'cms-dashpage', 'crumbs' => array(array('Dashboard', null)),
    'head' => page_head('Dashboard', 'Tour packages, pricing, offers and enquiries at a glance.', $right), 'content' => $content));
