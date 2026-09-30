<?php
/**
 * GLOBAL COMPONENT: "Enquire Now" dialog, opened by [data-hg-enquiry-open] (menu tab).
 * Layout from the owner's reference (2026-09-30) in the logo colours: photo panel with benefits on the left,
 * enquiry form on the right. Posts to /mail.php like every enquiry form (data-hg-enquiry → forms.js).
 * Deliberately not copied from the reference: "Best price guaranteed" (unverifiable), document upload (no secure
 * storage for passports), and the Privacy Policy link (no policy page yet — HG_PRIVACY_URL is empty).
 * Behaviour: assets/js/src/ui/login-dialog.js. Styles: .hg-enq (assets/css/src/components/login-dialog.css).
 */
$hgEnqPage = strtok((string) (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/'), '?');
$hgEnqDest = array();
$hgEnqData = json_decode((string) @file_get_contents(dirname(__DIR__) . '/data/destinations.json'), true);
foreach ((isset($hgEnqData['groups']) ? $hgEnqData['groups'] : array()) as $g) {
    if (!empty($g['name'])) $hgEnqDest[] = $g['name'];
}
$hgEnqServices = array('Holiday package', 'Customised tour', 'Pilgrimage tour', 'Flights, trains or buses', 'Hotels and stays', 'Car rental and transfers', 'Visa, insurance or cruise', 'Other');
// The form itself is the shared one (hg_enquire_form_markup in include/ui/core.php), used on every page.
?>
<dialog class="hg-dialog hg-enq" id="hg-enquiry" aria-labelledby="hg-enquiry-title">
    <form method="dialog" class="hg-dialog__close-form">
        <button class="hg-iconbtn hg-dialog__close" value="close"><?= hg_icon('close') ?><span class="hg-sr">Close</span></button>
    </form>
    <div class="hg-enq__grid">
        <aside class="hg-enq__side" aria-label="Why enquire with us">
            <picture class="hg-enq__photo" aria-hidden="true">
                <source type="image/webp" srcset="/assets/img/hero/kerala-backwaters-960.webp">
                <img src="/assets/img/hero/kerala-backwaters.jpg" alt="" width="960" height="1280" loading="lazy" decoding="async">
            </picture>
            <div class="hg-enq__sidein">
                <p class="hg-enq__sidetitle">Enquire <span>Now</span></p>
                <p class="hg-enq__sidelead">Get a quote, a customised itinerary and expert support for your next trip.</p>
                <ul class="hg-enq__benefits">
                    <li><span class="hg-enq__bicon"><?= hg_icon('route') ?></span>Customised itineraries</li>
                    <li><span class="hg-enq__bicon"><?= hg_icon('phone') ?></span>24×7 travel support</li>
                    <li><span class="hg-enq__bicon"><?= hg_icon('check') ?></span>Inclusions listed line by line</li>
                    <li><span class="hg-enq__bicon"><?= hg_icon('shield') ?></span>Office in Sector 27, Noida</li>
                </ul>
                <a class="hg-enq__help" href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener" data-hg-track="whatsapp">
                    <span class="hg-enq__helpicon"><?= hg_icon('whatsapp') ?></span>
                    <span>Need immediate assistance?<strong><?= hg_e(HG_PHONE_DISPLAY) ?></strong></span>
                </a>
            </div>
        </aside>

        <div class="hg-enq__main">
            <span class="hg-enq__bar" aria-hidden="true"></span>
            <h2 class="hg-enq__title" id="hg-enquiry-title">Enquire Now</h2>
            <p class="hg-enq__lead">Tell us about your travel plans and our team will get back to you with the best options.</p>
            <?= hg_enquire_form_markup('hg-enquiry-form', 'enqd-', '<input type="hidden" name="enquiry_type" value="' . hg_e('Enquire Now (menu) — ' . $hgEnqPage) . '">', '', 'Holiday package', $hgEnqServices, $hgEnqDest) ?>
        </div>
    </div>
</dialog>
