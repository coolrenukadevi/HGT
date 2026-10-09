<?php
/**
 * CMS domain tests (no browser). Uses a throwaway SQLite database and a temporary config, so it never touches
 * the staging database, the website data or any production system.
 *   php cms/tests/unit.php
 */
$tmp = sys_get_temp_dir() . '/hgcms-test-' . getmypid();
@mkdir($tmp . '/uploads', 0777, true);
file_put_contents("$tmp/config.php", '<?php return ' . var_export(array(
    'env' => 'staging', 'dsn' => "sqlite:$tmp/cms.sqlite", 'db_user' => null, 'db_pass' => null,
    'site_root' => realpath(__DIR__ . '/../../public_html'), 'site_url' => 'https://holidaygurutravel.in',
    'upload_dir' => "$tmp/uploads", 'upload_max_bytes' => 8388608, 'package_id_assignment' => true,
    'payment_gateway' => false, 'intake_token' => '', 'offer_zone_limit' => 100, 'reset_notify' => 'info@holidaygurutravel.in',
), true) . ';');
putenv("HG_CMS_CONFIG=$tmp/config.php");
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/packages.php';
require __DIR__ . '/../src/media.php';

$pass = 0; $fail = 0;
function t($ok, $msg) { global $pass, $fail; if ($ok) { $pass++; echo "PASS  $msg\n"; } else { $fail++; echo "FAIL  $msg\n"; } }
function throws($fn) { try { $fn(); return false; } catch (Throwable $e) { return $e->getMessage(); } }

$db = cms_db();
cms_migrate($db);
q("INSERT INTO users(email, name, role, password_hash, created_at) VALUES ('t@staging.invalid', 'Tester', 'super_admin', 'x', ?)", array(now()));
$_SESSION['uid'] = 1;

function mkpkg($name, $days = 4, $nights = 3)
{
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)) . '-' . mt_rand(1000, 9999);
    q("INSERT INTO packages(slug, name, destination, country, region, days, nights, status, public_url, created_at, updated_at, version) VALUES (?,?, 'kashmir', 'India', 'North India', ?, ?, 'draft', ?, ?, ?, 0)", array($slug, $name, $days, $nights, '/' . $slug, now(), now()));
    $pk = (int) cms_db()->lastInsertId();
    foreach (HG_STANDARD_EXCLUSIONS as $i => $t) q("INSERT INTO scope_items(package_pk, kind, category, name, sort_order, is_standard) VALUES (?, 'exclusion', 'Travel', ?, ?, 1)", array($pk, $t, $i));
    pkg_snapshot($pk, 'basic', 'Created');
    return $pk;
}

/* ---------- Package ID ---------- */
q("UPDATE sequences SET last_value = 107 WHERE name = 'package_id'");   // mapping reserves 0001–0107
$a = mkpkg('New Delhi Tour');
$p = pkg_load($a);
t(pkg_id_state($p)[1] === 'pending' && pkg_public_id($p) === null, 'new package: Package ID pending, nothing public');
$id = pkg_assign_id($a);
t($id === '0108', 'Package ID generated after the reserved mapping range (0108)');
t(pkg_public_id(pkg_load($a)) === '0108', 'approved Package ID is the public identifier');
t((bool) throws(function () use ($a) { pkg_assign_id($a); }), 'Package ID cannot be assigned twice (read-only once permanent)');
t((bool) throws(function () use ($a) { q("UPDATE packages SET package_id = '0000' WHERE package_pk = ?", array($a)); }), 'database rejects Package ID 0000');
$b = mkpkg('Second Tour');
t((bool) throws(function () use ($b) { q("UPDATE packages SET package_id = '0108' WHERE package_pk = ?", array($b)); }), 'database rejects a duplicate Package ID');
q("UPDATE packages SET proposed_package_id = '0042', package_id_status = 'proposed' WHERE package_pk = ?", array($b));
t(pkg_id_state(pkg_load($b)) === array('0042', 'proposed') && pkg_public_id(pkg_load($b)) === null, 'proposed Package ID is internal only');
t(pkg_assign_id($b) === '0042', 'imported package takes its approved proposed number');
t(qv("SELECT last_value FROM sequences WHERE name = 'package_id'") == 108, 'sequence never moves backwards');
q('DELETE FROM packages WHERE package_pk = ?', array($a));
$c = mkpkg('Third Tour');
t(pkg_assign_id($c) === '0109', 'a deleted package’s Package ID is never reused');

/* ---------- Itinerary linked to package; Package ID unchanged by edits ---------- */
q("INSERT INTO itinerary_days(package_pk, day_number, title, description) VALUES (?, 1, 'Arrival', 'Arrive and check in.')", array($c));
pkg_snapshot($c, 'itinerary');
t(pkg_load($c)['package_id'] === '0109', 'changing the itinerary keeps the Package ID');
$v = q1('SELECT * FROM package_versions WHERE package_pk = ? ORDER BY version DESC', array($c));
t($v['package_id'] === '0109' && strpos($v['sections'], 'itinerary') !== false, 'itinerary version records Package ID and changed section');
t(pkg_duration_issue(pkg_load($c)) === 'Itinerary contains 1 day but package duration is 4 days.', 'duration mismatch warning text');
foreach (array(2, 3, 4, 5) as $n) q("INSERT INTO itinerary_days(package_pk, day_number, title, description) VALUES (?, ?, 'Day', 'Text')", array($c, $n));
t(strpos(pkg_duration_issue(pkg_load($c)), 'contains 5 days but package duration is 4 days') !== false, 'too many itinerary days is flagged');
$chk = pkg_checklist(pkg_load($c));
$dur = array_values(array_filter($chk['Content'], function ($i) { return $i['key'] === 'duration'; }))[0];
t(!$dur['ok'], 'duration mismatch blocks publishing');
q("UPDATE packages SET duration_override = 'Day 5 is departure morning' WHERE package_pk = ?", array($c));
$chk = pkg_checklist(pkg_load($c));
$dur = array_values(array_filter($chk['Content'], function ($i) { return $i['key'] === 'duration'; }))[0];
t($dur['ok'], 'explicit override clears the duration block');

/* ---------- Pricing: versions, expiry ---------- */
putenv('HG_CMS_TODAY=2026-10-15');
q("INSERT INTO rate_versions(package_pk, version, base_price, price_unit, valid_from, valid_until, rate_status, created_at) VALUES (?, 1, 24999, 'per person', '2026-10-01', '2026-10-31', 'approved', ?)", array($c, now()));
$r = pkg_current_rate(pkg_load($c));
t($r && rate_label($r) === '₹24,999 / person', 'approved rate within validity is current (Indian grouping)');
q("INSERT INTO rate_versions(package_pk, version, base_price, price_unit, valid_from, valid_until, rate_status, created_at) VALUES (?, 2, 26999, 'per person', '2026-10-10', '2026-11-30', 'draft', ?)", array($c, now()));
t((int) pkg_current_rate(pkg_load($c))['version'] === 1, 'a draft price version is not shown');
q("UPDATE rate_versions SET rate_status = 'approved' WHERE package_pk = ? AND version = 2", array($c));
t((int) pkg_current_rate(pkg_load($c))['version'] === 2, 'new approved price version becomes current');
t(qv('SELECT base_price FROM rate_versions WHERE package_pk = ? AND version = 1', array($c)) == 24999, 'old price version kept unchanged');
putenv('HG_CMS_TODAY=2026-12-05');
t(pkg_current_rate(pkg_load($c)) === null && rate_label(null) === 'Price on request', 'expired rate → Price on request');
t((bool) throws(function () use ($c) { q("INSERT INTO rate_versions(package_pk, version, base_price, valid_from, valid_until, created_at) VALUES (?, 3, 1000, '2026-12-10', '2026-12-01', ?)", array($c, now())); }), 'database rejects validity ending before it starts');
t((bool) throws(function () use ($c) { q("INSERT INTO rate_versions(package_pk, version, base_price, valid_from, valid_until, created_at) VALUES (?, 2, 1000, '2026-12-10', '2026-12-20', ?)", array($c, now())); }), 'price version numbers are unique per package');
putenv('HG_CMS_TODAY=2026-10-15');
list($pay, $why) = pkg_paynow(pkg_load($c));
$gatewayWhy = site_pay_link() !== '' ? 'No per-package checkout connected (the website uses its general ' . (site_setting('HG_PAY_PROVIDER') ?: 'payment') . ' link)' : 'No payment gateway connected';
t(!$pay && in_array($gatewayWhy, $why, true), 'Pay Now disabled without a gateway, with the reason');
t(site_pay_link() === 'https://razorpay.me/@holidaygurutraveL' && site_setting('HG_PAY_PROVIDER') === 'Razorpay', "website pay link read from the website's site_config.php");

/* ---------- Standard exclusions / checklist ---------- */
$p = pkg_load($c);
$std = array_filter($p['scope'], function ($s) { return $s['is_standard']; });
t(count($std) === 3 && array_values(array_map(function ($s) { return $s['name']; }, $std)) === array('Airfare', 'Train fare', 'Bus fare'), 'standard exclusions: airfare, train fare, bus fare');
list($err) = pkg_blockers($p);
$keys = array_column($err, 'key');
t(in_array('description', $keys, true) && in_array('meta_title', $keys, true) && in_array('featured', $keys, true) && in_array('inclusions', $keys, true), 'thin package: publish blocked (description, meta title, image, inclusions)');
t(!in_array('pricing', $keys, true), 'Price on request does not block publishing (allowed state)');

/* ---------- Offers: separate sequence ---------- */
$o1 = next_offer_code(); $o2 = next_offer_code();
t($o1 === 'OF-0001' && $o2 === 'OF-0002', 'Offer Codes use their own OF- sequence');
t(qv("SELECT last_value FROM sequences WHERE name = 'package_id'") == 109, 'creating offers does not move the Package ID sequence');
q("INSERT INTO offers(offer_code, name, valid_from, valid_until, status, created_at) VALUES (?, 'Autumn', '2026-10-01', '2026-10-31', 'published', ?)", array($o1, now()));
q("INSERT INTO offers(offer_code, name, valid_from, valid_until, status, created_at) VALUES (?, 'Family', '2026-10-01', '2026-11-30', 'draft', ?)", array($o2, now()));
foreach (q('SELECT offer_pk FROM offers')->fetchAll() as $o) q('INSERT INTO offer_packages VALUES (?, ?)', array($o['offer_pk'], $c));
$p = pkg_load($c);
t(count($p['offers']) === 2 && $p['package_id'] === '0109', 'one package, many offers; Package ID unchanged');
t((bool) throws(function () { q("INSERT INTO offers(offer_code, name, valid_from, valid_until, created_at) VALUES ('0110', 'x', '2026-01-01', '2026-01-02', ?)", array(now())); }), 'database rejects an offer code shaped like a Package ID');

/* ---------- Search ---------- */
$s = cms_search('0109');
t(count($s['packages']) === 1 && (int) $s['packages'][0]['package_pk'] === $c, 'search by Package ID "0109"');
$s = cms_search('Package ID 109');
t(count($s['packages']) === 1, 'search "Package ID 109" (short form)');
$s = cms_search('0042');
t(count($s['packages']) === 1 && (int) $s['packages'][0]['package_pk'] === $b, 'search by another Package ID finds the right package');
$s = cms_search('of-1');
t($s['offer'] && $s['offer']['offer_code'] === 'OF-0001' && count($s['packages']) === 1, 'search by Offer Code returns the offer and its packages');
$s = cms_search('Third');
t(count($s['packages']) === 1, 'search by package name');

/* ---------- Rich text sanitiser ---------- */
$h = clean_html('<h2>Plan</h2><p onclick="x()">Hi <a href="javascript:alert(1)">x</a> <a href="https://a.b">y</a></p><script>alert(1)</script><aside class="hg-tip">Tip</aside><aside class="evil">z</aside><div><span>kept</span></div>');
t(strpos($h, 'script') === false && strpos($h, 'onclick') === false && strpos($h, 'javascript') === false, 'sanitiser strips scripts, handlers and javascript: links');
t(strpos($h, '<h2>Plan</h2>') !== false && strpos($h, 'rel="noopener"') !== false && strpos($h, 'class="hg-tip"') !== false && strpos($h, 'class="evil"') === false && strpos($h, 'kept') !== false, 'sanitiser keeps allowed structure and callout classes');

/* ---------- Activity log immutability ---------- */
cms_log('Test action', $c, 'f', 'a', 'b');
t((bool) throws(function () { q("UPDATE activity_log SET action = 'x'"); }), 'activity log rows cannot be updated');
t((bool) throws(function () { q('DELETE FROM activity_log'); }), 'activity log rows cannot be deleted');

/* ---------- Media validation ---------- */
$img = imagecreatetruecolor(1920, 1080);
imagefill($img, 0, 0, imagecolorallocate($img, 10, 22, 61));
imagejpeg($img, "$tmp/ok.jpg"); imagedestroy($img);
$small = imagecreatetruecolor(300, 200); imagepng($small, "$tmp/small.png"); imagedestroy($small);
file_put_contents("$tmp/fake.jpg", '<?php echo 1;');
$mid = media_store("$tmp/ok.jpg", 'Dal Lake.jpg', filesize("$tmp/ok.jpg"), array('alt_text' => 'Shikaras on Dal Lake at sunrise'), false);
$m = q1('SELECT * FROM media WHERE media_id = ?', array($mid));
t($m['width'] == 1920 && count(jd($m['variants'])) === 3 && is_file(media_abs(jd($m['variants'])['400'])), 'upload stores image and creates 1600/800/400 WebP variants');
t(strpos((string) throws(function () use ($tmp) { media_validate("$tmp/small.png", 'small.png', filesize("$tmp/small.png"), false); }), 'at least') !== false, 'too-small image rejected with a clear message');
t(strpos((string) throws(function () use ($tmp) { media_validate("$tmp/fake.jpg", 'fake.jpg', filesize("$tmp/fake.jpg"), false); }), 'Only JPG') !== false, 'non-image disguised as .jpg rejected (content check)');
$crop = media_crop($mid, '1:1');
$cm = q1('SELECT * FROM media WHERE media_id = ?', array($crop));
t($cm['width'] == 1080 && $cm['height'] == 1080 && is_file(media_abs($m['file_path'])), 'crop creates a new 1:1 image and keeps the original');

/* ---------- Permissions ---------- */
t(hg_may('super_admin', 'assign_package_id') && !hg_may('admin', 'assign_package_id') && !hg_may('content_manager', 'publish'), 'only Super Admin assigns Package IDs; Content Manager cannot publish');
t(hg_can('travel_consultant', 'pricing', 'request') && !hg_may('travel_consultant', 'approve_rate'), 'Travel Consultant can request, not approve, a rate');
t(!hg_can('reviewer', 'enquiries') && hg_can('sales_manager', 'enquiries', 'manage'), 'enquiry access follows the role matrix');

/* ---------- Accounts: password policy, 90-day expiry, in-place upgrade, reset tokens ---------- */
t(pw_problem('short1') !== '' && pw_problem('onlyletterspassword') !== '' && pw_problem('1234567890') !== '' && pw_problem('GoodPass2026') === '', 'password policy: 10+ characters with letters and numbers');
$h = password_hash('GoodPass2026', PASSWORD_DEFAULT);
t(pw_problem('GoodPass2026', $h) !== '' && pw_problem('NewerPass2026', $h) === '', 'password policy: the current password cannot be reused');
$now = gmdate('Y-m-d H:i:s');
t(!pw_expired(array('must_change' => 0, 'password_changed_at' => $now, 'created_at' => $now)), 'fresh password is not expired');
t(pw_expired(array('must_change' => 0, 'password_changed_at' => gmdate('Y-m-d H:i:s', time() - 91 * 86400), 'created_at' => $now)), 'password older than 90 days must be changed');
t(pw_expired(array('must_change' => 1, 'password_changed_at' => $now, 'created_at' => $now)), 'admin-set password must be changed at next sign-in');
t(pw_expired(array('must_change' => 0, 'password_changed_at' => null, 'created_at' => gmdate('Y-m-d H:i:s', time() - 100 * 86400))), 'never-changed password counts from account creation');
// Old database without the new columns is upgraded in place, keeping its rows
$old = new PDO("sqlite:$tmp/old.sqlite"); $old->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); $old->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$old->exec("CREATE TABLE users (user_id INTEGER PRIMARY KEY, email TEXT NOT NULL UNIQUE, name TEXT NOT NULL, role TEXT NOT NULL, password_hash TEXT NOT NULL, active INTEGER NOT NULL DEFAULT 1, created_at TEXT NOT NULL, last_login_at TEXT)");
$old->exec("INSERT INTO users(email, name, role, password_hash, created_at) VALUES ('keep@x.in', 'Keep', 'admin', 'h', '2026-10-01 00:00:00')");
cms_upgrade($old); cms_upgrade($old);
$cols = array_column($old->query('PRAGMA table_info(users)')->fetchAll(), 'name');
t(in_array('password_changed_at', $cols) && in_array('must_change', $cols) && in_array('photo', $cols) && $old->query("SELECT name FROM users")->fetchColumn() === 'Keep' && $old->query("SELECT COUNT(*) FROM sqlite_master WHERE name = 'password_resets'")->fetchColumn() == 1, 'old database upgraded in place (twice is safe), data kept');
// Reset tokens: stored hashed, one-time, expire
require __DIR__ . '/../src/mail.php';
require __DIR__ . '/../src/controllers/account.php';
putenv("HG_CMS_MAIL_DUMP=$tmp/mail.jsonl");
q("UPDATE users SET email = 'owner@x.in' WHERE user_id = 1");
reset_send(q1('SELECT * FROM users WHERE user_id = 1'), 'requested');
$mail = json_decode(file("$tmp/mail.jsonl", FILE_IGNORE_NEW_LINES)[0], true);
preg_match('/token=([a-f0-9]{64})/', $mail['text'], $m);
$all = array_map(function ($l) { return json_decode($l, true); }, file("$tmp/mail.jsonl", FILE_IGNORE_NEW_LINES));
t(count($all) === 2 && $all[1]['to'] === 'info@holidaygurutravel.in' && strpos($all[1]['text'], 'owner@x.in') !== false && strpos($all[1]['text'], 'token=') === false, 'info@ gets a notice of every reset (without the link)');
t($mail['to'] === 'owner@x.in' && isset($m[1]) && !qv('SELECT 1 FROM password_resets WHERE token_hash = ?', array($m[1])) && reset_row($m[1]), 'reset link emailed to the user; only a hash is stored');
q('UPDATE password_resets SET expires_at = ?', array(gmdate('Y-m-d H:i:s', time() - 1)));
t(!reset_row($m[1]), 'expired reset link is refused');
putenv('HG_CMS_MAIL_DUMP');

// Content hierarchy: CRM CUSTOM > PACKAGE OVERRIDE > GLOBAL STANDARD (public_html/include/package_resolve.php)
require_once __DIR__ . '/../src/site_export.php';
require_once __DIR__ . '/../src/quotes.php';
quote_resolver();
$base = array('name' => 'T', 'title' => '', 'nights' => 2, 'days' => 3, 'places' => array('A'), 'hotel' => '', 'meals' => '', 'transfers' => '', 'inclusions' => array(), 'exclusions' => array(),
    'itinerary' => array(array('title' => 'Day 1 : (A)Arrive', 'text' => 'x', 'generated' => true), array('title' => 'Day 2 : (A)See', 'text' => 'y'), array('title' => 'Day 3 : Leave', 'text' => 'z')));
$r = hg_package_resolve($base);
t($r['hotel'] === 'Standard / 3-star equivalent' && $r['_src']['hotel'] === 'standard' && $r['meals'] === 'Breakfast' && $r['_src']['inclusions'] === 'standard', 'resolver: global standard fills what the package does not state');
t($r['source_type'] === 'standard' && $r['suggested_note'] !== '', 'resolver: generated days = STANDARD source with the suggested-plan note');
$r = hg_package_resolve(array_merge($base, array('hotel' => 'Deluxe', 'meals' => 'Breakfast & dinner', 'inclusions' => array('Own line'))));
t($r['hotel'] === 'Deluxe' && $r['_src']['hotel'] === 'package' && $r['inclusions'] === array('Own line') && strpos($r['accommodation'], '3-star') === false, 'resolver: package values win and the standard is not shown next to them');
$r = hg_package_resolve(array_merge($base, array('hotel' => 'Deluxe')), array('hotel' => 'Super Deluxe', 'itinerary' => array(array('title' => 'Day 1 : (B)Custom', 'text' => 'c'))));
t($r['hotel'] === 'Super Deluxe' && $r['_src']['hotel'] === 'crm' && $r['source_type'] === 'crm' && $r['suggested_note'] === '' && count($r['itinerary']) === 1, 'resolver: CRM custom wins and shows no suggested-plan note');
$r = hg_package_resolve(array_merge($base, array('meals' => 'Breakfast & dinner', 'transfers' => 'Private cab')));
t(strpos(implode(' ', $r['inclusions']), 'Breakfast & dinner') !== false && strpos(implode(' ', $r['inclusions']), 'private cab') !== false, 'resolver: standard inclusions restate the package meal plan and transport (no contradiction)');
// Website day headings round-trip through the CMS
list($o, $tt) = site_split_day_title('Day 4 : (Pahalgam)Gulmarg to Pahalgam');
t($o === 'Pahalgam' && $tt === 'Gulmarg to Pahalgam' && site_day_title(array('day_number' => 4, 'title' => $tt, 'overnight' => $o)) === 'Day 4 : (Pahalgam)Gulmarg to Pahalgam', 'day heading splits into overnight + title and rebuilds the same');
// Quotation: one total only, CRM values resolved over the package
$q = array('title' => 'Q', 'days' => array(array('title' => 'Arrive', 'overnight' => 'Jaipur', 'text' => 't', 'sightseeing' => '')), 'inclusions' => array(), 'exclusions' => array(),
    'meal_plan' => 'MAPAI', 'hotel_category' => '', 'transport' => '', 'start' => '', 'end' => '', 'special_notes' => '');
$res = quote_resolved(array(), null, $q);
t($res['r']['meals'] === 'MAPAI' && $res['r']['hotel'] === 'Standard / 3-star equivalent' && $res['r']['source_type'] === 'crm', 'quotation resolves CRM values, then the standard');
t(count(quote_terms()) >= 9 && quote_terms() === quote_terms(), 'quotation terms are one fixed set for every quotation');

// Publish safety: a website record changed outside the CMS is never overwritten (SYNC REQUIRED)
$e1 = array('slug' => 'x', 'name' => 'A', 'itinerary' => array(array('title' => 'Day 1', 'text' => 't')));
$e2 = array('itinerary' => array(array('text' => 't', 'title' => 'Day 1')), 'name' => 'A', 'slug' => 'x');
t(site_pkg_hash($e1) === site_pkg_hash($e2) && site_pkg_hash($e1) !== site_pkg_hash(array_merge($e1, array('name' => 'B'))), 'website record hash ignores key order and changes with content');
$any = q1("SELECT * FROM packages WHERE source = 'site-import' LIMIT 1");
if ($any) {
    q('UPDATE packages SET site_hash = NULL WHERE package_pk = ?', array($any['package_pk']));
    t(site_sync_state(pkg_row($any['package_pk'])) === 'required', 'package never synced with the website → SYNC REQUIRED');
} else t(true, 'SYNC REQUIRED check (no imported package in the test database)');

// Website → CMS import of packages the CMS does not have yet (reads the website, writes only the test database)
$siteCount = count(site_json_read('packages.json', array()));
$dry = site_import_new(false);
t($dry['imported'] + $dry['known'] === $siteCount && $dry['imported'] > 0, 'import dry run: every website package is either new or already in the CMS');
$before = (int) qv('SELECT COUNT(*) FROM packages');
$res = site_import_new(true);
t((int) qv('SELECT COUNT(*) FROM packages') === $before + $res['imported'] && site_import_new(false)['imported'] === 0, 'import adds the new packages once; a second run imports nothing');
$imp = pkg_row((int) qv('SELECT package_pk FROM packages WHERE slug = ?', array($res['slugs'][0])));
t($imp['status'] === 'published' && (int) $imp['published_version'] === 1 && (int) $imp['site_baseline_version'] === 1 && site_sync_state($imp) === 'ok', 'imported package is live, baselined at version 1 and in sync with the website');
// A CMS package still under an old (renamed) URL is never added to the website as a new package
$ren = site_renamed_slugs();
$old = key($ren);
q('UPDATE packages SET slug = ? WHERE slug = ?', array($old, $ren[$old]));
$sb = site_build();
t(!in_array($old, $sb['summary']['added'], true) && in_array($old, $sb['summary']['sync_required'], true) && site_sync_state(pkg_row((int) qv('SELECT package_pk FROM packages WHERE slug = ?', array($old)))) === 'required', 'old renamed URL is SYNC REQUIRED, never re-added to the website');
t($sb['count'] === $siteCount, 'website sync after the import keeps the same number of website packages');

t(site_renamed_on_site() === array(), 'the website lists no package under an old (renamed) URL');
// Staging CMS never writes the website (the "changes here do not reach the live website" ribbon)
$h = sha1_file(site_data_path('packages.json'));
$sx = site_export('test');
t(!$sx['ok'] && !cms_site_writes() && sha1_file(site_data_path('packages.json')) === $h && !is_file(site_data_path('cms-seo.json')), 'staging CMS: website sync is refused and the website files stay unchanged');

exec('rm -rf ' . escapeshellarg($tmp));
echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
