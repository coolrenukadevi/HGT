<?php
/** CRM — custom itineraries & quotations. Vars: $rows. */
ob_start();
if (!$rows) {
    echo empty_state('No quotations yet', 'Open an enquiry and choose <b>Create quotation</b>. It starts from the package as the website shows it.', '<a class="cms-btn cms-btn--navy" href="/enquiries">Go to enquiries</a>');
} else { ?>
<div class="cms-tablewrap"><table class="cms-table">
    <thead><tr><th scope="col">Reference</th><th scope="col">Client</th><th scope="col">Tour</th><th scope="col">Package ID</th><th scope="col">Status</th><th scope="col">Updated</th><th scope="col"><span class="cms-sr">Actions</span></th></tr></thead>
    <tbody>
    <?php foreach ($rows as $x) { $p = jd($x['payload']); ?>
        <tr>
            <td><code><?= e(quote_ref($x)) ?></code></td>
            <td><a href="/enquiries/<?= (int) $x['enquiry_pk'] ?>"><?= e($x['name']) ?></a></td>
            <td><?= e(isset($p['title']) ? $p['title'] : $x['package_name']) ?></td>
            <td><?= $x['package_id'] ? '<b class="cms-code">' . e($x['package_id']) . '</b>' : '—' ?></td>
            <td><span class="cms-pill"><?= e(HG_QUOTE_STATUSES[$x['status']]) ?></span></td>
            <td><?= e(dmy($x['updated_at'])) ?><?= $x['uname'] ? ' <span class="cms-muted cms-small">· ' . e($x['uname']) . '</span>' : '' ?></td>
            <td class="cms-nowrap"><a class="cms-btn cms-btn--ghost cms-btn--sm" href="/enquiries/<?= (int) $x['enquiry_pk'] ?>/quotation">Edit</a> <a class="cms-btn cms-btn--primary cms-btn--sm" href="/enquiries/<?= (int) $x['enquiry_pk'] ?>/quotation/print" target="_blank" rel="noopener"><?= icon('download') ?>PDF</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table></div>
<?php }
$content = card('Quotations', ob_get_clean(), array('sub' => 'Client-specific itineraries (CRM custom). They override the package and the Holiday Guru Travel standard for that client only and are never published on the website.'));
cms_render('layout', array('title' => 'Quotations', 'active' => 'quotations', 'crumbs' => array(array('Dashboard', '/'), array('Quotations', null)), 'head' => page_head('Quotations', 'Custom itineraries and downloadable quotations'), 'content' => $content));
