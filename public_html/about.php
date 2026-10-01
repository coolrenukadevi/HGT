<?php
// About page (Phase 1). Story and mission text are the company's own wording
// from the previous About page; facts are from site configuration and data.
require __DIR__ . '/include/ui/core.php';


hg_layout_start(array(
    'title' => 'About us | Holiday Guru Travel',
    'description' => 'Holiday Guru Travel plans family trips, honeymoons, group tours and business travel across India and abroad from our office in Noida. Our story, what we do and how a booking works.',
    'path' => '/about',
    'breadcrumbs' => array(array('Home', '/'), array('About Us', null)),
    'schema' => array(array('@type' => 'AboutPage', 'name' => 'About Holiday Guru Travel', 'url' => hg_abs('/about'), 'about' => array('@id' => HG_SITE_URL . '/#organization'))),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">About us</p>
        <h1 class="hg-h1" id="page-title">Holidays planned by people, from our office in Noida</h1>
        <p class="hg-lead">Holiday Guru Travel plans family trips, honeymoons, group tours and business travel across India and abroad.</p>
    </div>
</section>

<section class="hg-section hg-section--tight">
    <div class="hg-container hg-grid hg-grid--2">
        <div class="hg-card hg-accent-card">
            <h2 class="hg-h3">Our story</h2>
            <p>Holiday Guru Travel started from a simple idea: a trip is remembered for its moments, not its paperwork. The people who plan your holiday should take care of the details so you can pay attention to the place and the people you are with.</p>
            <p>We began planning tours from Noida and grew through referrals from travellers who came back and booked again. Every booking is handled by a travel expert who confirms the itinerary, hotels and price with you before anything is paid for.</p>
        </div>
        <div class="hg-card hg-accent-card">
            <h2 class="hg-h3">Our mission</h2>
            <p>At the heart of our mission is a commitment to curating journeys that go beyond the ordinary — experiences that immerse you in the local culture and leave you with a deep appreciation for the diverse tapestry of our planet.</p>
            <p>Read <a href="/why-us">what you can expect when you book with us</a>.</p>
        </div>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="do-title">
    <div class="hg-container">
        <?= hg_section_head('What we do', 'Everything for your trip, in one place', '', null, 'do-title') ?>
        <div class="hg-grid hg-grid--3">
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('route') ?></span><h3>Holiday packages</h3><p>Ready itineraries for India and abroad that we adjust to your dates, hotel preference and budget.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('users') ?></span><h3>Customised tours</h3><p>Trips built from scratch for families, couples, friends and groups (FIT and GIT). <a href="/customized-holidays">Plan yours</a>.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('ticket') ?></span><h3>Flights, trains and buses</h3><p>Air, rail and bus tickets, booked on their own or as part of a package.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('bed') ?></span><h3>Hotels and stays</h3><p>Hotel and resort bookings matched to your itinerary and travel style.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('pin') ?></span><h3>Car rental and transfers</h3><p>Airport pick-ups, point-to-point transfers and cars with drivers for sightseeing.</p></div>
            <div class="hg-card hg-accent-card"><span class="hg-card__icon"><?= hg_icon('globe') ?></span><h3>Visa, insurance and cruises</h3><p>Visa application support, travel insurance, cruises and sightseeing tours.</p></div>
        </div>
    </div>
</section>

<section class="hg-section" aria-labelledby="where-title">
    <div class="hg-container">
        <?= hg_section_head('Where we travel', 'Destinations and holiday types', '', null, 'where-title') ?>
        <div class="hg-grid hg-grid--3">
            <div class="hg-chips-group"><h3>Abroad</h3><ul class="hg-chips"><li>Dubai</li><li>Singapore &amp; Malaysia</li><li>Thailand</li><li>Bali</li><li>Maldives</li></ul></div>
            <div class="hg-chips-group"><h3>In India</h3><ul class="hg-chips"><li>Kashmir</li><li>Kerala</li><li>Manali &amp; Himachal</li><li>North India circuits</li><li>Beaches</li><li>Pilgrimage routes</li></ul></div>
            <div class="hg-chips-group"><h3>Holiday types</h3><ul class="hg-chips"><li>Family</li><li>Honeymoon</li><li>Beach</li><li>Hill station</li><li>Pilgrimage</li><li>Adventure</li><li>Incentive and corporate</li></ul></div>
        </div>
    </div>
</section>

<section class="hg-section hg-band" aria-labelledby="how-title">
    <div class="hg-container">
        <?= hg_section_head('How a booking works', 'From your first message to your return', '', null, 'how-title') ?>
        <ol class="hg-steps">
            <li><strong>Share your plan</strong>Call, WhatsApp or email us with dates, number of travellers and what you'd like to see.</li>
            <li><strong>Get an itinerary and quote</strong>A travel expert sends a day-by-day plan with hotels and a price. Change anything you like.</li>
            <li><strong>Confirm with an advance</strong>Packages are confirmed with a 35% advance; your booking voucher arrives on WhatsApp or email once payment is received.</li>
            <li><strong>Travel with support</strong>Pay the balance before departure. Our team is reachable 24×7 for emergencies during your trip.</li>
        </ol>
    </div>
</section>

<section class="hg-section hg-section--tint" aria-labelledby="facts-title">
    <div class="hg-container">
        <?= hg_section_head('Company details', 'The company behind the brand', '', null, 'facts-title') ?>
        <dl class="hg-qf hg-qf--3">
            <div><dt>Brand</dt><dd>Holiday Guru Travel</dd></div>
            <div><dt>Legal name</dt><dd><?= hg_e(HG_LEGAL_NAME) ?></dd></div>
            <div><dt>Office</dt><dd><?= hg_e(HG_ADDRESS_LINE1) ?>, <?= hg_e(HG_ADDRESS_LINE2) ?></dd></div>
            <div><dt>Phone</dt><dd><a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a></dd></div>
            <div><dt>WhatsApp</dt><dd><a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener"><?= hg_e(HG_WHATSAPP_DISPLAY) ?></a> (24×7)</dd></div>
            <div><dt>Email</dt><dd><a href="<?= hg_e(hg_mailto_href()) ?>"><?= hg_e(HG_EMAIL_DISPLAY) ?></a></dd></div>
            <div><dt>People</dt><dd><a href="/leadership">Leadership</a> · <a href="/our-team">Our team</a> · <a href="/career">Careers</a></dd></div>
        </dl>
    </div>
</section>

<?= hg_cta_band('Planning a trip?', 'Tell us your dates and who\'s travelling. A travel expert will send an itinerary and quote.') ?>
<?php hg_layout_end(); ?>
