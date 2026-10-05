<?php
/**
 * Package content resolver — one place that decides which value a package page (or a CRM itinerary) shows.
 *
 *   Level 3  CRM CUSTOM        a client-specific itinerary/quotation built in the CMS/CRM (custom_itineraries)
 *   Level 2  PACKAGE OVERRIDE  the package's own data in packages.json (as published by the CMS)
 *   Level 1  GLOBAL STANDARD   include/data/standard-terms.php
 *   Fallback                   "confirmed with your quote"
 *
 * Priority: CRM CUSTOM > PACKAGE OVERRIDE > GLOBAL STANDARD > fallback. Only the winning value is returned, so a
 * page never shows a standard value next to the package's own (e.g. "Deluxe" replaces "Standard / 3-star").
 * Used by include/templates/package-detail.php (no custom layer on public pages) and by the CMS (cms/src) for
 * CRM custom itineraries. Pure functions only: no output, no database.
 */

if (!function_exists('hg_standard_terms')) {
    function hg_standard_terms()
    {
        static $t = null;
        if ($t === null) $t = (array) include __DIR__ . '/data/standard-terms.php';
        return $t;
    }

    /**
     * Hotels the package's own inclusions name: "2 Nights accommodation in Singapore at the V Lavender".
     * Returns [['hotel' => 'V Lavender', 'place' => 'Singapore', 'nights' => 2], ...]; [] when none are named.
     */
    function hg_package_named_hotels(array $p)
    {
        $out = array();
        foreach ($p['inclusions'] as $line) {
            if (preg_match('/^(\d+)\s+Nights?\s+accommodation\s+in\s+(.+?)\s+at\s+(?:the\s+)?(.+?)\.?$/iu', trim($line), $m)) {
                $out[] = array('hotel' => trim($m[3]), 'place' => trim($m[2]), 'nights' => (int) $m[1]);
            }
        }
        return $out;
    }

    /** True when a meal plan says no more than breakfast (so the standard breakfast line already describes it). */
    function hg_meals_breakfast_only($meals)
    {
        return (bool) preg_match('/^\s*(daily\s+)?(buffet\s+)?breakfast(\s*\(.*\))?\s*$/i', (string) $meals);
    }

    /**
     * Itinerary source state (internal metadata): 'crm' | 'package' | 'standard'.
     * 'standard' = the itinerary contains days completed from Holiday Guru Travel's standard circuits ("generated").
     */
    function hg_itinerary_source(array $p)
    {
        $s = isset($p['itinerary_source']) ? $p['itinerary_source'] : '';
        if (in_array($s, array('crm', 'package', 'standard'), true)) return $s;
        foreach ((array) (isset($p['itinerary']) ? $p['itinerary'] : array()) as $d) {
            if (!empty($d['generated'])) return 'standard';
        }
        return 'package';
    }

    /**
     * @param array $p      package as stored in packages.json
     * @param array $custom optional CRM custom layer: any of itinerary (list of {title,text}), inclusions,
     *                      exclusions, meals, hotel (category), hotel_names (list), transfers, start, end,
     *                      special_notes. Empty values are ignored, so a custom record only overrides what it sets.
     * @return array $p with every field resolved, plus:
     *   _src            field => 'crm' | 'package' | 'standard'  (internal; never printed to customers)
     *   source_type     'crm' | 'package' | 'standard' for the itinerary
     *   suggested_note  the disclaimer to show under the itinerary, or '' (only for the STANDARD source)
     *   accommodation   the one accommodation sentence that applies
     */
    function hg_package_resolve(array $p, array $custom = array())
    {
        $std = hg_standard_terms();
        $src = array();
        $pick = function ($field, $stdValue) use (&$p, $custom, &$src) {
            if (isset($custom[$field]) && $custom[$field] !== '' && $custom[$field] !== array()) { $p[$field] = $custom[$field]; $src[$field] = 'crm'; }
            elseif (!empty($p[$field])) $src[$field] = 'package';
            elseif ($stdValue !== null) { $p[$field] = $stdValue; $src[$field] = 'standard'; }
            else { $p[$field] = isset($p[$field]) ? $p[$field] : ''; $src[$field] = 'fallback'; }
        };
        foreach (array('itinerary', 'inclusions', 'exclusions', 'booking', 'terms', 'features', 'places') as $k) {
            if (!isset($p[$k]) || !is_array($p[$k])) $p[$k] = array();
        }

        // Itinerary: a CRM custom itinerary replaces the package's days as a whole (never mixed day by day).
        $p['source_type'] = !empty($custom['itinerary']) ? 'crm' : hg_itinerary_source($p);
        $pick('itinerary', null);
        $pick('start', null);
        $pick('end', null);
        $pick('special_notes', null);
        $pick('transfers', null);
        $pick('meals', $std['meals']);

        // Hotel: CRM hotel/property names, else a CRM category; else the package's category or named hotels;
        // else the standard 3-star-equivalent default. Never both a named hotel and a default category.
        if (!empty($custom['hotel_names'])) { $p['hotel_names'] = array_values((array) $custom['hotel_names']); $src['hotel'] = 'crm'; }
        if (empty($src['hotel'])) $pick('hotel', null);
        $namedInPackage = hg_package_named_hotels($p);
        if ($src['hotel'] === 'fallback' && $namedInPackage) $src['hotel'] = 'package';
        if ($src['hotel'] === 'fallback') { $p['hotel'] = $std['hotel_category']; $src['hotel'] = 'standard'; }
        if (empty($p['hotel_names'])) $p['hotel_names'] = array();

        if ($src['hotel'] === 'standard') $p['accommodation'] = $std['accommodation'];
        elseif ($p['hotel_names']) $p['accommodation'] = 'Stay at ' . implode('; ', $p['hotel_names']) . '.';
        elseif ($p['hotel'] !== '' && $p['hotel'] !== null) $p['accommodation'] = 'Stay in ' . $p['hotel'] . ' category accommodation as per the selected package plan.';
        else $p['accommodation'] = '';

        // Inclusions: the standard set, with the accommodation / meals / transport lines stated in this package's
        // own terms when it has them — so the list never contradicts Quick Facts.
        $stdIncl = $std['inclusions'];
        if ($src['hotel'] !== 'standard' && $p['hotel'] && !$p['hotel_names']) $stdIncl[0] = $p['hotel'] . ' category accommodation as per package plan. Room category subject to package/quotation.';
        if ($src['meals'] !== 'standard' && !hg_meals_breakfast_only($p['meals'])) $stdIncl[1] = 'Meals: ' . $p['meals'] . ' as per package plan. Other meals only where specifically included.';
        if ($p['transfers']) $stdIncl[2] = 'Transfers and sightseeing by ' . mb_strtolower($p['transfers']) . ' as specified in the itinerary.';
        $pick('inclusions', $stdIncl);
        $pick('exclusions', $std['exclusions']);

        $p['_src'] = $src;
        $p['suggested_note'] = $p['source_type'] === 'standard' ? $std['suggested_note'] : '';
        return $p;
    }
}
