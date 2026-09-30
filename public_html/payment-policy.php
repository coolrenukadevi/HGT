<?php
// Payment policy. Terms as printed on the package pages, extended with the owner's company-page pack
// (2026-09-30): when to pay, ways to pay, the company account and what happens after payment.
// Bank details: HG_BANK_* in include/site_config.php (shown only once filled in).
require __DIR__ . '/include/templates/policy.php';
$bankReady = HG_BANK_NAME !== '' && HG_BANK_ACCOUNT !== '' && HG_BANK_IFSC !== '';
ob_start(); ?>
<h2 class="hg-h3">When to pay</h2>
<div class="hg-tablewrap" tabindex="0" role="region" aria-label="When to pay"><table class="hg-table">
    <thead><tr><th scope="col">Booking type</th><th scope="col">At booking</th><th scope="col">Balance</th></tr></thead>
    <tbody>
        <tr><td>Holiday packages</td><td>35% advance to confirm</td><td>Before departure, on the date shown in your quote</td></tr>
        <tr><td>Flight tickets</td><td>100% of the fare</td><td>None</td></tr>
        <tr><td>Train tickets</td><td>100% of the fare</td><td>None</td></tr>
        <tr><td>Hotels, visas and other services</td><td>As stated in your quote</td><td>As stated in your quote</td></tr>
    </tbody>
</table></div>
<p>Once we receive the payment, we issue a booking voucher. Some hotels and airlines ask for full payment earlier, especially in peak season; if so, your quote will say so. Some packages note that prices are dynamic and subject to change until the booking is put on hold or vouchered.</p>

<h2 class="hg-h3">Taxes and extra travellers</h2>
<ul class="hg-checks hg-checks--info">
    <li>Many packages list GST at 5% as extra, on a GST bill; each package page shows whether GST is included or extra.</li>
    <li>Many packages charge an extra adult at 35% and an extra child at 25% of the package cost, with children below 5 complimentary; some packages use different rates, shown in their terms.</li>
</ul>

<h2 class="hg-h3">Ways to pay</h2>
<ul class="hg-checks hg-checks--info">
    <li><strong>UPI</strong> — Google Pay, PhonePe, Paytm or any UPI app, including scan-to-pay QR code.</li>
    <li><strong>Bank transfer</strong> — NEFT, IMPS or RTGS to our current account, or through net banking.</li>
    <li><strong>Cheque</strong> — payable to <?= hg_e(HG_LEGAL_NAME) ?>. The booking is confirmed once the cheque clears.</li>
</ul>
<p><strong>We do not accept cash</strong>, at the office or anywhere else.</p>

<h2 class="hg-h3">Our account details</h2>
<?php if ($bankReady) { ?>
<dl class="hg-qf">
    <div><dt>Account name</dt><dd><?= hg_e(HG_LEGAL_NAME) ?></dd></div>
    <div><dt>Bank</dt><dd><?= hg_e(HG_BANK_NAME) ?></dd></div>
    <div><dt>Account number</dt><dd><?= hg_e(HG_BANK_ACCOUNT) ?></dd></div>
    <div><dt>IFSC</dt><dd><?= hg_e(HG_BANK_IFSC) ?></dd></div>
    <div><dt>Account type</dt><dd>Current account</dd></div>
<?php if (HG_BANK_UPI !== '') { ?>
    <div><dt>UPI ID</dt><dd><?= hg_e(HG_BANK_UPI) ?></dd></div>
<?php } ?>
</dl>
<?php } else { ?>
<p>Your travel expert shares the company's bank and UPI details with your quote.</p>
<?php } ?>
<div class="hg-notice" role="note"><?= hg_icon('shield') ?><p><strong>Pay only to an account in the name of <?= hg_e(HG_LEGAL_NAME) ?>.</strong> We never ask you to pay into a personal account. If you receive different bank details by message or email, call <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a> to check before you pay.</p></div>

<h2 class="hg-h3">After you pay</h2>
<ol class="hg-steps hg-steps--list">
    <li><strong>Send your payment proof</strong><span>Share the UPI or bank reference on <a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp</a> or email <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a>, with your name and travel date.</span></li>
    <li><strong>We confirm receipt</strong><span>Accounts checks the payment and sends a receipt.</span></li>
    <li><strong>You get your voucher</strong><span>Your booking voucher is issued on WhatsApp or email once the payment is received.</span></li>
    <li><strong>Pay the balance</strong><span>We remind you before the balance due date. Final tickets and vouchers are released after full payment.</span></li>
</ol>

<h2 class="hg-h3">Online payment</h2>
<p>Online card payment and customer accounts on this website are not available yet. A travel expert shares your quote, payment request and voucher by WhatsApp or email. Keep your payment receipt until your booking voucher is issued.</p>
<p>Cancellations and refunds follow our <a href="/cancellation-policy">cancellation policy</a> and <a href="/refund-policy">refund policy</a>. See also our <a href="/disclaimer">disclaimer</a>.</p>
<?php
hg_render_policy(array(
    'path' => '/payment-policy', 'h1' => 'Payment policy',
    'title' => 'Payment Policy | Holiday Guru Travel',
    'description' => 'How to pay Holiday Guru Travel: 35% advance for packages, balance before departure, full payment for tickets. Bank transfer, UPI and cheque accepted. No cash.',
    'lead' => 'Pay only after you\'ve agreed your itinerary and quote. Bank transfer, UPI and cheque are accepted. We don\'t accept cash.',
), ob_get_clean());
