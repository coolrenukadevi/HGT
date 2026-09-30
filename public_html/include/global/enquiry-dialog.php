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
            <form class="hg-form hg-enq__form" id="hg-enquiry-form" data-hg-enquiry novalidate>
                <input type="hidden" name="enquiry_type" value="<?= hg_e('Enquire Now (menu) — ' . $hgEnqPage) ?>">
                <div class="hg-enq__fields">
                    <div class="hg-field hg-enq__f"><label for="enqd-name">Full name <span class="hg-enq__req" aria-hidden="true">*</span></label>
                        <span class="hg-enq__in"><?= hg_icon('user') ?><input id="enqd-name" name="name" autocomplete="name" required maxlength="100" placeholder="Enter your full name"></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-email">Email address <span class="hg-enq__req" aria-hidden="true">*</span></label>
                        <span class="hg-enq__in"><?= hg_icon('mail') ?><input id="enqd-email" name="email" type="email" autocomplete="email" required maxlength="150" placeholder="Enter your email address"></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-phone">Mobile number <span class="hg-enq__req" aria-hidden="true">*</span></label>
                        <span class="hg-enq__in"><?= hg_icon('phone') ?><input id="enqd-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" required maxlength="20" pattern="[0-9+ ]{8,20}" placeholder="+91 98xxx xxxxx"></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-service">Service</label>
                        <span class="hg-enq__in"><?= hg_icon('ticket') ?><select id="enqd-service" name="service"><?php foreach ($hgEnqServices as $sv) { ?><option><?= hg_e($sv) ?></option><?php } ?></select></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-dest">Destination</label>
                        <span class="hg-enq__in"><?= hg_icon('pin') ?><input id="enqd-dest" name="destination" list="enqd-dest-list" maxlength="100" placeholder="Where would you like to go?"></span>
                        <datalist id="enqd-dest-list"><?php foreach ($hgEnqDest as $d) { ?><option value="<?= hg_e($d) ?>"></option><?php } ?></datalist></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-date">Travel date</label>
                        <span class="hg-enq__in"><?= hg_icon('calendar') ?><input id="enqd-date" name="travel_date" type="date" min="<?= date('Y-m-d') ?>"></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-trav">No. of travellers</label>
                        <span class="hg-enq__in"><?= hg_icon('users') ?><select id="enqd-trav" name="travellers"><option value="">Select travellers</option><?php for ($i = 1; $i <= 9; $i++) { ?><option><?= $i ?></option><?php } ?><option>10+</option></select></span></div>
                    <div class="hg-field hg-enq__f"><label for="enqd-budget">Budget per person <span class="hg-optional">(optional)</span></label>
                        <span class="hg-enq__in"><span class="hg-enq__rupee" aria-hidden="true">₹</span><input id="enqd-budget" name="budget" inputmode="numeric" maxlength="20" placeholder="e.g. 50,000"></span></div>
                    <div class="hg-field hg-enq__f hg-enq__f--full"><label for="enqd-msg">Additional requirements <span class="hg-optional">(optional)</span></label>
                        <textarea id="enqd-msg" name="message" rows="3" maxlength="2000" placeholder="Tell us about your requirements, special requests, etc."></textarea></div>
                </div>
                <label class="hg-enq__consent"><input type="checkbox" name="consent" value="yes" required> I agree that Holiday Guru Travel may contact me by phone, WhatsApp or email about this enquiry.</label>
                <p class="hg-form__status" role="status" aria-live="polite"></p>
                <button class="hg-btn hg-enq__submit" type="submit"><?= hg_icon('plane') ?> Submit enquiry</button>
            </form>
        </div>
    </div>
</dialog>
