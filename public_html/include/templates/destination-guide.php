<?php
/**
 * Destination travel guide: /travel-guide/{key} (one per destination group in include/data/destinations.json).
 *
 * Everything shown comes from two verified sources only:
 *   - include/content/{content}.php   destination facts (places, best time, transport, stay, tips, who it suits)
 *   - include/data/packages.json      Holiday Guru Travel's own itineraries for the destination (routes, lengths,
 *                                     start points, hotel and meal plans — resolved by include/package_resolve.php)
 * Nothing is invented: a section is left out when its data is missing.
 *
 * Package → destination → guide → packages: package pages link here automatically (package-detail.php, "Travel guides
 * for this trip"); this page links back to every package in the destination and to the destination page.
 */
require_once __DIR__ . '/package-detail.php';
require_once dirname(__DIR__) . '/content/blog/index.php';

if (!function_exists('hg_guide_data')) {
    /** Facts for one destination, derived from its content file and its packages. */
    function hg_guide_data($key)
    {
        static $cache = array();
        if (isset($cache[$key])) return $cache[$key];
        $g = hg_group($key);
        if (!$g || empty($g['content'])) return $cache[$key] = null;
        $c = include dirname(__DIR__) . '/content/' . basename($g['content']) . '.php';
        $pk = hg_packages_in($key);
        usort($pk, function ($a, $b) { return ((int) $a['days'] <=> (int) $b['days']) ?: strcmp($a['url'], $b['url']); });
        $rows = array(); $starts = array(); $hotels = array(); $meals = array(); $transport = array();
        foreach ($pk as $p) {
            $r = hg_package_resolve($p);
            $route = array();
            foreach ($p['itinerary'] as $i => $d) {
                $day = hg_itinerary_day($d, $i, $p['places']);
                if ($day['stay'] !== '' && $i < (int) $p['nights'] && end($route) !== $day['stay']) $route[] = $day['stay'];
            }
            if (count($route) < 2) $route = $p['places'];
            $ends = hg_package_endpoints($p);
            if ($ends['start'] !== '') $starts[$ends['start']] = (isset($starts[$ends['start']]) ? $starts[$ends['start']] : 0) + 1;
            $hotels[$r['hotel']] = (isset($hotels[$r['hotel']]) ? $hotels[$r['hotel']] : 0) + 1;
            $meals[$r['meals']] = (isset($meals[$r['meals']]) ? $meals[$r['meals']] : 0) + 1;
            if ($r['transfers']) $transport[$r['transfers']] = (isset($transport[$r['transfers']]) ? $transport[$r['transfers']] : 0) + 1;
            $rows[] = array('p' => $p, 'id' => hg_package_id($p['slug']), 'route' => $route, 'start' => $ends['start'], 'end' => $ends['end']);
        }
        arsort($starts); arsort($hotels); arsort($meals); arsort($transport);
        return $cache[$key] = array('g' => $g, 'c' => $c, 'rows' => $rows, 'range' => hg_duration_range($pk),
            'starts' => $starts, 'hotels' => $hotels, 'meals' => $meals, 'transport' => $transport);
    }

    function hg_guide_list(array $x)
    {
        return count($x) > 1 ? implode(', ', array_slice($x, 0, -1)) . ' and ' . end($x) : (string) reset($x);
    }

    /** Counted values as text: "Deluxe on 6 of 9; Standard / 3-star equivalent on 3 of 9". */
    function hg_guide_counts(array $counts, $max = 3, $total = null)
    {
        if ($total === null) $total = array_sum($counts);
        $out = array();
        foreach (array_slice($counts, 0, $max, true) as $k => $n) $out[] = $n === $total ? $k . ' on all ' . $total . ' itineraries' : $k . ' on ' . $n . ' of ' . $total;
        return implode('; ', $out);
    }

    /** Destination FAQs answering the six guide questions, from data only. */
    function hg_guide_faqs($key)
    {
        $D = hg_guide_data($key);
        $g = $D['g']; $c = $D['c']; $n = $g['name'];
        $places = array_map(function ($pl) { return $pl[0]; }, $c['places']);
        $known = array_map(function ($w) { return mb_strtolower($w[0]); }, $c['why']);
        $faqs = array();
        $faqs[] = array('What is ' . $n . ' known for?', '<p>' . hg_e(ucfirst(hg_guide_list($known))) . '. ' . hg_e($c['why'][0][1]) . '</p>');
        $faqs[] = array('How many days should I spend in ' . $n . '?', '<p>' . hg_e($c['days_answer']) . ($D['range'] ? ' Our ' . $n . ' itineraries run ' . hg_e($D['range']) . '.' : '') . '</p>');
        $faqs[] = array('What is the best time to visit ' . $n . '?', '<p>' . hg_e($c['best_time_answer']) . '</p>');
        $faqs[] = array('Which places should I include in a ' . $n . ' trip?', '<p>' . hg_e(hg_guide_list(array_slice($places, 0, 6))) . '. ' . hg_e($c['places'][0][0] . ': ' . $c['places'][0][1]) . '</p>');
        $names = array_map(function ($r) { return ($r['p']['title'] ?: $r['p']['name']) . ' (' . $r['p']['duration'] . ')'; }, $D['rows']);
        $faqs[] = array('Which Holiday Guru Travel packages cover ' . $n . '?', '<p>' . count($D['rows']) . ' itinerar' . (count($D['rows']) === 1 ? 'y' : 'ies') . ': '
            . hg_e(implode('; ', array_slice($names, 0, 6))) . (count($names) > 6 ? '; and ' . (count($names) - 6) . ' more listed below' : '') . '.</p>');
        $faqs[] = array('Can a ' . $n . ' itinerary be customised?', '<p>Yes. Any of our ' . hg_e($n) . ' itineraries can be changed — add or remove nights, change the hotel category or add sightseeing. <a href="/customized-holidays?destination=' . rawurlencode($n) . '">Tell us what you need</a> and we send a quote for your dates.</p>');
        return $faqs;
    }

    /** Body sections shared by every guide (Kashmir's hand-written guide adds its own sections around them). */
    function hg_guide_sections($key, array $skip = array(), array $faqs = array())
    {
        $D = hg_guide_data($key);
        $g = $D['g']; $c = $D['c']; $n = $g['name'];
        ob_start();
        if (!in_array('facts', $skip, true)) { ?>
    <section class="hg-answer" id="facts" aria-labelledby="g-facts">
        <h2 class="hg-h2" id="g-facts"><?= hg_e($n) ?> quick facts</h2>
        <dl class="hg-qf">
            <?php if ($D['range']) { ?><div><dt>Trip length</dt><dd><?= hg_e($D['range']) ?> (<?= count($D['rows']) ?> itinerar<?= count($D['rows']) === 1 ? 'y' : 'ies' ?>)</dd></div><?php } ?>
            <div><dt>Best time</dt><dd><?= hg_e($c['best_time_answer']) ?></dd></div>
            <div><dt>Key places</dt><dd><?= hg_e(implode(', ', array_map(function ($pl) { return $pl[0]; }, $c['places']))) ?></dd></div>
            <?php if ($D['starts']) { ?><div><dt>Tours start in</dt><dd><?= hg_e(implode(', ', array_keys(array_slice($D['starts'], 0, 3, true)))) ?></dd></div><?php } ?>
            <?php if ($D['transport']) { ?><div><dt>Travel style</dt><dd><?= hg_e(hg_guide_counts($D['transport'], 2, count($D['rows']))) ?></dd></div><?php } ?>
            <div><dt>Hotels</dt><dd><?= hg_e(hg_guide_counts($D['hotels'])) ?></dd></div>
            <div><dt>Meal plans</dt><dd><?= hg_e(hg_guide_counts($D['meals'])) ?></dd></div>
            <?php if (!empty($c['who'])) { ?><div><dt>Suits</dt><dd><?= hg_e(implode(', ', array_map(function ($w) { return $w[0]; }, $c['who']))) ?></dd></div><?php } ?>
        </dl>
    </section>
        <?php }
        if (!in_array('places', $skip, true)) { ?>
    <section class="hg-answer" id="places" aria-labelledby="g-places">
        <h2 class="hg-h2" id="g-places">Best places to visit in <?= hg_e($n) ?></h2>
        <p class="hg-answer__direct"><?= hg_e(hg_guide_list(array_map(function ($pl) { return $pl[0]; }, array_slice($c['places'], 0, 5)))) ?> are the places our <?= hg_e($n) ?> itineraries are built around.</p>
        <div class="hg-places"><?php foreach ($c['places'] as $pl) { ?><div class="hg-place"><h3 class="hg-h3" style="font-size:18px"><?= hg_e($pl[0]) ?></h3><p><?= hg_e($pl[1]) ?></p></div><?php } ?></div>
        <?php if (!empty($c['things'])) { ?><h3 class="hg-h3" style="margin-top:24px">Things to do</h3><ul class="hg-checks"><?php foreach ($c['things'] as $t) { ?><li><?= hg_e($t) ?></li><?php } ?></ul><?php } ?>
    </section>
        <?php }
        if (!in_array('days', $skip, true)) { ?>
    <section class="hg-answer" id="days" aria-labelledby="g-days">
        <h2 class="hg-h2" id="g-days">How many days do you need?</h2>
        <p class="hg-answer__direct"><?= hg_e($c['days_answer']) ?></p>
        <?php if (!empty($c['days_rows'])) { ?><div class="hg-tablewrap" tabindex="0" role="region" aria-label="Trip lengths"><table class="hg-table"><thead><tr><th scope="col">Trip length</th><th scope="col">What it covers</th><th scope="col">Our itinerary</th></tr></thead><tbody>
        <?php foreach ($c['days_rows'] as $r) { $pp = hg_package($r[2]); if (!$pp) continue; ?><tr><td><?= hg_e($r[0]) ?></td><td><?= hg_e($r[1]) ?></td><td><a href="<?= hg_e($pp['url']) ?>"><?= hg_e($pp['title'] ?: $pp['name']) ?></a></td></tr><?php } ?>
        </tbody></table></div><?php } ?>
    </section>
        <?php }
        if (!in_array('when', $skip, true)) { ?>
    <section class="hg-answer" id="when" aria-labelledby="g-when">
        <h2 class="hg-h2" id="g-when">Best time to visit <?= hg_e($n) ?></h2>
        <p class="hg-answer__direct"><?= hg_e($c['best_time_answer']) ?></p>
        <div class="hg-tablewrap" tabindex="0" role="region" aria-label="Seasons"><table class="hg-table"><thead><tr><th scope="col">Months</th><th scope="col">Season</th><th scope="col">What to expect</th></tr></thead><tbody>
        <?php foreach ($c['best_time'] as $r) { ?><tr><td><?= hg_e($r[0]) ?></td><td><?= hg_e($r[1]) ?></td><td><?= hg_e($r[2]) ?></td></tr><?php } ?>
        </tbody></table></div>
    </section>
        <?php }
        // Suggested routes: one per trip length (shortest first), from the itineraries' own overnight places.
        $seen = array(); $routes = array();
        foreach ($D['rows'] as $r) { $k = (int) $r['p']['days']; if (isset($seen[$k]) || count($r['route']) < 1) continue; $seen[$k] = true; $routes[] = $r; }
        if ($routes && !in_array('routes', $skip, true)) { ?>
    <section class="hg-answer" id="routes" aria-labelledby="g-routes">
        <h2 class="hg-h2" id="g-routes">Suggested <?= hg_e($n) ?> routes</h2>
        <p class="hg-answer__direct">Routes from our own <?= hg_e($n) ?> itineraries, by trip length.</p>
        <ul class="hg-checks hg-checks--info">
            <?php foreach (array_slice($routes, 0, 6) as $r) { ?><li><strong><?= hg_e($r['p']['duration']) ?>:</strong> <?= hg_e(implode(' → ', $r['route'])) ?> — <a href="<?= hg_e($r['p']['url']) ?>"><?= hg_e($r['p']['title'] ?: $r['p']['name']) ?></a></li><?php } ?>
        </ul>
    </section>
        <?php }
        if (!in_array('consider', $skip, true)) { ?>
    <section class="hg-answer" id="consider" aria-labelledby="g-consider">
        <h2 class="hg-h2" id="g-consider">Things to consider</h2>
        <div class="hg-prose"><p><strong>Getting there and around:</strong> <?= hg_e($c['transport']) ?></p><p><strong>Where you stay:</strong> <?= hg_e($c['stay']) ?></p></div>
        <ul class="hg-checks hg-checks--info"><?php foreach ($c['tips'] as $t) { ?><li><?= hg_e($t) ?></li><?php } ?></ul>
    </section>
        <?php } ?>
        <?php if ($faqs && !in_array('faq', $skip, true)) { ?>
    <section class="hg-answer" id="gfaq" aria-labelledby="g-faq">
        <h2 class="hg-h2" id="g-faq"><?= hg_e($n) ?> travel questions</h2>
        <?= hg_faq($faqs, 'g') ?>
    </section>
        <?php }
        if (!in_array('packages', $skip, true)) { ?>
    <section class="hg-answer" id="packages" aria-labelledby="g-packages">
        <h2 class="hg-h2" id="g-packages"><?= hg_e($n) ?> holiday packages</h2>
        <p class="hg-answer__direct">All <?= count($D['rows']) ?> Holiday Guru Travel itinerar<?= count($D['rows']) === 1 ? 'y' : 'ies' ?> for <?= hg_e($n) ?>, shortest first. Each can be customised.</p>
        <div class="hg-tablewrap" tabindex="0" role="region" aria-label="<?= hg_e($n) ?> packages"><table class="hg-table"><thead><tr><th scope="col">Package ID</th><th scope="col">Package</th><th scope="col">Duration</th><th scope="col">Route</th></tr></thead><tbody>
        <?php foreach ($D['rows'] as $r) { ?><tr><td><?= hg_e($r['id']) ?></td><td><a href="<?= hg_e($r['p']['url']) ?>"><?= hg_e($r['p']['title'] ?: $r['p']['name']) ?></a></td><td><?= hg_e($r['p']['duration']) ?></td><td><?= hg_e(implode(' → ', $r['route'])) ?></td></tr><?php } ?>
        </tbody></table></div>
        <p><a class="hg-link-arrow" href="<?= hg_e($g['hub_url']) ?>">Compare all <?= hg_e($n) ?> tour packages with prices <span aria-hidden="true">&rarr;</span></a></p>
    </section>
        <?php }
        $blog = hg_blog_guides_for_group($key);
        $related = array();
        foreach ((array) (isset($c['related']) ? $c['related'] : array()) as $rk) {
            $rg = hg_group($rk);
            if ($rg && is_file(dirname(__DIR__, 2) . '/travel-guide/' . basename($rk) . '.php') && hg_page_status('/travel-guide/' . $rk) === 'approved') $related[] = array('url' => '/travel-guide/' . $rk, 'title' => $rg['name'] . ' travel guide');
        }
        if (($blog || $related) && !in_array('more', $skip, true)) { ?>
    <section class="hg-answer" id="more" aria-labelledby="g-more">
        <h2 class="hg-h2" id="g-more">Related reading</h2>
        <?php if ($blog) { ?><h3 class="hg-h3"><?= hg_e($n) ?> articles</h3><?= hg_blog_link_list($blog) ?><?php } ?>
        <?php if ($related) { ?><h3 class="hg-h3">Other destination guides</h3><?= hg_blog_link_list($related) ?><?php } ?>
    </section>
        <?php }
        return ob_get_clean();
    }

    /** Schema for a guide: TouristDestination + Article + FAQPage + ItemList of the destination's packages. */
    function hg_guide_schema($key, array $faqs, $title, $description)
    {
        $D = hg_guide_data($key);
        $g = $D['g']; $c = $D['c']; $path = '/travel-guide/' . $key;
        $dest = array('@type' => 'TouristDestination', '@id' => hg_abs($path) . '#destination', 'name' => $g['name'], 'description' => $c['intro'],
            'url' => hg_abs($g['hub_url']), 'image' => hg_abs($c['image']),
            'includesAttraction' => array_map(function ($pl) { return array('@type' => 'TouristAttraction', 'name' => $pl[0], 'description' => $pl[1]); }, $c['places']));
        if (!empty($c['who'])) $dest['touristType'] = array_map(function ($w) { return $w[0]; }, $c['who']);
        $list = array('@type' => 'ItemList', 'name' => $g['name'] . ' holiday packages', 'itemListElement' => array());
        foreach ($D['rows'] as $i => $r) $list['itemListElement'][] = array('@type' => 'ListItem', 'position' => $i + 1, 'url' => hg_abs($r['p']['url']), 'name' => $r['p']['title'] ?: $r['p']['name']);
        return array($dest, array(
            '@type' => 'Article', 'headline' => $title, 'description' => $description,
            'about' => array('@id' => hg_abs($path) . '#destination'),
            'author' => array('@id' => HG_SITE_URL . '/#organization'), 'publisher' => array('@id' => HG_SITE_URL . '/#organization'),
            'dateModified' => $c['reviewed'], 'mainEntityOfPage' => hg_abs($path), 'image' => hg_abs($c['image']),
        ), hg_faq_schema($faqs), $list);
    }

    /** SEO description from data, kept within 150–165 characters where possible. */
    function hg_guide_description($key)
    {
        $D = hg_guide_data($key);
        $n = $D['g']['name'];
        $places = array_map(function ($pl) { return $pl[0]; }, $D['c']['places']);
        for ($k = min(4, count($places)); $k >= 1; $k--) {
            $d = 'Plan a ' . $n . ' trip: when to go, how many days, ' . hg_guide_list(array_slice($places, 0, $k)) . ', and ' . count($D['rows']) . ' Holiday Guru Travel itineraries of ' . $D['range'] . '.';
            if (mb_strlen($d) <= 165) return $d;
        }
        return mb_substr($d, 0, 162) . '…';
    }

    function hg_render_guide($key)
    {
        $D = hg_guide_data($key);
        if (!$D) { http_response_code(404); include dirname(__DIR__, 2) . '/404.php'; return; }
        $g = $D['g']; $c = $D['c']; $n = $g['name']; $path = '/travel-guide/' . $key;
        $faqs = hg_guide_faqs($key);
        $title = $n . ' Travel Guide: Best Time, Places and Itineraries';
        $desc = hg_guide_description($key);
        hg_layout_start(array(
            'title' => $title, 'description' => $desc, 'path' => $path, 'index' => hg_page_status($path) === 'approved', 'type' => 'article',
            'image' => $c['image'],
            'breadcrumbs' => array(array('Home', '/'), array($n, $g['hub_url']), array($n . ' travel guide', null)),
            'schema' => hg_guide_schema($key, $faqs, $n . ' travel guide', $desc),
        ));
        $toc = array(array('facts', 'Quick facts'), array('places', 'Best places'), array('days', 'How many days'), array('when', 'Best time'), array('routes', 'Routes'), array('consider', 'Things to consider'), array('gfaq', 'FAQs'), array('packages', 'Packages'), array('more', 'Related reading'));
        ?>
<article class="hg-guidepage">
<header class="hg-pagehead">
    <div class="hg-container hg-narrow">
        <p class="hg-eyebrow">Travel guide · <?= hg_e($n) ?></p>
        <h1 class="hg-h1"><?= hg_e($n) ?> Travel Guide</h1>
        <p class="hg-lead"><?= hg_e($c['intro']) ?></p>
        <p class="hg-muted" style="font-size:14px">By Holiday Guru Travel · Updated <?= hg_e(hg_date_label($c['reviewed'])) ?></p>
    </div>
</header>
<div class="hg-container hg-narrow">
    <p class="hg-summary"><strong>In short:</strong> <?= hg_e(strip_tags($faqs[0][1])) ?> <?= hg_e($c['best_time_answer']) ?></p>
    <nav class="hg-toc" aria-label="In this guide"><p>In this guide</p><ol><?php foreach ($toc as $t) { ?><li><a href="#<?= $t[0] ?>"><?= hg_e($t[1]) ?></a></li><?php } ?></ol></nav>
    <?= hg_guide_sections($key, array(), $faqs) ?>
</div>
</article>
<?= hg_cta_band('Plan your ' . $n . ' trip with us', 'Tell us your dates and who is travelling — we suggest the right itinerary and customise it for you.') ?>
<?php
        hg_layout_end();
    }
}
