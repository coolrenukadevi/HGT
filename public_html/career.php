<?php
// Careers. Content from the owner's company-page pack (2026-09-30), built on the site framework.
// Applications go to the site email. Publishing status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';

$roles = array(
    array('Holiday planner / travel sales', '', 'Handle enquiries, build itineraries, send quotes and close bookings.', 'Enjoy talking to people and know at least one destination well.'),
    array('Operations executive', 'Domestic or international', 'Confirm hotels, transfers and sightseeing; prepare vouchers.', 'Are organised and calm on the phone with suppliers.'),
    array('Ticketing executive', '', 'Issue and reissue air, rail and bus tickets.', 'Have used a GDS or airline booking portal.'),
    array('Visa and documentation', '', 'Prepare and check visa files for travellers.', 'Are careful with detail and document checklists.'),
    array('Digital marketing and content', '', 'Write package pages and blog posts; run social media.', 'Write clearly and like travel photography.'),
    array('Accounts executive', '', 'Record payments, reconcile accounts and process refunds.', 'Know Tally or similar and basic GST.'),
    array('Internship', '', 'Work alongside planners and operations for 2 to 6 months.', 'Are studying travel, tourism or hospitality.'),
);
$applyHref = 'mailto:' . HG_EMAIL . '?subject=' . rawurlencode('Application: role name');

hg_layout_start(array(
    'title' => 'Careers | Work at Holiday Guru Travel, Noida',
    'description' => 'Jobs at Holiday Guru Travel in Noida: holiday planners, operations, ticketing, visa, accounts, marketing and internships. How to apply.',
    'path' => '/career', 'index' => hg_page_status('/career') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('About Us', '/about'), array('Careers', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Careers</p>
        <h1 class="hg-h1" id="page-title">Work on trips people will talk about for years</h1>
        <p class="hg-lead">We're a small travel team in Noida. You'll talk to real travellers, build real itineraries and learn how the travel business works from the inside.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-labelledby="work-title">
    <div class="hg-container">
        <?= hg_section_head('Working here', 'What the job is like', '', null, 'work-title') ?>
        <div class="hg-grid hg-grid--2">
            <div class="hg-card hg-accent-card"><h3>You own your travellers</h3><p>Planners follow their bookings from first call to the trip home.</p></div>
            <div class="hg-card hg-accent-card"><h3>You learn the whole business</h3><p>Sales, operations, ticketing and visas sit in the same office. You'll pick up all of it.</p></div>
            <div class="hg-card hg-accent-card"><h3>Destination knowledge</h3><p>Learn Dubai, Southeast Asia, the Maldives and India's main circuits in depth.</p></div>
            <div class="hg-card hg-accent-card"><h3>A small team</h3><p>Your work is visible, and good ideas get used quickly.</p></div>
        </div>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="roles-title">
    <div class="hg-container">
        <?= hg_section_head('Roles', 'Roles we hire for', 'Openings change through the year. Apply for the role closest to your experience, even if it isn\'t listed as open.', null, 'roles-title') ?>
        <div class="hg-tablewrap" tabindex="0" role="region" aria-label="Roles we hire for"><table class="hg-table">
            <thead><tr><th scope="col">Role</th><th scope="col">What you'll do</th><th scope="col">Good fit if you</th></tr></thead>
            <tbody>
<?php foreach ($roles as $r) { ?>
                <tr><td><?= hg_e($r[0]) ?><?= $r[1] !== '' ? '<br><span class="hg-muted" style="font-weight:400">' . hg_e($r[1]) . '</span>' : '' ?></td><td><?= hg_e($r[2]) ?></td><td><?= hg_e($r[3]) ?></td></tr>
<?php } ?>
            </tbody>
        </table></div>
    </div>
</section>

<section class="hg-section" aria-labelledby="apply-title">
    <div class="hg-container hg-narrow">
        <?= hg_section_head('How to apply', 'Four steps', '', null, 'apply-title') ?>
        <ol class="hg-steps hg-steps--list">
            <li><strong>Email your CV</strong><span>Send it to <a href="<?= hg_e($applyHref) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a> with the subject “Application: role name”.</span></li>
            <li><strong>Tell us a little</strong><span>In a few lines: your experience, notice period, and one destination you know well.</span></li>
            <li><strong>Phone conversation</strong><span>If your profile fits, we'll call you for a short conversation.</span></li>
            <li><strong>Meet us in Noida</strong><span>An interview at our office, sometimes with a short practical task.</span></li>
        </ol>
        <div class="hg-notice" role="note" style="margin-top:24px"><?= hg_icon('shield') ?><p><strong>We never charge a fee to apply or to get hired.</strong> If anyone asks you for money in our name for a job, do not pay. Report it to <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a>.</p></div>
    </div>
</section>
<?php hg_layout_end(); ?>
