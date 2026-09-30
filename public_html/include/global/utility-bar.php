<?php
/**
 * GLOBAL COMPONENT: utility bar (secure-site note, 24x7 WhatsApp support, Login menu). Owns nothing page-specific.
 * Included by include/global/header.php. Styles: .hg-utility (assets/css/src/components/utility-bar.css).
 * Behaviour: [data-hg-umenu] in assets/js/src/ui/login-dialog.js.
 */
?>
<div class="hg-utility">
    <div class="hg-container hg-utility__inner">
        <p class="hg-utility__secure"><?= hg_icon('lock') ?><span class="hg-utility__long">Secure SSL-encrypted website</span><span class="hg-utility__short">Secure website</span></p>
        <ul class="hg-utility__right">
            <li><a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener"><?= hg_icon('whatsapp') ?><span>24×7 Support</span></a></li>
            <li class="hg-umenu" data-hg-umenu>
                <button type="button" class="hg-umenu__btn" aria-expanded="false" aria-controls="hg-umenu-list">Login<?= hg_icon('chevron') ?></button>
                <ul class="hg-umenu__list" id="hg-umenu-list" hidden>
                    <li><button type="button" data-hg-login-open><?= hg_icon('user') ?>Login / Sign up</button></li>
                    <li><a href="<?= hg_e(hg_whatsapp_href("Hi Holiday Guru Travel,\nI would like an update on my enquiry/booking.")) ?>" target="_blank" rel="noopener"><?= hg_icon('whatsapp') ?>Check my booking</a></li>
                    <li><a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_icon('mail') ?>Email us</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
