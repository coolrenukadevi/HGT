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
}
return hg_blog_posts();
