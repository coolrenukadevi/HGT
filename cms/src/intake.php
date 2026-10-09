<?php
/**
 * Website enquiries: save one (cms_intake_enquiry) and look one up for its customer (cms_enquiry_status).
 * Used in-process by the website (src/website.php, no network needed) and by the HTTP API (controllers/api.php).
 * Needs bootstrap.php, packages.php and controllers/enquiries.php.
 */

/**
 * Save a website enquiry. The website sends the package slug (and optional offer code); the CMS derives Package ID,
 * rate, price version and validity itself — client-supplied values are ignored.
 * Returns ['id', 'enquiry_no', 'package_id', 'price_version', 'offer_code'] or ['error' => message].
 */
function cms_intake_enquiry(array $in)
{
    $name = trim((string) ($in['name'] ?? ''));
    $email = trim((string) ($in['email'] ?? ''));
    $phone = trim((string) ($in['phone'] ?? ''));
    if ($name === '' || ($email === '' && $phone === '')) return array('error' => 'name and phone or email are required');
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) return array('error' => 'invalid email');
    $pk = !empty($in['package_slug']) ? qv('SELECT package_pk FROM packages WHERE slug = ?', array((string) $in['package_slug'])) : null;
    $ctx = enquiry_package_context($pk, strtoupper((string) ($in['offer_code'] ?? '')));
    $addons = array();
    if ($ctx && !empty($in['addon_ids']) && is_array($in['addon_ids'])) {
        $ids = array_map('intval', $in['addon_ids']);
        foreach (q("SELECT addon_pk, name, price, price_unit FROM addons WHERE package_pk = ? AND status = 'active' AND availability <> 'unavailable'", array($ctx['package_pk']))->fetchAll() as $a) if (in_array((int) $a['addon_pk'], $ids, true)) $addons[] = $a;
    }
    $utm = array();
    foreach (array('utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content') as $k) if (!empty($in[$k]) && is_string($in[$k])) $utm[$k] = mb_substr($in[$k], 0, 100);
    // Website form extras: enquiry type, and the destination typed/chosen by the visitor (stored as the destination
    // key when it matches one of ours, otherwise as text).
    $destIn = trim((string) ($in['destination'] ?? '')); $destKey = '';
    foreach (hg_destinations() as $k => $d) if ($destIn !== '' && (strcasecmp($k, $destIn) === 0 || strcasecmp($d['name'], $destIn) === 0)) { $destKey = $k; break; }
    $extra = array();
    $type = trim((string) ($in['enquiry_type'] ?? ''));
    if ($type !== '') $extra['enquiry_type'] = mb_substr($type, 0, 150);
    if (empty($ctx) && $destIn !== '') $extra['destination'] = $destKey !== '' ? $destKey : mb_substr($destIn, 0, 80);
    // The visitor's package page is kept even when the package is not in the CMS yet, so staff can see it.
    if (empty($ctx) && !empty($in['package_slug'])) $extra['package_url'] = rtrim((string) cms_config('site_url'), '/') . '/' . preg_replace('/[^a-z0-9-]/', '', (string) $in['package_slug']);
    $id = enquiry_insert($ctx + $extra + array('source' => 'website', 'name' => mb_substr($name, 0, 120), 'email' => $email, 'phone' => mb_substr($phone, 0, 30),
        'travel_date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($in['travel_date'] ?? '')) ? $in['travel_date'] : '',
        'adults' => isset($in['adults']) && $in['adults'] !== null ? max(0, (int) $in['adults']) : null, 'children' => isset($in['children']) && $in['children'] !== null ? max(0, (int) $in['children']) : null,
        'departure_city' => mb_substr((string) ($in['departure_city'] ?? ''), 0, 80), 'message' => mb_substr((string) ($in['message'] ?? ''), 0, 4000),
        'addons' => je($addons), 'utm' => je($utm), 'is_test' => !empty($in['is_test']) ? 1 : 0, 'raw' => je($in)));
    cms_log('Website enquiry received', $ctx ? $ctx['package_pk'] : null, 'enquiry', '', enquiry_no($id));
    return array('id' => $id, 'enquiry_no' => enquiry_no($id), 'package_id' => $ctx ? $ctx['package_id'] : null, 'price_version' => $ctx ? $ctx['rate_version'] : null, 'offer_code' => $ctx ? $ctx['offer_code'] : null);
}

/** Customer wording for the CRM stages (Track your enquiry). */
const HG_STAGE_PUBLIC = array(
    'new'       => array('Received', 'We have your enquiry. A travel expert will call or WhatsApp you shortly.'),
    'contacted' => array('In progress', 'Our travel expert has contacted you and is working on your trip.'),
    'qualified' => array('In progress', 'We are planning your itinerary and checking hotels and prices.'),
    'quoted'    => array('Quotation sent', 'Your quotation has been sent. Please check your email or WhatsApp, or contact us with any changes.'),
    'won'       => array('Confirmed', 'Your trip is confirmed. Our team will share your booking details and vouchers.'),
    'lost'      => array('Closed', 'This enquiry is closed. Contact us any time to plan a new trip.'),
);

/**
 * Status of one enquiry for its customer: needs the enquiry number AND the email or phone used on it. A wrong number
 * or contact both give null, so a lookup never confirms that an enquiry number exists. Test enquiries are never shown.
 */
function cms_enquiry_status(array $in)
{
    $pk = enquiry_pk_from_no($in['enquiry_no'] ?? '');
    $contact = trim((string) ($in['contact'] ?? ''));
    $e = ($pk && $contact !== '') ? q1('SELECT * FROM enquiries WHERE enquiry_pk = ? AND is_test = 0', array($pk)) : null;
    $digits = function ($s) { return substr(preg_replace('/\D/', '', (string) $s), -10); };
    $match = $e && ((strpos($contact, '@') !== false && $e['email'] !== '' && strcasecmp($e['email'], $contact) === 0)
        || (strlen($digits($contact)) === 10 && $digits($e['phone']) === $digits($contact)));
    if (!$match) return null;
    $st = HG_STAGE_PUBLIC[$e['stage']] ?? HG_STAGE_PUBLIC['new'];
    $quote = q1('SELECT status, updated_at FROM custom_itineraries WHERE enquiry_pk = ?', array($pk));
    return array(
        'enquiry_no' => enquiry_no($pk), 'received' => substr($e['created_at'], 0, 10), 'status' => $st[0], 'status_text' => $st[1],
        'package_name' => (string) $e['package_name'], 'package_id' => $e['package_id'], 'travel_date' => (string) $e['travel_date'],
        'quotation_sent' => $quote && in_array($quote['status'], array('sent', 'confirmed'), true) ? substr($quote['updated_at'], 0, 10) : null,
    );
}
