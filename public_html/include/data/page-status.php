<?php
/**
 * Publishing status of pages awaiting owner approval (one place to approve).
 *   'draft'    — visible on the site but hidden from Google (noindex, not in sitemap.xml)
 *   'approved' — indexable; add the URL to sitemap.xml
 * Change a value to 'approved' once the owner has approved the page content.
 */
return array(
    '/faqs'                 => 'approved',
    '/india-tours'          => 'approved',
    '/travel-guide/kashmir' => 'approved',
    '/cancellation-policy'  => 'approved',
    '/refund-policy'        => 'approved',
    '/payment-policy'       => 'approved',
    '/leadership'           => 'approved',   // photos, names and roles to be supplied by the owner
    '/our-team'             => 'approved',   // photos, names and roles to be supplied by the owner
    '/grievance-redress'    => 'approved',   // officer details supplied by the owner
    '/offers'               => 'approved',   // offer details, prices and validity to be supplied by the owner
    // Company pages from the owner's page pack (2026-09-30).
    '/why-us'               => 'approved',   // owner's own wording
    '/career'               => 'approved',   // owner's own wording; applications to the site email
    '/blog'                 => 'draft',      // topics only until the first articles are written
    '/disclaimer'           => 'draft',      // legal review requested in the pack (liability limit, jurisdiction)
);
