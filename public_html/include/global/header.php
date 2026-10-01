<?php
/**
 * GLOBAL SHELL (top): skip link, utility bar, header (logo, search, contact actions, login), navigation.
 * Each part is its own component in include/global/. Nothing page-specific is rendered here.
 */
require_once __DIR__ . '/../ui/core.php';
?>
<a class="hg-skip" href="#main">Skip to content</a>
<?php include __DIR__ . '/utility-bar.php'; ?>

<header class="hg-header" data-hg-header>
    <div class="hg-container hg-header__inner">
        <button type="button" class="hg-iconbtn hg-header__menu" aria-controls="hg-nav" aria-expanded="false" data-hg-menu-toggle>
            <?= hg_icon('menu') ?><span class="hg-sr">Open menu</span>
        </button>
        <a class="hg-header__logo" href="/" aria-label="Holiday Guru Travel home">
            <picture>
                <source type="image/webp" srcset="/assets/brand/holiday-guru-travel-logo-script-270.webp 270w, /assets/brand/holiday-guru-travel-logo-script-540.webp 540w, /assets/brand/holiday-guru-travel-logo-script-810.webp 810w" sizes="(min-width: 1024px) 232px, 260px">
                <img src="/assets/brand/holiday-guru-travel-logo-script-540.png" alt="Holiday Guru Travel" width="232" height="41">
            </picture>
        </a>
<?php include __DIR__ . '/holiday-search.php'; ?>
        <div class="hg-header__actions">
            <button type="button" class="hg-iconbtn hg-header__searchbtn" aria-controls="hg-header-search" aria-expanded="false" data-hg-search-toggle><?= hg_icon('search') ?><span class="hg-sr">Search holiday packages</span></button>
            <a class="hg-hcontact" href="<?= hg_e(hg_tel_href()) ?>" aria-label="Call <?= hg_e(HG_PHONE_DISPLAY) ?>" title="Call <?= hg_e(HG_PHONE_DISPLAY) ?>"><?= hg_icon('phone') ?><span><strong>Call Now</strong><?= hg_e(HG_PHONE_DISPLAY) ?></span></a>
            <a class="hg-hcontact hg-hcontact--wa" href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener" aria-label="WhatsApp <?= hg_e(HG_WHATSAPP_DISPLAY) ?>" title="WhatsApp <?= hg_e(HG_WHATSAPP_DISPLAY) ?>"><?= hg_icon('whatsapp') ?><span><strong>WhatsApp</strong><?= hg_e(HG_WHATSAPP_DISPLAY) ?></span></a>
            <button type="button" class="hg-iconbtn hg-header__login" data-hg-login-open><?= hg_icon('user') ?><span class="hg-sr">Login</span></button>
        </div>
    </div>

<?php include __DIR__ . '/navigation.php'; ?>
</header>
