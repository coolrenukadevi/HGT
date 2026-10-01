<?php
// Payment page (/payment): Pay Now link, UPI QR code and bank transfer details, from the owner's page pack
// (2026-10-01), built with the site's own components. Settings: "Payment settings" in include/site_config.php.
// Account holder and UPI payee are always HG_LEGAL_NAME. The page is indexed only once a payment link or the
// bank details are filled in; until then it is noindex (and not in sitemap.xml).
require __DIR__ . '/include/ui/core.php';

$payLink = HG_PAY_LINK;
$upiId = HG_BANK_UPI;
$qrFile = HG_UPI_QR_IMAGE !== '' && is_file(__DIR__ . HG_UPI_QR_IMAGE);
$bankReady = HG_BANK_ACCOUNT !== '' && HG_BANK_IFSC !== '';
$upiLink = $upiId !== '' ? 'upi://pay?pa=' . rawurlencode($upiId) . '&pn=' . rawurlencode(HG_LEGAL_NAME) . '&cu=INR' : '';
$bankLine = trim(HG_BANK_NAME . (HG_BANK_BRANCH !== '' ? ', ' . HG_BANK_BRANCH : ''), ', ');
$bankRows = array(
    array('Account name', HG_LEGAL_NAME, false),
    array('Bank', $bankLine, false),
    array('Account number', HG_BANK_ACCOUNT, true),
    array('IFSC', HG_BANK_IFSC, true),
    array('Account type', HG_BANK_ACC_TYPE, false),
);
if (HG_BANK_SWIFT !== '') $bankRows[] = array('SWIFT code', HG_BANK_SWIFT, true);
if ($upiId !== '') $bankRows[] = array('UPI ID', $upiId, true);
$copyAll = "Account name: " . HG_LEGAL_NAME . ($bankLine !== '' ? "\nBank: " . $bankLine : '') . "\nAccount number: " . HG_BANK_ACCOUNT . "\nIFSC: " . HG_BANK_IFSC;

hg_layout_start(array(
    'title' => 'Payment: Pay Online, UPI or Bank Transfer | Holiday Guru Travel',
    'description' => 'Pay Holiday Guru Travel by UPI or bank transfer: 35% advance for packages, balance before departure, full fare for tickets. Cash is not accepted.',
    'path' => '/payment',
    'index' => hg_page_status('/payment') === 'approved' && ($payLink !== '' || $bankReady),
    'breadcrumbs' => array(array('Home', '/'), array('Payment', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container hg-narrow">
        <p class="hg-eyebrow">Payments</p>
        <h1 class="hg-h1" id="page-title">Pay for your booking</h1>
        <p class="hg-lead">Pay by UPI or bank transfer<?= $payLink !== '' ? ', or online with Pay Now' : '' ?>. Pay only after your travel expert has confirmed the itinerary and amount.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" id="pay" aria-label="Ways to pay">
    <div class="hg-container hg-narrow">
        <div class="hg-paynow">
            <div>
                <h2 class="hg-h3">Pay online</h2>
<?php if ($payLink !== '') { ?>
                <p>Pay on our secure payment page. Enter the amount your travel expert confirmed, with your name and travel date.</p>
<?php } else { ?>
                <p>Online payment is being set up. Until then, pay by UPI or bank transfer below, or <a href="<?= hg_e(hg_whatsapp_href("Hi Holiday Guru Travel,\nPlease send me a payment link for my booking.")) ?>" target="_blank" rel="noopener">ask for a payment link on WhatsApp</a>.</p>
<?php } ?>
            </div>
            <div class="hg-paynow__side">
<?php if ($payLink !== '') { ?>
                <a class="hg-btn hg-btn--primary hg-paynow__btn" href="<?= hg_e($payLink) ?>" target="_blank" rel="noopener"><?= hg_icon('lock') ?>Pay Now</a>
                <small><?= HG_PAY_PROVIDER !== '' ? 'Secure payment by ' . hg_e(HG_PAY_PROVIDER) . '. ' : '' ?>Opens in a new tab.</small>
<?php } else { ?>
                <span class="hg-btn hg-paynow__btn is-disabled" aria-disabled="true"><?= hg_icon('lock') ?>Pay Now</span>
                <small>Not available yet</small>
<?php } ?>
            </div>
        </div>

        <div class="hg-paycols">
            <div class="hg-paybox" id="upi">
                <h2 class="hg-h3">Scan to pay by UPI</h2>
                <p>Open any UPI app and scan the code. Check that the payee name shows <strong><?= hg_e(HG_LEGAL_NAME) ?></strong> before you pay.</p>
<?php if ($qrFile) { ?>
                <div class="hg-qr"><img src="<?= hg_e(HG_UPI_QR_IMAGE) ?>" alt="UPI QR code for <?= hg_e(HG_LEGAL_NAME) ?><?= $upiId !== '' ? ', UPI ID ' . hg_e($upiId) : '' ?>" width="260" height="260" loading="lazy"></div>
<?php } else { ?>
                <div class="hg-qr is-empty" role="img" aria-label="QR code not added yet">
                    <div><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM18 18h3v3h-3zM14 20h2M20 14v2"/></svg>
                    Our QR code will appear here. Until then, your travel expert shares it with your quote.</div>
                </div>
<?php } ?>
                <p class="hg-qr__apps">Works with Google Pay, PhonePe, Paytm, BHIM and bank UPI apps.</p>
<?php if ($upiLink !== '') { ?>
                <p class="hg-qr__open"><a class="hg-btn hg-btn--outline hg-btn--sm" href="<?= hg_e($upiLink) ?>">Open UPI app</a></p>
<?php } ?>
            </div>

            <div class="hg-paybox" id="bank">
                <h2 class="hg-h3">Bank transfer</h2>
                <p>Pay by NEFT, IMPS, RTGS or net banking. Cheques are payable to <?= hg_e(HG_LEGAL_NAME) ?>.</p>
<?php if ($bankReady) { ?>
                <dl class="hg-bank">
<?php foreach ($bankRows as $r) { if ($r[1] === '') continue; ?>
                    <div><dt><?= hg_e($r[0]) ?></dt><dd><?= hg_e($r[1]) ?></dd><?php if ($r[2]) { ?><button type="button" class="hg-copy" data-hg-copy="<?= hg_e($r[1]) ?>" aria-label="Copy <?= hg_e($r[0]) ?>">Copy</button><?php } else { ?><span></span><?php } ?></div>
<?php } ?>
                </dl>
                <p class="hg-bank__all"><button type="button" class="hg-copy" data-hg-copy="<?= hg_e($copyAll) ?>">Copy all bank details</button></p>
                <p class="hg-sr" aria-live="polite" id="copy-status"></p>
<?php } else { ?>
                <p class="hg-paybox__pending">Your travel expert shares the company's bank account details with your quote.</p>
<?php } ?>
            </div>
        </div>

        <div class="hg-notice" role="note"><?= hg_icon('shield') ?><p><strong>Pay only to an account in the name of <?= hg_e(HG_LEGAL_NAME) ?>.</strong> We never ask you to pay into a personal account or in cash. If you receive different bank or UPI details by message or email, call <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a> before you pay.</p></div>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-labelledby="after-title">
    <div class="hg-container hg-narrow hg-prose">
        <h2 class="hg-h3" id="after-title">After you pay</h2>
        <ol class="hg-steps hg-steps--list">
            <li><strong>Send your payment proof</strong><span>Share the screenshot or transaction reference on <a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp</a> or email <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL) ?></a>, with your name and travel date.</span></li>
            <li><strong>We confirm receipt</strong><span>Our accounts team checks the payment and sends you a receipt.</span></li>
            <li><strong>You get your voucher</strong><span>Your booking voucher is sent on WhatsApp or email once the payment is received.</span></li>
            <li><strong>Pay the balance</strong><span>We remind you before the due date. Final tickets and vouchers are released after full payment.</span></li>
        </ol>

        <h2 class="hg-h3">When to pay</h2>
        <div class="hg-tablewrap" tabindex="0" role="region" aria-label="When to pay"><table class="hg-table">
            <thead><tr><th scope="col">Booking type</th><th scope="col">At booking</th><th scope="col">Balance</th></tr></thead>
            <tbody>
                <tr><td>Holiday packages</td><td>35% advance to confirm</td><td>Before departure, on the date in your quote</td></tr>
                <tr><td>Flight tickets</td><td>100% of the fare</td><td>None</td></tr>
                <tr><td>Train tickets</td><td>100% of the fare</td><td>None</td></tr>
                <tr><td>Hotels, visas and other services</td><td>As stated in your quote</td><td>As stated in your quote</td></tr>
            </tbody>
        </table></div>
        <p>The same terms apply to domestic and international packages. Some hotels and airlines ask for full payment earlier, especially in peak season. If so, your quote will say so.</p>

        <h2 class="hg-h3">Cancellations and refunds</h2>
        <p>If you cancel, charges depend on how close to departure you cancel and on each supplier's rules. Refunds are made to the account you paid from, after suppliers return the amounts to us. See our <a href="/cancellation-policy">cancellation policy</a>, <a href="/refund-policy">refund policy</a> and <a href="/payment-policy">payment policy</a>.</p>
        <p>Questions about a payment? Call <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a> or email <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL) ?></a>.</p>
        <p class="hg-muted" style="font-size:14px">Holiday Guru Travel is operated by <?= hg_e(HG_LEGAL_NAME) ?><?= HG_GSTIN !== '' ? ' (GST No. ' . hg_e(HG_GSTIN) . ')' : '' ?>.</p>
    </div>
</section>
<?php if ($bankReady) { ?>
<script>
(function () {
    var status = document.getElementById('copy-status');
    function copyText(t) {
        if (navigator.clipboard && window.isSecureContext) return navigator.clipboard.writeText(t);
        return new Promise(function (res, rej) {
            var a = document.createElement('textarea'); a.value = t; a.setAttribute('readonly', '');
            a.style.position = 'absolute'; a.style.left = '-9999px'; document.body.appendChild(a); a.select();
            try { document.execCommand('copy') ? res() : rej(); } catch (err) { rej(err); } finally { document.body.removeChild(a); }
        });
    }
    document.querySelectorAll('[data-hg-copy]').forEach(function (btn) {
        var label = btn.textContent;
        btn.addEventListener('click', function () {
            copyText(btn.getAttribute('data-hg-copy')).then(function () {
                btn.textContent = 'Copied'; btn.classList.add('is-done'); status.textContent = 'Copied to clipboard';
                setTimeout(function () { btn.textContent = label; btn.classList.remove('is-done'); status.textContent = ''; }, 1800);
            }, function () { btn.textContent = 'Select and copy'; });
        });
    });
})();
</script>
<?php } ?>
<?php hg_layout_end(); ?>
