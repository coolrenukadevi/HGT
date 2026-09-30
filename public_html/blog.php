<?php
// Blog: travel guides from the Holiday Guru Travel team (articles in include/content/blog/, pages at /blog/{slug}).
// Design from the owner's page pack: featured guide, category filters, all guides. Status: include/data/page-status.php.
require __DIR__ . '/include/ui/core.php';
require_once __DIR__ . '/include/content/blog/index.php';

$posts = hg_blog_posts();
$featured = hg_blog_post('first-family-trip-dubai');
$cats = array();
foreach ($posts as $p) $cats[hg_blog_cat_key($p['cat'])] = $p['cat'];
$list = array();
foreach ($posts as $p) $list[] = array('@type' => 'ListItem', 'position' => count($list) + 1, 'url' => hg_abs($p['url']), 'name' => $p['title']);

hg_layout_start(array(
    'title' => 'Travel Blog: Guides and Tips | Holiday Guru Travel',
    'description' => 'Practical travel guides from Holiday Guru Travel: Kashmir, Ladakh, Kerala, Char Dham, Amarnath, Dubai, Singapore, the Maldives and booking tips.',
    'path' => '/blog', 'index' => hg_page_status('/blog') === 'approved',
    'breadcrumbs' => array(array('Home', '/'), array('Blog', null)),
    'schema' => array(array('@type' => 'Blog', 'name' => 'Holiday Guru Travel blog', 'url' => hg_abs('/blog'),
        'publisher' => array('@id' => HG_SITE_URL . '/#organization')), array('@type' => 'ItemList', 'itemListElement' => $list)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Blog</p>
        <h1 class="hg-h1" id="page-title">Notes from our travel desk</h1>
        <p class="hg-lead">Practical guides written by the people who plan these trips every week. Read before you book, or before you pack.</p>
    </div>
</section>

<section class="hg-section hg-section--tight" aria-label="Featured guide">
    <div class="hg-container">
        <article class="hg-postfeature">
            <div class="hg-postfeature__art" aria-hidden="true">
                <svg viewBox="0 0 240 150" width="100%"><g fill="none" stroke="#F98400" stroke-width="3" stroke-linecap="round"><path d="M10 130 Q70 40 120 80 T230 30" stroke-dasharray="2 9"/></g><circle cx="10" cy="130" r="7" fill="#F98400"/><circle cx="230" cy="30" r="7" fill="#fff"/><text x="10" y="112" fill="#C9D0DF" font-size="13">DEL</text><text x="200" y="58" fill="#C9D0DF" font-size="13">DXB</text></svg>
            </div>
            <div class="hg-postfeature__body">
                <p class="hg-topic__cat">Featured · <?= hg_e($featured['cat']) ?></p>
                <h2 class="hg-h2"><a href="<?= hg_e($featured['url']) ?>"><?= hg_e($featured['title']) ?></a></h2>
                <p><?= hg_e($featured['desc']) ?></p>
                <p class="hg-post__cardmeta"><?= (int) $featured['read'] ?> min read</p>
            </div>
        </article>
    </div>
</section>

<section class="hg-section" aria-labelledby="all-title">
    <div class="hg-container">
        <?= hg_section_head('All guides', count($posts) . ' guides to plan your trip', '', null, 'all-title') ?>
        <div class="hg-blogfilters" role="group" aria-label="Filter guides">
            <button type="button" data-hg-blogf="all" aria-pressed="true">All</button>
<?php foreach ($cats as $k => $c) { ?>
            <button type="button" data-hg-blogf="<?= hg_e($k) ?>" aria-pressed="false"><?= hg_e($c) ?></button>
<?php } ?>
        </div>
        <div class="hg-grid hg-grid--3" id="hg-blogposts">
<?php foreach ($posts as $p) { ?>
            <article class="hg-card hg-accent-card hg-topic" data-hg-blogc="<?= hg_e(hg_blog_cat_key($p['cat'])) ?>">
                <p class="hg-topic__cat"><?= hg_e($p['cat']) ?></p>
                <h3><a href="<?= hg_e($p['url']) ?>"><?= hg_e($p['title']) ?></a></h3>
                <p><?= hg_e($p['desc']) ?></p>
                <p class="hg-post__cardmeta"><?= (int) $p['read'] ?> min read</p>
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
<script>
(function () {
    var btns = document.querySelectorAll('[data-hg-blogf]'), posts = document.querySelectorAll('#hg-blogposts [data-hg-blogc]');
    btns.forEach(function (b) {
        b.addEventListener('click', function () {
            var f = b.getAttribute('data-hg-blogf');
            posts.forEach(function (p) { p.hidden = f !== 'all' && p.getAttribute('data-hg-blogc') !== f; });
            btns.forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
        });
    });
})();
</script>
<?php hg_layout_end(); ?>
