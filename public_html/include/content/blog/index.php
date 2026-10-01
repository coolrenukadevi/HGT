<?php
/**
 * Blog index: every article, in display order (newest topics first within each category file).
 * Articles live in the category files next to this one. Each gets: slug, url, date, words, read (minutes).
 * Published 2026-09-30 and approved for indexing by the owner (same day).
 */
if (!function_exists('hg_blog_posts')) {
    function hg_blog_posts()
    {
        static $all = null;
        if ($all !== null) return $all;
        $all = array();
        foreach (array('international', 'india-north', 'pilgrimage', 'india-south-east', 'tips') as $f) {
            foreach ((array) include __DIR__ . '/' . $f . '.php' as $slug => $p) {
                $text = $p['lead'] . ' ' . implode(' ', array_map(function ($s) { return $s[0] . ' ' . strip_tags($s[1]); }, $p['sections']))
                    . ' ' . implode(' ', array_map(function ($q) { return $q[0] . ' ' . strip_tags($q[1]); }, $p['faqs']));
                $words = count(preg_split('/\s+/', trim(html_entity_decode($text, ENT_QUOTES, 'UTF-8'))));
                $all[$slug] = $p + array('slug' => $slug, 'url' => '/blog/' . $slug, 'date' => '2026-09-30',
                    'words' => $words, 'read' => max(2, (int) ceil($words / 200)));
            }
        }
        return $all;
    }
    function hg_blog_post($slug)
    {
        $all = hg_blog_posts();
        return isset($all[$slug]) ? $all[$slug] : null;
    }
    /** Category key for filters. */
    function hg_blog_cat_key($cat)
    {
        return strtolower(preg_replace('/[^a-z]+/i', '-', $cat));
    }
    /** Lower-case plain text of an article (title, description, lead, sections, FAQs), for relevance matching. */
    function hg_blog_text(array $a)
    {
        $t = $a['title'] . ' ' . $a['desc'] . ' ' . $a['lead'] . ' '
            . implode(' ', array_map(function ($s) { return $s[0] . ' ' . strip_tags($s[1]); }, $a['sections'])) . ' '
            . implode(' ', array_map(function ($q) { return $q[0] . ' ' . strip_tags($q[1]); }, $a['faqs']));
        return mb_strtolower(html_entity_decode($t, ENT_QUOTES, 'UTF-8'));
    }
    /** How many of a package's places an article discusses (internal linking, Phase 2.2). */
    function hg_blog_package_score(array $a, array $pkg)
    {
        $text = hg_blog_text($a);
        $n = 0;
        foreach ($pkg['places'] as $pl) {
            if ($pl !== '' && preg_match('/\b' . preg_quote(mb_strtolower($pl), '/') . '\b/u', $text)) $n++;
        }
        return $n;
    }
    /** Guides about a destination (group key), in index order. */
    function hg_blog_guides_for_group($key)
    {
        return array_values(array_filter(hg_blog_posts(), function ($a) use ($key) { return $a['group'] === $key; }));
    }
    /** Up to $n guides for a package: same destination, those covering more of its places first. */
    function hg_blog_guides_for_package(array $pkg, $n = 2)
    {
        $list = hg_blog_guides_for_group($pkg['group']);
        $score = array();
        foreach ($list as $i => $a) $score[$a['slug']] = array(hg_blog_package_score($a, $pkg), -$i);
        usort($list, function ($x, $y) use ($score) { return $score[$y['slug']] <=> $score[$x['slug']]; });
        return array_slice($list, 0, $n);
    }
    /** Up to $n packages for an article: same destination, those whose places the article discusses first. */
    function hg_blog_related_packages(array $a, $n = 3)
    {
        if ($a['group'] === '') return array();
        $list = array_values(hg_packages_in($a['group']));
        $score = array();
        foreach ($list as $i => $p) $score[$p['slug']] = array(hg_blog_package_score($a, $p), -$i);
        usort($list, function ($x, $y) use ($score) { return $score[$y['slug']] <=> $score[$x['slug']]; });
        return array_slice($list, 0, $n);
    }
    /** Plain list of guide links (title as anchor text). */
    function hg_blog_link_list(array $guides)
    {
        $out = '<ul class="hg-linklist">';
        foreach ($guides as $a) $out .= '<li><a href="' . hg_e($a['url']) . '">' . hg_e($a['title']) . '</a></li>';
        return $out . '</ul>';
    }
}
return hg_blog_posts();
