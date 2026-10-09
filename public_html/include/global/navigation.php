<?php
/**
 * GLOBAL COMPONENT: primary navigation (#hg-nav) and mobile drawer. Mega-menu panels: mega-menu.php.
 * Tabs: Domestic · International · Inbound Tours · Special Tours · Fixed Departure (mega menus), Customised Tours,
 * Offers, About Us (mega menu), Enquire Now (opens the enquiry dialog; Contact is in About Us and the footer).
 * Pay Now (owner request 2026-10-01): HG_PAY_LINK (Razorpay) in a new tab; in the phone drawer it is a button
 * under "Enquire now". Hidden if HG_PAY_LINK is empty. Enquire Now and Pay Now share one list item so they
 * stay together at the right end of the menu row with a fixed gap (2026-10-05).
 * Behaviour: [data-hg-nav], [data-hg-mega], [data-hg-menu-*] in assets/js/hg-ui.js.
 */
?>
    <nav class="hg-nav" id="hg-nav" aria-label="Primary" data-hg-nav>
        <div class="hg-nav__drawerhead">
            <span class="hg-nav__drawertitle">Menu</span>
            <button type="button" class="hg-iconbtn" data-hg-menu-close><?= hg_icon('close') ?><span class="hg-sr">Close menu</span></button>
        </div>
        <ul class="hg-container hg-nav__list">
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-india" data-hg-mega>Domestic<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'india'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-intl" data-hg-mega>International<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'intl'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-inbound" data-hg-mega>Inbound Tours<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'inbound'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-spec" data-hg-mega>Special Tours<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'spec'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-fixed" data-hg-mega>Fixed Departure<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'fixed'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item"><a class="hg-nav__link" href="/customized-holidays">Customised Tours</a></li>
            <li class="hg-nav__item"><a class="hg-nav__link" href="/offers">Offers</a></li>
            <li class="hg-nav__item hg-nav__item--mega">
                <button type="button" class="hg-nav__trigger" aria-expanded="false" aria-controls="mega-about" data-hg-mega>About Us<?= hg_icon('chevron') ?></button>
<?php $hgMegaPanel = 'about'; include __DIR__ . '/mega-menu.php'; ?>
            </li>
            <li class="hg-nav__item hg-nav__item--cta">
                <button type="button" class="hg-nav__link hg-nav__enquire" data-hg-enquiry-open aria-haspopup="dialog">Enquire Now</button>
<?php if (HG_PAY_LINK !== '') { ?>
                <a class="hg-nav__link hg-nav__pay" href="<?= hg_e(HG_PAY_LINK) ?>" target="_blank" rel="noopener" data-hg-track="paynow_start"><?= hg_icon('lock') ?>Pay Now</a>
<?php } ?>
            </li>
        </ul>
        <div class="hg-nav__drawerfoot">
            <a class="hg-btn hg-btn--primary hg-btn--block" href="/customized-holidays">Enquire now</a>
<?php if (HG_PAY_LINK !== '') { ?>
            <a class="hg-btn hg-btn--navy hg-btn--block hg-nav__paybtn" href="<?= hg_e(HG_PAY_LINK) ?>" target="_blank" rel="noopener" data-hg-track="paynow_start"><?= hg_icon('lock') ?>Pay Now</a>
<?php } ?>
            <div class="hg-nav__quick">
                <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_icon('phone') ?> Call</a>
                <a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener"><?= hg_icon('whatsapp') ?> WhatsApp</a>
                <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_icon('mail') ?> Email</a>
            </div>
            <ul class="hg-nav__secondary"><li><a href="/track-enquiry">Track your enquiry</a></li><li><a href="/faqs">FAQs</a></li></ul>
        </div>
    </nav>
    <div class="hg-nav__scrim" data-hg-menu-close hidden></div>
