<?php
// Why book with us. Content from the owner's company-page pack (2026-09-30), built on the site framework.
// Company facts come from site configuration. Publishing status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';

hg_layout_start(array(
    'title' => 'Why Book With Us | Holiday Guru Travel',
    'description' => 'What to expect when you plan a holiday with Holiday Guru Travel: one travel expert, flexible itineraries, clear payment terms and 24x7 support on the road.',
    'path' => '/why-us', 'index' => hg_page_status('/why-us') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('About Us', '/about'), array('Why us', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Why choose us</p>
        <h1 class="hg-h1" id="page-title">What you can expect when you book with us</h1>
        <p class="hg-lead">No sales pitch here. These are the things we do on every booking, so you know how we work before you call.</p>
    </div>
</section>

<section class="hg-section hg-section--tight">
    <div class="hg-container hg-grid hg-grid--3">
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('user') ?></span><h2 class="hg-h3">One travel expert, start to finish</h2><p>The person who plans your trip is the person you call when something changes. You don't repeat your story to a new desk each time.</p></div>
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('route') ?></span><h2 class="hg-h3">Packages are a starting point</h2><p>Tell us your dates, travellers, hotel preference and what to add or drop. We send a revised itinerary and quote.</p></div>
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('check') ?></span><h2 class="hg-h3">Everything confirmed before you pay</h2><p>Itinerary, hotels and price are agreed with you first. The advance is only asked for once you're happy with the plan.</p></div>
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('shield') ?></span><h2 class="hg-h3">Clear payment terms</h2><p>35% advance for packages, balance before departure. Bank transfer, UPI or cheque. We don't take cash. See our <a href="/payment-policy">payment policy</a>.</p></div>
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('whatsapp') ?></span><h2 class="hg-h3">Documents on WhatsApp and email</h2><p>Your itinerary, quote and booking voucher are shared where you'll actually look for them.</p></div>
        <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('phone') ?></span><h2 class="hg-h3">Help while you travel</h2><p>A missed transfer or a hotel mix-up needs a quick fix. Our team is reachable 24×7 for emergencies.</p></div>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="office-title">
    <div class="hg-container hg-grid hg-grid--2">
        <div>
            <?= hg_section_head('Who we are', 'A company with an office you can visit', '', null, 'office-title') ?>
            <p>Holiday Guru Travel is operated by <?= hg_e(HG_LEGAL_NAME) ?>. Our office is at <?= hg_e(HG_ADDRESS_LINE1) ?>, <?= hg_e(HG_ADDRESS_LINE2) ?>. You are welcome to meet your travel expert in person before you book.</p>
            <p>Every payment goes to the company's account, and you receive a voucher for what you've paid for.</p>
        </div>
        <div class="hg-card">
            <h2 class="hg-h3">Before you book, ask us</h2>
            <p>A good travel agent answers these without hesitation. Ask us:</p>
            <ul class="hg-askus">
                <li>What exactly is included, and what isn't?</li>
                <li>Which hotels are confirmed, and what are the alternatives if one is full?</li>
                <li>What are the cancellation and refund terms for this package?</li>
                <li>Who do I call if something goes wrong at 11 pm?</li>
            </ul>
        </div>
    </div>
</section>

<?= hg_cta_band('Planning a trip?', 'Tell us your dates and who\'s travelling. A travel expert will send an itinerary and quote.') ?>
<?php hg_layout_end(); ?>
