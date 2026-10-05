<?php
/**
 * CRM CUSTOM itineraries + quotations (content level 3).
 *
 * One custom itinerary per enquiry (table custom_itineraries). It starts as a copy of the package as the website
 * shows it — package data resolved over the Holiday Guru Travel global standard by
 * public_html/include/package_resolve.php — and staff then change anything for this client: days, titles,
 * descriptions, overnight places, sightseeing, start/end, meals, transport, hotels, inclusions, exclusions, notes.
 * Whatever the quote leaves empty falls back to the package, then to the global standard. Never published.
 *
 * The cost is shown as ONE total (owner rule 2026-10-04: no per-person, hotel or transport break-up).
 */

require_once __DIR__ . '/packages.php';

const HG_QUOTE_STATUSES = array('draft' => 'Draft', 'sent' => 'Sent to client', 'confirmed' => 'Confirmed');

/** The website's resolver (shared, so the CRM and the package page can never disagree on a default). */
function quote_resolver()
{
    require_once site_path('include/package_resolve.php');
}

/** Website package (packages.json) for a CMS package row, or null. */
function quote_site_package($slug)
{
    static $all = null;
    if ($all === null) {
        $all = json_decode((string) @file_get_contents(site_path('include/data/packages.json')), true) ?: array();
    }
    foreach ($all as $p) if (isset($p['slug']) && $p['slug'] === $slug) return $p;
    return null;
}

/** "Day 3 : (Pushkar)Ringas to Pushkar" → ['overnight' => 'Pushkar', 'title' => 'Ringas to Pushkar']. */
function quote_split_day_title($t)
{
    $t = trim(preg_replace('/^(?:DAY\s+)?Day\s*\d+\s*[:|\-–]?\s*/iu', '', (string) $t));
    $over = '';
    if (preg_match('/^\(([^)]*)\)\s*(.*)$/u', $t, $m)) { $over = trim($m[1]); $t = trim($m[2]); }
    return array('overnight' => $over, 'title' => $t);
}

/** Lines of a textarea → list (blank lines and bullet characters dropped). */
function quote_lines($s)
{
    $out = array();
    foreach (preg_split('/\R/u', (string) $s) as $l) {
        $l = trim(preg_replace('/^[\s•▪✓\-\*·]+/u', '', $l));
        if ($l !== '') $out[] = $l;
    }
    return $out;
}

/** Company terms — the same on every quotation (owner, 2026-10-04: as in the reference quotation). Edit here only. */
function quote_terms()
{
    $t = array(
        'Important notes' => array(
            'Any change to the tour made by the guest during travel is chargeable.',
            'Any stay outside the itinerary requested by the guest is extra. Postponing or preponing services is not possible once the tour has started.',
            'If a vehicle breaks down during the tour, guests may wait for the repair or travel onward by another option at their own cost; we will try to arrange an alternative subject to availability.',
            'Char Dham tours only: the local union does not allow outside-state cabs to pick up from Haridwar, Rishikesh or Dehradun. A vehicle taken against this may be seized; another vehicle is then at the guest’s own cost.',
            'M/S Holiday Guru Travel is not responsible for a missed flight or train caused by a vehicle technical issue, bad weather or traffic.',
            'The itinerary is followed as given in the tour plan. Any other sightseeing is chargeable and must be arranged with us; the driver cannot add it directly.',
            'No refund for missed services: sightseeing, activities or services missed, skipped or unused because of time constraints, delayed arrival or departure, traffic, weather, operational reasons, local restrictions or any reason beyond the service provider’s control are not refunded, adjusted or compensated.',
        ),
        'Read carefully before booking' => array(
            'Air-conditioning in vehicles is switched off in hilly areas.',
            'If bad weather, landslides or similar circumstances force a change, the itinerary is rescheduled; extra food, accommodation or vehicle days are paid by the guest.',
            'Electricity, water or other faults inside a hotel are resolved by the hotel management.',
            'Expenses outside the planned itinerary (medical needs, extra meals during the journey, hotels other than those booked) are paid by the guest.',
            'All extras (food and beverage and any other services) taken by the guest are paid directly.',
            'In odd-number groups, the extra person shares a room with an extra bed or mattress.',
            'Rates are applicable to Indian nationals only.',
            'Meals or services not used, or partly used, are non-refundable.',
        ),
        'Travel checklist' => array(
            'Carry a photo ID and proof of address.',
            'Carry comfortable walking shoes (canvas or sports shoes).',
            'Carry dry snacks for long drives if you wish.',
            'Travel steadily and give the driver enough time to rest.',
            'Carry an umbrella or raincoat and a torch.',
            'Carry enough cash for personal expenses and your own personal medical kit.',
            'Please do not throw wrappers or plastic bags on the road or in the forest.',
        ),
        'Airline policy' => array(
            'Once a flight ticket is booked by M/S Holiday Guru Travel, any change is charged as per the airline’s rules and paid by the customer.',
            'If the airline cancels a flight, any refund is only as actually refunded by the airline; M/S Holiday Guru Travel is not responsible for any loss.',
            'A no-show at airport check-in is non-refundable.',
        ),
        'Hotel terms' => array(
            'Hotel bookings are non-refundable once confirmed.',
            'Standard check-in is 2 pm and check-out 11 am unless stated otherwise; early check-in or late check-out is subject to hotel availability.',
            'A guaranteed early check-in needs the room reserved from the previous night, charged extra.',
            'Breakfast is served as per the hotel’s menu (international with some Indian dishes), usually 7 am–10 am.',
            'The final day-wise itinerary can be modified only by M/S Holiday Guru Travel, to honour all cost inclusions and commitments in the best possible way.',
            'International packages: carry at least USD 500 (or equivalent) per person for personal use; immigration may ask to see it.',
            'International prices are based on the current exchange rate and may be revised with currency fluctuations.',
        ),
        'Tour terms & conditions' => array(
            'Extra adult 35% and extra child 25% of the package cost (children below 5 complimentary on domestic tours).',
            'If a listed hotel is unavailable, accommodation is arranged in a hotel of similar standard.',
            'Transport is provided as per the itinerary and is not at disposal; sightseeing depends on the time available.',
            'Cancellation charges are calculated on the gross tour cost and depend on the date of departure and the date of cancellation.',
            'Cancellation charges for any transport ticket follow the rules of the concerned airline, railway or operator.',
            'Any refund due is paid after M/S Holiday Guru Travel receives the refund from the service providers; processing charges are deducted.',
            'The cost may change if the number of travellers changes.',
        ),
        'Child policy' => array(
            'With two children, one is with bed and one without bed (sharing with parents), whatever their ages.',
            'Children below 5 travel free on domestic tour packages; children above 2 are chargeable on international packages.',
            'Children aged 2–7 are without bed; children above 7 are with bed.',
        ),
        'How we work' => array(
            'Holiday packages: confirmation vouchers are issued within 72 working hours of the advance payment, subject to supplier and hotel confirmations. The balance is payable as per the payment policy.',
            'Cab and driver details are shared one day before departure.',
            'Air tickets are emailed as soon as payment is received.',
            'If any dues are outstanding, services may be stopped.',
        ),
        'Booking process' => array(
            'Pay 35% of the total package cost as an advance to confirm; the balance is due before departure.',
            'Air and train tickets need full payment.',
            'Mode of payment: net banking, IMPS, NEFT, cheque, UPI (Google Pay, PhonePe, Paytm) or QR code. Cash is not accepted.',
            'Check-in and check-out as per hotel policy.',
            'A booking voucher is issued once payment is received.',
        ),
        'Cancellation policy' => array(
            '30 days or more before departure: non-refundable deposit (25% of the total package).',
            '29–20 days before departure: non-refundable deposit + 25% of the holiday cost.',
            '19–14 days before departure: non-refundable deposit + 50% of the holiday cost.',
            '13–8 days before departure: non-refundable deposit + 75% of the holiday cost.',
            '7 days or less before departure: 100% of the holiday cost.',
            'If the tour is cancelled after tickets are issued, airfare (base fare) is 100% cancelled and only taxes are refunded; land arrangements are 75% cancelled. Confirmed cruises are non-refundable.',
        ),
    );
    return $t;
}

/** A new quote for an enquiry: the package as the website shows it (CRM layer still empty = nothing overridden). */
function quote_defaults(array $e, $pkg)
{
    quote_resolver();
    $site = $pkg ? quote_site_package($pkg['slug']) : null;
    $r = $site ? hg_package_resolve($site) : null;
    $days = array();
    $stays = array();
    if ($r) {
        foreach ($r['itinerary'] as $i => $d) {
            $h = quote_split_day_title(isset($d['title']) ? $d['title'] : '');
            $days[] = array('title' => $h['title'], 'overnight' => $i < (int) $r['nights'] ? $h['overnight'] : '', 'text' => trim(isset($d['text']) ? $d['text'] : ''), 'sightseeing' => '');
            if ($i < (int) $r['nights'] && $h['overnight'] !== '') {
                $n = count($stays) - 1;
                if ($n >= 0 && $stays[$n]['place'] === $h['overnight']) $stays[$n]['nights']++;
                else $stays[] = array('place' => $h['overnight'], 'nights' => 1, 'hotel' => '');
            }
        }
        $named = hg_package_named_hotels($r);
        foreach ($stays as &$s) {
            foreach ($named as $nh) if (strcasecmp($nh['place'], $s['place']) === 0) $s['hotel'] = $nh['hotel'];
            if ($s['hotel'] === '') $s['hotel'] = $r['_src']['hotel'] === 'standard' ? 'Standard / 3-star equivalent hotel' : $r['hotel'] . ' category hotel';
        }
        unset($s);
    }
    $from = $e['travel_date'] ?: '';
    $nDays = $r ? (int) $r['days'] : 0;
    $trav = array();
    if ($e['adults'] !== null && $e['adults'] !== '') $trav[] = (int) $e['adults'] . ' adult' . ((int) $e['adults'] === 1 ? '' : 's');
    if ($e['children']) $trav[] = (int) $e['children'] . ' child' . ((int) $e['children'] === 1 ? '' : 'ren');
    return array(
        'title' => $r ? ($r['title'] ?: $r['name']) : ($e['package_name'] ?: ''),
        'salutation' => 'Dear ' . ($e['name'] ?: 'Guest') . ',',
        'destination' => $e['destination'] ?: ($r ? implode(', ', $r['places']) : ''),
        'duration' => $r ? $r['duration'] : '',
        'travel_from' => $from,
        'travel_to' => ($from && $nDays) ? date('Y-m-d', strtotime($from . ' +' . ($nDays - 1) . ' days')) : '',
        'travellers' => implode(', ', $trav),
        // Overrides: empty = package value, then global standard (shown as placeholders in the editor).
        'meal_plan' => '', 'hotel_category' => '', 'transport' => '', 'start' => '', 'end' => '',
        'hotels' => $stays,
        'days' => $days,
        'inclusions' => array(), 'exclusions' => array(),
        'total_amount' => '', 'total_label' => 'Total package cost', 'tax_note' => '',
        'valid_until' => date('Y-m-d', strtotime(today() . ' +7 days')),
        'special_notes' => '',
    );
}

function quote_row($enquiryPk)
{
    return q1('SELECT * FROM custom_itineraries WHERE enquiry_pk = ?', array((int) $enquiryPk));
}

/**
 * Everything a quotation shows, resolved CRM CUSTOM > PACKAGE > GLOBAL STANDARD.
 * Returns ['q' => payload, 'r' => resolved package view, 'site' => website package|null].
 */
function quote_resolved(array $e, $pkg, array $q)
{
    quote_resolver();
    $site = $pkg ? quote_site_package($pkg['slug']) : null;
    $days = array();
    foreach ($q['days'] as $i => $d) $days[] = array('title' => 'Day ' . ($i + 1) . ' : ' . ($d['overnight'] !== '' ? '(' . $d['overnight'] . ')' : '') . $d['title'], 'text' => $d['text']);
    $custom = array(
        'itinerary' => $days, 'inclusions' => $q['inclusions'], 'exclusions' => $q['exclusions'],
        'meals' => $q['meal_plan'], 'hotel' => $q['hotel_category'], 'transfers' => $q['transport'],
        'start' => $q['start'], 'end' => $q['end'], 'special_notes' => $q['special_notes'],
    );
    $base = $site ?: array('name' => $q['title'], 'title' => $q['title'], 'itinerary' => array(), 'inclusions' => array(), 'exclusions' => array(), 'meals' => '', 'hotel' => '', 'transfers' => '', 'nights' => 0, 'days' => 0, 'places' => array());
    $r = hg_package_resolve($base, $custom);
    return array('q' => $q, 'r' => $r, 'site' => $site);
}

/** Quote reference shown on the document: HGT-Q-<enquiry>-v<version>. */
function quote_ref(array $row) { return 'HGT-Q-' . str_pad((string) $row['enquiry_pk'], 4, '0', STR_PAD_LEFT) . '-v' . (int) $row['version']; }

/** Read the editor form into a payload. */
function quote_from_post()
{
    $days = array();
    $titles = (array) (isset($_POST['day_title']) ? $_POST['day_title'] : array());
    foreach ($titles as $i => $t) {
        $d = array(
            'title' => trim((string) $t),
            'overnight' => trim((string) (isset($_POST['day_overnight'][$i]) ? $_POST['day_overnight'][$i] : '')),
            'text' => trim((string) (isset($_POST['day_text'][$i]) ? $_POST['day_text'][$i] : '')),
            'sightseeing' => trim((string) (isset($_POST['day_sights'][$i]) ? $_POST['day_sights'][$i] : '')),
        );
        if (!empty($_POST['day_remove'][$i]) || ($d['title'] === '' && $d['text'] === '')) continue;
        $days[] = $d;
    }
    $hotels = array();
    foreach ((array) (isset($_POST['hotel_place']) ? $_POST['hotel_place'] : array()) as $i => $pl) {
        $h = array('place' => trim((string) $pl), 'nights' => (int) (isset($_POST['hotel_nights'][$i]) ? $_POST['hotel_nights'][$i] : 0), 'hotel' => trim((string) (isset($_POST['hotel_name'][$i]) ? $_POST['hotel_name'][$i] : '')));
        if ($h['place'] !== '' || $h['hotel'] !== '') $hotels[] = $h;
    }
    $date = function ($k) { $v = post($k); return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : ''; };
    return array(
        'title' => post('title'), 'salutation' => post('salutation'), 'destination' => post('destination'), 'duration' => post('duration'),
        'travel_from' => $date('travel_from'), 'travel_to' => $date('travel_to'), 'travellers' => post('travellers'),
        'meal_plan' => post('meal_plan'), 'hotel_category' => post('hotel_category'), 'transport' => post('transport'),
        'start' => post('start'), 'end' => post('end'),
        'hotels' => $hotels, 'days' => $days,
        'inclusions' => quote_lines(post('inclusions')), 'exclusions' => quote_lines(post('exclusions')),
        'total_amount' => trim(preg_replace('/[^\d,\.]/', '', post('total_amount'))), 'total_label' => post('total_label') ?: 'Total package cost', 'tax_note' => post('tax_note'),
        'valid_until' => $date('valid_until'), 'special_notes' => post('special_notes'),
    );
}
