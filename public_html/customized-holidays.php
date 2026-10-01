<?php
// Customized holiday request. Posts to the existing Contact Us workflow (mail.php)
// with enquiry type "Customized Holiday"; prefilled from the search context.
require __DIR__ . '/include/ui/core.php';

$S = hg_search_state();
$package = isset($_GET['package']) && is_string($_GET['package']) ? mb_substr(trim($_GET['package']), 0, 150) : '';

$faqs = array(
    array('How does a customized holiday work?', '<p>Tell us where and when you want to travel, who is travelling and your budget. A travel expert calls or WhatsApps you, suggests a day-by-day itinerary and hotels, and revises it until it suits you. Our booking terms ask for a 35% advance to confirm, with the balance before departure; we then issue a booking voucher.</p>'),
    array('Can you add flights or trains?', '<p>Yes. Our standard package cost excludes airfare, train fare and bus fare, but we can quote tickets with your trip. Air and train tickets need full payment at booking.</p>'),
    array('Can I start from one of your packages?', '<p>Yes — open any package and choose “Customize this package”, or name it in the form below.</p>'),
);

hg_layout_start(array(
    'title' => 'Customized Holiday Packages | Holiday Guru Travel',
    'description' => 'Plan a customized holiday in India or abroad. Share your destination, dates, travellers and budget, and a Holiday Guru Travel expert builds your itinerary and quote.',
    'path' => '/customized-holidays',
    'index' => empty($_GET),
    'breadcrumbs' => array(array('Home', '/'), array('Customized Holidays', null)),
    'schema' => array(hg_faq_schema($faqs)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Customized holidays</p>
        <h1 class="hg-h1" id="page-title">Plan a holiday your way</h1>
        <p class="hg-lead">Tell us where, when and who is travelling. A travel expert designs the itinerary, hotels and transfers around you.</p>
    </div>
</section>

<section class="hg-section hg-section--tight">
    <div class="hg-container hg-layout">
        <div>
            <?php // Same form as everywhere (Enquire Now), with "Customised tour" pre-selected (owner request 2026-09-30). ?>
            <?php if ($package !== '') { ?><p class="hg-notice">Starting from: <strong><?= hg_e($package) ?></strong></p><?php } ?>
            <div class="hg-form--card"><?= hg_enquiry_form('custom-form', 'Your trip', array_merge(
                array('enquiry_type' => 'Customized Holiday', '_service' => 'Customised tour', 'destination' => $S['destination'],
                      '_lead' => 'Tell us where, when and who is travelling. A travel expert designs the itinerary, hotels and transfers around you.'),
                $package !== '' ? array('package' => $package) : array()
            )) ?></div>
        </div>
        <aside class="hg-layout__side">
            <div class="hg-sidecard">
                <h2 class="hg-h3">How it works</h2>
                <ol class="hg-steps hg-steps--list">
                    <li><strong>Tell us your trip</strong><span>Destination, dates, travellers and budget.</span></li>
                    <li><strong>Get your plan</strong><span>A day-by-day itinerary, hotels and a quote.</span></li>
                    <li><strong>Fine-tune it</strong><span>Change anything until it suits you.</span></li>
                    <li><strong>Confirm</strong><span>35% advance to confirm; booking voucher issued once payment is received.</span></li>
                </ol>
            </div>
            <div class="hg-sidecard">
                <h2 class="hg-h3">Prefer to talk?</h2>
                <p><a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_icon('phone') ?> <?= hg_e(HG_PHONE_DISPLAY) ?></a></p>
                <a class="hg-btn hg-btn--wa hg-btn--block" href="<?= hg_e(hg_whatsapp_href("Hi Holiday Guru Travel,\nI would like to plan a customized holiday" . ($S['destination'] ? ' to ' . $S['destination'] : '') . '.')) ?>" target="_blank" rel="noopener"><?= hg_icon('whatsapp') ?>Chat on WhatsApp</a>
            </div>
        </aside>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="faq-title">
    <div class="hg-container hg-narrow">
        <h2 class="hg-h2" id="faq-title">Customized holiday questions</h2>
        <?= hg_faq($faqs) ?>
    </div>
</section>
<?php hg_layout_end(); ?>
