<?php
/**
 * CRM custom itinerary + quotation for an enquiry (level 3 content; see src/quotes.php).
 *   GET  /quotations                      list
 *   GET  /enquiries/{id}/quotation        editor (new quotes start from the package as the website shows it)
 *   POST /enquiries/{id}/quotation        save (version +1 on every save after the first)
 *   GET  /enquiries/{id}/quotation/print  A4 document — "Download PDF" uses the browser's Save as PDF
 */

require_once dirname(__DIR__) . '/quotes.php';

function quote_context($id)
{
    $e = q1('SELECT * FROM enquiries WHERE enquiry_pk = ?', array((int) $id));
    if (!$e) { http_response_code(404); cms_render('error', array('title' => 'Not found', 'message' => 'Enquiry not found.')); return null; }
    $pkg = $e['package_pk'] ? pkg_row($e['package_pk']) : null;
    $row = quote_row($id);
    $q = $row ? array_merge(quote_defaults($e, $pkg), jd($row['payload'])) : quote_defaults($e, $pkg);
    return compact('e', 'pkg', 'row', 'q');
}

function quotations_list()
{
    need('enquiries');
    $rows = q('SELECT c.*, e.name, e.package_name, e.stage, u.name uname FROM custom_itineraries c JOIN enquiries e ON e.enquiry_pk = c.enquiry_pk LEFT JOIN users u ON u.user_id = c.updated_by ORDER BY c.updated_at DESC LIMIT 200')->fetchAll();
    cms_render('quotations', array('rows' => $rows));
}

function quote_edit($id)
{
    need('enquiries');
    $c = quote_context($id);
    if (!$c) return;
    $c['res'] = quote_resolved($c['e'], $c['pkg'], $c['q']);
    $c['manage'] = hg_can(role(), 'enquiries', 'manage');
    cms_render('quote-edit', $c);
}

function quote_save($id)
{
    need('enquiries', 'manage');
    $c = quote_context($id);
    if (!$c) return;
    $q = quote_from_post();
    $status = isset(HG_QUOTE_STATUSES[post('status')]) ? post('status') : 'draft';
    if ($c['row']) {
        q('UPDATE custom_itineraries SET payload = ?, status = ?, version = version + 1, updated_at = ?, updated_by = ? WHERE itinerary_pk = ?', array(je($q), $status, now(), uid(), $c['row']['itinerary_pk']));
    } else {
        q('INSERT INTO custom_itineraries(enquiry_pk, package_pk, package_id, status, payload, version, created_at, created_by, updated_at, updated_by) VALUES (?,?,?,?,?,1,?,?,?,?)',
            array((int) $id, $c['e']['package_pk'], $c['e']['package_id'], $status, je($q), now(), uid(), now(), uid()));
    }
    if (in_array($status, array('sent', 'confirmed'), true) && in_array($c['e']['stage'], array('new', 'contacted', 'qualified'), true)) {
        q("UPDATE enquiries SET stage = 'quoted' WHERE enquiry_pk = ?", array((int) $id));
    }
    cms_log('Quotation saved', $c['e']['package_pk'], 'enquiry #' . (int) $id, '', $status);
    flash('Quotation saved.');
    redirect('/enquiries/' . (int) $id . '/quotation');
}

function quote_print($id)
{
    need('enquiries');
    $c = quote_context($id);
    if (!$c) return;
    if (!$c['row']) { flash('Save the quotation once before downloading it.', 'warn'); redirect('/enquiries/' . (int) $id . '/quotation'); }
    $c['res'] = quote_resolved($c['e'], $c['pkg'], $c['q']);
    $c['by'] = q1('SELECT name FROM users WHERE user_id = ?', array((int) ($c['row']['updated_by'] ?: $c['row']['created_by'])));
    $c['terms'] = quote_terms();
    $c['co'] = array(
        'phone' => site_setting('HG_PHONE_DISPLAY'), 'whatsapp' => site_setting('HG_WHATSAPP_DISPLAY'), 'email' => site_setting('HG_EMAIL_DISPLAY') ?: 'info@holidaygurutravel.in',
        'legal' => site_setting('HG_LEGAL_NAME') ?: 'M/S Holiday Guru Travel',
        'addr1' => site_setting('HG_ADDRESS_LINE1'), 'addr2' => site_setting('HG_ADDRESS_LINE2'), 'gstin' => site_setting('HG_GSTIN'),
        'site' => preg_replace('#^https?://#', '', site_setting('HG_SITE_URL') ?: 'https://holidaygurutravel.in'),
    );
    extract($c);
    require dirname(__DIR__) . '/views/quote-print.php';
}
