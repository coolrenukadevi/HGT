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
    '/travel-guide/ladakh'                => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/himachal'              => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/uttarakhand'           => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/char-dham'             => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/amarnath'              => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/sikkim-darjeeling'     => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/kerala'                => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/south-india'           => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/goa'                   => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/rajasthan'             => 'approved',   // destination guide, 2026-10-08 (new destination, batch 4)
    '/travel-guide/golden-triangle'       => 'approved',   // destination guide, 2026-10-08 (new destination, batch 5)
    '/travel-guide/kashi-ayodhya'         => 'approved',   // destination guide, 2026-10-08 (new destination, batch 5)
    '/travel-guide/vaishno-devi'          => 'approved',   // destination guide, 2026-10-08 (new destination, batch 6)
    '/travel-guide/tamil-nadu'            => 'approved',   // destination guide, 2026-10-08 (new destination, batch 6)
    '/travel-guide/dubai'                 => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/singapore-malaysia'    => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/maldives'              => 'approved',   // destination guide, 2026-10-05 (owner: generate all 13 guides)
    '/travel-guide/thailand'              => 'approved',   // destination guide, 2026-10-08 (new destination, international batch I1)
    '/travel-guide/bali'                  => 'approved',   // destination guide, 2026-10-08 (new destination, international batch I2)
    '/travel-guide/vietnam'               => 'approved',   // destination guide, 2026-10-08 (new destination, international batch I2)
    '/travel-guide/japan'                 => 'approved',   // destination guide, 2026-10-08 (new destination, international batch I2)
    '/travel-guide/cambodia'              => 'approved',   // destination guide, 2026-10-08 (new destination, international batch I2)
    '/travel-guide/andaman'               => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D1)
    '/travel-guide/meghalaya-assam'       => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D1)
    '/travel-guide/arunachal-nagaland'    => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D1)
    '/travel-guide/odisha'                => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D1)
    '/travel-guide/gujarat'               => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D2)
    '/travel-guide/madhya-pradesh'        => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D2)
    '/travel-guide/maharashtra'           => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D2)
    '/travel-guide/andhra-telangana'      => 'approved',   // destination guide, 2026-10-08 (new destination, domestic phase D3)
    '/cancellation-policy'  => 'approved',
    '/refund-policy'        => 'approved',
    '/payment-policy'       => 'approved',
    '/payment'              => 'approved',   // indexed (and to be added to sitemap.xml) once a Pay Now link or bank details are filled in
    '/leadership'           => 'approved',   // photos, names and roles to be supplied by the owner
    '/our-team'             => 'approved',   // photos, names and roles to be supplied by the owner
    '/grievance-redress'    => 'approved',   // officer details supplied by the owner
    '/offers'               => 'approved',   // offer details, prices and validity to be supplied by the owner
    // Company pages from the owner's page pack (2026-09-30).
    '/why-us'               => 'approved',   // owner's own wording
    '/career'               => 'approved',   // owner's own wording; applications to the site email
    '/blog'                 => 'approved',   // 35 articles (/blog/{slug}); approved for indexing by the owner 2026-09-30
    '/disclaimer'           => 'draft',      // legal review requested in the pack (liability limit, jurisdiction)
);
