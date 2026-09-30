<?php
/**
 * GLOBAL COMPONENT: "Enquire Now" dialog, opened by [data-hg-enquiry-open] (menu tab; owner request 2026-09-30).
 * Uses the site's standard enquiry form (hg_enquiry_form → /mail.php). The email says which page it came from.
 * Behaviour: assets/js/src/ui/login-dialog.js. Styles: .hg-dialog--enquiry (assets/css/src/components/login-dialog.css).
 */
$hgEnqPage = strtok((string) (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/'), '?');
?>
<dialog class="hg-dialog hg-dialog--enquiry" id="hg-enquiry" aria-labelledby="hg-enquiry-title">
    <form method="dialog" class="hg-dialog__close-form">
        <button class="hg-iconbtn hg-dialog__close" value="close"><?= hg_icon('close') ?><span class="hg-sr">Close</span></button>
    </form>
    <p class="hg-eyebrow">Enquire now</p>
    <h2 class="hg-dialog__title" id="hg-enquiry-title">Plan your trip with a travel expert</h2>
    <p class="hg-dialog__lead">Share a few details and we'll send an itinerary and quote on phone or WhatsApp.</p>
    <?= hg_enquiry_form('hg-enquiry-form', '', array('enquiry_type' => 'Enquire Now (menu) — ' . $hgEnqPage), true) ?>
</dialog>
