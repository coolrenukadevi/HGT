<?php
// Blog article: /blog/{slug} (rewritten to blog-article.php?slug=… in .htaccess). Content: include/content/blog/.
// Unknown slugs return the site's 404 page.
require __DIR__ . '/include/ui/core.php';
require_once __DIR__ . '/include/content/blog/index.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';
$a = $slug !== '' ? hg_blog_post($slug) : null;
if (!$a) {
    include __DIR__ . '/404.php';
    exit;
}
$path = $a['url'];
$group = $a['group'] !== '' ? hg_group($a['group']) : null;
$related = hg_blog_related_packages($a, 3);
$more = array_slice(array_values(array_filter(hg_blog_posts(), function ($p) use ($a) { return $p['cat'] === $a['cat'] && $p['slug'] !== $a['slug']; })), 0, 3);
$schema = array(array(
    '@type' => 'BlogPosting', 'headline' => $a['title'], 'description' => $a['desc'],
    'datePublished' => $a['date'], 'dateModified' => $a['date'], 'articleSection' => $a['cat'], 'wordCount' => $a['words'],
    'author' => array('@id' => HG_SITE_URL . '/#organization'), 'publisher' => array('@id' => HG_SITE_URL . '/#organization'),
    'mainEntityOfPage' => hg_abs($path), 'inLanguage' => 'en-IN',
));
if ($a['faqs']) $schema[] = hg_faq_schema($a['faqs']);

hg_layout_start(array(
    'title' => $a['title'] . ' | Holiday Guru Travel',
    'description' => $a['desc'],
    'path' => $path, 'index' => hg_page_status('/blog') === 'approved', 'type' => 'article',
    'breadcrumbs' => array(array('Home', '/'), array('Blog', '/blog'), array($a['title'], null)),
    'schema' => $schema,
));
?>
<article class="hg-guidepage hg-post">
<header class="hg-pagehead">
    <div class="hg-container hg-narrow">
        <p class="hg-eyebrow"><a href="/blog">Blog</a> · <?= hg_e($a['cat']) ?></p>
        <h1 class="hg-h1"><?= hg_e($a['title']) ?></h1>
        <p class="hg-lead"><?= hg_e($a['lead']) ?></p>
        <p class="hg-post__meta">By the Holiday Guru Travel team · <?= hg_e(hg_date_label($a['date'])) ?> · <?= (int) $a['read'] ?> min read</p>
    </div>
</header>

<div class="hg-container hg-narrow hg-prose">
<?php if (count($a['sections']) > 3) { ?>
    <nav class="hg-toc" aria-label="In this article"><p>In this article</p><ol><?php foreach ($a['sections'] as $i => $s) { ?><li><a href="#s<?= $i + 1 ?>"><?= hg_e($s[0]) ?></a></li><?php } ?></ol></nav>
<?php } ?>
<?php foreach ($a['sections'] as $i => $s) { ?>
    <section class="hg-post__sec" id="s<?= $i + 1 ?>">
        <h2 class="hg-h2"><?= hg_e($s[0]) ?></h2>
        <?= $s[1] ?>
    </section>
<?php } ?>
<?php if ($a['faqs']) { ?>
    <section class="hg-post__sec" id="faq">
        <h2 class="hg-h2">Questions travellers ask</h2>
        <?= hg_faq($a['faqs'], 'pfaq') ?>
    </section>
<?php } ?>
    <aside class="hg-post__plan" aria-label="Plan this trip">
        <p class="hg-post__plantitle">Plan this trip with a travel expert</p>
        <p>Tell us your dates and who's travelling. We'll send a day-by-day itinerary and quote.</p>
        <div class="hg-post__planbtns">
            <button type="button" class="hg-btn hg-btn--primary" data-hg-enquiry-open aria-haspopup="dialog">Enquire Now</button>
            <a class="hg-btn hg-btn--light" href="<?= hg_e(hg_whatsapp_href('Hi Holiday Guru Travel, I read "' . $a['title'] . '" and would like help planning my trip.')) ?>" target="_blank" rel="noopener"><?= hg_icon('whatsapp') ?> WhatsApp us</a>
        </div>
    </aside>
</div>
</article>

<?php if ($related) { ?>
<section class="hg-section hg-section--tint" aria-labelledby="rel-title">
    <div class="hg-container">
        <?= hg_section_head('Packages', $group ? $group['name'] . ' tours to start from' : 'Tours to start from', 'Every package can be changed: dates, hotels, nights and sightseeing.', $group ? array('All ' . $group['name'] . ' tours', '/tours/' . $group['key']) : null, 'rel-title') ?>
        <?= hg_package_grid($related) ?>
    </div>
</section>
<?php } ?>

<?php if ($more) { ?>
<section class="hg-section" aria-labelledby="more-title">
    <div class="hg-container">
        <?= hg_section_head('Keep reading', 'More ' . strtolower($a['cat']) . ' guides', '', array('All guides', '/blog'), 'more-title') ?>
        <div class="hg-grid hg-grid--3">
<?php foreach ($more as $m) { ?>
            <article class="hg-card hg-accent-card hg-topic"><p class="hg-topic__cat"><?= hg_e($m['cat']) ?></p><h3><a href="<?= hg_e($m['url']) ?>"><?= hg_e($m['title']) ?></a></h3><p><?= hg_e($m['desc']) ?></p><p class="hg-post__cardmeta"><?= (int) $m['read'] ?> min read</p></article>
<?php } ?>
        </div>
    </div>
</section>
<?php } ?>
<?php hg_layout_end(); ?>
