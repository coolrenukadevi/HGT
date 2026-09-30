<?php
// Leadership. Profiles: include/content/people.php (from the owner's company-page pack, 2026-09-30).
// Publishing status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';
$people = include __DIR__ . '/include/content/people.php';

hg_layout_start(array(
    'title' => 'Leadership | Holiday Guru Travel',
    'description' => 'The leadership of Holiday Guru Travel (' . HG_LEGAL_NAME . '), Noida: who runs the company and how every booking is handled.',
    'path' => '/leadership', 'index' => hg_page_status('/leadership') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('About Us', '/about'), array('Leadership', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Leadership</p>
        <h1 class="hg-h1" id="page-title">The people responsible for your trip</h1>
        <p class="hg-lead">Holiday Guru Travel is run by a small leadership team that still speaks with travellers and reviews itineraries every week.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-label="Leadership team">
    <div class="hg-container hg-leaders">
<?php foreach ($people['leadership'] as $p) {
    if (trim($p['name']) === '') continue;
    $words = preg_split('/\s+/', trim($p['name']));
    $initials = mb_substr($words[0], 0, 1) . (count($words) > 1 ? mb_substr(end($words), 0, 1) : ''); ?>
        <article class="hg-leader">
            <div class="hg-leader__avatar"<?= $p['photo'] === '' ? ' aria-hidden="true"' : '' ?>><?= $p['photo'] !== '' ? hg_img($p['photo'], $p['name'], 192, 192, '') : hg_e($initials) ?></div>
            <div>
                <h2 class="hg-leader__name"><?= hg_e($p['name']) ?></h2>
                <p class="hg-leader__role"><?= hg_e($p['role']) ?></p>
<?php foreach ((array) $p['bio'] as $para) { ?>
                <p><?= hg_e($para) ?></p>
<?php } ?>
            </div>
        </article>
<?php } ?>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="run-title">
    <div class="hg-container">
        <?= hg_section_head('How we work', 'How we run the company', '', null, 'run-title') ?>
        <div class="hg-grid hg-grid--3">
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('check') ?></span><h3>The plan comes before the payment</h3><p>No advance is taken until the traveller has agreed the itinerary, hotels and price.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('shield') ?></span><h3>Money goes to the company, on record</h3><p>Payments are accepted only by bank transfer, UPI or cheque in the company's name. No cash.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('phone') ?></span><h3>Someone answers during the trip</h3><p>A 24×7 emergency line is part of every booking, not an add-on.</p></div>
        </div>
    </div>
</section>

<section class="hg-section" aria-labelledby="write-title">
    <div class="hg-container hg-narrow">
        <?= hg_section_head('Contact', 'Write to the leadership team', '', null, 'write-title') ?>
        <p>For partnerships, supplier enquiries or feedback about a booking, email <a href="mailto:<?= hg_e(HG_EMAIL) ?>?subject=<?= rawurlencode('Attention: Management') ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a> with “Attention: Management” in the subject line.</p>
        <p>For complaints, please see our <a href="/grievance-redress">grievance redress</a> process.</p>
    </div>
</section>
<?php hg_layout_end(); ?>
