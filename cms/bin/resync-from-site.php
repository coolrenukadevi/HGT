<?php
/**
 * Re-sync CMS packages from the website's current data (public_html/include/data/packages.json).
 *
 * Use after website package data was improved outside the CMS (e.g. the 2026-10-04 itinerary work), so a later CMS
 * publish never writes older imported data back over it. For each website package with the same slug it updates the
 * itinerary (overnight places, suggested-day flags), inclusions/exclusions, hotel category, meal plan, transport,
 * start/end and route, records a new version and makes that version the export baseline.
 * Package IDs, status, rates, offers, SEO and media are not changed. Packages with unpublished CMS edits are skipped
 * unless --force is given.
 *
 *   php cms/bin/resync-from-site.php            # dry run: lists what would change
 *   php cms/bin/resync-from-site.php --apply
 *   php cms/bin/resync-from-site.php --apply --force
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/packages.php';
require __DIR__ . '/../src/site_export.php';

$apply = in_array('--apply', $argv, true);
$force = in_array('--force', $argv, true);
$db = cms_db();
cms_upgrade($db);
$site = json_decode((string) file_get_contents(site_path('include/data/packages.json')), true) ?: array();
$bySlug = array();
foreach ($site as $sp) $bySlug[$sp['slug']] = $sp;

$done = 0; $skipped = array(); $missing = array(); $retired = array();
foreach (q('SELECT package_pk, slug, status, version, published_version FROM packages ORDER BY package_pk')->fetchAll() as $row) {
    if (site_archive_if_retired($row)) { $retired[] = $row['slug']; continue; }
    $slug = site_resync_slug($row, $bySlug);
    if ($slug === null) { $missing[] = $row['slug']; continue; }
    if (!$force && (int) $row['version'] !== (int) $row['published_version']) { $skipped[] = $row['slug']; continue; }
    if ($apply) site_resync_package($row['package_pk'], $bySlug[$slug]);
    $done++;
}
if ($apply) q("INSERT INTO activity_log(at, action, new_value, ip) VALUES (?, 'Packages re-synced from the website', ?, 'cli')", array(now(), $done . ' packages'));
echo ($apply ? 'Re-synced ' : 'Would re-sync ') . $done . " packages.\n";
if ($skipped) echo 'Skipped (unpublished CMS edits; publish or use --force): ' . implode(', ', $skipped) . "\n";
if ($retired) echo 'Archived (Package ID retired on the website): ' . implode(', ', $retired) . "\n";
if ($missing) echo 'Not on the website (left unchanged): ' . implode(', ', $missing) . "\n";
if (!$apply) echo "Dry run. Add --apply to write.\n";
