<?php
/** CRM custom itinerary + quotation editor. Vars: $e, $pkg, $row, $q, $res, $manage. */
$r = $res['r'];
$src = $r['_src'];
$lvl = array('crm' => 'This quotation', 'package' => 'Package', 'standard' => 'HGT standard', 'fallback' => '—');
// The inherited value for a field (what the client sees if this quote leaves it empty).
$inherit = quote_resolved($e, $pkg, array_merge($q, array('meal_plan' => '', 'hotel_category' => '', 'transport' => '', 'start' => '', 'end' => '', 'inclusions' => array(), 'exclusions' => array())))['r'];
$isrc = $inherit['_src'];
$ih = function ($field, $value) use ($isrc, $lvl, $q) {
    $map = array('meals' => 'meal_plan', 'hotel' => 'hotel_category', 'transfers' => 'transport', 'start' => 'start', 'end' => 'end');
    if (isset($map[$field]) && $q[$map[$field]] !== '') return 'Overridden for this client. Clear to use the ' . e(strtolower($lvl[$isrc[$field]])) . ' value' . ($value !== '' ? ' (' . e($value) . ')' : '') . '.';
    return 'Empty = ' . e($lvl[$isrc[$field]]) . ($value !== '' ? ': <b>' . e($value) . '</b>' : '');
};
$ro = $manage ? array() : array('disabled' => true);
ob_start(); ?>
<form method="post" action="/enquiries/<?= (int) $e['enquiry_pk'] ?>/quotation" class="cms-editor" id="quote-form">
    <?= csrf_field() ?>
    <div class="cms-editor__main">
        <?php ob_start(); ?>
        <div class="cms-grid2">
            <?= field_text('title', 'Tour name', $q['title'], array('required' => true) + $ro) ?>
            <?= field_text('salutation', 'Greeting line', $q['salutation'], array('placeholder' => 'Dear Trade Partner,') + $ro) ?>
            <?= field_text('destination', 'Destination', $q['destination'], $ro) ?>
            <?= field_text('duration', 'Duration', $q['duration'], array('placeholder' => '6 Nights / 7 Days') + $ro) ?>
            <?= field_text('travel_from', 'Travel from', $q['travel_from'], array('type' => 'date') + $ro) ?>
            <?= field_text('travel_to', 'Travel to', $q['travel_to'], array('type' => 'date') + $ro) ?>
            <?= field_text('travellers', 'Travellers', $q['travellers'], array('placeholder' => '9 adults') + $ro) ?>
        </div>
        <?php echo card('Trip', ob_get_clean(), array('sub' => 'Starts from ' . ($pkg ? 'Package ID ' . e($e['package_id'] ?: '—') . ' as the website shows it' : 'a blank plan (no package on this enquiry)') . '. Everything below applies to this client only.')); ?>

        <?php ob_start(); ?>
        <div class="cms-grid2">
            <?= field_text('hotel_category', 'Hotel category', $q['hotel_category'], array('placeholder' => $inherit['hotel'], 'hint' => $ih('hotel', $inherit['hotel'])) + $ro) ?>
            <?= field_text('meal_plan', 'Meal plan', $q['meal_plan'], array('placeholder' => $inherit['meals'], 'hint' => $ih('meals', $inherit['meals'])) + $ro) ?>
            <?= field_text('transport', 'Transport', $q['transport'], array('placeholder' => $inherit['transfers'] ?: 'e.g. Tempo Traveller 12 seater', 'hint' => $ih('transfers', $inherit['transfers'])) + $ro) ?>
            <?= field_text('start', 'Start point', $q['start'], array('placeholder' => $inherit['start'], 'hint' => $ih('start', $inherit['start'])) + $ro) ?>
            <?= field_text('end', 'End point', $q['end'], array('placeholder' => $inherit['end'], 'hint' => $ih('end', $inherit['end'])) + $ro) ?>
        </div>
        <?php echo card('Stay, meals & transport', ob_get_clean(), array('sub' => 'Priority: this quotation › package › Holiday Guru Travel standard. Only the winning value is printed.')); ?>

        <?php ob_start(); ?>
        <div class="cms-tablewrap"><table class="cms-table cms-table--form" id="hotel-rows">
            <thead><tr><th scope="col">Destination</th><th scope="col" style="width:6rem">Nights</th><th scope="col">Hotel / property (or category)</th></tr></thead>
            <tbody>
            <?php foreach (array_merge($q['hotels'], array(array('place' => '', 'nights' => '', 'hotel' => ''), array('place' => '', 'nights' => '', 'hotel' => ''))) as $i => $h) { ?>
                <tr>
                    <td><input aria-label="Destination <?= $i + 1 ?>" name="hotel_place[]" value="<?= e($h['place']) ?>"<?= $manage ? '' : ' disabled' ?>></td>
                    <td><input aria-label="Nights <?= $i + 1 ?>" name="hotel_nights[]" type="number" min="0" max="60" value="<?= e($h['nights']) ?>"<?= $manage ? '' : ' disabled' ?>></td>
                    <td><input aria-label="Hotel <?= $i + 1 ?>" name="hotel_name[]" value="<?= e($h['hotel']) ?>"<?= $manage ? '' : ' disabled' ?>></td>
                </tr>
            <?php } ?>
            </tbody>
        </table></div>
        <p class="cms-hint">Name a hotel only once it is booked or offered; otherwise keep the category (e.g. “Standard / 3-star equivalent hotel”). Empty rows are ignored.</p>
        <?php echo card('Hotel details', ob_get_clean()); ?>

        <?php ob_start(); ?>
        <ol class="cms-days" id="day-list">
            <?php foreach ($q['days'] as $i => $d) { ?>
            <li class="cms-day">
                <div class="cms-grid2">
                    <?= field_text('day_title[]', 'Day ' . ($i + 1) . ' title', $d['title'], array('id' => 'dt' . $i, 'placeholder' => 'Delhi arrival → Ringas') + $ro) ?>
                    <?= field_text('day_overnight[]', 'Overnight', $d['overnight'], array('id' => 'do' . $i, 'placeholder' => 'Leave empty on the departure day') + $ro) ?>
                </div>
                <?= field_text('day_text[]', 'Description', $d['text'], array('id' => 'dx' . $i, 'type' => 'textarea', 'rows' => 4) + $ro) ?>
                <?= field_text('day_sights[]', 'Sightseeing (one per line, optional)', $d['sightseeing'], array('id' => 'ds' . $i, 'type' => 'textarea', 'rows' => 2) + $ro) ?>
                <?php if ($manage) { ?><label class="cms-check"><input type="checkbox" name="day_remove[<?= $i ?>]" value="1"><span>Remove this day</span></label><?php } ?>
            </li>
            <?php } ?>
        </ol>
        <?php if ($manage) { ?><button type="button" class="cms-btn cms-btn--ghost cms-btn--sm" id="add-day"><?= icon('plus') ?>Add day</button><?php } ?>
        <?php echo card('Day-wise itinerary', ob_get_clean(), array('sub' => count($q['days']) . ' day' . (count($q['days']) === 1 ? '' : 's') . '. Edit titles, descriptions, overnight places and sightseeing for this client. A CRM itinerary is printed as confirmed information (no “suggested plan” note).')); ?>

        <?php ob_start(); ?>
        <div class="cms-grid2">
            <?= field_text('inclusions', 'Inclusions (one per line)', implode("\n", $q['inclusions']), array('type' => 'textarea', 'rows' => 8, 'placeholder' => implode("\n", $inherit['inclusions']), 'hint' => $q['inclusions'] ? 'Overridden for this client.' : 'Empty = ' . e(strtolower($lvl[$isrc['inclusions']])) . ' list (shown faded).') + $ro) ?>
            <?= field_text('exclusions', 'Exclusions (one per line)', implode("\n", $q['exclusions']), array('type' => 'textarea', 'rows' => 8, 'placeholder' => implode("\n", $inherit['exclusions']), 'hint' => $q['exclusions'] ? 'Overridden for this client.' : 'Empty = ' . e(strtolower($lvl[$isrc['exclusions']])) . ' list (shown faded).') + $ro) ?>
        </div>
        <?php if ($manage) { ?><button type="button" class="cms-btn cms-btn--ghost cms-btn--sm" data-copy-inherited><?= icon('copy') ?>Copy the inherited lists in to edit them</button><?php } ?>
        <?php echo card('Inclusions & exclusions', ob_get_clean()); ?>

        <?php ob_start(); ?>
        <div class="cms-grid2">
            <?= field_text('total_amount', 'Total package cost (₹)', $q['total_amount'], array('placeholder' => '1,35,000', 'inputmode' => 'decimal') + $ro) ?>
            <?= field_text('total_label', 'Label', $q['total_label'], array('placeholder' => 'Total package cost for 9 travellers') + $ro) ?>
            <?= field_text('tax_note', 'Tax note', $q['tax_note'], array('placeholder' => '+ GST 5%  or  inclusive of GST') + $ro) ?>
            <?= field_text('valid_until', 'Quote valid until', $q['valid_until'], array('type' => 'date') + $ro) ?>
        </div>
        <p class="cms-hint">The document prints one total only — no per-person, hotel or transport break-up.</p>
        <?php echo card('Package cost', ob_get_clean()); ?>

        <?php ob_start(); ?>
        <?= field_text('special_notes', 'Special notes for this client', $q['special_notes'], array('type' => 'textarea', 'rows' => 3, 'placeholder' => 'e.g. Departure train 4:30 pm from Delhi.') + $ro) ?>
        <p class="cms-hint">Terms &amp; conditions are the same on every quotation and print automatically.</p>
        <?php echo card('Notes', ob_get_clean()); ?>
    </div>
    <aside class="cms-editor__rail">
        <?php ob_start(); ?>
        <dl class="cms-dl">
            <div><dt>Reference</dt><dd><?= $row ? '<code>' . e(quote_ref($row)) . '</code>' : '<span class="cms-muted">Not saved yet</span>' ?></dd></div>
            <div><dt>Client</dt><dd><a href="/enquiries/<?= (int) $e['enquiry_pk'] ?>"><?= e($e['name']) ?></a></dd></div>
            <div><dt>Package ID</dt><dd><?= $e['package_id'] ? '<b class="cms-code">' . e($e['package_id']) . '</b>' : '—' ?></dd></div>
            <div><dt>Itinerary source</dt><dd>CRM custom<?= $row ? ' · v' . (int) $row['version'] : '' ?></dd></div>
        </dl>
        <?= field_select('status', 'Status', $row ? $row['status'] : 'draft', HG_QUOTE_STATUSES, $ro) ?>
        <?php if ($manage) { ?><button class="cms-btn cms-btn--navy cms-btn--block" type="submit"><?= icon('save') ?>Save quotation</button><?php } ?>
        <?php if ($row) { ?><a class="cms-btn cms-btn--primary cms-btn--block" href="/enquiries/<?= (int) $e['enquiry_pk'] ?>/quotation/print" target="_blank" rel="noopener"><?= icon('download') ?>Download PDF</a>
        <p class="cms-hint">Opens the branded A4 quotation; choose <b>Save as PDF</b> in the print dialog.</p><?php } else { ?><p class="cms-hint">Save once to enable <b>Download PDF</b>.</p><?php } ?>
        <?php echo card('Quotation', ob_get_clean()); ?>
    </aside>
</form>
<template id="day-tpl">
    <li class="cms-day">
        <div class="cms-grid2">
            <div class="cms-field"><label>New day title</label><input name="day_title[]"></div>
            <div class="cms-field"><label>Overnight</label><input name="day_overnight[]"></div>
        </div>
        <div class="cms-field"><label>Description</label><textarea name="day_text[]" rows="4"></textarea></div>
        <div class="cms-field"><label>Sightseeing (one per line, optional)</label><textarea name="day_sights[]" rows="2"></textarea></div>
    </li>
</template>
<script>
(function () {
    var add = document.getElementById('add-day'), list = document.getElementById('day-list'), tpl = document.getElementById('day-tpl');
    if (add) add.addEventListener('click', function () {
        var n = list.children.length, node = tpl.content.cloneNode(true);
        node.querySelectorAll('label').forEach(function (l, i) {
            var id = 'nd' + n + '-' + i, f = l.nextElementSibling; f.id = id; l.htmlFor = id;
            if (i === 0) l.textContent = 'Day ' + (n + 1) + ' title';
        });
        list.appendChild(node);
        list.lastElementChild.querySelector('input').focus();
    });
    var copy = document.querySelector('[data-copy-inherited]');
    if (copy) copy.addEventListener('click', function () {
        ['f-inclusions', 'f-exclusions'].forEach(function (id) { var t = document.getElementById(id); if (t && !t.value.trim()) t.value = t.placeholder; });
    });
})();
</script>
<?php
$content = ob_get_clean();
cms_render('layout', array('title' => 'Quotation · Enquiry ' . enquiry_no($e['enquiry_pk']), 'active' => 'quotations',
    'crumbs' => array(array('Dashboard', '/'), array('Enquiries', '/enquiries'), array(enquiry_no($e['enquiry_pk']), '/enquiries/' . (int) $e['enquiry_pk']), array('Quotation', null)),
    'head' => page_head('Custom itinerary & quotation', $e['name'] . ($e['package_name'] ? ' · ' . $e['package_name'] : ''), $row ? '<a class="cms-btn cms-btn--primary" href="/enquiries/' . (int) $e['enquiry_pk'] . '/quotation/print" target="_blank" rel="noopener">' . icon('download') . 'Download PDF</a>' : '', $row ? '<span class="cms-pill">' . e(HG_QUOTE_STATUSES[$row['status']]) . '</span> <span class="cms-muted cms-small">Updated ' . e(dmy($row['updated_at'])) . '</span>' : ''),
    'content' => $content));
