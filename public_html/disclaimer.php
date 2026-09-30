<?php
// Disclaimer. Text from the owner's company-page pack (2026-09-30); company details from site configuration.
// The pack asks for a legal review (liability limit, jurisdiction) — keep 'draft' in page-status.php until then.
// Complaints point to the existing Grievance Redress page instead of repeating (unconfirmed) timelines.
require __DIR__ . '/include/templates/policy.php';
$address = HG_ADDRESS_LINE1 . ', ' . HG_ADDRESS_LINE2;
ob_start(); ?>
<nav class="hg-toc" aria-label="On this page"><p>On this page</p><ol>
    <li><a href="#d1">Who we are</a></li><li><a href="#d2">Our role as a travel agent</a></li><li><a href="#d3">Prices and availability</a></li>
    <li><a href="#d4">Visas and travel documents</a></li><li><a href="#d5">Website content and images</a></li><li><a href="#d6">Third-party links</a></li>
    <li><a href="#d7">Events outside our control</a></li><li><a href="#d8">Limitation of liability</a></li><li><a href="#d9">Health and safety</a></li>
    <li><a href="#d10">Complaints and contact</a></li><li><a href="#d11">Governing law</a></li></ol></nav>

<h2 class="hg-h3" id="d1">1. Who we are</h2>
<p>This website, holidaygurutravel.in, is operated by <?= hg_e(HG_LEGAL_NAME) ?>, which runs the brand Holiday Guru Travel from <?= hg_e($address) ?>. “We”, “us” and “our” refer to <?= hg_e(HG_LEGAL_NAME) ?>. “You” refers to anyone using the website or booking with us.</p>

<h2 class="hg-h3" id="d2">2. Our role as a travel agent</h2>
<p>We plan itineraries and arrange travel services on your behalf. Flights, trains, hotels, cruises, transport, sightseeing, insurance and similar services are provided by independent third parties such as airlines, hotels, transport operators and insurers.</p>
<p>Each supplier's own terms and conditions apply to its service, including its rules on changes, cancellations, baggage, check-in and refunds. We are not responsible for a supplier's acts, omissions, delays, overbooking or service standards, though we will do our best to help you resolve any problem.</p>

<h2 class="hg-h3" id="d3">3. Prices and availability</h2>
<ul>
    <li>Prices shown on the website are indicative and are confirmed only in your written quote.</li>
    <li>Prices and availability can change until your booking is confirmed and paid for, because of supplier rates, currency movement, taxes or government levies.</li>
    <li>A booking is confirmed only when we receive the required payment and issue a booking voucher. See our <a href="/payment-policy">payment policy</a>.</li>
    <li>Hotel star ratings, room categories and facilities are as described by the hotel and may differ between countries.</li>
</ul>

<h2 class="hg-h3" id="d4">4. Visas and travel documents</h2>
<p>You are responsible for holding a valid passport, visa, permits and any health documents your trip requires. We can help you prepare a visa application, but the decision to grant or refuse a visa, and how long it takes, rests entirely with the embassy, consulate or immigration authority. Visa fees and our service charges are generally not refundable if a visa is refused.</p>
<p>Entry rules change. Please check current requirements with the relevant authority before you travel.</p>

<h2 class="hg-h3" id="d5">5. Website content and images</h2>
<p>We try to keep the information on this website accurate and current, but we do not warrant that it is complete or error-free. Itineraries, timings and descriptions are for guidance. Photographs are illustrative and may not show the exact hotel, room, vehicle or view you will receive.</p>
<p>The content, design and logo on this website belong to <?= hg_e(HG_LEGAL_NAME) ?> or are used with permission. Do not copy or reuse them without written consent.</p>

<h2 class="hg-h3" id="d6">6. Third-party links</h2>
<p>This website may link to other websites, such as airlines, hotels, payment providers or government portals. We do not control those websites and are not responsible for their content, privacy practices or availability.</p>

<h2 class="hg-h3" id="d7">7. Events outside our control</h2>
<p>We are not liable for changes, delays or cancellations caused by events outside our reasonable control, including natural disasters, bad weather, pandemics, strikes, political unrest, government orders, road or airport closures, and technical failures of suppliers. Any extra cost that arises from such events is borne by the traveller, and refunds depend on what suppliers return to us.</p>

<h2 class="hg-h3" id="d8">8. Limitation of liability</h2>
<p>To the extent permitted by law, our total liability for any booking is limited to the amount of service charge we received for that booking. We are not liable for indirect or consequential loss, including loss of enjoyment, missed connections booked separately, or loss of personal belongings.</p>

<h2 class="hg-h3" id="d9">9. Health and safety</h2>
<p>Some activities, such as trekking, water sports, snow activities and high-altitude travel, carry risk. Please consult a doctor about fitness and vaccinations, follow local guides' instructions, and buy travel insurance that covers your planned activities.</p>

<h2 class="hg-h3" id="d10">10. Complaints and contact</h2>
<p>If you have a complaint, please follow our <a href="/grievance-redress">grievance redress</a> process, or email <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a> with your booking reference and the details. Phone and WhatsApp: <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a>.</p>

<h2 class="hg-h3" id="d11">11. Governing law</h2>
<p>This disclaimer is governed by the laws of India. Any dispute is subject to the exclusive jurisdiction of the courts at Gautam Buddh Nagar (Noida), Uttar Pradesh.</p>
<p>We may update this disclaimer from time to time. Last updated: September 2026.</p>
<?php
hg_render_policy(array(
    'path' => '/disclaimer', 'h1' => 'Disclaimer',
    'title' => 'Disclaimer | Holiday Guru Travel',
    'description' => 'Disclaimer for holidaygurutravel.in: our role as a travel agent, third-party services, prices, visas, website content and limitation of liability.',
    'lead' => 'Please read this before you book. It explains what we are responsible for, and what depends on airlines, hotels, embassies and other suppliers.',
), ob_get_clean());
