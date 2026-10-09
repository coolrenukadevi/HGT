<?php
// Enquiry form endpoint (contactForm1/2/3). Responds "1" on success, "0" on failure.
require __DIR__ . '/include/mail_helper.php';
require __DIR__ . '/include/cms_client.php';
require_once __DIR__ . '/include/site_config.php';
require_once __DIR__ . '/include/package_registry.php';

hgt_guard_request();

$name       = hgt_field('name', 100);
$email      = hgt_field('email', 150);
$phone      = preg_replace('/[^0-9+ ]/', '', hgt_field('phone', 20));
$travellers = hgt_field('travellers', 10);
$message    = hgt_text('message', 2000);
$page       = hgt_source_page();

if ($name === '' || $phone === '' || !PHPMailer::validateAddress($email)) {
    hgt_fail(422);
}

$rows = array(
    'Name'               => $name,
    'Email'              => $email,
    'Phone'              => $phone,
    'No. of travellers'  => $travellers,
);

// Optional trip details sent by the Phase 1 enquiry forms (all plain text, escaped when mailed).
$optional = array(
    'enquiry_type'   => 'Enquiry type',
    'package_id'     => 'Package ID',
    'package'        => 'Package name',
    'internal_ref'   => 'Internal ref',
    'offer_code'     => 'Offer code',
    'destination'    => 'Destination',
    'travel_date'    => 'Travel date',
    'travel_month'   => 'Travel month',
    'adults'         => 'Adults',
    'children'       => 'Children',
    'departure_city' => 'Departure city',
    'budget'         => 'Budget per person',
    'holiday_type'   => 'Holiday type',
    'service'        => 'Service',
    'hotel_category' => 'Hotel category',
    'duration'       => 'Trip length',
    'country'        => 'Country of residence',
    'package_url'    => 'Package URL',
    'displayed_rate' => 'Displayed rate',
    'rate_version'   => 'Rate version',
    'rate_validity'  => 'Rate validity',
    'utm_source'     => 'UTM source',
    'utm_medium'     => 'UTM medium',
    'utm_campaign'   => 'UTM campaign',
);
foreach ($optional as $key => $label) {
    $value = hgt_field($key, 150);
    if ($value !== '') {
        $rows[$label] = $value;
    }
}
// Package enquiries: take the package name, Package ID, internal ref and the rate shown from our
// own data (by URL path), never from the submitted text, so they cannot be spoofed or mistyped.
// Identifier and rate fields are only ever set by the server (below); drop anything submitted.
$hgOfferCode = isset($rows['Offer code']) ? $rows['Offer code'] : '';
foreach (array('Package ID', 'Internal ref', 'Offer code', 'Displayed rate', 'Rate version', 'Rate validity') as $k) unset($rows[$k]);
if (isset($rows['Package URL'])) {
    $path = parse_url($rows['Package URL'], PHP_URL_PATH);
    $slug = is_string($path) ? trim($path, '/') : '';
    $known = null;
    $data = __DIR__ . '/include/data/packages.json';
    if ($slug !== '' && is_file($data)) {
        foreach ((array) json_decode((string) file_get_contents($data), true) as $pkg) {
            if (isset($pkg['slug']) && $pkg['slug'] === $slug) {
                $known = $pkg;
                break;
            }
        }
    }
    unset($rows['Package name']);
    if ($known) {
        $ctx = hg_package_enquiry_context($known['slug'], $hgOfferCode);
        $rows['Package ID'] = $ctx['Package ID'] !== '' ? $ctx['Package ID'] : 'Not assigned yet';
        $rows['Package name'] = $known['title'];
        unset($ctx['Package ID']);
        $rows = array_merge($rows, array_filter($ctx, 'strlen'));
        $rows['Package URL'] = rtrim(HG_SITE_URL, '/') . '/' . $known['slug'];
    } else {
        unset($rows['Package URL']);
    }
}
$rows['Message'] = $message;
$rows['Page'] = $page;

// Record it in the CMS (CRM) first, so the email and the visitor's confirmation carry the enquiry number. The
// package, travel date and party size go to their own fields; any other details are kept in the message.
$extra = array();
foreach (array('No. of travellers', 'Travel month', 'Budget per person', 'Holiday type', 'Service', 'Hotel category', 'Trip length', 'Country of residence', 'Displayed rate', 'Page') as $k) {
    if (isset($rows[$k]) && $rows[$k] !== '') $extra[] = $k . ': ' . $rows[$k];
}
$enquiryNo = hgt_cms_forward(array(
    'name' => $name, 'email' => $email, 'phone' => $phone,
    'package_slug' => isset($known) && $known ? $known['slug'] : '',
    'offer_code' => $hgOfferCode,
    'enquiry_type' => hgt_field('enquiry_type', 150),
    'destination' => hgt_field('destination', 150),
    'travel_date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', hgt_field('travel_date', 20)) ? hgt_field('travel_date', 20) : '',
    'adults' => hgt_field('adults', 5) !== '' ? (int) hgt_field('adults', 5) : null,
    'children' => hgt_field('children', 5) !== '' ? (int) hgt_field('children', 5) : null,
    'departure_city' => hgt_field('departure_city', 80),
    'message' => trim($message . ($extra ? "\n\n" . implode("\n", $extra) : '')),
    'utm_source' => hgt_field('utm_source', 100), 'utm_medium' => hgt_field('utm_medium', 100), 'utm_campaign' => hgt_field('utm_campaign', 100),
));
if ($enquiryNo !== '') $rows = array('Enquiry no.' => $enquiryNo) + $rows;

$subject = 'Website enquiry' . ($enquiryNo !== '' ? ' ' . $enquiryNo : '') . ': ' . $name;
if (isset($rows['Package name'])) {
    $subject .= ' - ' . (isset($rows['Package ID']) && preg_match('/^\d{4}$/', $rows['Package ID']) ? 'Package ID ' . $rows['Package ID'] . ' ' : '') . $rows['Package name'];
} elseif (isset($rows['Destination'])) {
    $subject .= ' - ' . $rows['Destination'];
}

$sent = hgt_send_mail($subject, $rows, $email, $name);

// The enquiry is safe if either the email went out or the CMS saved it. "1|HGT-E-00012" shows the visitor the number.
echo ($sent || $enquiryNo !== '') ? ($enquiryNo !== '' ? '1|' . $enquiryNo : '1') : '0';
