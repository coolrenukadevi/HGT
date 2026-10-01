<?php
/**
 * Mobile sticky contact bar (below 1024px): Call · WhatsApp · Enquire, on every page (owner approval 2026-10-01).
 * A page may set $GLOBALS['hgMobileBar'] before hg_layout_end():
 *   'whatsapp' => pre-filled WhatsApp message (default: the general greeting from hg_whatsapp_href())
 *   'enquire'  => link for Enquire (e.g. the page's own enquiry form); without it Enquire opens the Enquire Now dialog
 *   'track'    => false to leave out the analytics click tags (default true)
 * Styles: assets/css/src/components/mobile-bar.css.
 */
$hgBar = isset($GLOBALS['hgMobileBar']) ? $GLOBALS['hgMobileBar'] : array();
$hgBarTrack = !isset($hgBar['track']) || $hgBar['track'];
$hgBarTag = function ($event) use ($hgBarTrack) { return $hgBarTrack ? ' data-hg-track="' . $event . '"' : ''; };
?>
<div class="hg-bottombar" aria-label="Quick contact">
    <a href="<?= hg_e(hg_tel_href()) ?>"<?= $hgBarTag('call_click') ?>><?= hg_icon('phone') ?>Call</a>
    <a href="<?= hg_e(hg_whatsapp_href(isset($hgBar['whatsapp']) ? $hgBar['whatsapp'] : '')) ?>" target="_blank" rel="noopener"<?= $hgBarTag('whatsapp_click') ?>><?= hg_icon('whatsapp') ?>WhatsApp</a>
<?php if (!empty($hgBar['enquire'])) { ?>
    <a class="is-primary" href="<?= hg_e($hgBar['enquire']) ?>"<?= $hgBarTag('enquiry_start') ?>><?= hg_icon('mail') ?>Enquire</a>
<?php } else { ?>
    <button type="button" class="is-primary" data-hg-enquiry-open<?= $hgBarTag('enquiry_start') ?>><?= hg_icon('mail') ?>Enquire</button>
<?php } ?>
</div>
