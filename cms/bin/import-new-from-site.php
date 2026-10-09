<?php
/**
 * Import the website packages the CMS does not have yet (include/data/packages.json), e.g. the packages added on the
 * website after the CMS database was first filled. Each one arrives as a published, site-imported package with its
 * itinerary, inclusions/exclusions, SEO and photo, and version 1 as its baseline, so a later CMS publish writes only
 * what an editor changes. Existing CMS packages, rates, offers, curation, users and settings are not changed.
 * Retired Package IDs are never imported. Same as "Import new packages" on the CMS dashboard.
 *
 *   php cms/bin/import-new-from-site.php            # dry run: lists what would be imported
 *   php cms/bin/import-new-from-site.php --apply
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/packages.php';
require __DIR__ . '/../src/site_export.php';

$apply = in_array('--apply', $argv, true);
cms_upgrade(cms_db());
try {
    $r = site_import_new($apply);
} catch (Throwable $x) {
    fwrite(STDERR, 'Not imported: ' . $x->getMessage() . "\n");
    exit(1);
}
if ($dup = site_renamed_on_site()) echo 'Warning: the website lists ' . count($dup) . " packages under old (renamed) URLs; restore its packages.json before --apply.\n";
echo ($apply ? 'Imported ' : 'Would import ') . $r['imported'] . ' website packages (' . $r['known'] . " already in the CMS).\n";
if ($r['slugs'] && !$apply) echo '  ' . implode("\n  ", array_slice($r['slugs'], 0, 20)) . (count($r['slugs']) > 20 ? "\n  … and " . (count($r['slugs']) - 20) . " more" : '') . "\n";
if ($r['id_taken']) echo 'Package ID already used by another CMS package (imported with the ID left proposed): ' . implode(', ', array_map(function ($s, $id) { return "$s ($id)"; }, array_keys($r['id_taken']), $r['id_taken'])) . "\n";
if ($r['retired']) echo 'Retired on the website (not imported): ' . implode(', ', $r['retired']) . "\n";
if (!$apply) echo "Dry run. Add --apply to write.\n";
