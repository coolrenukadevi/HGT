<?php
/** Screen 1 — Dashboard. Internal counts are allowed here (the CMS is not public). */

function dashboard()
{
    need('packages');
    $rows = q("SELECT package_pk FROM packages WHERE status <> 'archived'")->fetchAll();
    $ready = 0; $gaps = array(); $gapCount = array();
    $readiness = array('Ready to publish' => 0, '1–2 items missing' => 0, '3–5 items missing' => 0, '6 or more missing' => 0);
    foreach ($rows as $r) {
        $p = pkg_load($r['package_pk']);
        list($err) = pkg_blockers($p);
        $n = count($err);
        $readiness[$n === 0 ? 'Ready to publish' : ($n <= 2 ? '1–2 items missing' : ($n <= 5 ? '3–5 items missing' : '6 or more missing'))]++;
        if (!$err) { $ready++; continue; }
        foreach ($err as $i) $gapCount[$i['key']] = array($i['label'], (isset($gapCount[$i['key']]) ? $gapCount[$i['key']][1] : 0) + 1, $i['tab']);
    }
    uasort($gapCount, function ($a, $b) { return $b[1] - $a[1]; });
    $by = array();
    foreach (q('SELECT status, COUNT(*) n FROM packages GROUP BY status')->fetchAll() as $r) $by[$r['status']] = (int) $r['n'];
    $ids = q('SELECT package_id_status s, COUNT(*) n FROM packages GROUP BY package_id_status')->fetchAll();
    $idBy = array(); foreach ($ids as $r) $idBy[$r['s']] = (int) $r['n'];
    $soon = gmdate('Y-m-d', strtotime(today() . ' +14 days'));
    $expiring = q("SELECT r.*, p.name, p.package_pk, p.package_id, p.proposed_package_id, p.package_id_status FROM rate_versions r JOIN packages p ON p.package_pk = r.package_pk
        WHERE r.rate_status = 'approved' AND r.valid_until BETWEEN ? AND ? ORDER BY r.valid_until", array(today(), $soon))->fetchAll();
    $review = q("SELECT * FROM packages WHERE status IN ('in_review','approved') ORDER BY updated_at DESC LIMIT 8")->fetchAll();
    $activity = q('SELECT a.*, u.name uname FROM activity_log a LEFT JOIN users u ON u.user_id = a.user_id ORDER BY log_pk DESC LIMIT 8')->fetchAll();
    $enq = hg_can(role(), 'enquiries') ? q('SELECT * FROM enquiries ORDER BY enquiry_pk DESC LIMIT 5')->fetchAll() : array();
    $offersLive = (int) qv("SELECT COUNT(*) FROM offers WHERE status = 'published' AND valid_until >= ?", array(today()));
    $rated = (int) qv("SELECT COUNT(DISTINCT package_pk) FROM rate_versions WHERE rate_status = 'approved' AND ? BETWEEN valid_from AND valid_until", array(today()));
    // Charts: packages per destination (top 8 + Other) and enquiries per day for the last 30 days.
    $names = hg_destinations(); $dest = array(); $other = 0;
    foreach (q("SELECT destination d, COUNT(*) n FROM packages WHERE status <> 'archived' GROUP BY destination ORDER BY n DESC, destination")->fetchAll() as $i => $r) {
        $label = isset($names[$r['d']]) ? $names[$r['d']]['name'] : ($r['d'] !== '' ? ucwords(str_replace('-', ' ', $r['d'])) : 'No destination');
        if ($i < 8) $dest[] = array($label, (int) $r['n'], '/packages?dest=' . rawurlencode($r['d'])); else $other += (int) $r['n'];
    }
    if ($other) $dest[] = array('Other destinations', $other, null);
    $readiness = array_map(function ($k, $v) { return array($k, $v, null); }, array_keys($readiness), $readiness);
    $enqDays = array();
    if (hg_can(role(), 'enquiries')) {
        $from = gmdate('Y-m-d', strtotime(today() . ' -29 days'));
        $per = array();
        foreach (q('SELECT substr(created_at, 1, 10) d, COUNT(*) n FROM enquiries WHERE is_test = 0 AND substr(created_at, 1, 10) >= ? GROUP BY d', array($from))->fetchAll() as $r) $per[$r['d']] = (int) $r['n'];
        for ($i = 29; $i >= 0; $i--) { $d = gmdate('Y-m-d', strtotime(today() . " -$i days")); $enqDays[] = array($d, isset($per[$d]) ? $per[$d] : 0); }
    }
    // Enquiries this week vs the 7 days before (test enquiries excluded); null when the role cannot see enquiries.
    $week = $prevWeek = null;
    if ($enqDays) {
        $week = array_sum(array_map(function ($r) { return $r[1]; }, array_slice($enqDays, -7)));
        $prevWeek = array_sum(array_map(function ($r) { return $r[1]; }, array_slice($enqDays, -14, 7)));
    }
    // Destination spotlight: destinations with packages and a photo on the website, most packages first.
    $spot = array();
    foreach (q("SELECT destination d, COUNT(*) n FROM packages WHERE status <> 'archived' AND destination <> '' GROUP BY destination ORDER BY n DESC")->fetchAll() as $r) {
        $img = '/assets/img/destinations/' . $r['d'] . '-960.webp';
        if (!isset($names[$r['d']]) || !is_file(site_path($img))) continue;
        $spot[] = array('key' => $r['d'], 'name' => $names[$r['d']]['name'], 'n' => (int) $r['n'], 'img' => $img);
        if (count($spot) === 6) break;
    }
    // Calendar (month from ?cal=YYYY-MM, default this month) with customers' travel dates, and upcoming trips.
    $cal = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) get('cal')) ? get('cal') : substr(today(), 0, 7);
    $tripDays = array(); $trips = array();
    if (hg_can(role(), 'enquiries')) {
        foreach (q("SELECT travel_date d, COUNT(*) n FROM enquiries WHERE is_test = 0 AND stage <> 'lost' AND travel_date LIKE ? GROUP BY travel_date", array($cal . '-%'))->fetchAll() as $r) $tripDays[$r['d']] = (int) $r['n'];
        foreach (q("SELECT e.enquiry_pk, e.name, e.package_name, e.destination, e.travel_date, e.adults, e.children, p.destination pdest
            FROM enquiries e LEFT JOIN packages p ON p.package_pk = e.package_pk
            WHERE e.is_test = 0 AND e.stage <> 'lost' AND e.travel_date >= ? ORDER BY e.travel_date, e.enquiry_pk LIMIT 4", array(today()))->fetchAll() as $t) {
            $key = $t['pdest'] ?: $t['destination'];
            $img = $key !== '' && is_file(site_path('assets/img/destinations/' . $key . '-480.webp')) ? '/assets/img/destinations/' . $key . '-480.webp' : '';
            $place = $t['package_name'] ?: (isset($names[$key]) ? $names[$key]['name'] : ($t['destination'] ?: 'Trip enquiry'));
            $trips[] = array('pk' => (int) $t['enquiry_pk'], 'title' => $place, 'who' => $t['name'], 'date' => $t['travel_date'], 'pax' => (int) $t['adults'] + (int) $t['children'], 'img' => $img);
        }
    }
    cms_render('dashboard', compact('cal', 'tripDays', 'trips', 'rows', 'ready', 'gapCount', 'by', 'idBy', 'expiring', 'review', 'activity', 'enq', 'offersLive', 'rated', 'dest', 'readiness', 'enqDays', 'week', 'prevWeek', 'spot'));
}


/** POST /site-sync: update the website now (Super Admin / Admin / Package Manager). */
/** Re-sync CMS packages from the website's packages.json (same as bin/resync-from-site.php --apply). */
function site_resync_post()
{
    if (!hg_may(role(), 'publish')) deny();
    $site = json_decode((string) file_get_contents(site_path('include/data/packages.json')), true) ?: array();
    $bySlug = array(); foreach ($site as $sp) $bySlug[$sp['slug']] = $sp;
    $n = 0; $skip = 0;
    foreach (q('SELECT package_pk, slug, status, version, published_version FROM packages')->fetchAll() as $row) {
        if (site_archive_if_retired($row)) continue;
        $slug = site_resync_slug($row, $bySlug);
        if ($slug === null) continue;
        if ((int) $row['version'] !== (int) $row['published_version']) { $skip++; continue; }
        site_resync_package($row['package_pk'], $bySlug[$slug]);
        $n++;
    }
    cms_log('Packages re-synced from the website', null, '', '', $n . ' packages');
    flash('Re-synced ' . $n . ' package' . ($n === 1 ? '' : 's') . ' from the website.' . ($skip ? ' Skipped ' . $skip . ' with unpublished CMS edits.' : ''), $skip ? 'warn' : 'ok');
    redirect('/');
}

function site_sync_post()
{
    need('packages', 'manage');
    $r = site_export('manual');
    flash($r['message'], $r['ok'] ? 'ok' : 'err');
    redirect('/');
}
