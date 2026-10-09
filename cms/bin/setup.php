<?php
/**
 * Staging setup: create the CMS database, import the website's package data (read-only), create one
 * staging user per role with a random password.
 *
 *   php cms/bin/setup.php            create / update (keeps existing data)
 *   php cms/bin/setup.php --reset    delete the staging database and uploads first
 *   --no-staging-users               do not create the staging test users (for a database that goes to a server;
 *                                    add real logins with bin/create-user.php)
 *
 * Refuses to run when config env is 'production'. Never writes into the website folder.
 * Staging logins are written to cms/storage/.staging-credentials (gitignored, mode 0600).
 */
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/packages.php';
require __DIR__ . '/../src/site_export.php';

if (PHP_SAPI !== 'cli') exit(1);
if (!cms_is_staging()) { fwrite(STDERR, "Refusing: config env is production.\n"); exit(2); }

$reset = in_array('--reset', $argv, true);
$dsn = cms_config('dsn');
if ($reset && strpos($dsn, 'sqlite:') === 0) {
    $file = substr($dsn, 7);
    foreach (array($file, "$file-wal", "$file-shm") as $f) if (is_file($f)) unlink($f);
    $up = cms_config('upload_dir');
    if (is_dir($up)) exec('rm -rf ' . escapeshellarg($up));
    @unlink(CMS_ROOT . '/storage/.staging-credentials');
}
@mkdir(cms_config('upload_dir'), 0775, true);

$db = cms_db();
cms_migrate($db);

/* ---------- users ---------- */
$creds = array();
foreach (in_array('--no-staging-users', $argv, true) ? array() : HG_ROLES as $role => $label) {
    $email = str_replace('_', '.', $role) . '@staging.invalid';
    if (qv('SELECT 1 FROM users WHERE email = ?', array($email))) continue;
    $pw = rtrim(strtr(base64_encode(random_bytes(12)), '+/', 'Ab'), '=');
    q('INSERT INTO users(email, name, role, password_hash, created_at) VALUES (?,?,?,?,?)', array($email, 'Staging ' . $label, $role, password_hash($pw, PASSWORD_DEFAULT), now()));
    $creds[] = sprintf('%-20s %-34s %s', $label, $email, $pw);
}
if ($creds) {
    $cf = CMS_ROOT . '/storage/.staging-credentials';
    file_put_contents($cf, "# Staging CMS logins (random, staging only; never commit)\n" . implode("\n", $creds) . "\n", FILE_APPEND);
    chmod($cf, 0600);
    echo "Created " . count($creds) . " staging users. Logins: $cf\n";
}

/* ---------- import website packages (only when the database has none) ---------- */
if ((int) qv('SELECT COUNT(*) FROM packages') === 0) {
    $read = function ($f) { return json_decode((string) file_get_contents(site_path('include/data/' . $f)), true); };
    $pkgs = $read('packages.json');
    $reg = $read('package-registry.json');
    $rates = $read('rates.json');
    $offers = $read('offers.json');
    $cur = $read('curation.json');
    $proposed = array();
    foreach ($reg['entries'] as $r) $proposed[$r['slug']] = $r;
    $db->beginTransaction();
    $pkByslug = array();
    foreach ($pkgs as $p) $pkByslug[$p['slug']] = site_import_package($p, isset($proposed[$p['slug']]) ? $proposed[$p['slug']] : null);
    foreach ($rates['versions'] as $v) {
        if (!isset($pkByslug[$v['slug']])) continue;
        q('INSERT INTO rate_versions(package_pk, version, base_price, currency, price_unit, valid_from, valid_until, rate_status, price_notes, created_at, approved_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)', array(
            $pkByslug[$v['slug']], $v['version'], $v['base_price'], $v['currency'], $v['price_unit'], $v['rate_valid_from'], $v['rate_valid_until'], $v['rate_status'], $v['price_notes'] ?? '', $v['rate_updated_at'] ?? now(), $v['rate_status'] === 'approved' ? now() : null));
    }
    foreach ($offers['offers'] as $o) {
        q('INSERT INTO offers(offer_code, name, valid_from, valid_until, terms, status, created_at) VALUES (?,?,?,?,?,?,?)', array($o['offer_code'], $o['title'], $o['valid_from'], $o['valid_until'], $o['terms'] ?? '', $o['status'], now()));
        $op = $db->lastInsertId();
        foreach ($o['packages'] as $s) if (isset($pkByslug[$s])) q('INSERT INTO offer_packages VALUES (?,?)', array($op, $pkByslug[$s]));
    }
    foreach ($cur['tours'] as $slug => $c) {
        if (!isset($pkByslug[$slug])) continue;
        q('INSERT INTO curation(package_pk, priority_rank, featured, homepage_featured, search_featured, seasonal_featured, speciality_featured) VALUES (?,?,?,?,?,?,?)', array(
            $pkByslug[$slug], $c['priority_rank'] ?? null, (int) !empty($c['is_featured']), (int) !empty($c['homepage_featured']), (int) !empty($c['search_featured']), (int) !empty($c['seasonal_featured']), (int) !empty($c['speciality_featured'])));
    }
    // Sequences: new packages continue after the highest number in the Package ID mapping; offers after the highest code.
    $maxId = 0;
    foreach ($reg['entries'] as $r) $maxId = max($maxId, (int) $r['package_id']);
    q("UPDATE sequences SET last_value = MAX(last_value, ?) WHERE name = 'package_id'", array($maxId));
    $maxOf = 0;
    foreach ($offers['offers'] as $o) $maxOf = max($maxOf, (int) substr($o['offer_code'], 3));
    q("UPDATE sequences SET last_value = MAX(last_value, ?) WHERE name = 'offer_code'", array($maxOf));
    foreach ($pkByslug as $pk) site_import_finish($pk);
    q("INSERT INTO activity_log(at, action, new_value, ip) VALUES (?, 'Website packages imported', ?, 'cli')", array(now(), count($pkByslug) . ' packages'));
    $db->commit();
    echo 'Imported ' . count($pkByslug) . " website packages (Package IDs stay proposed until the owner approves the mapping).\n";
}
echo "Done. Start the staging CMS:  php -S 127.0.0.1:8099 -t cms/public cms/public/router.php\n";
