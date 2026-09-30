<?php
// Blog / travel desk. From the owner's company-page pack (2026-09-30). The pack listed six articles that are
// not written yet, so they are shown as upcoming topics (no links or read times) until each article exists.
// The one topic already covered on the site links to that page. Publishing status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';

$topics = array(
    array('International', 'Planning your first family trip to Dubai', 'How many nights you really need, which areas suit families, when the weather is kind, and what to sort out before you apply for a visa.', ''),
    array('India', 'Kashmir by season: what each month is like', 'Tulips in spring, green meadows in summer, gold in autumn and snow in winter. How to pick your month and what changes in the itinerary.', ''),
    array('International', 'Singapore and Malaysia in one trip', 'How to split the nights, whether to cross by road or air, and which order works better with children.', ''),
    array('International', 'Choosing a Maldives resort: seaplane, speedboat or local island', 'Transfer type changes your budget and your arrival time. What to weigh up before you pick a resort.', ''),
    array('India', 'Kerala for first-timers: Munnar, Thekkady and a houseboat', 'A classic route, the drive times between stops, and when a houseboat night is worth it.', ''),
    array('Booking tips', 'How the 35% advance and balance payment work', 'What the advance secures, when the balance is due, and why flight and train tickets are paid in full.', '/payment-policy'),
    array('Booking tips', 'Travel insurance: what to check before you buy', 'Medical cover, trip cancellation and baggage delay. The questions that matter and the ones that don\'t.', ''),
);

hg_layout_start(array(
    'title' => 'Travel Desk Blog | Holiday Guru Travel',
    'description' => 'Practical travel guides from the Holiday Guru Travel team: Dubai, Kashmir, Singapore and Malaysia, the Maldives, Kerala and booking tips.',
    'path' => '/blog', 'index' => hg_page_status('/blog') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('Blog', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Blog</p>
        <h1 class="hg-h1" id="page-title">Notes from our travel desk</h1>
        <p class="hg-lead">Practical guides written by the people who plan these trips every week. Read before you book, or before you pack.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-labelledby="topics-title">
    <div class="hg-container">
        <?= hg_section_head('Guides', 'What we\'re writing', 'Our first guides are on the way. Until then, ask your travel expert — or read the <a href="/travel-guide/kashmir">Kashmir travel guide</a> and our <a href="/faqs">FAQs</a>.', null, 'topics-title') ?>
        <div class="hg-grid hg-grid--3">
<?php foreach ($topics as $t) { ?>
            <article class="hg-card hg-accent-card hg-topic">
                <p class="hg-topic__cat"><?= hg_e($t[0]) ?></p>
                <h3><?= $t[3] !== '' ? '<a href="' . hg_e($t[3]) . '">' . hg_e($t[1]) . '</a>' : hg_e($t[1]) ?></h3>
                <p><?= hg_e($t[2]) ?></p>
                <?= $t[3] === '' ? '<p class="hg-topic__soon">Coming soon</p>' : '' ?>
            </article>
<?php } ?>
        </div>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="suggest-title">
    <div class="hg-container hg-narrow">
        <?= hg_section_head('Suggest a topic', 'Can\'t find an answer?', '', null, 'suggest-title') ?>
        <p>Planning a trip and can't find an answer? Email <a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a> and we may write about it. We'll reply to your question either way.</p>
    </div>
</section>
<?php hg_layout_end(); ?>
