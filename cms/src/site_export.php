<?php
/**
 * CMS → website sync. Writes the PUBLISHED version of each package (never drafts) into the website's data files,
 * so the live site changes only through Publish / Pause / Archive and approved rates, offers and curation.
 *
 *   include/data/packages.json   package pages, listings, search (one entry per live package)
 *   include/data/rates.json      approved price versions (the site shows a price only inside its validity dates)
 *   include/data/offers.json     Offer Zone
 *   include/data/curation.json   featured / top tours (internal ordering only)
 *   include/data/cms-seo.json    SEO titles/descriptions edited in the CMS (override include/data/seo-meta.php)
 *
 * Safety:
 *  - Only fields that were actually edited in the CMS are written: each imported package is compared with its
 *    import snapshot (version 1), so untouched website content (and the Phase 2 SEO text) stays byte-for-byte.
 *  - Packages that exist only on the website (not in the CMS) are kept as they are.
 *  - Every file that changes is backed up first to cms/storage/site-backups/<time>/ (outside the web root);
 *    writes are atomic (temp file + rename); one sync at a time (file lock).
 *  - A sync that would remove more than half of the live packages is refused.
 */

const HG_SYNC_FILES = array('packages.json', 'rates.json', 'offers.json', 'curation.json', 'cms-seo.json');

function site_data_path($f) { return site_path('include/data/' . $f); }

function site_json_read($f, $default)
{
    $p = site_data_path($f);
    if (!is_file($p)) return $default;
    $d = json_decode((string) file_get_contents($p), true);
    return is_array($d) ? $d : $default;
}

/** Same layout as the website's existing data files (1-space indent, unescaped Unicode and slashes, final newline). */
function site_json_encode($d)
{
    $o = json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return preg_replace_callback('/^( +)/m', function ($m) { return str_repeat(' ', intdiv(strlen($m[1]), 4)); }, $o) . "\n";
}

/** Snapshot of a package version as stored by pkg_snapshot (array) or null. */
function site_snapshot($pk, $version)
{
    if (!$version) return null;
    $r = q1('SELECT snapshot FROM package_versions WHERE package_pk = ? AND version = ?', array($pk, (int) $version));
    $s = $r ? json_decode($r['snapshot'], true) : null;
    return is_array($s) ? $s : null;
}

function site_plain($html)
{
    $t = preg_replace('~</p>\s*<p[^>]*>|<br\s*/?>~i', "\n\n", (string) $html);
    return trim(html_entity_decode(strip_tags($t), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

/** Website field values derived from a CMS snapshot. Keys are groups that are compared and written together. */
function site_fields(array $s)
{
    $scope = function ($kind) use ($s) {
        $out = array();
        foreach ($s['scope'] as $i) if ($i['kind'] === $kind && $i['status'] === 'active' && empty($i['is_standard'])) $out[] = $i['name'];
        return $out;
    };
    $featured = '';
    foreach ($s['media'] as $m) if ($m['role'] === 'featured' && $m['status'] === 'active') { $featured = $m['file_path']; break; }
    $route = trim((string) $s['city_route']);
    return array(
        'name'        => array('name' => $s['name']),
        'duration'    => array('nights' => (int) $s['nights'], 'days' => (int) $s['days'], 'duration' => (int) $s['nights'] . ' Nights / ' . (int) $s['days'] . ' Days'),
        'route'       => array('cities' => $route, 'places' => $route === '' ? array() : array_values(array_filter(array_map('trim', preg_split('/\s+[–—-]\s+/u', $route))))),
        'group'       => array('group' => $s['destination']),
        'pilgrimage'  => array('pilgrimage' => $s['package_type'] === 'Pilgrimage'),
        'description' => array('description' => site_plain($s['description_html']) ?: (string) $s['short_description']),
        'itinerary'   => array(
            'itinerary' => array_map(function ($d) {
                $o = array('title' => site_day_title($d), 'text' => $d['description']);
                if (!empty($d['generated'])) $o['generated'] = true;
                return $o;
            }, $s['days_list']),
            // Source state (internal): days completed from a standard circuit keep the "suggested plan" note.
            'itinerary_source' => array_filter($s['days_list'], function ($d) { return !empty($d['generated']); }) ? 'standard' : 'package',
        ),
        'inclusions'  => array('inclusions' => $scope('inclusion')),
        'exclusions'  => array('exclusions' => $scope('exclusion')),
        'features'    => array('features' => array_values((array) $s['highlights'])),
        'image'       => array('image' => $featured),
        'page_title'  => array('page_title' => (string) $s['seo']['meta_title']),
        // Package-level overrides (level 2). One group each, so changing one never blanks another on the website;
        // an empty value lets the Holiday Guru Travel standard apply (public_html/include/package_resolve.php).
        'hotel'         => array('hotel' => (string) (isset($s['hotel_category']) ? $s['hotel_category'] : '')),
        'meals'         => array('meals' => (string) (isset($s['meal_plan']) ? $s['meal_plan'] : '')),
        'transfers'     => array('transfers' => (string) (isset($s['transport']) ? $s['transport'] : '')),
        'endpoints'     => array('start' => (string) (isset($s['start_point']) ? $s['start_point'] : ''), 'end' => (string) (isset($s['end_point']) ? $s['end_point'] : '')),
        'special_notes' => array('special_notes' => (string) (isset($s['special_notes']) ? $s['special_notes'] : '')),
    );
}

/** Hash of one website package record (packages.json entry), independent of key order. */
function site_pkg_hash($entry)
{
    if (!is_array($entry)) return null;
    $norm = function ($v) use (&$norm) { if (!is_array($v)) return $v; if (array_keys($v) !== range(0, count($v) - 1)) ksort($v); return array_map($norm, $v); };
    return sha1(json_encode($norm($entry), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

/** Slugs retired in the website's Package ID registry (never re-added by the CMS). */
function site_retired_slugs()
{
    $r = site_json_read('package-registry.json', array());
    $out = array();
    foreach ((array) (isset($r['entries']) ? $r['entries'] : array()) as $e) if (isset($e['status']) && $e['status'] === 'retired') $out[$e['slug']] = $e;
    return $out;
}

/** Old slugs of website packages (registry previous_slugs, approved URL migrations): never added back as new packages. */
function site_renamed_slugs()
{
    $r = site_json_read('package-registry.json', array());
    $out = array();
    foreach ((array) (isset($r['entries']) ? $r['entries'] : array()) as $e) foreach ((array) (isset($e['previous_slugs']) ? $e['previous_slugs'] : array()) as $old) $out[$old] = $e['slug'];
    return $out;
}

/** Website packages still listed under an old (renamed) URL: duplicates that must be removed before any sync or import. */
function site_renamed_on_site()
{
    $renamed = site_renamed_slugs();
    $out = array();
    foreach (site_json_read('packages.json', array()) as $e) if (isset($renamed[$e['slug']]) || isset($renamed[str_replace(array('–', '—'), '-', $e['slug'])])) $out[] = $e['slug'];
    return $out;
}

/**
 * Publish safety: 'ok' when the website still holds what the CMS last synced or wrote for this package;
 * 'required' when the website record changed outside the CMS (or was never synced) — the CMS must not overwrite it;
 * 'new' when the package is not on the website yet; 'retired' when its Package ID is retired.
 */
function site_sync_state(array $row)
{
    $site = array();   // read fresh every time: a cached copy could hide a change made a moment ago
    foreach (site_json_read('packages.json', array()) as $e) $site[$e['slug']] = $e;
    if (isset(site_retired_slugs()[$row['slug']])) return 'retired';
    if (!isset($site[$row['slug']])) {
        $renamed = site_renamed_slugs();
        return isset($renamed[$row['slug']]) || isset($renamed[str_replace(array('–', '—'), '-', $row['slug'])]) ? 'required' : 'new';
    }
    return (!empty($row['site_hash']) && $row['site_hash'] === site_pkg_hash($site[$row['slug']])) ? 'ok' : 'required';
}

/** Website day heading: "Day N : (Overnight)Title" — the format package-detail.php reads the overnight place from. */
function site_day_title(array $d)
{
    $t = trim((string) $d['title']);
    $over = trim((string) (isset($d['overnight']) ? $d['overnight'] : ''));
    if ($over === '') return $t;
    $t = trim(preg_replace('/^(?:DAY\s+)?Day\s*\d+\s*[:|\-–]?\s*(\([^)]*\))?\s*/iu', '', $t));
    return 'Day ' . (int) $d['day_number'] . ' : (' . $over . ')' . $t;
}

/** "Day 3 : (Pushkar)Ringas to Pushkar" → ['Pushkar', 'Ringas to Pushkar'] (title keeps its own text only). */
function site_split_day_title($title)
{
    $t = trim(preg_replace('/^(?:DAY\s+)?Day\s*\d+\s*[:|\-–]?\s*/iu', '', (string) $title));
    if (preg_match('/^\(([^)]*)\)\s*(.*)$/su', $t, $m)) return array(trim($m[1]), trim($m[2]));
    return array('', $t);
}

/** Archive a CMS package whose Package ID / slug is retired on the website (owner duplicate rule). True when archived. */
function site_archive_if_retired(array $row)
{
    $ret = site_retired_slugs();
    if (!isset($ret[$row['slug']])) return false;
    if ($row['status'] !== 'archived') {
        q("UPDATE packages SET status = 'archived', archived_at = ? WHERE package_pk = ?", array(now(), (int) $row['package_pk']));
        cms_log('Package archived: Package ID retired on the website', (int) $row['package_pk'], 'status', $row['status'], 'archived (' . (isset($ret[$row['slug']]['redirect_to']) ? '301 to ' . $ret[$row['slug']]['redirect_to'] : 'retired') . ')');
    }
    return true;
}

/**
 * The website slug for a CMS package row. Follows the approved en-dash → ASCII URL migration
 * (docs/url-migrations.csv) and updates the CMS slug / public URL to match; null when the site has no such package.
 */
function site_resync_slug(array $row, array $bySlug)
{
    if (isset($bySlug[$row['slug']])) return $row['slug'];
    // Approved URL migrations: the registry keeps every previous slug of a Package ID.
    $reg = site_json_read('package-registry.json', array());
    foreach ((array) (isset($reg['entries']) ? $reg['entries'] : array()) as $e) {
        $prev = (array) (isset($e['previous_slugs']) ? $e['previous_slugs'] : array());
        if (!(in_array($row['slug'], $prev, true) || in_array(str_replace(array('–', '—'), '-', $row['slug']), $prev, true)) || !isset($bySlug[$e['slug']])) continue;
        q('UPDATE packages SET slug = ?, public_url = ? WHERE package_pk = ?', array($e['slug'], '/' . $e['slug'], (int) $row['package_pk']));
        cms_log('Slug updated to the migrated URL', (int) $row['package_pk'], 'slug', $row['slug'], $e['slug']);
        return $e['slug'];
    }
    $ascii = str_replace(array('–', '—'), '-', $row['slug']);
    if ($ascii === $row['slug'] || !isset($bySlug[$ascii])) return null;
    q('UPDATE packages SET slug = ?, public_url = ? WHERE package_pk = ?', array($ascii, '/' . $ascii, (int) $row['package_pk']));
    cms_log('Slug updated to the migrated URL', (int) $row['package_pk'], 'slug', $row['slug'], $ascii);
    return $ascii;
}

/**
 * Re-sync one CMS package from the website's current data (packages.json): itinerary (with overnight places and the
 * generated flag), inclusions/exclusions, hotel category, meal plan, transport, start/end and route. Package ID,
 * status, rates, SEO and media are not touched. Records a new version and makes it the export baseline, so a later
 * CMS publish only writes what an editor changes afterwards (never the older imported data).
 */
function site_resync_package($pk, array $sp, $note = 'Re-synced from the website (packages.json)')
{
    $db = cms_db();
    $own = !$db->inTransaction();
    if ($own) $db->beginTransaction();
    q('UPDATE packages SET days = ?, nights = ?, city_route = ?, hotel_category = ?, meal_plan = ?, transport = ?, start_point = ?, end_point = ?, itinerary_source = ? WHERE package_pk = ?', array(
        (int) $sp['days'], (int) $sp['nights'], $sp['places'] ? implode(' – ', $sp['places']) : (string) $sp['cities'],
        (string) $sp['hotel'], (string) $sp['meals'], (string) $sp['transfers'], (string) (isset($sp['start']) ? $sp['start'] : ''), (string) (isset($sp['end']) ? $sp['end'] : ''),
        isset($sp['itinerary_source']) && $sp['itinerary_source'] === 'standard' ? 'standard' : 'package', (int) $pk));
    q('DELETE FROM itinerary_days WHERE package_pk = ?', array((int) $pk));
    foreach ($sp['itinerary'] as $i => $d) {
        list($over, $title) = site_split_day_title($d['title']);
        q('INSERT INTO itinerary_days(package_pk, day_number, title, overnight, description, generated) VALUES (?,?,?,?,?,?)',
            array((int) $pk, $i + 1, $title, $over, trim((string) $d['text']), empty($d['generated']) ? 0 : 1));
    }
    q("DELETE FROM scope_items WHERE package_pk = ? AND is_standard = 0", array((int) $pk));
    foreach (array('inclusion' => $sp['inclusions'], 'exclusion' => $sp['exclusions']) as $kind => $list) {
        $n = 100;
        foreach ($list as $t) q("INSERT INTO scope_items(package_pk, kind, category, name, sort_order) VALUES (?, ?, 'Imported', ?, ?)", array((int) $pk, $kind, $t, $n++));
    }
    $v = pkg_snapshot((int) $pk, 'resync', $note);
    q('UPDATE packages SET site_baseline_version = ?, published_version = ?, site_hash = ? WHERE package_pk = ?', array($v, $v, site_pkg_hash($sp), (int) $pk));
    if ($own) $db->commit();
    return $v;
}

/** Copy a CMS-uploaded image into the website (original + 480/960 WebP, the sizes hg_img() looks for). */
function site_publish_image($rel, $slug)
{
    if ($rel === '') return '';
    if (strpos($rel, 'site:') === 0) return substr($rel, 5);
    $src = media_abs($rel);
    if (!$src || !is_file($src)) return '';
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($src);
    $ext = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp')[$mime] ?? null;
    if (!$ext) return '';
    $dir = 'assets/img/packages/cms';
    $base = $slug . '-' . substr(sha1_file($src), 0, 10);
    $dest = site_path($dir . '/' . $base . '.' . $ext);
    if (!cms_site_writes()) return $dir . '/' . $base . '.' . $ext;   // staging: report the path, copy nothing
    if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
    if (!is_file($dest)) {
        copy($src, $dest);
        $img = gd_open($dest, $mime);
        if ($img && function_exists('imagewebp')) {
            $w = imagesx($img); $h = imagesy($img);
            foreach (array(480, 960) as $vw) {
                if ($vw > $w) continue;
                $dst = imagecreatetruecolor($vw, (int) round($h * $vw / $w));
                imagecopyresampled($dst, $img, 0, 0, 0, 0, $vw, (int) round($h * $vw / $w), $w, $h);
                imagewebp($dst, site_path($dir . '/' . $base . '-' . $vw . '.webp'), 82);
                imagedestroy($dst);
            }
            imagedestroy($img);
        }
    }
    return $dir . '/' . $base . '.' . $ext;
}

/** Create the small page file for a new package (same as every other package page). */
function site_ensure_page($slug)
{
    if (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug)) return false;
    $f = site_path($slug . '.php');
    if (is_file($f)) return false;
    file_put_contents($f, "<?php\n// Package detail page, created by the CMS on publish. Content comes from this package's\n// data in include/data/packages.json (written by the CMS).\nrequire __DIR__ . '/include/templates/package-detail.php';\nhg_render_package('" . $slug . "');\n");
    $sm = site_path('sitemap.xml');
    if (is_file($sm) && is_writable($sm)) {
        $x = (string) file_get_contents($sm);
        $loc = rtrim(cms_config('site_url'), '/') . '/' . $slug;
        if ($x !== '' && strpos($x, '<loc>' . $loc . '</loc>') === false && strpos($x, '</urlset>') !== false) {
            file_put_contents($sm, str_replace('</urlset>', "  <url><loc>" . $loc . "</loc><lastmod>" . date('Y-m-d') . "</lastmod></url>\n</urlset>", $x));
        }
    }
    return true;
}

/** Build the new contents of every synced file. Returns [files => [name => json string], summary => [...]] */
function site_build()
{
    $site = site_json_read('packages.json', array());
    $bySlug = array(); foreach ($site as $i => $e) $bySlug[$e['slug']] = $i;
    $sum = array('updated' => array(), 'added' => array(), 'removed' => array(), 'pages' => array(), 'sync_required' => array(), 'hashes' => array());
    $retired = site_retired_slugs();
    $renamed = site_renamed_slugs();
    $seo = array(); $live = array();
    foreach (q('SELECT package_pk, slug, status, source, published_version, site_baseline_version, site_hash, public_url FROM packages ORDER BY package_pk')->fetchAll() as $row) {
        $slug = $row['slug']; $onSite = isset($bySlug[$slug]);
        if (in_array($row['status'], array('paused', 'archived'), true)) {
            if ($onSite) { $site[$bySlug[$slug]] = null; $sum['removed'][] = $slug; }
            continue;
        }
        $pub = site_snapshot($row['package_pk'], $row['published_version']);
        if (!$pub || !in_array($row['status'], array('published', 'approved', 'in_review', 'draft'), true) || !$row['published_version']) continue;
        $live[$slug] = $pub;
        // Baseline = what the website already shows for this package: the last re-sync, else the original import.
        $baseV = !empty($row['site_baseline_version']) ? (int) $row['site_baseline_version'] : ($row['source'] === 'site-import' ? 1 : 0);
        $base = $baseV ? site_snapshot($row['package_pk'], $baseV) : null;
        $now = site_fields($pub); $was = $base ? site_fields($base) : null;
        if ($onSite) {
            $e = $site[$bySlug[$slug]]; $changed = array();
            foreach ($now as $grp => $vals) {
                if ($grp === 'page_title') continue;
                if ($was && $was[$grp] === $vals) continue;
                if ($grp === 'image') $vals['image'] = site_publish_image($vals['image'], $slug) ?: $e['image'];
                if ($grp === 'description' && $vals['description'] === '') continue;
                foreach ($vals as $k => $v) if (!array_key_exists($k, $e) || $e[$k] !== $v) { $e[$k] = $v; $changed[$grp] = true; }
            }
            if ($changed) {
                // Never overwrite a website record that changed outside the CMS since the last sync (SYNC REQUIRED).
                if (empty($row['site_hash']) || $row['site_hash'] !== site_pkg_hash($site[$bySlug[$slug]])) { $sum['sync_required'][] = $slug; }
                else { $site[$bySlug[$slug]] = $e; $sum['updated'][$slug] = array_keys($changed); $sum['hashes'][$slug] = site_pkg_hash($e); }
            }
        } elseif (isset($retired[$slug]) || isset($renamed[$slug]) || isset($renamed[str_replace(array('–', '—'), '-', $slug)])) {
            $sum['sync_required'][] = $slug;   // retired Package ID, or an old URL the website renamed: never re-add
        } else {
            $f = array(); foreach ($now as $vals) $f += $vals;
            $f['image'] = site_publish_image($f['image'], $slug);
            $site[] = array('slug' => $slug, 'url' => '/' . $slug, 'title' => $pub['name'] . ' for ' . $f['duration'], 'name' => $f['name'],
                'page_title' => $f['page_title'] ?: $pub['name'] . ' – ' . $f['duration'], 'description' => $f['description'], 'duration' => $f['duration'],
                'nights' => $f['nights'], 'days' => $f['days'], 'cities' => $f['cities'], 'image' => $f['image'], 'group' => $f['group'], 'departure' => null,
                'pilgrimage' => $f['pilgrimage'], 'itinerary' => $f['itinerary'], 'inclusions' => $f['inclusions'], 'exclusions' => $f['exclusions'],
                'terms' => array(), 'booking' => array(), 'warnings' => array(), 'corrections' => array(), 'places' => $f['places'], 'features' => $f['features'],
                'hotel' => $f['hotel'], 'meals' => $f['meals'], 'transfers' => $f['transfers'], 'start' => $f['start'], 'end' => $f['end'],
                'special_notes' => $f['special_notes'], 'itinerary_source' => $f['itinerary_source']);
            $sum['added'][] = $slug; $sum['pages'][] = $slug;
            $sum['hashes'][$slug] = site_pkg_hash(end($site));
        }
        // SEO edited in the CMS (compared with the import, so the approved Phase 2 metadata is kept unless changed)
        $t = trim((string) $pub['seo']['meta_title']); $d = trim((string) $pub['seo']['meta_description']);
        $bt = $base ? trim((string) $base['seo']['meta_title']) : null; $bd = $base ? trim((string) $base['seo']['meta_description']) : null;
        $o = array();
        if ($t !== '' && $t !== $bt) $o['title'] = $t;
        if ($d !== '' && $d !== $bd) $o['description'] = $d;
        if ($o) $seo['/' . $slug] = $o;
    }
    $site = array_values(array_filter($site));
    // Rates: approved versions of live packages (the website itself checks the validity dates)
    $versions = array();
    foreach (q("SELECT r.*, p.slug, p.package_id, p.package_id_status, u.name approver FROM rate_versions r JOIN packages p ON p.package_pk = r.package_pk LEFT JOIN users u ON u.user_id = r.approved_by WHERE r.rate_status = 'approved' ORDER BY p.slug, r.version")->fetchAll() as $r) {
        if (!isset($live[$r['slug']])) continue;
        $versions[] = array('slug' => $r['slug'], 'package_id' => $r['package_id_status'] === 'approved' ? $r['package_id'] : null, 'version' => (int) $r['version'],
            'base_price' => (float) $r['base_price'], 'currency' => $r['currency'], 'price_unit' => $r['price_unit'], 'rate_status' => 'approved',
            'rate_valid_from' => $r['valid_from'], 'rate_valid_until' => $r['valid_until'], 'rate_updated_at' => $r['approved_at'] ?: $r['created_at'],
            'approved_by' => (string) $r['approver'], 'price_notes' => (string) $r['price_notes']);
    }
    $rates = site_json_read('rates.json', array()); $rates['versions'] = $versions;
    // Offers
    $offers = site_json_read('offers.json', array()); $list = array();
    foreach (q("SELECT * FROM offers WHERE status IN ('published','expired') ORDER BY offer_code")->fetchAll() as $o) {
        $slugs = q('SELECT p.slug FROM offer_packages op JOIN packages p ON p.package_pk = op.package_pk WHERE op.offer_pk = ? ORDER BY p.slug', array($o['offer_pk']))->fetchAll(PDO::FETCH_COLUMN);
        $list[] = array('offer_code' => $o['offer_code'], 'title' => $o['name'], 'packages' => array_values(array_filter($slugs, function ($s) use ($live) { return isset($live[$s]); })),
            'status' => $o['status'], 'valid_from' => $o['valid_from'], 'valid_until' => $o['valid_until'], 'terms' => (string) $o['terms']);
    }
    $offers['offers'] = $list;
    // Curation
    $cur = site_json_read('curation.json', array()); $tours = array();
    foreach (q('SELECT c.*, p.slug FROM curation c JOIN packages p ON p.package_pk = c.package_pk ORDER BY p.slug')->fetchAll() as $c) {
        if (!isset($live[$c['slug']])) continue;
        $row = array_filter(array('priority_rank' => $c['priority_rank'] !== null ? (int) $c['priority_rank'] : null, 'is_featured' => (bool) $c['featured'], 'homepage_featured' => (bool) $c['homepage_featured'],
            'search_featured' => (bool) $c['search_featured'], 'seasonal_featured' => (bool) $c['seasonal_featured'], 'speciality_featured' => (bool) $c['speciality_featured']), function ($v) { return $v !== null && $v !== false; });
        if ($row) $tours[$c['slug']] = $row;
    }
    $cur['tours'] = $tours ?: new stdClass();
    $seoDoc = array('_about' => 'Written by the CMS. SEO titles/descriptions edited in the CMS; they override include/data/seo-meta.php for the same path.', 'pages' => $seo ?: new stdClass());
    return array('files' => array(
        'packages.json' => site_json_encode($site),
        'rates.json' => site_json_encode($rates), 'offers.json' => site_json_encode($offers), 'curation.json' => site_json_encode($cur), 'cms-seo.json' => site_json_encode($seoDoc),
    ), 'summary' => $sum, 'count' => count($site));
}

/**
 * Run a sync. Returns ['ok' => bool, 'changed' => [files], 'summary' => [...], 'message' => string].
 * $dry = true builds everything and reports what would change, without writing.
 */
function site_export($reason = 'auto', $dry = false)
{
    // Staging CMS: never write the website (the ribbon promises it). Dry runs still report what would change.
    if (!$dry && !cms_site_writes()) return array('ok' => false, 'changed' => array(), 'summary' => array(), 'message' => 'Staging CMS: the website was not changed. Website sync is switched off (config site_sync).');
    $dup = site_renamed_on_site();
    if (!$dry && $dup) return array('ok' => false, 'changed' => array(), 'summary' => array(), 'message' => 'Refused: the website still lists ' . count($dup) . ' packages under old (renamed) URLs, e.g. ' . $dup[0] . '. Restore include/data/packages.json from the backup first (INSTALL-CMS.md, part A).');
    $lockFile = CMS_ROOT . '/storage/site-export.lock';
    $lock = fopen($lockFile, 'c');
    if (!$lock || !flock($lock, LOCK_EX)) return array('ok' => false, 'changed' => array(), 'summary' => array(), 'message' => 'Another sync is running.');
    try {
        $before = count(site_json_read('packages.json', array()));
        $b = site_build();
        if ($before > 0 && $b['count'] < $before / 2) {
            cms_log('Website sync refused', null, 'packages', (string) $before, (string) $b['count']);
            return array('ok' => false, 'changed' => array(), 'summary' => $b['summary'], 'message' => "Refused: the sync would leave {$b['count']} of {$before} packages on the website.");
        }
        $changed = array();
        foreach ($b['files'] as $f => $json) {
            $cur = is_file(site_data_path($f)) ? (string) file_get_contents(site_data_path($f)) : null;
            if ($cur !== null && ($cur === $json || json_decode($cur, true) === json_decode($json, true))) continue;
            $changed[] = $f;
        }
        $sr = $b['summary']['sync_required'] ? ' SYNC REQUIRED (left unchanged): ' . implode(', ', $b['summary']['sync_required']) . '.' : '';
        if ($sr) cms_log('Website sync: sync required', null, $reason, '', mb_substr($sr, 0, 1900));
        if ($dry || !$changed) return array('ok' => true, 'changed' => $changed, 'summary' => $b['summary'], 'message' => ($changed ? 'Would update: ' . implode(', ', $changed) : 'The website is up to date.') . $sr);
        $bk = CMS_ROOT . '/storage/site-backups/' . date('Ymd-His');
        if (!is_dir($bk)) mkdir($bk, 0775, true);
        foreach ($changed as $f) if (is_file(site_data_path($f))) copy(site_data_path($f), $bk . '/' . $f);
        foreach ($changed as $f) {
            $tmp = site_data_path($f) . '.tmp-' . getmypid();
            if (file_put_contents($tmp, $b['files'][$f]) === false || !rename($tmp, site_data_path($f))) { @unlink($tmp); throw new RuntimeException('Could not write ' . $f . ' (check folder permissions).'); }
        }
        foreach ($b['summary']['pages'] as $slug) site_ensure_page($slug);
        foreach ($b['summary']['hashes'] as $slug => $h) q('UPDATE packages SET site_hash = ? WHERE slug = ?', array($h, $slug));
        // keep the 30 most recent backups
        $all = glob(CMS_ROOT . '/storage/site-backups/*', GLOB_ONLYDIR) ?: array(); sort($all);
        foreach (array_slice($all, 0, max(0, count($all) - 30)) as $old) { array_map('unlink', glob($old . '/*') ?: array()); @rmdir($old); }
        $s = $b['summary'];
        $msg = ($s['sync_required'] ? 'SYNC REQUIRED — not changed on the website: ' . implode(', ', $s['sync_required']) . ' (re-sync from website, then publish again). ' : '') . 'Website updated (' . implode(', ', $changed) . ')' . ($s['updated'] ? ' · changed: ' . implode(', ', array_keys($s['updated'])) : '') . ($s['added'] ? ' · new: ' . implode(', ', $s['added']) : '') . ($s['removed'] ? ' · taken off: ' . implode(', ', $s['removed']) : '');
        cms_log('Website updated', null, $reason, '', mb_substr($msg, 0, 1900));
        file_put_contents(CMS_ROOT . '/storage/site-export.json', je(array('at' => now(), 'reason' => $reason, 'files' => $changed, 'backup' => basename($bk), 'summary' => $s)));
        return array('ok' => true, 'changed' => $changed, 'summary' => $s, 'message' => $msg);
    } catch (Throwable $x) {
        error_log('hg cms site sync: ' . $x->getMessage());
        return array('ok' => false, 'changed' => array(), 'summary' => array(), 'message' => 'Website sync failed: ' . $x->getMessage());
    } finally {
        flock($lock, LOCK_UN); fclose($lock);
    }
}

/** Last sync record for the dashboard, or null. */
function site_last_sync()
{
    $f = CMS_ROOT . '/storage/site-export.json';
    $d = is_file($f) ? json_decode((string) file_get_contents($f), true) : null;
    return is_array($d) ? $d : null;
}


/**
 * Mandatory items that block publishing. A package that is already live may publish an update while it still has
 * gaps it already had (e.g. the packages imported from the website), but not add a new gap; a package going live
 * for the first time must pass every mandatory check.
 */
function pkg_publish_blockers(array $p)
{
    list($err) = pkg_blockers($p);
    if (!$err || empty($p['published_version'])) return $err;
    $live = site_snapshot($p['package_pk'], $p['published_version']);
    $had = $live ? array_column(pkg_blockers($live)[0], 'key') : array();
    return array_values(array_filter($err, function ($i) use ($had) { return !in_array($i['key'], $had, true); }));
}

/* ---------- Website → CMS import of packages the CMS does not have yet ---------- */

/**
 * Insert one website package (packages.json entry) into the CMS as a published, site-imported package: package row,
 * itinerary days (overnight places, suggested-day flags), inclusions/exclusions, SEO and the featured photo.
 * $reg is the website's registry entry for the slug (or null); $usePackageId = false leaves the Package ID unassigned
 * (it stays "proposed") when the number is already held by another CMS package. Returns the new package_pk.
 * The caller writes version 1 (site_import_finish) once rates, offers and curation are attached.
 */
function site_import_package(array $p, $reg, $usePackageId = true)
{
    $db = cms_db();
    $dest = hg_destinations();
    $g = isset($dest[$p['group']]) ? $dest[$p['group']] : array('country' => '', 'region' => '');
    $idStatus = $reg ? (($reg['status'] === 'approved' && $usePackageId) ? 'approved' : 'proposed') : 'pending';
    q('INSERT INTO packages(package_id, proposed_package_id, package_id_status, slug, name, country, region, destination, city_route, package_type, speciality_type, days, nights,
        suitable_for, short_description, description_html, highlights, status, public_url, source, created_at, updated_at, published_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
        $idStatus === 'approved' ? $reg['package_id'] : null, $reg ? $reg['package_id'] : null, $idStatus,
        $p['slug'], $p['name'], $g['country'], $g['region'], $p['group'], $p['places'] ? implode(' – ', $p['places']) : $p['cities'],
        $p['pilgrimage'] ? 'Pilgrimage' : '', $p['pilgrimage'] ? 'Pilgrimage' : '', (int) $p['days'], (int) $p['nights'],
        '[]', mb_strlen($p['description']) <= 300 ? $p['description'] : '', '<p>' . e($p['description']) . '</p>', je(array_values((array) $p['features'])),
        'published', $p['url'], 'site-import', now(), now(), now(),
    ));
    $pk = (int) $db->lastInsertId();
    // Package-level values and overnight places / suggested-day flags, so the first export baseline matches the site.
    q('UPDATE packages SET hotel_category = ?, meal_plan = ?, transport = ?, start_point = ?, end_point = ?, special_notes = ?, itinerary_source = ?, site_hash = ? WHERE package_pk = ?', array(
        (string) $p['hotel'], (string) $p['meals'], (string) $p['transfers'], (string) ($p['start'] ?? ''), (string) ($p['end'] ?? ''), (string) ($p['special_notes'] ?? ''),
        ($p['itinerary_source'] ?? '') === 'standard' ? 'standard' : 'package', site_pkg_hash($p), $pk));
    foreach ($p['itinerary'] as $i => $d) {
        list($over, $title) = site_split_day_title(preg_replace('/\s+/', ' ', $d['title']));
        q('INSERT INTO itinerary_days(package_pk, day_number, title, overnight, description, generated) VALUES (?,?,?,?,?,?)', array($pk, $i + 1, $title, $over, trim($d['text']), empty($d['generated']) ? 0 : 1));
    }
    $n = 0;
    foreach ($p['inclusions'] as $t) q("INSERT INTO scope_items(package_pk, kind, category, name, sort_order) VALUES (?, 'inclusion', 'Imported', ?, ?)", array($pk, $t, $n++));
    $n = 0;
    foreach (HG_STANDARD_EXCLUSIONS as $t) q("INSERT INTO scope_items(package_pk, kind, category, name, sort_order, is_standard, icon) VALUES (?, 'exclusion', 'Travel', ?, ?, 1, 'ticket')", array($pk, $t, $n++));
    foreach ($p['exclusions'] as $t) q("INSERT INTO scope_items(package_pk, kind, category, name, sort_order) VALUES (?, 'exclusion', 'Imported', ?, ?)", array($pk, $t, $n++));
    q('INSERT INTO package_seo(package_pk, meta_title, meta_description, canonical) VALUES (?,?,?,?)', array($pk, $p['page_title'], $p['description'], rtrim(cms_config('site_url'), '/') . $p['url']));
    if ($p['image'] && is_file(site_path($p['image']))) {
        $rel = 'site:' . $p['image'];
        $mid = qv('SELECT media_id FROM media WHERE file_path = ?', array($rel));
        if (!$mid) {
            $info = @getimagesize(site_path($p['image']));
            q('INSERT INTO media(file_path, mime, width, height, bytes, destination, created_at) VALUES (?,?,?,?,?,?,?)', array(
                $rel, $info ? $info['mime'] : '', $info ? $info[0] : 0, $info ? $info[1] : 0, filesize(site_path($p['image'])), $p['group'], now()));
            $mid = $db->lastInsertId();
        }
        q("INSERT INTO package_media(package_pk, media_id, role) VALUES (?, ?, 'featured')", array($pk, $mid));
    }
    return $pk;
}

/** Version 1 of an imported package: the snapshot the website shows, used as the publish and export baseline. */
function site_import_finish($pk, $note = 'Imported from the website (include/data/packages.json)')
{
    $p = pkg_load($pk); unset($p['reviews']);
    q('INSERT INTO package_versions(package_pk, package_id, version, sections, note, snapshot, changed_by, changed_at) VALUES (?,?,?,?,?,?,?,?)', array($pk, pkg_public_id($p), 1, 'import', $note, je($p), uid(), now()));
    q('UPDATE packages SET version = 1, published_version = 1, site_baseline_version = 1 WHERE package_pk = ?', array($pk));
}

/**
 * Website packages the CMS does not hold yet. A website package counts as already in the CMS when a CMS package has
 * its slug, one of its previous slugs (registry, approved URL migrations) or its en-dash spelling; retired Package IDs
 * are never imported. Returns ['new' => [entry + '_reg' + '_id_taken'], 'known' => int, 'retired' => [slugs]].
 */
function site_import_candidates()
{
    $site = site_json_read('packages.json', array());
    $reg = site_json_read('package-registry.json', array());
    $regBySlug = array();
    foreach ((array) (isset($reg['entries']) ? $reg['entries'] : array()) as $e) $regBySlug[$e['slug']] = $e;
    $retired = site_retired_slugs();
    $cmsSlugs = array();
    foreach (q('SELECT slug FROM packages')->fetchAll(PDO::FETCH_COLUMN) as $s) { $cmsSlugs[$s] = true; $cmsSlugs[str_replace(array('–', '—'), '-', $s)] = true; }
    $ids = array_flip(q('SELECT package_id FROM packages WHERE package_id IS NOT NULL')->fetchAll(PDO::FETCH_COLUMN));
    $out = array('new' => array(), 'known' => 0, 'retired' => array());
    foreach ($site as $p) {
        $r = isset($regBySlug[$p['slug']]) ? $regBySlug[$p['slug']] : null;
        if (isset($retired[$p['slug']])) { $out['retired'][] = $p['slug']; continue; }
        $known = isset($cmsSlugs[$p['slug']]);
        foreach ((array) ($r && isset($r['previous_slugs']) ? $r['previous_slugs'] : array()) as $old) if (isset($cmsSlugs[$old]) || isset($cmsSlugs[str_replace(array('–', '—'), '-', $old)])) $known = true;
        if ($known) { $out['known']++; continue; }
        $p['_reg'] = $r;
        $p['_id_taken'] = $r && isset($ids[$r['package_id']]);
        $out['new'][] = $p;
    }
    return $out;
}

/**
 * Import every website package the CMS does not have yet (one transaction). Existing CMS packages, rates, offers,
 * curation, users and settings are not touched. $apply = false only reports. Returns
 * ['imported' => n, 'known' => n, 'id_taken' => [slug => id], 'retired' => [...], 'slugs' => [...]].
 */
function site_import_new($apply)
{
    $dup = site_renamed_on_site();
    if ($apply && $dup) throw new RuntimeException('The website still lists ' . count($dup) . ' packages under old (renamed) URLs, e.g. ' . $dup[0] . '. Restore include/data/packages.json from the backup first (INSTALL-CMS.md, part A).');
    if ($apply) {
        // CMS packages still under an old (renamed) URL take the website's current slug first, as a re-sync does.
        $bySlug = array(); foreach (site_json_read('packages.json', array()) as $sp) $bySlug[$sp['slug']] = true;
        foreach (q('SELECT package_pk, slug FROM packages')->fetchAll() as $row) if (!isset($bySlug[$row['slug']])) site_resync_slug($row, $bySlug);
    }
    $c = site_import_candidates();
    $res = array('imported' => count($c['new']), 'known' => $c['known'], 'id_taken' => array(), 'retired' => $c['retired'], 'slugs' => array());
    foreach ($c['new'] as $p) { $res['slugs'][] = $p['slug']; if ($p['_id_taken']) $res['id_taken'][$p['slug']] = $p['_reg']['package_id']; }
    if (!$apply || !$c['new']) return $res;
    $db = cms_db();
    $db->beginTransaction();
    try {
        foreach ($c['new'] as $p) {
            $entry = $p['_reg']; $taken = $p['_id_taken'];
            unset($p['_reg'], $p['_id_taken']);   // helper keys: never part of the stored record or its hash
            $pk = site_import_package($p, $entry, !$taken);
            site_import_finish($pk);
        }
        // New CMS packages continue after the highest number in the website's Package ID registry.
        $reg = site_json_read('package-registry.json', array());
        $maxId = 0;
        foreach ((array) (isset($reg['entries']) ? $reg['entries'] : array()) as $e) $maxId = max($maxId, (int) $e['package_id']);
        q("UPDATE sequences SET last_value = ? WHERE name = 'package_id' AND last_value < ?", array($maxId, $maxId));
        cms_log('New website packages imported', null, '', '', count($c['new']) . ' packages' . ($res['id_taken'] ? '; Package ID already in use, left proposed: ' . implode(', ', array_keys($res['id_taken'])) : ''));
        $db->commit();
    } catch (Throwable $x) {
        $db->rollBack();
        throw $x;
    }
    return $res;
}
