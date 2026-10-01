<?php
// Our team. Content from the owner's company-page pack (2026-09-30): roles rather than named people.
// Named profiles can be added later in include/content/people.php ('team'). Status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';

hg_layout_start(array(
    'title' => 'Our Team | Holiday Guru Travel',
    'description' => 'The Holiday Guru Travel team in Noida: holiday planners, operations, ticketing, visa, accounts and 24x7 traveller support — who does what and how to reach them.',
    'path' => '/our-team', 'index' => hg_page_status('/our-team') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('About Us', '/about'), array('Our team', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Our team</p>
        <h1 class="hg-h1" id="page-title">The team behind each booking</h1>
        <p class="hg-lead">Your trip passes through several hands before you leave. Here's who does what, and how to reach them.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-labelledby="who-title">
    <div class="hg-container">
        <?= hg_section_head('Who does what', 'Six teams, one trip', '', null, 'who-title') ?>
        <div class="hg-grid hg-grid--3">
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('users') ?></span><h3>Holiday planners</h3><p>Your first contact. They understand what you want, build the itinerary, send quotes and stay your point of contact through the trip.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('bed') ?></span><h3>Operations and reservations</h3><p>Confirm hotels, transfers and sightseeing with our partners, and prepare your booking voucher.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('ticket') ?></span><h3>Ticketing</h3><p>Book and reissue flight, train and bus tickets, and handle date changes and cancellations.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('globe') ?></span><h3>Visa and documents</h3><p>Help you prepare visa applications and check travel documents against current requirements.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('check') ?></span><h3>Accounts</h3><p>Record payments, share receipts and process refunds under our <a href="/cancellation-policy">cancellation policy</a>.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('phone') ?></span><h3>Traveller support</h3><p>Answer the 24×7 emergency line and sort out problems on the ground while you travel.</p></div>
        </div>
    </div>
</section>

<section class="hg-section hg-band" aria-labelledby="flow-title">
    <div class="hg-container">
        <?= hg_section_head('How it works', 'How your trip moves through the team', '', null, 'flow-title') ?>
        <ol class="hg-steps">
            <li><strong>Holiday planner</strong>Takes your enquiry and agrees the plan with you.</li>
            <li><strong>Accounts</strong>Receives your advance and confirms it.</li>
            <li><strong>Operations and ticketing</strong>Book hotels, transport and tickets, then issue your voucher.</li>
            <li><strong>Traveller support</strong>On call while you're away, alongside your planner.</li>
        </ol>
    </div>
</section>

<section class="hg-section" aria-labelledby="reach-title">
    <div class="hg-container">
        <?= hg_section_head('Contact', 'Reach the right person', '', null, 'reach-title') ?>
        <dl class="hg-qf hg-qf--3">
            <div><dt>New trip or quote</dt><dd><a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a></dd></div>
            <div><dt>Existing booking</dt><dd><a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp <?= hg_e(HG_WHATSAPP_DISPLAY) ?></a></dd></div>
            <div><dt>Phone</dt><dd><a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a></dd></div>
        </dl>
        <p style="margin-top:20px">Want to join us? We hire holiday planners, operations staff and interns from time to time. See <a href="/career">careers</a> for how to apply.</p>
    </div>
</section>
<?php hg_layout_end(); ?>
